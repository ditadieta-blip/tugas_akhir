@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --soft-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        --dark-blue: #2e59d9;
    }

    .main-container {
        padding: 25px;
        background-color: #f8f9fc;
        min-height: 100vh;
    }

    /* Header & Search Styling */
    .header-box {
        margin-bottom: 30px;
    }

    .page-title {
        font-weight: 800;
        color: #2e59d9;
        letter-spacing: -0.5px;
        margin: 0;
    }

    /* Button Back Style (Disinkronkan dari menu induk) */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #858796;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        padding: 8px 16px;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        width: fit-content;
    }

    .btn-back:hover {
        color: var(--dark-blue);
        transform: translateX(-5px);
        background: #f1f3f9;
        text-decoration: none;
    }

    .search-input-group {
        position: relative;
        max-width: 300px;
    }

    .search-input-group i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #b7b9cc;
        z-index: 5;
    }

    .search-control {
        border-radius: 12px;
        padding-left: 40px;
        border: 1px solid #e3e6f0;
        height: 45px;
        transition: 0.3s;
    }

    .search-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
    }

    /* Table Styling */
    .table-container {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: var(--soft-shadow);
        overflow: hidden;
        padding: 15px;
    }

    .table-custom thead th {
        background: #f8f9fc;
        border: none;
        color: #4e73df;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 15px;
    }

    .table-custom tbody td {
        padding: 20px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f9;
    }

    /* Participant Badge */
    .participant-badge {
        background: #eef2ff;
        color: #4e73df;
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-block;
    }

    /* Pagination Styling */
    .pagination {
        margin: 0;
        gap: 5px;
    }

    .pagination .page-item .page-link {
        border-radius: 8px !important;
        border: none;
        background: #f8f9fc;
        color: #4e73df;
        font-weight: 600;
        padding: 8px 16px;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.2);
    }

    /* Responsive */
    @media (max-width: 767px) {
        .header-box { text-align: center; }
        .search-input-group { max-width: 100%; margin-top: 15px; }
        
        .table-custom thead { display: none; }
        .table-custom tbody td {
            display: block;
            text-align: right;
            padding: 10px 15px;
            border: none;
        }
        .table-custom tbody td::before {
            content: attr(data-label);
            float: left;
            font-weight: 700;
            color: #4e73df;
            text-transform: uppercase;
            font-size: 0.7rem;
        }
        .table-custom tbody tr {
            display: block;
            border-bottom: 2px solid #f1f3f9;
            margin-bottom: 10px;
        }
        .footer-pagination {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        .mb-mobile-3 {
            margin-bottom: 15px;
        }
    }
</style>

<div class="main-container">
    <div class="container-fluid container-lg">
        
        {{-- Tombol Kembali di Sebelah Kiri Atas --}}
        <div class="row mb-3">
            <div class="col-12 text-start">
                <a href="{{ route('admin.transaksi.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        {{-- Header & Search --}}
        <div class="row align-items-center header-box">
            <div class="col-md-6 mb-mobile-3">
                <h4 class="page-title">Transaksi Pembayaran Wisata</h4>
                <p class="text-muted small mb-0">Menampilkan seluruh anggota yang telah mendaftar wisata</p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                <form action="{{ url()->current() }}" method="GET" class="search-input-group w-100">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control search-control shadow-sm" 
                           placeholder="Cari wisata..." value="{{ request('search') }}">
                </form>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Nama Wisata</th>
                            <th>Tanggal</th>
                            <th class="text-center">Jumlah Pendaftar</th>
                            <th class="text-end">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wisata as $w)
                        <tr>
                            <td data-label="Nama Wisata">
                                <div class="fw-bold text-dark">{{ $w->nama_wisata }}</div>
                            </td>
                            <td data-label="Tanggal">
                                <div class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ \Carbon\Carbon::parse($w->tanggal_wisata)->locale('id')->translatedFormat('d F Y') }}
                                </div>
                            </td>
                            <td data-label="Peserta" class="text-md-center">
                               @php
                                    $confirmedCount = $w->pendaftaran->count();
                                @endphp
                                <span class="participant-badge">
                                    <i class="bi bi-people-fill"></i>
                                    {{ $w->pendaftaran->count() }} Orang
                                </span>
                            </td>
                            <td data-label="Opsi" class="text-md-end">
                                <a href="{{ route('admin.wisata.show', $w->id_wisata) }}" 
                                   class="btn btn-primary btn-sm px-3 shadow-sm" 
                                   style="border-radius: 10px; background: var(--primary-gradient); border: none;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Data wisata tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            @if($wisata->hasPages() || $wisata->total() > 0)
            <div class="footer-pagination d-flex justify-content-between align-items-center mt-4 px-2">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $wisata->firstItem() }}</strong> sampai <strong>{{ $wisata->lastItem() }}</strong> dari <strong>{{ $wisata->total() }}</strong> data
                </div>
                <div>
                    {{ $wisata->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection