<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranIuran;
use App\Models\PembayaranWisata;
use App\Models\JSenam;
use App\Models\AbsensiSenam;
use Midtrans\Snap;
use Midtrans\Config;

class PembayaranIuranController extends Controller
{
    // DETAIL IURAN
    public function show($id_senam)
    {
        $user = auth()->user();

        $senam = JSenam::with('user')->findOrFail($id_senam);

        // CEK ABSENSI
        $absen = AbsensiSenam::where('id_senam', $id_senam)
            ->where('id_user', $user->id_user)
            ->first();

        // CEK PEMBAYARAN
        $pembayaran = PembayaranIuran::where('id_user', $user->id_user)
            ->where('id_senam', $id_senam)
            ->where('status', 'success')
            ->first();

        // ======================================
        // AUTO POTONG SALDO
        // ======================================

        // jika belum bayar
        // DAN user hadir
        // DAN saldo cukup
        if (
            !$pembayaran &&
            $absen &&
            $absen->status == 'hadir' &&
            $user->saldo_iuran >= 2500
        ) {

            // potong saldo
            $user->saldo_iuran -= 2500;
            $user->save();

            // buat pembayaran otomatis
            $pembayaran = PembayaranIuran::create([

                'id_user' => $user->id_user,
                'id_senam' => $id_senam,

                'nominal_bayar' => 2500,
                'nominal_dibayar' => 0,

                'metode' => 'saldo',
                'status' => 'success',

                'tanggal_bayar' => now(),
            ]);
        }

        return view('anggota.iuran.index', compact(
            'senam',
            'absen',
            'pembayaran'
        ));
    }

    // BAYAR (MIDTRANS SNAP)
    public function bayar($id_senam)
    {
        $user = auth()->user();

        // wajib hadir
        $absen = AbsensiSenam::where('id_senam', $id_senam)
            ->where('id_user', $user->id_user)
            ->where('status', 'hadir')
            ->first();

        if (!$absen) {
            return response()->json(['error' => 'Harus hadir dulu'], 403);
        }

        // cek sudah bayar
        $cek = PembayaranIuran::where('id_user', $user->id_user)
            ->where('id_senam', $id_senam)
            ->where('status', 'success')
            ->first();

        if ($cek) {
            return response()->json(['error' => 'Sudah bayar'], 400);
        }

        // MIDTRANS CONFIG
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $order_id = 'IURAN-' . $id_senam . '-' . time();

        $grossAmount = 2500; // FIX sesuai UI kamu

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $user->nama_user,
                'email' => $user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        PembayaranIuran::updateOrCreate([
            'id_user' => $user->id_user,
            'id_senam' => $id_senam,
            'nominal_bayar' => $grossAmount,
            'status' => 'pending',
            'midtrans_order_id' => $order_id,
            'midtrans_snap_token' => $snapToken
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $order_id
        ]);
    }

public function callback(Request $request)
{
    \Log::info('CALLBACK MASUK', $request->all());
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = false;
    try {
        $notif = new \Midtrans\Notification();
        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;

        //TOP UP SALDO
        if (str_contains($order_id, 'TOPUP')) {
            if ($transaction == 'settlement' || $transaction == 'capture') {
                $userId = explode('-', $order_id)[2] ?? null;
                $user = \App\Models\User::where('id_user', $userId)->first();
                if ($user) {
                    $user->saldo_iuran += (int) $notif->gross_amount;
                    $user->save();
                    \Log::info('TOPUP BERHASIL', [
                        'user_id' => $userId,
                        'nominal' => (int) $notif->gross_amount,
                        'saldo_baru' => $user->saldo_iuran
                    ]);
                }
            }
            return response()->json([
                'message' => 'Topup saldo berhasil diproses'
            ]);
        }

        //PEMBAYARAN WISATA / IURAN
        if (str_contains($order_id, 'WISATA')) {
            $payment = \App\Models\PembayaranWisata::where('midtrans_order_id', $order_id)->first();
        } else {
            $payment = \App\Models\PembayaranIuran::where('midtrans_order_id', $order_id)->first();
        }
        if (!$payment) {
            return response()->json([
                'message' => 'Data pembayaran tidak ditemukan'
            ], 404);
        }
        if ($transaction == 'settlement' || $transaction == 'capture') {
            if (str_contains($order_id, 'WISATA')) {
                $payment->update([
                    'status' => 'lunas',
                ]);
                $pendaftaran = \App\Models\PendaftaranWisata::with('jwisata')
                    ->find($payment->id_daftar_wisata);
                if ($pendaftaran) {
                    $totalTagihan = $pendaftaran->jwisata->biaya_wisata;
                    $totalTerbayar = \App\Models\PembayaranWisata::where(
                        'id_daftar_wisata',
                        $pendaftaran->id_daftar_wisata
                    )
                    ->whereIn('status', [
                        'lunas',
                        'cicilan',
                        'cash'
                    ])
                    ->sum('jumlah_bayar');
                    if ($totalTerbayar >= $totalTagihan) {
                        $pendaftaran->update([
                            'status_daftar' => 'menunggu_perjalanan'
                        ]);
                    } else {
                        $pendaftaran->update([
                            'status_daftar' => 'menunggu_pembayaran'
                        ]);
                    }
                }
            } else {
                $payment->update([
                    'status' => 'success',
                    'tanggal_bayar' => now(),
                ]);
            }
        } elseif (in_array($transaction, ['expire', 'cancel', 'deny'])) {
            $payment->update([
                'status' => 'failed'
            ]);
        }
        return response()->json([
            'message' => 'Callback Berhasil Diproses'
        ]);
    } catch (\Exception $e) {
        \Log::error('Gagal memproses callback: ' . $e->getMessage());
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
    }
}
}