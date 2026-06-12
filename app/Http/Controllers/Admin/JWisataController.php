<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JWisataController extends Controller
{
    public function index(Request $request)
    {
        $query = JWisata::query();

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

        // Statistik
        $jatim = JWisata::where('lokasi_wisata', 'like', '%Jawa Timur%')->count();
        $jateng = JWisata::where('lokasi_wisata', 'like', '%Jawa Tengah%')->count();
        $jabar = JWisata::where('lokasi_wisata', 'like', '%Jawa Barat%')->count();

        return view('admin.jwisata.index', compact(
            'jwisata', 'jatim', 'jateng', 'jabar'
        ));
    }

    public function create()
    {
        return view('admin.jwisata.create');
    }

    public function store(Request $request)
    {
        // 1. Bersihkan titik dari input biaya_wisata agar menjadi angka murni
        if ($request->filled('biaya_wisata')) {
            $cleanedBiaya = preg_replace('/[^\d]/', '', $request->biaya_wisata);
            $request->merge(['biaya_wisata' => $cleanedBiaya]);
        }

        // 2. Validasi Input
        $request->validate([
            'nama_wisata'      => 'required|string|max:255',
            'lokasi_wisata'    => 'required|string|max:255',
            'tanggal_wisata'   => 'required|date',
            'biaya_wisata'     => 'required|numeric|min:0',
            'kuota'            => 'required|integer|min:1',
            'keterangan_wisata'=> 'nullable|string',
        ], [
            'nama_wisata.required'    => 'Nama Wisata wajib diisi.',
            'lokasi_wisata.required'  => 'Lokasi Wisata wajib diisi.',
            'tanggal_wisata.required' => 'Tanggal Wisata wajib diisi.',
            'biaya_wisata.required'   => 'Biaya Wisata wajib diisi.',
            'biaya_wisata.numeric'    => 'Biaya Wisata harus berupa angka valid.',
            'kuota.required'          => 'Kuota wajib diisi.',
            'kuota.integer'           => 'Kuota harus berupa angka bulat.',
            'kuota.min'               => 'Kuota minimal diisi 1.',
        ]);

        try {
            // 3. LOGIKA INPUT ID MANUAl OTOMATIS (Mencegah Error Database Tanpa Auto Increment)
            // Cari angka ID terbesar yang ada di tabel saat ini
            $maxId = JWisata::max('id_wisata');
            
            // Jika tabel masih kosong, mulai dari angka 1. Jika sudah ada isinya, ID terbesar + 1
            $nextId = $maxId ? ($maxId + 1) : 1;

            // 4. Masukkan ID buatan kita ke dalam array data penyusunan
            $data = $request->only([
                'nama_wisata', 'lokasi_wisata', 'tanggal_wisata', 'biaya_wisata', 'kuota', 'keterangan_wisata'
            ]);
            
            $data['id_wisata'] = $nextId; // Suntik ID Integer Manual ke sini
            $data['is_open'] = 0; 

            // Simpan ke database
            JWisata::create($data);

            return redirect()->route('admin.jwisata.index')
                             ->with('success', 'Jadwal wisata baru telah ditambahkan');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan jadwal wisata: ' . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->withErrors(['error' => 'Gagal menyimpan ke database. Detail Kendala: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $data = JWisata::findOrFail($id);
        return view('admin.jwisata.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        if ($request->filled('biaya_wisata')) {
            $cleanedBiaya = preg_replace('/[^\d]/', '', $request->biaya_wisata);
            $request->merge(['biaya_wisata' => $cleanedBiaya]);
        }

        $request->validate([
            'nama_wisata'      => 'required|string|max:255',
            'lokasi_wisata'    => 'required|string|max:255',
            'tanggal_wisata'   => 'required|date',
            'biaya_wisata'     => 'required|numeric|min:0',
            'kuota'            => 'required|integer|min:1',
            'keterangan_wisata'=> 'nullable|string',
        ], [
            'nama_wisata.required'   => 'Nama Wisata wajib diisi.',
            'lokasi_wisata.required' => 'Lokasi Wisata wajib diisi.',
            'tanggal_wisata.required'=> 'Tanggal Wisata wajib diisi.',
            'biaya_wisata.required'  => 'Biaya Wisata wajib diisi.',
            'biaya_wisata.numeric'   => 'Biaya Wisata harus berupa angka.',
            'kuota.required'         => 'Kuota wajib diisi.',
            'kuota.integer'          => 'Kuota harus berupa angka.',
        ]);

        try {
            $data = JWisata::findOrFail($id);
            $data->update($request->all());

            return redirect()->route('admin.jwisata.index')
                             ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui jadwal wisata ID '.$id.': ' . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->withErrors(['error' => 'Sistem gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            JWisata::destroy($id);
            return redirect()->route('admin.jwisata.index')
                             ->with('success', 'Data jadwal wisata berhasil dihapus dari sistem.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus jadwal wisata ID '.$id.': ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Data ini gagal dihapus karena terikat dengan pendaftaran pengguna.']);
        }
    }

    public function toggle($id)
    {
        try {
            $wisata = JWisata::findOrFail($id);
            $wisata->is_open = !$wisata->is_open;
            $wisata->save();

            return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengubah status pendaftaran aktif.']);
        }
    }
}