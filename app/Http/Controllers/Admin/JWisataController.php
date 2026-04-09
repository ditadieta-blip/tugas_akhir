<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\JWisata;
use Illuminate\Http\Request;

class JWisataController extends Controller
{
    public function index(Request $request)
    {
        $query = Jwisata::query();

        // SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_wisata', 'like', '%' . $request->search . '%')
                ->orWhere('lokasi_wisata', 'like', '%' . $request->search . '%')
                ->orWhere('keterangan_wisata', 'like', '%' . $request->search . '%');
            });
        }

        $jwisata = $query
            ->orderBy('tanggal_wisata', 'desc') 
            ->paginate(5)
            ->withQueryString();

        // Statistik tetap jalan
        $jatim = Jwisata::where('lokasi_wisata', 'like', '%Jawa Timur%')->count();
        $jateng = Jwisata::where('lokasi_wisata', 'like', '%Jawa Tengah%')->count();
        $jabar = Jwisata::where('lokasi_wisata', 'like', '%Jawa Barat%')->count();

        return view('admin.jwisata.index', compact(
            'jwisata',
            'jatim',
            'jateng',
            'jabar'
        ));
    }

    public function create()
    {
        return view('admin.jwisata.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_wisata' => 'required|unique:jwisata,id_wisata',
            'nama_wisata' => 'required',
            'lokasi_wisata' => 'required',
            'tanggal_wisata' => 'required|date',
            'biaya_wisata' => 'required|numeric',
        ], [
            'id_wisata.required' => 'ID Wisata wajib diisi.',
            'id_wisata.unique' => 'ID Wisata sudah ada.',
            'nama_wisata.required' => 'Nama Wisata wajib diisi.',
            'lokasi_wisata.required' => 'Lokasi Wisata wajib diisi.',
            'tanggal_wisata.required' => 'Tanggal Wisata wajib diisi.',
            'tanggal_wisata.date' => 'Format tanggal tidak valid.',
            'biaya_wisata.required' => 'Biaya Wisata wajib diisi.',
            'biaya_wisata.numeric' => 'Biaya Wisata harus berupa angka.',
        ]);

        JWisata::create($request->all());

        return redirect()->route('admin.jwisata.index')
                        ->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = JWisata::findOrFail($id);
        return view('admin.jwisata.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_wisata' => 'required',
            'lokasi_wisata' => 'required',
            'tanggal_wisata' => 'required|date',
            'biaya_wisata' => 'required|numeric',
        ], [
            'nama_wisata.required' => 'Nama Wisata wajib diisi.',
            'lokasi_wisata.required' => 'Lokasi Wisata wajib diisi.',
            'tanggal_wisata.required' => 'Tanggal Wisata wajib diisi.',
            'tanggal_wisata.date' => 'Format tanggal tidak valid.',
            'biaya_wisata.required' => 'Biaya Wisata wajib diisi.',
            'biaya_wisata.numeric' => 'Biaya Wisata harus berupa angka.',
        ]);

        $data = JWisata::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.jwisata.index')
                        ->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        JWisata::destroy($id);

        return redirect()->route('admin.jwisata.index')
                        ->with('success', 'Data berhasil dihapus');
    }

    public function toggle($id)
    {
        $wisata = JWisata::findOrFail($id);

        $wisata->is_open = !$wisata->is_open;
        $wisata->save();

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diubah');
    }

}
