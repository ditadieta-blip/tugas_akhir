<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranIuran;
use App\Models\AbsensiSenam;
use App\Models\JSenam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbsensiSenamController extends Controller
{
    // Simpan absensi (user klik hadir/tidak)
public function store(Request $request, $id_senam)
{
    $request->validate([
        'status' => 'required|in:hadir,tidak'
    ]);

    DB::beginTransaction();

    try {

        $user = auth()->user();

        $senam = JSenam::findOrFail($id_senam);

        // simpan absensi
        AbsensiSenam::updateOrCreate(
            [
                'id_senam' => $id_senam,
                'id_user' => $user->id_user
            ],
            [
                'status' => $request->status,
                'is_confirmed' => true
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | AUTO POTONG SALDO
        |--------------------------------------------------------------------------
        */
        if ($request->status === 'hadir') {

            $sudahBayar = PembayaranIuran::where('id_user', $user->id_user)
                ->where('id_senam', $id_senam)
                ->exists();

            if (!$sudahBayar) {

                $nominalIuran =
                    $senam->biaya_iuran
                    ?? $senam->nominal_iuran
                    ?? 2500;

                // jika saldo cukup
                if ($user->saldo_iuran >= $nominalIuran) {

                    // potong saldo
                    $user->saldo_iuran -= $nominalIuran;
                    $user->save();

                    // buat pembayaran otomatis
                    PembayaranIuran::create([
                        'id_user' => $user->id_user,
                        'id_senam' => $id_senam,
                        'nominal_bayar' => $nominalIuran,
                        'nominal_dibayar' => $nominalIuran,
                        'metode' => 'saldo',
                        'status' => 'success',
                        'tanggal_bayar' => now(),
                    ]);
                }
            }
        }

        DB::commit();

        return back()->with(
            'success',
            'Absensi berhasil dikirim'
        );

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with(
            'error',
            $e->getMessage()
        );
    }
}

    // Otomatis tandai tidak hadir jika lewat batas waktu absensi
    private function autoSetTidakHadir($id_senam)
    {
        $senam = JSenam::find($id_senam);

        if (!$senam) {
            return;
        }

        $batasAbsensi = Carbon::parse($senam->tanggal . ' 13:00:00');

        if (now()->greaterThan($batasAbsensi)) {
            $peserta = User::where('role', 'anggota')->get();

            foreach ($peserta as $user) {
                AbsensiSenam::firstOrCreate(
                    [
                        'id_senam' => $id_senam,
                        'id_user' => $user->id_user,
                    ],
                    [
                        'status' => 'tidak',
                        'is_confirmed' => true,
                    ]
                );
            }
        }
    }

    // 📌 Lihat absensi per jadwal (admin)
    public function show($id_senam)
    {
        $this->autoSetTidakHadir($id_senam);

        $senam = JSenam::with('absensi.user')->findOrFail($id_senam);

        return view('instruktur.absensi.index', compact('senam'));
    }

    // 📌 Rekap jumlah hadir
    public function rekap($id_senam)
    {
        $this->autoSetTidakHadir($id_senam);

        $hadir = AbsensiSenam::where('id_senam', $id_senam)
            ->where('status', 'hadir')
            ->count();

        $tidak = AbsensiSenam::where('id_senam', $id_senam)
            ->where('status', 'tidak')
            ->count();

        return response()->json([
            'hadir' => $hadir,
            'tidak_hadir' => $tidak
        ]);
    }
}
