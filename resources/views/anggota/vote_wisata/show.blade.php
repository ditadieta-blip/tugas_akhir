@extends('layouts.anggota')

@section('title', 'Pilih Voting Wisata')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%); min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-xl-11">
            
            <!-- TOMBOL KEMBALI: Shadow lebih kelihatan & interaktif -->
            <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-down">
                <a href="{{ route('anggota.vote-wisata.index') }}" class="btn btn-link text-decoration-none text-dark fw-bold p-0 btn-hover-back">
                    <div class="bg-white shadow rounded-circle d-inline-flex align-items-center justify-content-center me-2 transition-all" style="width: 40px; height: 40px;">
                        <i class="bi bi-chevron-left"></i>
                    </div>
                    <span>Kembali</span>
                </a>
            </div>

            @if($sudahVoting)
                <div class="card border-0 shadow-lg rounded-5 overflow-hidden animate-fade-up">
                    <div class="card-body p-5 text-center">
                        <div class="py-5">
                            <div class="display-1 text-success mb-4"><i class="bi bi-check2-circle animate-bounce"></i></div>
                            <h2 class="fw-black text-dark">Terima Kasih!</h2>
                            <p class="text-muted fs-5">Suara Anda telah berhasil kami amankan dalam sistem.</p>
                            <a href="{{ route('anggota.vote-wisata.index') }}" class="btn btn-blue-gradient btn-lg rounded-pill px-5 mt-3 shadow">Lihat Hasil Real-time</a>
                        </div>
                    </div>
                </div>
            @else
                <form action="{{ route('anggota.vote-wisata.store', $voting->id_voting) }}" method="POST">
                    @csrf
                    <div class="row g-4 align-items-start"> <!-- Ditambahkan align-items-start agar card kiri tidak ikut memanjang -->
                        
                        <!-- CARD KIRI: Sudah tidak pakai h-100, tinggi otomatis pas sesuai konten -->
                        <div class="col-lg-4 animate-fade-up">
                            <div class="card border-0 shadow-sm rounded-5 bg-white p-4">
                                <h1 class="fw-bold text-dark fs-4 mb-3 tracking-tight">{{ $voting->judul_voting }}</h1>
                                
                                <div class="bg-light rounded-4 p-3 border border-dashed">
                                    <div class="d-flex align-items-center mb-1.5">
                                        <i class="bi bi-shield-check text-success me-2 fs-5"></i>
                                        <span class="fw-bold text-dark small">Satu Orang, Satu Vote</span>
                                    </div>
                                    <small class="text-muted d-block lh-sm" style="font-size: 0.8rem;">Pilihan yang sudah dikirimkan tidak dapat diubah kembali demi keakuratan data.</small>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: Pilihan Opsi -->
                        <div class="col-lg-8">
                            <div class="mb-4 animate-fade-up" style="animation-delay: 0.1s;">
                                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center text-xs tracking-widest">
                                    <span class="badge btn-dark-blue-gradient rounded-circle me-2 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 10px;">01</span>
                                    PILIH DESTINASI
                                </h5>
                                <div class="row g-3">
                                    @foreach($opsiLokasi as $opsi)
                                        <div class="col-md-6 col-xl-4">
                                            <input type="radio" class="btn-check" name="id_opsi_lokasi" id="loc-{{ $opsi->id_opsi }}" value="{{ $opsi->id_opsi }}" required>
                                            <label class="card border-0 shadow-sm option-card h-100 p-3" for="loc-{{ $opsi->id_opsi }}">
                                                <div class="d-flex flex-column h-100">
                                                    <div class="mb-3 d-flex justify-content-between">
                                                        <div class="icon-avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                            <i class="bi bi-geo-alt"></i>
                                                        </div>
                                                        <div class="check-tick"><i class="bi bi-check-circle-fill"></i></div>
                                                    </div>
                                                    <span class="fw-semibold text-dark small">{{ $opsi->nilai_opsi }}</span>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4 animate-fade-up" style="animation-delay: 0.2s;">
                                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center text-xs tracking-widest">
                                    <span class="badge btn-dark-blue-gradient rounded-circle me-2 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 10px;">02</span>
                                    PILIH TANGGAL
                                </h5>
                                <div class="row g-3">
                                    @foreach($opsiTanggal as $opsi)
                                        <div class="col-md-6 col-xl-4">
                                            <input type="radio" class="btn-check" name="id_opsi_tanggal" id="date-{{ $opsi->id_opsi }}" value="{{ $opsi->id_opsi }}" required>
                                            <label class="card border-0 shadow-sm option-card h-100 p-3" for="date-{{ $opsi->id_opsi }}">
                                                <div class="d-flex flex-column h-100">
                                                    <div class="mb-3 d-flex justify-content-between">
                                                        <div class="icon-avatar bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                            <i class="bi bi-calendar3"></i>
                                                        </div>
                                                        <div class="check-tick text-success"><i class="bi bi-check-circle-fill"></i></div>
                                                    </div>
                                                    <span class="fw-semibold text-dark small">{{ \Carbon\Carbon::parse($opsi->nilai_opsi)->locale('id')->translatedFormat('d F Y') }}</span>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- TOMBOL KIRIM GRADASI BIRU -->
                            <div class="text-end mt-5 animate-fade-up" style="animation-delay: 0.3s;">
                                <div class="d-inline-block text-center text-md-end">
                                    <button type="submit" class="btn btn-blue-gradient rounded-pill px-4 py-2.5 fw-bold shadow hover-lift border-0" style="font-size: 0.9rem;">
                                        Kirim 
                                    </button>
                                    <p class="text-muted mt-2 mb-0" style="font-size: 0.75rem;">
                                        <i class="bi bi-info-circle me-1"></i> Pastikan pilihan sudah sesuai
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .text-xs { font-size: 0.75rem; }
    .tracking-tight { letter-spacing: -0.02em; }
    .tracking-widest { letter-spacing: 0.08em; }
    
    .transition-all { transition: all 0.25s ease-in-out; }

    /* Efek Hover Tombol Kembali */
    .btn-hover-back:hover .rounded-circle {
        transform: translateX(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.12) !important;
        background-color: #f8fafc !important;
    }

    /* TOMBOL GRADASI BIRU TERANG */
    .btn-blue-gradient {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white !important;
    }

    .btn-blue-gradient:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3) !important;
    }

    /* GRADASI BIRU AGAK GELAP (Untuk Penomoran 01 dan 02) */
    .btn-dark-blue-gradient {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: #f8fafc;
    }

    /* Option Cards */
    .option-card {
        cursor: pointer;
        background: #ffffff;
        border-radius: 20px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent !important;
    }

    .option-card:hover {
        transform: translateY(-5px);
        background: #fdfdfd;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
    }

    .check-tick {
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.3s ease;
        font-size: 1.2rem;
        color: #0d6efd;
    }

    /* Selected State */
    .btn-check:checked + .option-card {
        background: #ffffff !important;
        border: 2px solid #0d6efd !important;
        box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15) !important;
    }

    .btn-check:checked + .option-card .check-tick {
        opacity: 1;
        transform: scale(1);
    }

    .btn-check:checked + .option-card .icon-avatar {
        background: #0d6efd !important;
        color: #ffffff !important;
    }

    /* Floating Animation */
    .hover-lift { transition: all 0.2s ease; }
    .hover-lift:hover { transform: translateY(-3px); }

    /* Animations */
    .animate-fade-up { animation: fadeUp 0.6s ease-out both; }
    .animate-fade-down { animation: fadeDown 0.6s ease-out both; }
    .animate-bounce { animation: bounce 2s infinite; }

    @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes bounce { 0%, 20%, 50%, 80%, 100% {transform: translateY(0);} 40% {transform: translateY(-10px);} 60% {transform: translateY(-5px);} }

    .border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: #dee2e6 !important; }
</style>
@endsection