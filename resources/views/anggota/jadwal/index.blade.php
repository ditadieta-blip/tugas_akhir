@extends('layouts.anggota')
@section('content')
@php
\Carbon\Carbon::setLocale('id');
@endphp

<style>
:root{
    --primary-gradient:linear-gradient(135deg,#4e73df 0%,#224abe 100%);
    --soft-bg:#f8f9fc;
    --success-soft:#e8f5e9;
    --success-text:#2e7d32;
    --danger-soft:#ffebee;
    --danger-text:#c62828;
    --secondary-soft:#f1f3f9;
    --secondary-text:#5a5c69;
}

body{
    font-size:.875rem;
}

.main-container{
    padding:20px;
    background:var(--soft-bg);
    min-height:100vh;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap: wrap;
    gap: 15px;
}

/* Container agar tombol & search bar sejajar rapi */
.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-isi-saldo {
    background: var(--primary-gradient);
    color: white;
    border: none;
    height: 40px;
    padding: 0 18px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-isi-saldo:hover {
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(78, 115, 223, 0.3);
}

.card-modern{
    background:#fff;
    border:none;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,.03);
    padding:25px;
}

.table-custom{
    border-collapse:separate;
    border-spacing:0 10px;
    font-size:.85rem;
}

.table-custom thead th{
    background:transparent;
    border:none;
    color:#abb3ba;
    font-weight:700;
    text-transform:uppercase;
    font-size:.7rem;
    padding:10px 20px;
    letter-spacing:.5px;
}

.table-custom tbody tr{
    background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,.02);
    transition:.2s;
}

.table-custom tbody tr:hover{
    transform:translateY(-2px);
    box-shadow:0 5px 15px rgba(0,0,0,.05);
    background:#fcfdff;
}

.table-custom tbody td{
    padding:15px 20px;
    border:none;
    vertical-align:middle;
}

.table-custom tbody td:first-child{
    border-radius:10px 0 0 10px;
}

.table-custom tbody td:last-child{
    border-radius:0 10px 10px 0;
}

.badge-modern{
    padding:6px 12px;
    border-radius:8px;
    font-weight:600;
    font-size:.75rem;
    display:inline-flex;
    align-items:center;
    gap:5px;
}

.badge-soft-success{
    background:var(--success-soft);
    color:var(--success-text);
}

.badge-soft-danger{
    background:var(--danger-soft);
    color:var(--danger-text);
}

.badge-soft-secondary{
    background:var(--secondary-soft);
    color:var(--secondary-text);
}

.badge-saldo{
    background:#fff7e6;
    color:#d48806;
    border:1px solid #ffe58f;
}

.btn-action{
    padding:6px 14px;
    border-radius:8px;
    font-size:.75rem;
    font-weight:600;
    transition:.2s;
    border:none;
}

.btn-hadir{
    background:#4e73df;
    color:#fff;
}

.btn-hadir:hover{
    background:#224abe;
    transform:scale(1.05);
}

.btn-tidak{
    background:#eaecf4;
    color:#5a5c69;
}

.btn-tidak:hover{
    background:#d1d3e2;
    transform:scale(1.05);
}

.detail-kegiatan .instruktur-name{
    display:block;
    font-weight:700;
    color:#3a3b45;
    font-size:.9rem;
}

.detail-kegiatan .lokasi-name{
    display:flex;
    align-items:center;
    gap:4px;
    color:#858796;
    font-size:.75rem;
    margin-top:2px;
}

.date-pill{
    background:#f1f3f9;
    color:#5a5c69;
    padding:5px 12px;
    border-radius:100px;
    font-size:.75rem;
    font-weight:600;
    display:inline-flex;
    align-items:center;
    gap:5px;
}

.search-wrapper{
    position:relative;
    width:280px;
}

.search-wrapper input{
    border-radius:10px;
    padding-left:35px;
    border:1.5px solid #e3e6f0;
    height:40px;
}

.search-wrapper i{
    position:absolute;
    left:12px;
    top:50%;
    transform:translateY(-50%);
    color:#b7b9cc;
}

.pagination{
    gap:6px;
    margin-bottom:0;
}

.pagination .page-item .page-link{
    border:none;
    border-radius:8px!important;
    color:#4e73df;
    font-weight:600;
    padding:8px 14px;
    box-shadow:0 2px 4px rgba(0,0,0,.03);
}

