<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\PembayaranWisata;
use App\Models\PembayaranIuran;
use App\Models\AbsensiSenam; 
use App\Models\JSenam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->jenis;
        $from = $request->from;
        $to = $request->to;

        // DATA PEMBAYARAN WISATA
        $wisataQuery = PembayaranWisata::with(['pendaftaranWisata.user', 'pendaftaranWisata.jwisata'])->where('jumlah_bayar', '>', 0);
        if ($from && $to) {
            $wisataQuery->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
        }

        $wisata = $wisataQuery->get()->map(function ($item) {
            $status = strtolower($item->status ?? '');
            $labelStatus = ($status == 'lunas' || $status == 'success') ? 'Lunas' : 'Cicilan';

            return [
                'nama' => optional($item->pendaftaranWisata->user)->nama_user ?? '-',
                'jenis' => 'Wisata',
                'keterangan' => (optional($item->pendaftaranWisata->jwisata)->nama_wisata ?? 'Wisata') . ' - ' . $labelStatus,
                'jumlah' => $item->jumlah_bayar ?? 0,
                'tanggal' => $item->created_at ?? now(),
            ];
        });

        // DATA PEMBAYARAN IURAN
        $iuranQuery = PembayaranIuran::with(['user', 'senam'])->where(function ($q) {
            $q->whereIn('status', ['success', 'berhasil', 'paid', 'lunas'])
              ->orWhereIn('midtrans_transaction_status', ['settlement', 'capture']);
        });

        if ($from && $to) {
            $iuranQuery->whereBetween('tanggal_bayar', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
        }

        $iuran = $iuranQuery->get()->map(function ($item) {
            $tanggalSenam = optional($item->senam)->tanggal ? Carbon::parse($item->senam->tanggal)->locale('id')->translatedFormat('d F Y') : '-';
            return [
                'nama' => optional($item->user)->nama_user ?? '-',
                'jenis' => 'Iuran',
                'keterangan' => (optional($item->senam)->nama_senam ?? 'Iuran Senam') . ' - ' . $tanggalSenam,
                'jumlah' => $item->nominal_bayar ?? 0,
                'tanggal' => $item->tanggal_bayar ?? now(),
            ];
        });

        $data = $wisata->merge($iuran);
        if ($jenis) {
            $data = $data->where('jenis', $jenis);
        }

        $data = $data->sortByDesc('tanggal')->values();

        return view('admin.laporan.index', [
            'data' => $data,
            'total' => $data->sum('jumlah'),
            'totalWisata' => $data->where('jenis', 'Wisata')->sum('jumlah'),
            'totalIuran' => $data->where('jenis', 'Iuran')->sum('jumlah'),
            'totalTransaksi' => $data->count()
        ]);
    }

    public function keaktifan(Request $request)
{
    // =========================
    // FILTER RENTANG TANGGAL
    // =========================
    $from = $request->input('from');
    $to = $request->input('to');

    // Ambil semua jadwal senam yang sesuai dengan filter tanggal
    $jadwalQuery = JSenam::query();
    if ($from && $to) {
        $jadwalQuery->whereBetween('tanggal', [
            Carbon::parse($from)->startOfDay(),
            Carbon::parse($to)->endOfDay()
        ]);
    }
    $senamList = $jadwalQuery->orderBy('tanggal', 'desc')->get();
    $totalJadwal = $senamList->count();

    // Ambil data anggota beserta absensinya
    $users = User::with(['absensiSenam.senam', 'role'])->whereHas('role', function ($q) {
        $q->whereRaw('LOWER(nama_role) = ?', ['anggota']);
    })->get();

    $keaktifan = collect();
    foreach ($users as $user) {
        // Filter absensi sesuai rentang tanggal yang dipilih
        $riwayat = $user->absensiSenam->filter(function ($absen) use ($from, $to) {
            $isHadir = strtolower($absen->status) == 'hadir' && $absen->is_confirmed == 1 && $absen->senam;
            
            if ($isHadir && $from && $to) {
                $tglSenam = Carbon::parse($absen->senam->tanggal);
                return $tglSenam->between(Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay());
            }
            return $isHadir;
        });

        $jumlahHadir = $riwayat->count();
        $persentase = $totalJadwal > 0 ? round(($jumlahHadir / $totalJadwal) * 100) : 0;
        
        $detailRiwayat = $riwayat->map(function ($absen) {
            return (object)[
                'tanggal' => Carbon::parse($absen->senam->tanggal)->locale('id')->translatedFormat('d F Y'),
                'nama_senam' => $absen->senam->nama_senam ?? 'Senam',
            ];
        });

        if ($persentase >= 75) {
            $status = 'Sangat Aktif';
        } elseif ($persentase >= 50) {
            $status = 'Cukup Aktif';
        } else {
            $status = 'Kurang Aktif';
        }

        $keaktifan->push((object)[
            'nama_user' => $user->nama_user,
            'jumlah_hadir' => $jumlahHadir,
            'total_pertemuan' => $totalJadwal,
            'persentase' => $persentase,
            'status' => $status,
            'riwayat' => $detailRiwayat,
        ]);
    }

    $topMember = $keaktifan->sortByDesc('jumlah_hadir')->first();

    return view('admin.laporan.keaktifan', [
        'keaktifan' => $keaktifan,
        'totalAnggota' => $users->count(),
        'topMemberNama' => $topMember ? $topMember->nama_user : '-',
        'from' => $from,
        'to' => $to
    ]);
}

    public function tagihan(Request $request)
    {
        $search = $request->search;
        $bulan  = $request->bulan;
        $sort   = $request->sort;

        $anggota = User::whereHas('role', function ($q) {
            $q->whereRaw('LOWER(nama_role) = ?', ['anggota']);
        })->get();

        $senamQuery = JSenam::query();
        if ($bulan) {
            $parsed = Carbon::parse($bulan);
            $senamQuery->whereMonth('tanggal', $parsed->month)->whereYear('tanggal', $parsed->year);
        }
        $senamList = $senamQuery->orderBy('tanggal', 'desc')->get();

        $dataTagihan = [];
        foreach ($anggota as $item) {
            if ($search && !str_contains(strtolower($item->nama_user), strtolower($search))) {
                continue;
            }

            $belumBayar = [];
            foreach ($senamList as $senam) {
                // CEK ABSENSI HADIR
                $hadir = AbsensiSenam::where('id_user', $item->id_user)
                    ->where('id_senam', $senam->id_senam)
                    ->where('status', 'hadir')
                    ->exists();

                if (!$hadir) {
                    continue;
                }

                $sudahBayar = PembayaranIuran::where('id_user', $item->id_user)
                    ->where('id_senam', $senam->id_senam)
                    ->where(function ($q) {
                        $q->whereIn('status', ['success', 'berhasil', 'paid', 'lunas'])
                        ->orWhereIn('midtrans_transaction_status', ['settlement', 'capture']);
                    })
                    ->exists();

                if (!$sudahBayar) {
                    $belumBayar[] = [
                        'nama_senam' => $senam->nama_senam ?? 'Senam',
                        'tanggal' => Carbon::parse($senam->tanggal)
                            ->locale('id')
                            ->translatedFormat('d F Y'),
                        'iuran' => $senam->biaya_iuran ?? $senam->nominal_iuran ?? 2500,
                    ];
                }
            }

            if (count($belumBayar) > 0) {
                $dataTagihan[] = [
                    'anggota' => $item,
                    'tagihan' => $belumBayar,
                    'jumlah_pertemuan' => count($belumBayar),
                    'nominal_per_pertemuan' => $belumBayar[0]['iuran'] ?? 2500,
                    'total_tunggakan' => collect($belumBayar)->sum('iuran'),
                ];
            }
        }

        // HITUNG STATISTIK TOTAL
        $totalAnggotaMenunggak = count($dataTagihan);
        $totalTagihan = collect($dataTagihan)->sum('jumlah_pertemuan');
        $totalNominal = collect($dataTagihan)->sum('total_tunggakan');

        // PROSES PAGINASI MANUAL UNTUK ARRAY
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($dataTagihan, ($currentPage - 1) * $perPage, $perPage);
        $dataTagihanPaginated = new LengthAwarePaginator(
            $currentItems,
            count($dataTagihan),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('admin.laporan.tagihan', [
            'dataTagihan' => $dataTagihanPaginated,
            'totalAnggotaMenunggak' => $totalAnggotaMenunggak,
            'totalTagihan' => $totalTagihan,
            'totalNominal' => $totalNominal
        ]);
    }

public function sendWhatsappAll(Request $request)
{
    // 1. Ambil semua anggota dengan role 'anggota' + Filter Pencarian Nama jika ada di URL
    $anggotaQuery = User::whereHas('role', function ($q) {
        $q->whereRaw('LOWER(nama_role) = ?', ['anggota']);
    });

    if ($request->has('search') && !empty($request->search)) {
        $anggotaQuery->where('nama_user', 'like', '%' . $request->search . '%');
    }

    $anggota = $anggotaQuery->get();

    // 2. Ambil jadwal senam + Filter Bulan & Tahun berdasarkan input tanggal dari URL
    $senamQuery = JSenam::query();

    if ($request->has('bulan') && !empty($request->bulan)) {
        // Mengonversi input format YYYY-MM-DD menjadi bulan dan tahun saja
        $tahun = date('Y', strtotime($request->bulan));
        $bulan = date('m', strtotime($request->bulan));
        
        $senamQuery->whereMonth('tanggal', $bulan)
                   ->whereYear('tanggal', $tahun);
    }

    $senamList = $senamQuery->orderBy('tanggal', 'desc')->get();

    $terkirim = 0;

    foreach ($anggota as $item) {
        // Lewati jika anggota tidak memiliki nomor HP
        if (empty($item->no_hp)) {
            continue;
        }

        $detailTagihan = "";
        $totalTunggakan = 0;
        $jumlahPertemuan = 0;

        // Cek satu per satu jadwal senam yang masuk dalam filter untuk anggota ini
        foreach ($senamList as $senam) {
            $hadir = AbsensiSenam::where('id_user', $item->id_user)
                ->where('id_senam', $senam->id_senam)
                ->where('status', 'hadir')
                ->exists();

            if (!$hadir) {
                continue;
            }

            $sudahBayar = PembayaranIuran::where('id_user', $item->id_user)
                ->where('id_senam', $senam->id_senam)
                ->where(function ($q) {
                    $q->whereIn('status', ['success', 'berhasil', 'paid', 'lunas'])
                    ->orWhereIn('midtrans_transaction_status', ['settlement', 'capture']);
                })
                ->exists();

            if (!$sudahBayar) {
                $iuran = $senam->biaya_iuran ?? $senam->nominal_iuran ?? 2500;
                $totalTunggakan += $iuran;
                $jumlahPertemuan++;
                $tanggal = Carbon::parse($senam->tanggal)
                    ->locale('id')
                    ->translatedFormat('d F Y');
                $detailTagihan .= "• {$senam->nama_senam} - {$tanggal}\n";
            }
        }

        // 3. Jika terbukti memiliki tunggakan pada filter bulan ini, kirimkan via Fonnte
        if ($totalTunggakan > 0) {
            // Normalisasi nomor HP (mengubah awalan 0 menjadi 62)
            $nomor = preg_replace('/^0/', '62', $item->no_hp);

            // Menyusun pesan WhatsApp
            $pesan = "Halo *{$item->nama_user}*,\n\nBerikut daftar iuran senam Anda yang belum dibayar:\n\n{$detailTagihan}\n*Total Tunggakan ({$jumlahPertemuan} Pertemuan):*\n*Rp " . number_format($totalTunggakan, 0, ',', '.') . "*\n\nMohon segera melakukan penyelesaian pembayaran ya. Pembayaran dapat dilakukan melalui aplikasi atau diserahkan ke admin. Terima kasih! 🙏";

            // Proses hit ke API Fonnte
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $nomor,
                'message' => $pesan,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                $terkirim++;
            }
        }
    }

    return redirect()->back()->with('success', "Berhasil memproses kirim tagihan serentak! Sebanyak {$terkirim} pesan WhatsApp pengingat telah berhasil terkirim ke anggota berdasarkan filter.");
}
    public function exportPdf(Request $request)
    {
        $search = $request->search;
        $bulan  = $request->bulan;
        $sort   = $request->sort;

        $anggota = User::whereHas('role', function ($q) {
            $q->whereRaw('LOWER(nama_role) = ?', ['anggota']);
        })->get();

        $senamQuery = JSenam::query();
        if ($bulan) {
            $parsed = Carbon::parse($bulan);
            $senamQuery->whereMonth('tanggal', $parsed->month)->whereYear('tanggal', $parsed->year);
        }
        $senamList = $senamQuery->orderBy('tanggal', 'desc')->get();

        $dataTagihan = [];
        foreach ($anggota as $item) {
            if ($search && !str_contains(strtolower($item->nama_user), strtolower($search))) {
                continue;
            }

            $belumBayar = [];
            foreach ($senamList as $senam) {

                $hadir = AbsensiSenam::where('id_user', $item->id_user)
                    ->where('id_senam', $senam->id_senam)
                    ->where('status', 'hadir')
                    ->exists();

                if (!$hadir) {
                    continue;
                }

                $sudahBayar = PembayaranIuran::where('id_user', $item->id_user)
                    ->where('id_senam', $senam->id_senam)
                    ->where(function ($q) {
                        $q->whereIn('status', ['success', 'berhasil', 'paid', 'lunas'])
                        ->orWhereIn('midtrans_transaction_status', ['settlement', 'capture']);
                    })
                    ->exists();

                if (!$sudahBayar) {
                    $belumBayar[] = [
                        'nama_senam' => $senam->nama_senam ?? 'Senam',
                        'tanggal' => Carbon::parse($senam->tanggal)
                            ->locale('id')
                            ->translatedFormat('d F Y'),
                        'iuran' => $senam->biaya_iuran ?? $senam->nominal_iuran ?? 2500,
                    ];
                }
            }

            if (count($belumBayar) > 0) {
                $dataTagihan[] = [
                    'anggota' => $item,
                    'tagihan' => $belumBayar,
                    'jumlah_pertemuan' => count($belumBayar),
                    'total_tunggakan' => collect($belumBayar)->sum('iuran'),
                ];
            }
        }

        if ($sort == 'terbesar') {
            usort($dataTagihan, fn($a, $b) => $b['total_tunggakan'] <=> $a['total_tunggakan']);
        } elseif ($sort == 'terkecil') {
            usort($dataTagihan, fn($a, $b) => $a['total_tunggakan'] <=> $b['total_tunggakan']);
        } elseif ($sort == 'nama') {
            usort($dataTagihan, fn($a, $b) => strcmp($a['anggota']->nama_user, $b['anggota']->nama_user));
        }

        // Menggunakan DomPDF bawaan barryvdh jika terinstall
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.tagihan_pdf', compact('dataTagihan'));
            return $pdf->download('Laporan_Tunggakan_Iuran.pdf');
        }

        return back()->with('error', 'Library DomPDF belum terinstall di project kamu.');
    }

    public function kirimWaTagihan($id)
    {
        $user = User::findOrFail($id);
        if (!$user->no_hp) {
            return back()->with('error', 'Nomor HP anggota tidak tersedia');
        }

        $nomor = preg_replace('/^0/', '62', $user->no_hp);
        $senamList = JSenam::orderBy('tanggal', 'desc')->get();
        $detailTagihan = "";
        $totalTunggakan = 0;

        foreach ($senamList as $senam) {
            $hadir = AbsensiSenam::where('id_user', $user->id_user)
                ->where('id_senam', $senam->id_senam)
                ->where('status', 'hadir')
                ->exists();

            if (!$hadir) {
                continue;
            }

            $sudahBayar = PembayaranIuran::where('id_user', $user->id_user)
                ->where('id_senam', $senam->id_senam)
                ->where(function ($q) {
                    $q->whereIn('status', ['success', 'berhasil', 'paid', 'lunas'])
                    ->orWhereIn('midtrans_transaction_status', ['settlement', 'capture']);
                })
                ->exists();

            if (!$sudahBayar) {

                $iuran = $senam->biaya_iuran ?? $senam->nominal_iuran ?? 2500;

                $totalTunggakan += $iuran;

                $tanggal = Carbon::parse($senam->tanggal)
                    ->locale('id')
                    ->translatedFormat('d F Y');

                $detailTagihan .= " {$senam->nama_senam} - {$tanggal}\n";
            }
        }

        if ($totalTunggakan <= 0) {
            return back()->with('info', 'Anggota tidak memiliki tagihan');
        }

        $pesan = "Halo {$user->nama_user},\n\nBerikut daftar iuran senam Anda yang belum dibayar:\n\n{$detailTagihan}\nTotal Tunggakan:\nRp " . number_format($totalTunggakan,0,',','.') . "\n\nMohon segera melakukan penyelesaian pembayaran ya. Pembayaran dapat dilakukan melalui aplikasi atau diserahkan ke admin. Terima kasih! 🙏";

        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $pesan,
            'countryCode' => '62',
        ]);

        return back()->with('success', 'Notifikasi WhatsApp berhasil dikirim');
    }
}