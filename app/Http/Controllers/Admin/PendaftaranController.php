<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranWisata;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Tampilkan daftar pendaftar wisata dengan search dan pagination.
     */
    public function index(Request $request)
    {
        $query = PendaftaranWisata::with(['user', 'jwisata']);

        // Search berdasarkan nama user atau nama wisata
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request){
                $q->where('nama_user', 'like', '%'.$request->search.'%');
            })->orWhereHas('jwisata', function($q) use ($request){
                $q->where('nama_wisata', 'like', '%'.$request->search.'%');
            });
        }

        // Pagination 7 per halaman, urut terbaru
        $pendaftaran = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('admin.pendaftaran.index', compact('pendaftaran'));
    }

    /**
     * Update status pendaftaran (diterima / ditolak)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_daftar' => 'required|in:diterima,ditolak'
        ]);

        $pendaftaran = PendaftaranWisata::findOrFail($id);

        $pendaftaran->update([
            'status_daftar' => $request->status_daftar
        ]);

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $pendaftaran = PendaftaranWisata::findOrFail($id);
            $pendaftaran->delete();

            return redirect()->back()->with('success', 'Data pendaftaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}