.pagination .page-item.active .page-link{
    background:var(--primary-gradient)!important;
    color:#fff!important;
}

.iuran-wrapper{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:3px;
}

.iuran-text{
    font-size:.68rem;
    color:#858796;
    font-weight:600;
}

.btn-bayar{
    font-size:.72rem;
    padding:5px 14px;
    border-radius:20px;
    font-weight:600;
}

/* Responsif: Menyesuaikan tata letak pada perangkat mobile */
@media (max-width: 576px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .header-actions {
        width: 100%;
        flex-direction: row-reverse;
        justify-content: space-between;
    }
    .search-wrapper {
        flex-grow: 1;
        width: auto;
    }
}
</style>

<div class="main-container">
    <div class="page-header">
        <div>
            <h4 class="fw-bold text-dark mb-0">Jadwal Senam</h4>
            <p class="text-muted small mb-0">
                Daftar lengkap agenda kegiatan senam
            </p>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('anggota.saldo') }}" class="btn btn-isi-saldo text-decoration-none">
                <i class="bi bi-wallet2"></i>
                Isi Saldo
            </a>
            <div class="search-wrapper">
                <form action="{{ request()->url() }}" method="GET">
                    <i class="bi bi-search"></i>
                    <input type="text"
                           name="search"
                           class="form-control shadow-sm"
                           placeholder="Cari lokasi atau instruktur..."
                           value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </div>

    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Detail Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Kehadiran</th>
                        <th class="text-center">Absensi</th>
                        <th class="text-center">Iuran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $index => $item)
                    @php
                        $absen = $item->absensi->first();
                        $pembayaran = $item->pembayaran;
                        $statusBayar = $pembayaran->status ?? null;
                        $metodeBayar = $pembayaran->metode ?? null;
                        $saldoUser = auth()->user()->saldo_iuran ?? 0;
                        $sekarang = \Carbon\Carbon::now();
                        $tanggalSenam = \Carbon\Carbon::parse($item->tanggal);
                        $jamMulaiAbsen = $tanggalSenam->copy()->setTime(7,0,0);
                        $jamBatasAbsen = $tanggalSenam->copy()->setTime(13,0,0);
                        $absenTerlambat = !$absen && $sekarang->gt($jamBatasAbsen);
                        if($absenTerlambat){
                            $absen = (object)[
                                'status' => 'tidak'
                            ];
                        }
                    @endphp
                    <tr>
                        <td class="text-center">
                            <span class="text-muted fw-bold">
                                {{ $jadwal->firstItem() + $index }}
                            </span>
                        </td>
                        <td>
                            <div class="detail-kegiatan">
                                <span class="instruktur-name">
                                    {{ $item->user->nama_user ?? '-' }}
                                </span>
                                <span class="lokasi-name">
                                    <i class="bi bi-geo-alt-fill text-primary"></i>
                                    {{ $item->tempat_senam }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="date-pill">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}
                            </div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:.8rem;">
                                {{ $item->keterangan_senam ?? '-' }}
                            </span>
                        </td>
                        {{-- KEHADIRAN --}}
                        <td class="text-center">
                            @if($absen)
                                @if($absen->status == 'hadir')
                                    <span class="badge-modern badge-soft-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Hadir
                                    </span>
                                @else
                                    <span class="badge-modern badge-soft-danger">
                                        <i class="bi bi-x-circle-fill"></i>
                                        Tidak Hadir
                                    </span>
                                @endif
                            @else
                                <span class="badge-modern badge-soft-secondary">
                                    <i class="bi bi-clock-history"></i>
                                    Belum Absen
                                </span>
                            @endif
                        </td>

                        {{-- ABSENSI --}}
                        <td class="text-center">
                            @if(!$item->absensi->first() && !$absenTerlambat)
                                @php
                                    $isHariH = $sekarang->isSameDay($tanggalSenam);
                                    $bolehAbsen = $isHariH &&
                                                   $sekarang->between($jamMulaiAbsen,$jamBatasAbsen);
                                @endphp
                                @if($bolehAbsen)
                                    <form action="{{ route('anggota.absensi.store',$item->id_senam) }}"
                                          method="POST"
                                          class="d-flex justify-content-center gap-2">
                                        @csrf
                                        <button name="status"
                                                value="hadir"
                                                class="btn-action btn-hadir">
                                            Hadir
                                        </button>
                                        <button name="status"
                                                value="tidak"
                                                class="btn-action btn-tidak">
                                            Tidak
                                        </button>
                                    </form>
                                @else
                                    <span class="badge-modern badge-soft-secondary">
                                        <i class="bi bi-lock-fill"></i>
                                        Tertutup
                                    </span>
                                @endif
                            @else
                                <span class="text-muted small fw-600">
                                    <i class="bi bi-check2-all text-primary"></i>
                                    Selesai
                                </span>
                            @endif
                        </td>

                        {{-- IURAN --}}
                        <td class="text-center">
                            @if($absen && $absen->status == 'hadir')
                                @if($statusBayar == 'success')
                                    {{-- SALDO --}}
                                    @if($metodeBayar == 'saldo')
                                        <div class="iuran-wrapper">
                                            <span class="badge-modern badge-saldo">
                                                <i class="bi bi-wallet2"></i>
                                                Potong Saldo
                                            </span>
                                            <span class="iuran-text">
                                                Saldo:
                                                Rp {{ number_format($saldoUser,0,',','.') }}
                                            </span>
                                        </div>

                                    {{-- TUNAI --}}
                                    @elseif($metodeBayar == 'tunai')
                                        <div class="iuran-wrapper">
                                            <span class="badge-modern badge-soft-success">
                                                <i class="bi bi-cash-coin"></i>
                                                Lunas
                                            </span>
                                            <span class="iuran-text">
                                                via Tunai
                                            </span>
                                            @if($saldoUser > 0)
                                            <span class="iuran-text">
                                                Saldo:
                                                Rp {{ number_format($saldoUser,0,',','.') }}
                                            </span>
                                            @endif
                                        </div>

                                    {{-- MIDTRANS --}}
                                    @else
                                        <div class="iuran-wrapper">
                                            <span class="badge-modern badge-soft-success">
                                                <i class="bi bi-patch-check-fill"></i>
                                                Lunas
                                            </span>
                                            <span class="iuran-text">
                                                via Midtrans
                                            </span>
                                            @if($saldoUser > 0)
                                            <span class="iuran-text">
                                                Saldo:
                                                Rp {{ number_format($saldoUser,0,',','.') }}
                                            </span>
                                            @endif
                                        </div>
                                    @endif
                                @elseif($statusBayar == 'pending')
                                    <div class="iuran-wrapper">
                                        <span class="badge-modern badge-soft-secondary">
                                            <i class="bi bi-clock-history"></i>
                                            Diproses
                                        </span>
                                    </div>
                                @else
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <a href="{{ route('anggota.iuran.show',$item->id_senam) }}"
                                           class="btn btn-primary btn-sm btn-bayar text-decoration-none">
                                            <i class="bi bi-credit-card me-1"></i>
                                            Bayar
                                        </a>
                                        @if($saldoUser > 0)
                                        <span class="iuran-text">
                                            Saldo:
                                            Rp {{ number_format($saldoUser,0,',','.') }}
                                        </span>
                                        @endif
                                    </div>
                                @endif
                            @elseif($absen && $absen->status == 'tidak')
                                <span class="text-muted small">
                                    Tidak Wajib
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-calendar-x display-4 text-light"></i>
                            <p class="text-muted mt-2 mb-0">
                                Belum ada jadwal senam yang dirilis.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination bar --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
            <div class="text-muted fw-bold" style="font-size:.75rem;">
                Menampilkan {{ $jadwal->firstItem() ?? 0 }} - {{ $jadwal->lastItem() ?? 0 }} dari {{ $jadwal->total() }} jadwal
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    @if ($jadwal->onFirstPage())
                        <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $jadwal->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a></li>
                    @endif
                    @php
                        $start = max($jadwal->currentPage() - 1, 1);
                        $end = min($start + 2, $jadwal->lastPage());
                        if (($end - $start) < 2) { $start = max($end - 2, 1); }
                    @endphp
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $jadwal->currentPage() == $i ? 'active' : '' }}"><a class="page-link" href="{{ $jadwal->url($i) }}">{{ $i }}</a></li>
                    @endfor
                    @if ($jadwal->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $jadwal->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Session Success & Error Alerts --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonText: 'OK' });
    });
</script>
@endif
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}", confirmButtonText: 'OK' });
    });
</script>
@endif
@endsection