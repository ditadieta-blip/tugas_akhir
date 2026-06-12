<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\VotingWisata;
use App\Models\VotingWisataDetail;
use App\Models\VotingWisataOpsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteWisataController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id_user;

        $votings = VotingWisata::where('status', 'aktif')
            // 1. Ambil opsi untuk grafik, ambil detail untuk cek status sudah vote atau belum
            ->with(['opsi', 'detailVoting' => function($query) use ($userId) {
                $query->where('id_user', $userId);
            }])
            ->withCount('detailVoting')
            ->latest()
            ->get();

        return view('anggota.vote_wisata.index', compact('votings'));
    }

    public function show($id)
    {
        $voting = VotingWisata::with('opsi')
            ->where('status', 'aktif')
            ->findOrFail($id);

        $sudahVoting = VotingWisataDetail::where('id_voting', $id)
            ->where('id_user', Auth::user()->id_user)
            ->exists();

        $opsiLokasi = $voting->opsi->where('jenis_opsi', 'lokasi');
        $opsiTanggal = $voting->opsi->where('jenis_opsi', 'tanggal');

        return view('anggota.vote_wisata.show', compact(
            'voting',
            'opsiLokasi',
            'opsiTanggal',
            'sudahVoting'
        ));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'id_opsi_lokasi' => 'required|exists:voting_wisata_opsi,id_opsi',
            'id_opsi_tanggal' => 'required|exists:voting_wisata_opsi,id_opsi',
        ]);

        $voting = VotingWisata::where('status', 'aktif')->findOrFail($id);

        $sudahVoting = VotingWisataDetail::where('id_voting', $id)
            ->where('id_user', Auth::user()->id_user)
            ->exists();

        if ($sudahVoting) {
            return redirect()
                ->route('anggota.vote-wisata.show', $id)
                ->with('error', 'Anda sudah memberikan suara pada voting ini.');
        }

        DB::transaction(function () use ($request, $id) {
            VotingWisataDetail::create([
                'id_voting' => $id,
                'id_user' => Auth::user()->id_user,
                'id_opsi_lokasi' => $request->id_opsi_lokasi,
                'id_opsi_tanggal' => $request->id_opsi_tanggal,
            ]);

            VotingWisataOpsi::where('id_opsi', $request->id_opsi_lokasi)
                ->increment('jumlah_vote');

            VotingWisataOpsi::where('id_opsi', $request->id_opsi_tanggal)
                ->increment('jumlah_vote');
        });

        return redirect()
            ->route('anggota.vote-wisata.index')
            ->with('success', 'Voting berhasil dikirim. Terima kasih atas partisipasi Anda.');
    }
}
