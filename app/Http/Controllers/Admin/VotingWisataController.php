<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotingWisata;
use App\Models\VotingWisataOpsi;
use App\Models\VotingWisataDetail;
use App\Models\JWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VotingWisataController extends Controller
{
    public function index()
    {
        $votings = VotingWisata::withCount([
            'opsi',
            'detailVoting as total_pemilih'
        ])->latest()->get();

        return view('admin.voting-wisata.index', compact('votings'));
    }

    public function create()
    {
        return view('admin.voting-wisata.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_voting' => 'required|string|max:255',
            'opsi_lokasi' => 'required|array|min:2|max:3',
            'opsi_lokasi.*' => 'required|string|max:255',
            'opsi_tanggal' => 'required|array|min:2|max:3',
            'opsi_tanggal.*' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $voting = VotingWisata::create([
                'judul_voting' => $request->judul_voting,
                'status' => 'draft',
            ]);

            foreach ($request->opsi_lokasi as $lokasi) {
                VotingWisataOpsi::create([
                    'id_voting' => $voting->id_voting,
                    'jenis_opsi' => 'lokasi',
                    'nilai_opsi' => $lokasi,
                ]);
            }

            foreach ($request->opsi_tanggal as $tanggal) {
                VotingWisataOpsi::create([
                    'id_voting' => $voting->id_voting,
                    'jenis_opsi' => 'tanggal',
                    'nilai_opsi' => $tanggal,
                ]);
            }
        });

        return redirect()
            ->route('admin.voting-wisata.index')
            ->with('success', 'Voting wisata berhasil dibuat.');
    }

    public function show($id)
    {
        $voting = VotingWisata::with(['opsi', 'detailVoting'])->findOrFail($id);

        $opsiLokasi = $voting->opsi->where('jenis_opsi', 'lokasi')->sortByDesc('jumlah_vote');
        $opsiTanggal = $voting->opsi->where('jenis_opsi', 'tanggal')->sortByDesc('jumlah_vote');

        return view('admin.voting-wisata.show', compact(
            'voting',
            'opsiLokasi',
            'opsiTanggal'
        ));
    }

    public function edit($id)
    {
        $voting = VotingWisata::with('opsi')->findOrFail($id);

        return view('admin.voting-wisata.edit', compact('voting'));
    }

    public function update(Request $request, $id)
    {
        $voting = VotingWisata::findOrFail($id);

        // 1. Validasi
        $request->validate([
            'judul_voting' => 'required|string|max:255',
            'status' => 'required|in:draft,aktif,selesai',
        ]);

        DB::transaction(function () use ($request, $voting) {
            // 2. Update Data Utama
            $voting->update([
                'judul_voting' => $request->judul_voting,
                'status' => $request->status,
            ]);

            // 3. Kelola Opsi yang sudah ada (Existing)
            // Kita ambil ID yang dikirim dari form
            $existingIds = $request->input('existing_opsi_id', []);
            
            // Hapus opsi di database yang tidak ada lagi di form (yang di-klik hapus/sampah)
            $voting->opsi()->whereNotIn('id_opsi', $existingIds)->delete();

            // Update teks untuk opsi lama
            if ($request->has('existing_opsi_nilai')) {
                foreach ($request->existing_opsi_nilai as $opsiId => $nilai) {
                    VotingWisataOpsi::where('id_opsi', $opsiId)->update([
                        'nilai_opsi' => $nilai
                    ]);
                }
            }

            // 4. Tambah Opsi Baru (jika ada input baru dari tombol +)
            if ($request->has('new_opsi_lokasi')) {
                foreach ($request->new_opsi_lokasi as $nilai) {
                    if ($nilai) {
                        $voting->opsi()->create([
                            'jenis_opsi' => 'lokasi',
                            'nilai_opsi' => $nilai,
                            'jumlah_vote' => 0
                        ]);
                    }
                }
            }

            if ($request->has('new_opsi_tanggal')) {
                foreach ($request->new_opsi_tanggal as $nilai) {
                    if ($nilai) {
                        $voting->opsi()->create([
                            'jenis_opsi' => 'tanggal',
                            'nilai_opsi' => $nilai,
                            'jumlah_vote' => 0
                        ]);
                    }
                }
            }
        });

        if ($request->status === 'selesai') {
            $this->simpanHasilKeJadwalWisata($voting);
        }

        return redirect()
            ->route('admin.voting-wisata.index')
            ->with('success', 'Data voting dan opsi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $voting = VotingWisata::findOrFail($id);
        $voting->delete();

        return redirect()
            ->route('admin.voting-wisata.index')
            ->with('success', 'Voting berhasil dihapus.');
    }

    private function simpanHasilKeJadwalWisata(VotingWisata $voting)
    {
        $lokasiTerpilih = $voting->opsi()
            ->where('jenis_opsi', 'lokasi')
            ->orderByDesc('jumlah_vote')
            ->first();

        $tanggalTerpilih = $voting->opsi()
            ->where('jenis_opsi', 'tanggal')
            ->orderByDesc('jumlah_vote')
            ->first();

        if (!$lokasiTerpilih || !$tanggalTerpilih) {
            return;
        }

        $lastId = JWisata::max('id_wisata') ?? 0;

        JWisata::create([
            'id_wisata' => $lastId + 1,
            'nama_wisata' => $voting->judul_voting,
            'lokasi_wisata' => $lokasiTerpilih->nilai_opsi,
            'keterangan_wisata' => 'Hasil voting anggota',
            'tanggal_wisata' => $tanggalTerpilih->nilai_opsi,
            'biaya_wisata' => 0,
            'kuota' => 20, // sesuaikan kebutuhan
        ]);
    }
}