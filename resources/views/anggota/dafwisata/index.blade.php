@extends('layouts.anggota')

@section('content')
<style>
    :root {
        /* Warna Biru Royal yang serasi dengan Sidebar */
        --primary-blue: #2b59c3;
        --soft-bg: #f8f9fa;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    .main-container {
        padding: 10px 5px;
        background-color: transparent;
    }

    /* Judul Halaman */
    .section-title {
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.5px;
        font-size: 1.2rem;
        position: relative;
        padding-bottom: 8px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 35px;
        height: 3px;
        background: var(--primary-blue);
        border-radius: 10px;
    }

    /* Card Styling - Dibuat Lebih Ringkas & Kecil */
    .travel-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .travel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.06);
        border-color: var(--primary-blue);
    }

    .card-banner {
        height: 4px;
        background: var(--primary-blue);
    }

    /* Badge Status - Mungil & Halus */
    .status-badge {
        font-size: 0.6rem; 
        font-weight: 700;
        text-transform: uppercase;
        padding: 2px 8px;
        border-radius: 6px;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    /* Card Header Section */
    .card-title-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 8px;
    }

    .card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        line-height: 1.3;
    }

    /* Info List */
    .info-list {
        padding: 0;
        margin: 12px 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 6px;
        font-size: 0.75rem; /* Font diperkecil */
        color: var(--text-muted);
    }

    .info-item i {
        width: 24px;
        height: 24px;
        background: #eff6ff;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        color: var(--primary-blue);
        font-size: 0.8rem;
    }

    /* Harga & Tombol */
    .price-tag {
        font-size: 1rem;
        font-weight: 700;
        color: var(--primary-blue);
    }

    .btn-register {
        border-radius: 8px;
        padding: 7px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: 0.3s;
    }

    .btn-blue {
        background: var(--primary-blue);
        border: none;
        color: white;
    }

    .btn-blue:hover {
        background: #1e44a3;
        color: white;
    }

    .custom-alert {
        border: none;
        border-radius: 10px;
        font-size: 0.8rem;
    }
</style>

<div class="main-container">
    <div class="container-fluid">
        <div class="mb-4">
            <h4 class="section-title mb-1">Daftar Wisata</h4>
            <p class="text-muted small mb-0">Temukan agenda perjalanan terbaik Anda</p>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="alert alert-success custom-alert shadow-sm mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            @forelse($wisata as $item)
            @php
                $status = $pendaftaranIds[$item->id_wisata] ?? null;

                // Status tambahan: tidak mengikuti
                if (!$status && $item->is_open == 0) {
                    $status = 'tidak_mengikuti';
                }

                $badgeClass = match($status) {
                    'diterima' => 'bg-success text-white',
                    'ditolak' => 'bg-danger text-white',
                    'menunggu' => 'bg-warning text-dark',
                    'tidak_mengikuti' => 'bg-secondary text-white',
                    default => null
                };

                $badgeText = match($status) {
                    'diterima' => 'Terdaftar',
                    'ditolak' => 'Ditolak',
                    'menunggu' => 'Menunggu',
                    'tidak_mengikuti' => 'Tidak Mengikuti',
                    default => null
                };
            @endphp

                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card travel-card h-100">
                        <div class="card-banner"></div>
                        <div class="card-body d-flex flex-column p-3">
                            
                            <div class="card-title-section">
                                <h6 class="card-title">{{ $item->nama_wisata }}</h6>
                                @if($status)
                                    <span class="status-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                                @endif
                            </div>

                            <p class="text-muted mb-3" style="font-size: 0.75rem; line-height: 1.4; height: 32px; overflow: hidden;">
                                {{ Str::limit($item->deskripsi, 65) }}
                            </p>

                            <div class="info-list">
                                <div class="info-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>{{ Str::limit($item->lokasi_wisata, 20) }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>{{ \Carbon\Carbon::parse($item->tanggal_wisata)->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>

                            <div class="mt-auto border-top pt-3">
                                <div class="price-info mb-3 text-center">
                                    <span class="text-muted d-block" style="font-size: 0.65rem;">Biaya</span>
                                    <span class="price-tag">Rp {{ number_format($item->biaya_wisata, 0, ',', '.') }}</span>
                                </div>

                                @if($item->is_open == 1)
                                    @if($status)
                                        <button class="btn btn-light btn-register w-100 disabled text-muted border">
                                            <i class="bi bi-check2-all"></i> Sudah Terdaftar
                                        </button>
                                    @else
                                        <form action="{{ route('anggota.dafwisata.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_wisata" value="{{ $item->id_wisata }}">
                                            <button type="submit" class="btn btn-blue btn-register w-100 shadow-sm">
                                                Daftar Sekarang
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <button class="btn btn-secondary btn-register w-100 opacity-50" disabled>
                                        Pendaftaran Ditutup
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada agenda wisata yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection