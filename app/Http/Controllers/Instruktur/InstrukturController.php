<?php

namespace App\Http\Controllers\Instruktur;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\JSenam;
use App\Models\Role;
use App\Models\User;
use App\Models\AbsensiSenam;


class InstrukturController extends Controller
{
    public function index(Request $request)
    {
        $query = JSenam::with('user')
        ->withCount(['absensi as total_hadir' => function ($q) {
            $q->where('status', 'hadir')
            ->where('is_confirmed', true);
        }]);

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

    $jadwal = JSenam::create($request->all());

    // AMBIL SEMUA ANGGOTA
    $anggota = User::whereHas('role', function ($q) {
        $q->where('nama_role', 'anggota');
    })->get();

    foreach ($anggota as $user) {

        // LEWATI JIKA NOMOR KOSONG
        if (!$user->no_hp) {
            continue;
        }

        // FORMAT NOMOR
        $nomor = $user->no_hp;

        // FORMAT TANGGAL INDONESIA
        $tanggal = Carbon::parse($jadwal->tanggal)
            ->locale('id')
            ->translatedFormat('d F Y');

        // PESAN WHATSAPP
        $pesan = "Halo {$user->nama_user},\n\n"
        . "📢 Jadwal senam baru telah ditambahkan simak informasinya berikut ini\n\n"
        . "📅 Tanggal : {$tanggal}\n"
        . "👩‍🏫 Instruktur : {$jadwal->user->nama_user}\n"
        . "📍 Tempat : {$jadwal->tempat_senam}\n"
        . "📝 Keterangan : {$jadwal->keterangan_senam}\n\n"
        . "Terima kasih dan sampai jumpa di pertemuan senam yang akan datang💪";

        // KIRIM KE FONNTE
        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $pesan,
            'countryCode' => '62',
        ]);
    }

    return redirect()->route('instruktur.jsenam.index')
        ->with('success', 'Jadwal berhasil ditambahkan dan notifikasi WhatsApp terkirim');
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

    // DASHBOARD INSTRUKTUR
public function dashboard()
{
    // =========================
    // TOTAL SEMUA SESI SENAM
    // =========================
    $totalSesi = JSenam::count();

    // =========================
    // DATA GRAFIK
    // =========================
    $dataGrafik = JSenam::withCount([

        'absensi as total_hadir' => function ($query) {

            $query->where('status', 'hadir')
                  ->where('is_confirmed', true);
        }

    ])

    ->orderBy('tanggal', 'asc')

    ->get();

    // =========================
    // LABEL GRAFIK
    // =========================
    $pertemuanLabels = $dataGrafik->map(function ($item) {

        return Carbon::parse($item->tanggal)
            ->locale('id')
            ->translatedFormat('d M Y');

    })->toArray();

    // =========================
    // TOTAL KEHADIRAN
    // =========================
    $totalKehadiran = $dataGrafik
        ->pluck('total_hadir')
        ->toArray();

    // =========================
    // RETURN VIEW
    // =========================
    return view(
        'instruktur.dashboard.index',
        compact(
            'totalSesi',
            'pertemuanLabels',
            'totalKehadiran'
        )
    );
}
    
    // CONTROLLER TAMPILAN ANGGOTA
    public function jadwalAnggota(Request $request)
    {
        $search = $request->search;

        $jadwal = JSenam::with([
            'user',
            'absensi' => function ($q) {
                $q->where('id_user', auth()->user()->id_user);
            }
        ])
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
