<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\JWisata;
use Carbon\Carbon;
use App\Models\JSenam;
use App\Models\PendaftaranWisata;
use Illuminate\Http\Request;

class DaftarWisataController extends Controller
{
 // ✅ DASHBOARD ANGGOTA (FIX)
    public function dashboard()
    {
        $senam = JSenam::with('user') // ✅ ambil relasi user (instruktur)
                        ->where('tanggal', '>=', Carbon::today())
                        ->orderBy('tanggal', 'asc')
                        ->first();

        return view('anggota.dashboard.index', compact('senam'));
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
    

    // Proses daftar wisata
    public function store(Request $request)
    {
        $request->validate([
            'id_wisata' => 'required|exists:jwisata,id_wisata'
        ]);

        $userId = auth()->user()->id_user;

        // Cek apakah sudah daftar
        $cek = PendaftaranWisata::where('id_user', $userId)
                ->where('id_wisata', $request->id_wisata)
                ->first();

        if ($cek) {
            return back()->with('error', 'Anda sudah mendaftar wisata ini.');
        }

        // Simpan data pendaftaran
        PendaftaranWisata::create([
            'id_user' => $userId,
            'id_wisata' => $request->id_wisata,
            'status_daftar' => 'menunggu' // status awal
        ]);

        return back()->with('success', 'Berhasil mendaftar, silakan tunggu konfirmasi admin.');
    }
}