<?php

namespace App\Http\Controllers\Instruktur;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JSenam;
use App\Models\Role;
use App\Models\User;


class InstrukturController extends Controller
{
    public function index(Request $request)
    {
        $query = JSenam::with('user');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('tanggal', 'like', "%$search%")
                ->orWhere('tempat_senam', 'like', "%$search%")
                ->orWhere('keterangan_senam', 'like', "%$search%")
                ->orWhereHas('user', function($q2) use ($search) {
                    $q2->where('nama_user', 'like', "%$search%");
                });
            });
        }

        $jadwal = $query->orderBy('tanggal', 'desc')
                        ->paginate(5)
                        ->withQueryString();

        return view('instruktur.jsenam.index', compact('jadwal'));
    }


    public function create()
    {
        $roleInstruktur = Role::where('nama_role', 'instruktur')->first();

        $instruktur = User::where('id_role', $roleInstruktur->id_role)->get();

        return view('instruktur.jsenam.create', compact('instruktur'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'tanggal' => 'required|date',
            'tempat_senam' => 'required',
            'keterangan_senam' => 'required'
        ]);

        JSenam::create($request->all());

        return redirect()->route('instruktur.jsenam.index')
                        ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jadwal = JSenam::findOrFail($id);

        $roleInstruktur = Role::where('nama_role', 'instruktur')->first();
        $instruktur = User::where('id_role', $roleInstruktur->id_role)->get();

        return view('instruktur.jsenam.edit', compact('jadwal', 'instruktur'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required',
            'tanggal' => 'required|date',
            'tempat_senam' => 'required',
            'keterangan_senam' => 'required'
        ]);

        $jadwal = JSenam::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->route('instruktur.jsenam.index')
                        ->with('success', 'Jadwal berhasil diperbarui');
    }


    public function destroy($id)
    {
        $jadwal = JSenam::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('instruktur.jsenam.index')
                         ->with('success', 'Jadwal berhasil dihapus');
    }
    public function dashboard()
    {
        return view('instruktur.dashboard.index');
    }

    // controller di menu anggota
    public function jadwalAnggota(Request $request)
    {
        $search = $request->search;

        $jadwal = JSenam::with('user')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('tempat_senam', 'like', "%$search%")
                    ->orWhere('keterangan_senam', 'like', "%$search%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('nama_user', 'like', "%$search%");
                    });

                });

            })
            ->orderBy('tanggal','desc')
            ->paginate(5)
            ->withQueryString();

        return view('anggota.jadwal.index', compact('jadwal'));
    }
}
