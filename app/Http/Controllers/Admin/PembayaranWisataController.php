<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranWisata;
use App\Models\PembayaranWisata;
use App\Models\JWisata;

//PEMBAYARAN WISATA SECARA TUNAI

class PembayaranWisataController extends Controller
{
    public function index()
    {
        $data = PendaftaranWisata::with(['user','jwisata','pembayaranWisata'])
        ->whereIn('status_daftar', [
            'menunggu_pembayaran',
            'menunggu_perjalanan'
        ])
        ->get();

        return view('admin.transaksi.index', compact('data'));
    }
    public function bayarCash(Request $request, $id)
{
    $request->validate([
        'jumlah_bayar' => 'required|numeric|min:1'
    ]);

    $pendaftaran = PendaftaranWisata::with(['pembayaranWisata', 'jwisata'])->findOrFail($id);

    $totalTagihan = $pendaftaran->jwisata->biaya_wisata;
    // Hitung total yang sudah dibayar (termasuk status cicilan dan lunas)
    $totalTerbayar = $pendaftaran->pembayaranWisata->whereIn('status', ['lunas', 'success', 'settlement', 'cicilan', 'cash'])->sum('jumlah_bayar');

    $jumlah = $request->jumlah_bayar;

    if ($jumlah > ($totalTagihan - $totalTerbayar)) {
        return back()->with('error', 'Nominal melebihi sisa tagihan');
    }

    $totalBaru = $totalTerbayar + $jumlah;
    $sisa = max(0, $totalTagihan - $totalBaru);

    $pembayaran = PembayaranWisata::create([
        'id_daftar_wisata' => $id,
        'jumlah_bayar' => $jumlah,
        'total_terbayar' => $totalBaru,
        'sisa_tagihan' => $sisa,
        'cicilan_ke' => $pendaftaran->pembayaranWisata->count() + 1,
        // KEMBALIKAN KE 'cicilan' agar tidak error ENUM database
        'status' => $sisa == 0 ? 'lunas' : 'cicilan', 
        'metode_pembayaran' => 'cash',
        'midtrans_order_id' => 'CASH-' . time(),
        'midtrans_snap_token' => null,
    ]);

    if ($sisa == 0) {
        $pendaftaran->update([
            'status_daftar' => 'menunggu_perjalanan'
        ]);
    }

    return redirect()
    ->route('admin.wisata.nota', $pembayaran->id_pembayaran_wisata)
    ->with('success', 'Pembayaran berhasil, silakan cetak nota');
}
    public function wisata(Request $request)
    {
        // Mengambil keyword pencarian
        $search = $request->get('search');

        $wisata = JWisata::withCount('pendaftaran')
            ->when($search, function($query) use ($search) {
                // Perbaikan: Mencari berdasarkan nama_wisata (bukan nama_user)
                $query->where('nama_wisata', 'like', "%{$search}%"); 
            })
            ->orderBy('tanggal_wisata', 'desc')
            ->paginate(5) 
            ->withQueryString();

        return view('admin.transaksi.wisata', compact('wisata'));
    }
    public function show($id, Request $request)
    {
        // Mengambil keyword pencarian untuk nama anggota
        $search = $request->get('search');
        
        $wisata = JWisata::findOrFail($id);

        $data = PendaftaranWisata::with(['user', 'jwisata', 'pembayaranWisata'])
            ->where('id_wisata', $id)
            ->whereIn('status_daftar', [
                'menunggu_pembayaran',
                'menunggu_perjalanan'
            ])
            ->when($search, function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('nama_user', 'like', "%{$search}%"); // Cari berdasarkan nama user/anggota
                });
            })
            ->paginate(5) // Tambahkan paginate di sini juga
            ->withQueryString(); // Supaya saat pindah halaman, hasil pencarian tidak hilang

        return view('admin.transaksi.show', compact('data', 'wisata'));
    }
public function getHistoriJson($id_daftar_wisata)
{
    // Ambil SEMUA riwayat pembayaran (Tunai maupun Midtrans) berdasarkan id pendaftaran wisata
    $histori = PembayaranWisata::where('id_daftar_wisata', $id_daftar_wisata)
        ->orderBy('cicilan_ke', 'asc')
        ->get();

    return response()->json($histori);
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