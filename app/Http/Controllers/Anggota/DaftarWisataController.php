<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\JWisata;
use Carbon\Carbon;
use App\Models\JSenam;
use App\Models\PendaftaranWisata;
use Illuminate\Http\Request;
use App\Models\VotingWisata;        // ✅ Diperbarui sesuai model asli
use App\Models\VotingWisataDetail;
use App\Models\PembayaranWisata;
use Illuminate\Support\Facades\Auth;

class DaftarWisataController extends Controller
{
    // ✅ DASHBOARD ANGGOTA (FIX - AKURAT)
    public function dashboard()
{
    // 1. Ambil ID user dari anggota yang sedang login
    $id_user = Auth::id(); 

    // 2. Ambil jadwal senam terdekat
    $senam = JSenam::with('user') 
                    ->where('tanggal', '>=', Carbon::today())
                    ->orderBy('tanggal', 'asc')
                    ->first();

    // 3. Ambil agenda voting wisata yang statusnya sedang 'aktif'
    $votingAktif = VotingWisata::where('status', 'aktif')->first();

    // 4. Cek apakah anggota ini sudah ikut memberikan suara pada voting yang aktif
    $sudahVoting = false;
    if ($votingAktif) {
        $sudahVoting = VotingWisataDetail::where('id_voting', $votingAktif->id_voting)
                                         ->where('id_user', $id_user)
                                         ->exists();
    }

    
    // ===================================================================
    // 5. Ambil status pembayaran wisata terbaru (LOGIKA NYICIL & SISA TAGIHAN)
    // ===================================================================
    $pendaftaran = PendaftaranWisata::where('id_user', $id_user)->first();

    $statusPembayaran = 'belum_bayar'; // Default jika belum daftar/belum ada transaksi

    if ($pendaftaran) {
        // Cari transaksi terakhir yang sukses/lunas (agar tidak membaca transaksi yang expired/gagal)
        $pembayaranTerakhir = PembayaranWisata::where('id_daftar_wisata', $pendaftaran->id_daftar_wisata)
                                              ->whereIn('status', ['lunas', 'success']) // Sesuaikan dengan string sukses di tokomu
                                              ->orderBy('created_at', 'desc')
                                              ->first();

        if ($pembayaranTerakhir) {
            // Kita cek sisa tagihannya, bukan sekadar status transaksinya
            if ($pembayaranTerakhir->sisa_tagihan > 0) {
                $statusPembayaran = 'cicilan'; // Status bahwa dia sedang mencicil dan belum lunas
            } else {
                $statusPembayaran = 'lunas'; // Benar-benar lunas tanpa sisa tagihan
            }
        }
    }

    // ✅ Kirimkan semua variabel ke view dashboard anggota
    return view('anggota.dashboard.index', compact(
        'senam', 
        'votingAktif', 
        'sudahVoting', 
        'statusPembayaran'
    ));
}

    public function index()
    {
        $wisata = JWisata::orderBy('tanggal_wisata', 'desc')->get();

        // Ambil status pendaftaran anggota saat ini
        $pendaftaranIds = auth()->user()->pendaftaran()
                            ->pluck('status_daftar', 'id_wisata')
                            ->toArray();

        return view('anggota.dafwisata.index', compact('wisata', 'pendaftaranIds'));
    }
    

    // DAFTAR WISATA ANGGOTA
    public function store(Request $request)
    {
        $request->validate([
            'id_wisata' => 'required|exists:jwisata,id_wisata'
        ]);
        $userId = auth()->user()->id_user;

        $wisata = JWisata::findOrFail($request->id_wisata);

        // CEK KUOTA
        if ($wisata->kuota <= 0) {
            return back()->with('error', 'Kuota wisata sudah penuh.');
        }
        // Cek apakah sudah pernah daftar wisata ini
        $cek = PendaftaranWisata::where('id_user', $userId)
                ->where('id_wisata', $request->id_wisata)
                ->first();
        if ($cek) {
            return back()->with('error', 'Anda sudah terdaftar pada wisata ini.');
        }

        // Simpan pendaftaran
        PendaftaranWisata::create([
            'id_user'        => $userId,
            'id_wisata'      => $request->id_wisata,
            'status_daftar'  => 'menunggu_pembayaran'
        ]);

        // Kurangi kuota otomatis
        $wisata->decrement('kuota');
        return back()->with(
            'success',
            'Pendaftaran berhasil. Silakan lakukan pembayaran wisata.'
        );
    }
}