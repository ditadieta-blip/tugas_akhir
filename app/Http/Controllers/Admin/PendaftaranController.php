<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JWisata;
use App\Models\PendaftaranWisata;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Daftar wisata yang memiliki pendaftar
     */
    public function index(Request $request)
    {
        $query = JWisata::withCount('pendaftaran');

        if ($request->search) {
            $query->where('nama_wisata', 'like', '%' . $request->search . '%');
        }

        $wisata = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('admin.pendaftaran.index', compact('wisata'));
    }

    /**
     * Detail peserta wisata
     */
    public function show(Request $request, $id)
    {
        $wisata = JWisata::findOrFail($id);

        $query = PendaftaranWisata::with(['user', 'jwisata'])
            ->where('id_wisata', $id);

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nama_user', 'like', '%' . $request->search . '%');
            });
        }

        $pendaftaran = $query
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('admin.pendaftaran.show', compact(
            'pendaftaran',
            'wisata'
        ));
    }

    /**
     * Hapus data pendaftaran
     */
    public function destroy($id)
    {
        try {

            $pendaftaran = PendaftaranWisata::findOrFail($id);

            $pendaftaran->delete();

            return back()->with(
                'success',
                'Data pendaftaran berhasil dihapus.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Gagal menghapus data: ' . $e->getMessage()
            );
        }
    }
}