@extends('layouts.anggota')

@section('content')
<style>
    /* Custom Theme */
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --danger-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        --soft-bg: #f8f9fc;
    }

    .header-gradient {
        background: var(--primary-gradient);
        border-radius: 20px;
        color: white;
        padding: 25px 30px; /* Diperkecil sedikit */
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(78, 115, 223, 0.2);
    }

    .header-circle {
        position: absolute;
        right: -50px;
        top: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    /* Font Header User diperkecil */
    .user-greeting {
        font-size: 1.4rem; /* Ukuran lebih proporsional */
        font-weight: 700;
    }

    .deadline-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border-left: 5px solid #e74a3b;
    }

    .card-wisata {
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        background: #ffffff;
        border: 1px solid #edf0f7;
    }

    .card-wisata:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.06) !important;
    }

    /* Perbaikan Bentuk Ikon (Lebih Cantik & Modern) */
    .icon-shape {
        width: 54px;
        height: 54px;
        border-radius: 16px; /* Rounded square yang lembut */
        background: #eef2ff;
        color: #4e73df;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 18px;
        font-size: 1.2rem;
        position: relative;
        overflow: hidden;
    }

    .icon-shape::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: currentColor;
        opacity: 0.1;
    }

    .btn-pay {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 8px 20px;
        font-weight: 600;
        transition: 0.3s;
        font-size: 0.85rem;
    }

    .btn-pay:hover {
        color: white;
        opacity: 0.9;
        transform: scale(1.02);
    }
</style>

<div class="container py-4">
    <!-- Header Section -->
    <div class="header-gradient mb-5">
        <div class="header-circle"></div>
        <div class="row align-items-center position-relative">
            <div class="col-md-7">
                <!-- Ukuran Font User sudah diperkecil -->
                <h4 class="user-greeting mb-1">Halo, {{ auth()->user()->nama_user }}</h4>
                <p class="opacity-75 mb-0 small">Cek detail tagihan dan batas waktu pelunasan wisatamu.</p>
            </div>
            <div class="col-md-5 mt-3 mt-md-0">
                <div class="deadline-card p-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="text-danger me-3">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 9px;">Aturan Bayar</small>
                            <span class="text-dark fw-bold small">Wajib lunas H-5 keberangkatan.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($pendaftaran as $item)
            @php
                $totalTagihan = $item->jwisata->biaya_wisata ?? 0;
                $totalTerbayar = $item->pembayaranWisata->sum('jumlah_bayar');
                $sisaTagihan = $totalTagihan - $totalTerbayar;
                
                $tglWisata = \Carbon\Carbon::parse($item->jwisata->tanggal_wisata);
                $deadline = $tglWisata->subDays(5);
            @endphp

            <div class="col-12 mb-4">
                <div class="card card-wisata shadow-sm p-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Destinasi & Lokasi -->
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <!-- Bentuk Persegi Modern (icon-shape) -->
                                    <div class="icon-shape shadow-sm">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $item->jwisata->nama_wisata }}</h6>
                                        <div class="d-flex flex-column">
                                            <small class="text-muted mb-1">
                                                <i class="fas fa-location-dot text-danger me-1"></i> {{ $item->jwisata->lokasi_wisata }}
                                            </small>
                                            <small class="text-primary fw-bold" style="font-size: 0.75rem;">
                                                <i class="far fa-calendar-alt me-1"></i> Deadline: {{ $deadline->translatedFormat('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Pembayaran -->
                            <div class="col-md-5 mb-3 mb-md-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-bold" style="font-size: 0.8rem;">Status Tagihan</small>
                                    <small class="fw-bold {{ $sisaTagihan <= 0 ? 'text-success' : 'text-primary' }}" style="font-size: 0.8rem;">
                                        {{ $totalTagihan > 0 ? number_format(($totalTerbayar/$totalTagihan)*100, 0) : 0 }}%
                                    </small>
                                </div>
                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated {{ $sisaTagihan <= 0 ? 'bg-success' : 'bg-primary' }}" 
                                         role="progressbar" 
                                         style="width: {{ $totalTagihan > 0 ? ($totalTerbayar/$totalTagihan)*100 : 0 }}%"></div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Sisa Tagihan: <span class="text-danger fw-bold">Rp{{ number_format($sisaTagihan, 0, ',', '.') }}</span></small>
                                </div>
                            </div>

                            <!-- Action -->
                            <div class="col-md-3 text-md-end">
                                @if($sisaTagihan > 0)
                                    <a href="{{ route('anggota.pembayaran-wisata.show', $item->id_daftar_wisata) }}" 
                                       class="btn btn-pay rounded-pill shadow-sm">
                                        <i class="fas fa-credit-card me-1"></i> Bayar
                                    </a>
                                @else
                                    <span class="badge bg-success-subtle text-success p-2 px-3 rounded-pill border border-success border-opacity-25">
                                        <i class="fas fa-check-circle me-1"></i> Lunas
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12 text-center py-5">
                <img src="https://illustrations.popsy.co/amber/shaking-hands.svg" alt="No Data" style="width: 220px;" class="mb-4">
                <h4 class="fw-bold text-dark">Belum ada tagihan aktif</h4>
                <p class="text-muted small">Daftar wisata terlebih dahulu untuk melihat tagihan Anda di sini.</p>
                <a href="{{ route('anggota.dafwisata.index') }}" class="btn btn-primary rounded-pill px-4 mt-2 btn-sm">Jelajahi Wisata</a>
            </div>
        @endforelse
    </div>
</div>
@endsection