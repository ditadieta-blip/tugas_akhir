<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_user', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(5)->withQueryString();

        $jumlahAnggota = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'anggota');
        })->count();

        $jumlahInstruktur = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'instruktur');
        })->count();

        return view('admin.user.index', compact(
            'users',
            'jumlahAnggota',
            'jumlahInstruktur'
        ));
    }
    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|numeric|digits_between:10,15',
            'alamat' => 'required|string|max:255',
            'id_role' => 'required|exists:role,id_role',
            'password' => 'required|min:6'
        ], [

            // NAMA
            'nama_user.required' => 'Nama lengkap wajib diisi.',
            'nama_user.string' => 'Nama harus berupa teks.',
            'nama_user.max' => 'Nama maksimal 100 karakter.',

            // EMAIL
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',

            // NO HP
            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
            'no_hp.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'no_hp.digits_between' => 'Nomor WhatsApp harus 10 sampai 15 digit.',

            // ALAMAT
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat terlalu panjang.',

            // ROLE
            'id_role.required' => 'Role harus dipilih.',
            'id_role.exists' => 'Role tidak valid.',

            // PASSWORD
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        // simpan user
        User::create([
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'id_role' => $request->id_role,
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('admin.user.index')
            ->with('success','User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = [
            'nama_user' => $request->nama_user,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'id_role' => $request->id_role,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')
                        ->with('success', 'User berhasil diupdate');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')
                        ->with('success', 'User berhasil dihapus');
    }
    public function dashboard()
    {
        return view('anggota.index');
    }


    // =======================
    // PROFIL 
    // =======================
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        // 1. VALIDASI (Tambahkan kolom baru jika ada, misal: no_hp, alamat)
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'no_hp'     => 'nullable|string|max:15',
            'alamat'    => 'nullable|string|max:255',
            'password'  => 'nullable|min:6',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 2. DATA DASAR
        $data = [
            'nama_user' => $request->nama_user,
            'email'     => $request->email,
            'no_hp'     => $request->no_hp,
            'alamat'    => $request->alamat,
        ];

        // 3. PASSWORD (Hanya jika diisi)
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 4. FOTO PROFIL
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists('foto/' . $user->foto)) {
                Storage::disk('public')->delete('foto/' . $user->foto);
            }

            $file = $request->file('foto');
            $namaFile = uniqid('user_') . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke storage/app/public/foto
            $file->storeAs('foto', $namaFile, 'public');
            $data['foto'] = $namaFile;
        }

        // 5. UPDATE KE DATABASE
        $user->update($data);

        // 6. REDIRECT BACK (Penting untuk Modal)
        // Gunakan back() agar user tetap di halaman yang sama saat modal tertutup
        return redirect()->back()->with('success_profil', 'Profil berhasil diperbarui!');
    }
}
