@extends('layouts.main')

@section('content')

{{-- Menambahkan library pendukung tanpa merusak tatanan CSS awal --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root{
        --primary:#4f46e5;
        --success:#10b981;
        --danger:#ef4444;
        --warning:#f59e0b;
        --dark:#1e293b;
        --border:#e2e8f0;
        --bg:#f8fafc;
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
    }

    body{
        background: var(--bg);
    }

    .page-title{
        font-size: 26px;
        font-weight: 700;
        color: var(--dark);
    }

    .card-summary{
        border: none;
        border-radius: 16px;
        overflow: hidden;
        color: white;
        position: relative;
    }

    .gradient-primary{ background: linear-gradient(135deg,#4f46e5,#6366f1); }
    .gradient-danger{ background: linear-gradient(135deg,#ef4444,#f87171); }
    .gradient-warning{ background: linear-gradient(135deg,#f59e0b,#fbbf24); }
    .gradient-success{ background: linear-gradient(135deg,#10b981,#34d399); }

    .card-summary .icon{
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 40px;
        opacity: .15;
    }

    .card-summary h2{
        font-size: 26px;
        font-weight: 700;
        margin-top: 5px;
    }

    .table-card{
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .table thead{
        background: #f8fafc;
        border-bottom: 2px solid var(--border);
    }

    .table th{
        font-weight: 700;
        color: #334155;
        padding: 14px 16px;
        white-space: nowrap;
    }

    .table td{
        padding: 14px 16px;
        vertical-align: middle;
    }

    .badge-danger-soft{
        background: #fee2e2;
        color: #dc2626;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        white-space: nowrap;
    }

    .avatar{
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #4338ca;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }

    .btn-action{
        border-radius: 10px;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .empty-state{
        padding: 50px 20px;
    }

    /* Menyembunyikan tombol bawaan DataTables agar tidak dobel */
    .dt-buttons {
        display: none !important;
    }

    /* ==========================================================================
       PAGINATION MODERN (DISAMAKAN AGAR SERAGAM DENGAN MENU LAIN)
       ========================================================================== */
    .pagination {
        gap: 6px;
        margin-bottom: 0;
    }

    .pagination .page-item {
        display: none;
    }

    /* Tampilkan tombol prev, next, dan halaman aktif */
    .pagination .page-item.previous,
    .pagination .page-item.next,
    .pagination .page-item.active,
    .pagination .page-item:first-child,
    .pagination .page-item:last-child {
        display: block;
    }

    /* Tampilkan angka kiri & kanan dari page yang sedang aktif */
    .pagination .page-item.active + .page-item,
    .pagination .page-item:has(+ .active) {
        display: block;
    }

    .pagination .page-item .page-link {
        border: none;
        border-radius: 10px !important;
        color: var(--primary);
        font-weight: 600;
        padding: 8px 14px;
        font-size: 0.82rem;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        background: white;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }

    .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #eef2ff;
        transform: translateY(-1px);
    }

    .pagination .page-item.disabled .page-link {
        background: #f8fafc;
        color: #c0c4d6;
        box-shadow: none;
    }
</style>

<div class="container-fluid py-4">

    {{-- HEADER STATISTIK --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="page-title">Laporan Tagihan Iuran</div>
            <small class="text-muted">Daftar anggota yang belum membayar iuran senam</small>
        </div>
    </div>

    {{-- CARD RINGKASAN --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-summary gradient-primary shadow-sm">
                <div class="card-body">
                    <div class="icon"><i class="bi bi-people-fill"></i></div>
                    <small>Total Anggota Menunggak</small>
                    <h2>{{ $totalAnggotaMenunggak }}</h2>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-summary gradient-danger shadow-sm">
                <div class="card-body">
                    <div class="icon"><i class="bi bi-receipt"></i></div>
                    <small>Total Tagihan Belum Dibayar</small>
                    <h2>{{ $totalTagihan }}</h2>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-summary gradient-warning shadow-sm">
                <div class="card-body">
                    <div class="icon"><i class="bi bi-calendar-event"></i></div>
                    <small>Total Pertemuan Menunggak</small>
                    <h2>{{ $totalTagihan }}</h2>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-summary gradient-success shadow-sm">
                <div class="card-body">
                    <div class="icon"><i class="bi bi-cash-stack"></i></div>
                    <small>Total Nominal Tunggakan</small>
                    <h2>Rp {{ number_format($totalNominal,0,',','.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- AREA UTAMA: FILTER DAN ACTION BUTTONS --}}
    <div class="card table-card shadow-sm">
        <div class="card-header bg-white pt-4 pb-3 px-4 border-0">
            <form method="GET" action="">
                <div class="row g-3 align-items-end">
                    
                    {{-- Pencarian Nama --}}
                    <div class="col-xl-4 col-md-6">
                        <label class="form-label fw-semibold small text-muted">Cari Anggota</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama anggota..." value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Filter Bulan --}}
                    <div class="col-xl-3 col-md-3">
                        <label class="form-label fw-semibold small text-muted">Filter Tanggal / Bulan</label>
                        <input type="date" name="bulan" id="bulan" class="form-control form-control-sm bg-light" value="{{ request('bulan') }}">
                    </div>

                    {{-- Tombol Cari & Reset --}}
                    <div class="col-xl-2 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100 rounded-2 py-2">
                            <i class="bi bi-funnel-fill me-1"></i> Cari
                        </button>
                        <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-secondary rounded-2 px-3 py-2" title="Reset Pencarian">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>

                    {{-- Tombol Aksi WhatsApp Serentak --}}
                    <div class="col-xl-3 col-md-12 d-flex justify-content-end">
                        {{-- WhatsApp Serentak meneruskan semua parameter filter aktif --}}
                        <a href="{{ route('admin.laporan.tagihan.whatsapp.all', request()->query()) }}" 
                        id="btnKirimSerentak"
                        class="btn btn-sm btn-success rounded-2 px-3 py-2 w-100 shadow-sm">
                            <i class="bi bi-whatsapp me-1"></i> Kirim Tunggakan WA Serentak
                        </a>
                    </div>

                </div>
            </form>
        </div>

        {{-- KONTEN TABEL --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tabelLaporanTagihanReal" class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="60" class="text-center">No</th>
                            <th>Anggota</th>
                            <th>Total Pertemuan</th>
                            <th>Nominal / Pertemuan</th>
                            <th>Total Tunggakan</th>
                            <th width="120" class="text-center">Status</th>
                            <th width="140" class="text-center action-column">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataTagihan as $index => $item)
                        <tr>
                            <td class="text-center text-muted">
                                {{ $dataTagihan instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($dataTagihan->firstItem() + $index) : ($index + 1) }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        {{ strtoupper(substr($item['anggota']->nama_user,0,1)) }}
                                    </div>
                                    <div style="min-width: 150px;">
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 200px;">{{ $item['anggota']->nama_user }}</div>
                                        <small class="text-muted d-block">
                                            {{ $item['anggota']->no_hp ?? '-' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-secondary">
                                    {{ $item['jumlah_pertemuan'] }} Pertemuan
                                </span>
                            </td>
                            <td class="text-nowrap">
                                Rp {{ number_format($item['nominal_per_pertemuan'] ?? 2500,0,',','.') }}
                            </td>
                            <td class="text-nowrap">
                                Rp {{ number_format($item['total_tunggakan'],0,',','.') }}
                            </td>
                            <td class="text-center text-nowrap" style="min-width: 130px;">
                                <span class="badge-danger-soft border border-danger border-opacity-10">
                                    Belum Bayar
                                </span>
                            </td>
                            <td class="text-center action-column">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-outline-primary btn-action" data-bs-toggle="modal" data-bs-target="#detailModal{{ $index }}" title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    {{-- Tombol WA per orang juga membawa parameter filter --}}
                                    <a href="{{ route('admin.laporan.tagihan.whatsapp', array_merge(['id' => $item['anggota']->id_user], request()->query())) }}" class="btn btn-sm btn-success btn-action" title="Kirim WA">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-check-circle-fill fs-2 text-success d-block mb-2"></i>
                                    <h6 class="fw-bold text-dark">Tidak Ada Tunggakan</h6>
                                    <small class="text-muted">Semua data anggota telah melunasi iuran senam.</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KONTEN PAGINASI RESPONSIF --}}
    @if ($dataTagihan instanceof \Illuminate\Pagination\LengthAwarePaginator && $dataTagihan->hasPages())
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 px-2">
            <small class="text-muted fw-medium">
                Menampilkan {{ $dataTagihan->firstItem() }} - {{ $dataTagihan->lastItem() }} dari {{ $dataTagihan->total() }} data
            </small>
            <div>
                {!! $dataTagihan->appends(request()->query())->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    @endif
</div>

{{-- MODAL DETAIL DATA --}}
@foreach ($dataTagihan as $index => $item)
<div class="modal fade" id="detailModal{{ $index }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">Detail Tagihan Iuran</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div class="mb-3 p-3 bg-light rounded-3">
                    <div class="fw-bold text-dark fs-5">{{ $item['anggota']->nama_user }}</div>
                    <small class="text-danger fw-semibold">
                        Total Tunggakan: Rp {{ number_format($item['total_tunggakan'],0,',','.') }}
                    </small>
                </div>
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pertemuan</th>
                                <th>Tanggal</th>
                                <th>Iuran</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item['tagihan'] as $tagihan)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $tagihan['nama_senam'] }}</td>
                                <td class="text-muted">{{ $tagihan['tanggal'] }}</td>
                                <td>Rp {{ number_format($tagihan['iuran'],0,',','.') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded">Belum Bayar</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button class="btn btn-sm btn-secondary rounded-2 px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- SCRIPT JAVASCRIPT --}}
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    // 1. Handle Konfirmasi Klik Tombol Kirim Serentak
    $('#btnKirimSerentak').on('click', function (e) {
        e.preventDefault(); 
        var targetUrl = $(this).attr('href');

        Swal.fire({
            title: 'Kirim Tagihan Serentak?',
            text: "Sistem akan mengirimkan pesan pengingat WhatsApp ke semua anggota yang masuk dalam daftar saat ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981', 
            cancelButtonColor: '#64748b',  
            confirmButtonText: 'Ya, Kirim Sekarang!',
            cancelButtonText: 'Batal',
            backdrop: `rgba(15, 23, 42, 0.3)` 
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Pengiriman...',
                    text: 'Mohon tunggu sebentar, pesan WhatsApp sedang dikirim.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                window.location.href = targetUrl;
            }
        });
    });

    @if(session('success'))
        Swal.fire({
            title: 'Berhasil Terkirim!',
            text: "{!! session('success') !!}",
            icon: 'success',
            confirmButtonColor: '#4f46e5' 
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Gagal Proses!',
            text: "{!! session('error') !!}",
            icon: 'error',
            confirmButtonColor: '#ef4444' 
        });
    @endif
});
</script>

<script>
$(document).ready(function () {
    // Inisialisasi DataTable murni tanpa modul Buttons ekspor PDF
    var table = $('#tabelLaporanTagihanReal').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        responsive: true,
        dom: 'rt'
    });
});
</script>

@endsection