<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranIuran;
use App\Models\JSenam;
use App\Models\AbsensiSenam;
use App\Models\User;

class PembayaranTunaiController extends Controller
{
public function index(Request $request)
{
    // =========================
    // LIST JADWAL SENAM (FITUR LAMA)
    // =========================
    $search = $request->input('search');

    $senam = JSenam::withCount([
        'absensi as jumlah_hadir' => function ($q) {
            $q->where('status', 'hadir');
        }
    ])
    ->when($search, function ($query, $search) {
        return $query->where('tempat_senam', 'like', "%{$search}%")
                     ->orWhere('tanggal', 'like', "%{$search}%");
    })
    ->orderBy('tanggal', 'desc')
    ->paginate(5)
    ->withQueryString();

    // =========================
    // MULTI TAGIHAN
    // =========================
    $namaAnggota = $request->input('anggota');

    $tagihanMulti = collect();

    if ($namaAnggota) {

        $tagihanMulti = AbsensiSenam::with([
                'user',
                'senam'
            ])
            ->where('status', 'hadir')

            ->whereHas('user', function ($q) use ($namaAnggota) {
                $q->where('id_role', 1013)
                  ->where('nama_user', 'like', "%{$namaAnggota}%");
            })

            ->whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from('pembayaran_iuran_senam')
                    ->whereColumn(
                        'pembayaran_iuran_senam.id_user',
                        'absensi_senam.id_user'
                    )
                    ->whereColumn(
                        'pembayaran_iuran_senam.id_senam',
                        'absensi_senam.id_senam'
                    )
                    ->where('status', 'success');
            })

            ->orderBy('id_user')
            ->get();
    }

    return view(
        'admin.transaksi.tunai',
        compact(
            'senam',
            'tagihanMulti',
            'namaAnggota'
        )
    );
}

    // =========================
    // DETAIL (Tetap sama)
    // =========================
    public function detail($id_senam)
    {
        $senam = JSenam::findOrFail($id_senam);

        $anggota = AbsensiSenam::with('user')
            ->where('id_senam', $id_senam)
            ->where('status', 'hadir')
            ->get();

        $sudahBayar = PembayaranIuran::where('id_senam', $id_senam)
            ->where('status', 'success')
            ->pluck('id_user')
            ->toArray();

        return view('admin.transaksi.detail', compact(
            'senam',
            'anggota',
            'sudahBayar'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_senam' => 'required',
            'nominal' => 'required|array'
        ]);

        foreach ($request->nominal as $id_user => $nominalDibayar) {

            // skip jika kosong
            if (!$nominalDibayar || $nominalDibayar < 2500) {
                continue;
            }

            // cek sudah bayar
            $cek = PembayaranIuran::where('id_user', $id_user)
                ->where('id_senam', $request->id_senam)
                ->where('status', 'success')
                ->exists();

            if ($cek) continue;

            // hitung saldo
            $tagihan = 2500;
            $sisaSaldo = $nominalDibayar - $tagihan;

            // simpan pembayaran
            PembayaranIuran::create([
                'id_user' => $id_user,
                'id_senam' => $request->id_senam,

                'nominal_bayar' => $tagihan,
                'nominal_dibayar' => $nominalDibayar,

                'metode' => 'tunai',
                'status' => 'success',
                'tanggal_bayar' => now(),
            ]);

            // tambah saldo user jika ada sisa
            if ($sisaSaldo > 0) {

                $user = User::find($id_user);

                $user->saldo_iuran += $sisaSaldo;
                $user->save();
            }
        }

        return redirect()
            ->route('admin.tunai.detail', $request->id_senam)
            ->with('success', 'Pembayaran tunai berhasil');
    }

    public function multiBayar(Request $request)
{
    $request->validate([
        'tagihan' => 'required|array'
    ]);

    foreach ($request->tagihan as $tagihan) {

        [$idUser, $idSenam] = explode('|', $tagihan);

        $sudahAda = PembayaranIuran::where('id_user', $idUser)
            ->where('id_senam', $idSenam)
            ->where('status', 'success')
            ->exists();

        if ($sudahAda) {
            continue;
        }

        PembayaranIuran::create([
            'id_user' => $idUser,
            'id_senam' => $idSenam,
            'nominal_bayar' => 2500,
            'nominal_dibayar' => 2500,
            'metode' => 'tunai',
            'status' => 'success',
            'tanggal_bayar' => now(),
        ]);
    }

    return redirect()
        ->route('admin.tunai.index')
        ->with('success', 'Multi pembayaran berhasil diproses.');
}
}