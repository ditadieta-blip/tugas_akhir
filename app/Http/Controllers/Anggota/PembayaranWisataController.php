<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\PembayaranWisata;
use App\Models\PendaftaranWisata;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Carbon\Carbon; // Pastikan Carbon sudah di-import

class PembayaranWisataController extends Controller
{
    public function index()
    {
        // Menyaring pendaftaran wisata yang tanggal wisatanya HARI INI atau DI MASA DEPAN (>= today)
        $pendaftaran = PendaftaranWisata::with(['jwisata', 'pembayaranWisata'])
            ->where('id_user', auth()->user()->id_user)
            ->whereIn('status_daftar', [
                'menunggu_pembayaran',
                'menunggu_perjalanan'
            ])
            ->whereHas('jwisata', function($query) {
                $query->where('tanggal_wisata', '>=', Carbon::today());
            })
            ->get();

        return view('anggota.pembayaran_wisata.index', compact('pendaftaran'));
    }

    public function show($id)
    {
        // Pastikan di halaman detail juga tidak bisa diakses jika tanggalnya sudah lewat
        $pendaftaran = PendaftaranWisata::with(['jwisata', 'pembayaranWisata'])
            ->where('id_user', auth()->user()->id_user)
            ->whereIn('status_daftar', [
                'menunggu_pembayaran',
                'menunggu_perjalanan'
            ])
            ->whereHas('jwisata', function($query) {
                $query->where('tanggal_wisata', '>=', Carbon::today());
            })
            ->findOrFail($id);

        return view('anggota.pembayaran_wisata.show', compact('pendaftaran'));
    }

    public function bayar(Request $request, $id)
    {
        try {
            // Amankan juga fungsi proses bayar agar tidak bisa menembak transaksi jika wisata sudah lewat
            $pendaftaran = PendaftaranWisata::with(['jwisata', 'pembayaranWisata'])
                ->where('id_user', auth()->user()->id_user)
                ->whereHas('jwisata', function($query) {
                    $query->where('tanggal_wisata', '>=', Carbon::today());
                })
                ->findOrFail($id);

            $jumlahBayar = $request->jumlah_input;

            $totalTerbayarLama = $pendaftaran->pembayaranWisata()
                ->whereIn('status', ['lunas', 'success', 'settlement'])
                ->sum('jumlah_bayar');

            $biayaWisata = $pendaftaran->jwisata->biaya_wisata ?? 0;
            $sisaTagihanSaatIni = $biayaWisata - $totalTerbayarLama;

            if ($jumlahBayar > $sisaTagihanSaatIni) {
                return response()->json([
                    'error' => 'Jumlah bayar melebihi sisa tagihan.'
                ], 400);
            }

            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $orderId = 'WISATA-' . time() . '-' . $pendaftaran->id_daftar_wisata;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $jumlahBayar,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->nama_user ?? auth()->user()->nama,
                    'email' => auth()->user()->email,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $akumulasiTerbayar = $totalTerbayarLama + $jumlahBayar;
            $sisaSetelahBayar = $biayaWisata - $akumulasiTerbayar;
            $cicilanKe = $pendaftaran->pembayaranWisata()->count() + 1;

            PembayaranWisata::create([
                'id_daftar_wisata'    => $pendaftaran->id_daftar_wisata,
                'jumlah_bayar'        => $jumlahBayar,
                'total_terbayar'      => $akumulasiTerbayar,
                'sisa_tagihan'        => $sisaSetelahBayar,
                'cicilan_ke'          => $cicilanKe,
                'status'              => 'pending',
                'midtrans_order_id'   => $orderId,
                'midtrans_snap_token' => $snapToken,
            ]);

            return response()->json([
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        try {
            $notif = new Notification();

            $orderId = $notif->order_id;
            $transactionStatus = $notif->transaction_status;

            $pembayaran = PembayaranWisata::where(
                'midtrans_order_id',
                $orderId
            )->first();

            if (!$pembayaran) {
                return response()->json([
                    'message' => 'Data pembayaran tidak ditemukan'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */
            if (
                $transactionStatus == 'settlement' ||
                $transactionStatus == 'capture'
            ) {
                $pembayaran->update([
                    'status' => 'lunas'
                ]);
                $pendaftaran = PendaftaranWisata::with('jwisata')
                    ->find($pembayaran->id_daftar_wisata);

                if ($pendaftaran) {
                    $totalTagihan = $pendaftaran->jwisata->biaya_wisata;

                    $totalTerbayar = PembayaranWisata::where(
                        'id_daftar_wisata',
                        $pendaftaran->id_daftar_wisata
                    )
                    ->whereIn('status', [
                        'lunas',
                        'success',
                        'settlement',
                        'cash'
                    ])
                    ->sum('jumlah_bayar');

                    /*
                    |--------------------------------------------------------------------------
                    | JIKA SUDAH LUNAS
                    |--------------------------------------------------------------------------
                    */
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
            }

            /*
            |--------------------------------------------------------------------------
            | GAGAL
            |--------------------------------------------------------------------------
            */
            elseif (in_array($transactionStatus, [
                'expire',
                'cancel',
                'deny'
            ])) {
                $pembayaran->update([
                    'status' => 'gagal'
                ]);
            }
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function cetakNota($id_pembayaran)
{
    $pembayaran = PembayaranWisata::with([
        'pendaftaranWisata.user',
        'pendaftaranWisata.jwisata'
    ])->findOrFail($id_pembayaran);

    return view('admin.transaksi.nota', compact('pembayaran'));
}
}