@extends('layouts.main')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --primary-color: #4e73df;
        --dark-navy: #2d336b;
    }

    .main-container {
        padding: 20px 15px;
        background-color: #f8f9fc;
        min-height: 100vh;
    }

    .page-title {
        font-weight: 800;
        color: var(--dark-navy);
        font-size: 1.4rem;
        letter-spacing: -0.5px;
    }

    .card-modern {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(45, 51, 107, 0.05);
        padding: 24px;
        border: 1px solid rgba(226, 232, 240, 0.6);
    }

    /* Search Box Kapsul Modern */
    .search-box {
        position: relative;
        width: 100%;
    }

    @media (min-width: 768px) {
        .search-box {
            max-width: 300px;
        }
    }

    .search-box i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .search-input {
        border-radius: 30px;
        padding: 10px 16px 10px 44px;
        border: 1px solid #e2e8f0;
        height: 42px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.25s ease-in-out;
        background-color: #ffffff;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.12);
        outline: none;
        background-color: #fff;
    }

    /* Table Custom Styling */
    .table-custom {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        border: none;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 14px 16px;
        letter-spacing: 0.75px;
    }

    .table-custom thead th:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .table-custom thead th:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .table-custom tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-custom tbody td {
        padding: 16px;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    /* Badge Jumlah Pendaftar */
    .count-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        background: #eef2ff;
        color: #4e73df;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(78, 115, 223, 0.1);
    }

    /* Tombol Aksi */
    .btn-action-view {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
        border: none;
        background: #f1f5f9;
        color: #475569;
        text-decoration: none;
    }

    .btn-action-view:hover {
        background: var(--primary-gradient);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.25);
    }

    /* Kustomisasi Icon Destinasi */
    .icon-destination-box {
        width: 40px;
        height: 40px;
        background-color: rgba(78, 115, 223, 0.08);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Kustomisasi Paginasi Lembut */
    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }

    .pagination .page-item .page-link {
        border: 1px solid #e2e8f0;
        border-radius: 8px !important;
        padding: 8px 14px;
        font-size: 0.85rem;
        color: #475569;
        background-color: #ffffff;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }

    .pagination .page-item .page-link:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white !important;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.25);
    }
</style>

<div class="main-container">
    {{-- Header Section --}}
    <div class="row align-items-center g-3 mb-4">
        <div class="col-12 col-md-7 text-start">
            <h4 class="page-title mb-1">
                Pendaftar Wisata
            </h4>
            <p class="text-muted small mb-0">Kelola dan pantau list data pendaftaran dari setiap wisata </p>
        </div>

        <div class="col-12 col-md-5 d-flex justify-content-md-end">
            <div class="search-box">
                <form action="{{ route('admin.pendaftaran.index') }}" method="GET">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control search-input shadow-sm" 
                           placeholder="Cari wisata pilihan..." value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th width="70" class="text-center">No</th>
                        <th>Destinasi Wisata</th>
                        <th width="180" class="text-center">Total Pendaftar</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wisata as $index => $item)
                    <tr>
                        <td class="text-center fw-bold text-secondary opacity-75">
                            {{ $wisata->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-destination-box">
                                    <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-0 fs-6">{{ $item->nama_wisata }}</span>
                                    <span class="text-muted" style="font-size: 0.8rem;">
                                        <i class="bi bi-calendar3 me-1 text-secondary"></i> 
                                        {{ $item->tanggal_wisata ? \Carbon\Carbon::parse($item->tanggal_wisata)->locale('id')->translatedFormat('d M Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="count-badge">
                                <i class="bi bi-people-fill text-primary"></i>
                                <span>{{ $item->pendaftaran_count }} Orang</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.pendaftaran.show', $item->id_wisata) }}" 
                               class="btn-action-view" title="Lihat Detail Pendaftar">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-journal-x text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                <h6 class="text-dark fw-bold mt-3">Data Wisata Tidak Ditemukan</h6>
                                <p class="text-muted small">Coba cari kata kunci lain atau periksa kembali data Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Paginasi --}}
        <div class="mt-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 border-top pt-4">
            <div class="small text-secondary fw-semibold">
                Menampilkan <span class="text-primary fw-bold">{{ $wisata->firstItem() ?? 0 }}</span> 
                sampai <span class="text-primary fw-bold">{{ $wisata->lastItem() ?? 0 }}</span> 
                dari <span class="text-primary fw-bold">{{ $wisata->total() }}</span> total destinasi wisata
            </div>
            <nav>
                {{ $wisata->onEachSide(1)->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>
@endsection