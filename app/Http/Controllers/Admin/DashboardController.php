<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JWisata;
use App\Models\PembayaranWisata;
use App\Models\PembayaranIuran;
use App\Models\AbsensiSenam;
use App\Models\VotingWisata;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // =========================
        // SET LOCALE INDONESIA
        // =========================
        Carbon::setLocale('id');

        // =========================
        // TOTAL ANGGOTA
        // =========================
        $jumlahAnggota = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'anggota');
        })->count();

        // =========================
        // TOTAL INSTRUKTUR
        // =========================
        $jumlahInstruktur = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'instruktur');
        })->count();

        // =========================
        // TOTAL WISATA
        // =========================
        $totalWisata = JWisata::count();

        // =========================
        // TOTAL PEMASUKAN

        $totalPemasukan = PembayaranIuran::where(function ($q) {
                $q->whereIn('status', ['success', 'berhasil', 'paid', 'lunas'])
                ->orWhereIn('midtrans_transaction_status', ['settlement', 'capture']);
            })
            ->whereMonth('tanggal_bayar', \Carbon\Carbon::now()->month)
            ->whereYear('tanggal_bayar', \Carbon\Carbon::now()->year)
            ->sum('nominal_bayar') ?? 0;
        // =========================
        // WISATA TERDEKAT
        // =========================
        $wisataTerdekat = JWisata::withCount('pendaftaran')
            ->where('is_open', 1)
            ->whereDate('tanggal_wisata', '>=', now()->toDateString())
            ->orderBy('tanggal_wisata', 'asc')
            ->get()
            ->map(function ($item) {

                $item->tanggal_indonesia = Carbon::parse($item->tanggal_wisata)
                    ->translatedFormat('d F Y');

                return $item;
            });

        // =========================
        // GRAFIK KEHADIRAN
        // =========================
        $pertemuanLabels = [];
        $totalKehadiran = [];

        $absensi = AbsensiSenam::with('senam')
            ->selectRaw('id_senam, COUNT(*) as total_hadir')
            ->where('status', 'hadir')
            ->groupBy('id_senam')
            ->get()
            ->sortByDesc(function ($item) {

                return optional($item->senam)->tanggal;
            })
            ->take(7)
            ->reverse();

        foreach ($absensi as $item) {

            if ($item->senam && $item->senam->tanggal) {

                $pertemuanLabels[] = Carbon::parse(
                    $item->senam->tanggal
                )->translatedFormat('d M Y');

            } else {

                $pertemuanLabels[] = 'Tidak diketahui';
            }

            $totalKehadiran[] = $item->total_hadir;
        }

        // =========================
        // JIKA DATA KEHADIRAN KOSONG
        // =========================
        if (count($pertemuanLabels) == 0) {

            $pertemuanLabels = ['Belum Ada Data'];
            $totalKehadiran = [0];
        }

        // =========================
        // GRAFIK WISATA
        // =========================
        $wisataLabels = [];
        $totalPesertaWisata = [];

        $grafikWisata = JWisata::withCount('pendaftaran')
            ->orderBy('tanggal_wisata', 'desc')
            ->take(7)
            ->get()
            ->reverse();

        foreach ($grafikWisata as $item) {

            $wisataLabels[] = $item->nama_wisata;
            $totalPesertaWisata[] = $item->pendaftaran_count;
        }

        // =========================
        // JIKA DATA WISATA KOSONG
        // =========================
        if (count($wisataLabels) == 0) {

            $wisataLabels = ['Belum Ada Wisata'];
            $totalPesertaWisata = [0];
        }

        // =========================
        // HASIL VOTING SEMENTARA
        // =========================
        $votingAktif = VotingWisata::with('opsi')
            ->where('status', 'aktif')
            ->latest()
            ->first();

        $hasilVoting = [];

        if ($votingAktif) {

            $totalVote = $votingAktif->opsi->sum('jumlah_vote');

            foreach ($votingAktif->opsi as $opsi) {

                $persen = $totalVote > 0
                    ? round(($opsi->jumlah_vote / $totalVote) * 100)
                    : 0;

                $hasilVoting[] = [
                    'nama'   => $opsi->nilai_opsi,
                    'jenis'  => $opsi->jenis_opsi,
                    'vote'   => $opsi->jumlah_vote,
                    'persen' => $persen,
                ];
            }

            usort($hasilVoting, function ($a, $b) {
                return $b['vote'] <=> $a['vote'];
            });

            // Ambil 4 voting teratas
            $hasilVoting = array_slice($hasilVoting, 0, 4);
        }

        // =========================
        // RINGKASAN PEMBAYARAN
        // =========================
        $pembayaranLunas = PembayaranWisata::where('status', 'lunas')
            ->sum('jumlah_bayar') ?? 0;

        $pembayaranCicilan = PembayaranWisata::where('status', 'cicilan')
            ->sum('jumlah_bayar') ?? 0;

        $pembayaranPending = PembayaranWisata::where('status', 'pending')
            ->sum('jumlah_bayar') ?? 0;

        return view('admin.dashboard.index', compact(
            'jumlahAnggota',
            'jumlahInstruktur',
            'totalWisata',
            'totalPemasukan',
            'wisataTerdekat',
            'pertemuanLabels',
            'totalKehadiran',
            'wisataLabels',
            'totalPesertaWisata',
            'hasilVoting',
            'pembayaranLunas',
            'pembayaranCicilan',
            'pembayaranPending',
            'votingAktif',
            'hasilVoting'
        ));
    }
}