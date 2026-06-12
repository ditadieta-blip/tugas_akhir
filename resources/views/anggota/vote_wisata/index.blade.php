@extends('layouts.anggota')

@section('content')
<div class="container-fluid py-4 py-md-5" style="background: #f8fafc; min-height: 100vh;">
    
    <!-- HEADER: Modern, Clean, Estetik & Jauh dari Kesan Kuno -->
    <div class="row mb-5 justify-content-center">
        <div class="col-12 max-width-container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 pb-3 border-bottom border-gray-200">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h1 class="fw-bold text-dark fs-3 mb-0 tracking-tight">Vote Wisata</h1>
                    </div>
                    <p class="text-muted small mb-0 ps-1">Suara kamu menentukan arah liburan keluarga besar BSC berikutnya.</p>
                </div>
                
                <!-- Tombol kecil fungsional agar terlihat seperti aplikasi premium -->
                <div class="d-flex align-items-center gap-2 ps-1 ps-md-0">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-3 fw-medium small d-flex align-items-center gap-1.5 shadow-sm">
                        <span class="d-inline-block bg-success rounded-circle" style="width: 7px; height: 7px;"></span>
                        {{ $votings->count() }} Agenda Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if($votings->isEmpty())
        <div class="row">
            <div class="col-md-6 mx-auto text-center py-5">
                <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">
                    <i class="bi bi-calendar2-x fs-1 text-muted mb-3"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Agenda Voting</h5>
                    <p class="text-muted small mb-0">Voting akan segera muncul di sini begitu panitia merilis agenda wisata baru.</p>
                </div>
            </div>
        </div>
    @else
        <div class="row g-4 justify-content-center">
            @foreach($votings as $voting)
                @php
                    $sudahVote = $voting->detailVoting->isNotEmpty();
                    $opsiLokasi = $voting->opsi->where('jenis_opsi', 'lokasi')->sortByDesc('jumlah_vote');
                    $opsiTanggal = $voting->opsi->where('jenis_opsi', 'tanggal')->sortByDesc('jumlah_vote');
                @endphp
                
                <div class="col-12 max-width-container">
                    <div class="card border-0 shadow-sm card-voting-wide overflow-hidden">
                        <div class="row g-0">
                            <!-- KOLOM KIRI: Detail Info & Button -->
                            <div class="col-xl-4 col-lg-5 p-4 p-md-4.5 bg-white d-flex flex-column justify-content-between border-end-dashed">
                                <div class="w-full">
                                    <div class="mb-3">
                                        <span class="badge {{ $sudahVote ? 'bg-light text-muted border' : 'bg-primary bg-opacity-10 text-primary' }} rounded-3 px-2.5 py-1.5 text-xs font-semibold">
                                            <i class="bi {{ $sudahVote ? 'bi-check-circle-fill text-success' : 'bi-lightning-charge-fill' }} me-1"></i>
                                            {{ $sudahVote ? 'Sudah Diikuti' : 'Belum Memilih' }}
                                        </span>
                                    </div>
                                    <h3 class="fw-bold text-dark fs-4 mb-2 tracking-tight">{{ $voting->judul_voting }}</h3>
                                    <p class="text-muted mb-4 small">Total partisipan: <strong class="text-dark">{{ $voting->detail_voting_count }} Anggota</strong></p>
                                </div>
                                
                                <!-- BAGIAN BUTTON: Menggunakan Gradasi Biru -->
                                <div class="mt-auto pt-2">
                                    @if($sudahVote)
                                        <div class="p-2.5 bg-success bg-opacity-10 border border-success border-opacity-20 rounded-3 text-success small fw-medium d-inline-flex align-items-center gap-2 shadow-inner-sm w-100 justify-content-center justify-content-md-start">
                                            <i class="bi bi-check2-circle fs-5"></i>
                                            Suara Anda berhasil masuk
                                        </div>
                                    @else
                                        <!-- Menggunakan tombol baru berbasis Gradasi Biru yang dinamis -->
                                        <a href="{{ route('anggota.vote-wisata.show', $voting->id_voting) }}" 
                                           class="btn btn-blue-gradient rounded-3 py-2.5 px-4 fw-semibold text-white shadow-sm d-inline-flex align-items-center justify-content-center w-100 w-md-auto transition-all">
                                            <span>Vote Wisata</span>
                                            <i class="bi bi-chevron-right ms-1.5 small"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- KOLOM KANAN: Hasil Progres Bar -->
                            <div class="col-xl-8 col-lg-7 p-4 bg-light-subtle d-flex align-items-center">
                                <div class="row g-4 w-100 m-0">
                                    <div class="col-md-6 p-0 pe-md-3 mb-3 mb-md-0">
                                        <h6 class="fw-bold mb-3 text-uppercase small tracking-widest text-secondary d-flex align-items-center" style="font-size: 11px;">
                                            <i class="bi bi-geo-alt me-1.5 text-primary"></i>Pilihan Destinasi
                                        </h6>
                                        @foreach($opsiLokasi as $opsi)
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="small fw-medium text-dark">{{ $opsi->nilai_opsi }}</span>
                                                    <span class="small text-muted" style="font-size: 11px;">{{ $opsi->jumlah_vote }} suara</span>
                                                </div>
                                                <div class="progress rounded-3 shadow-inner" style="height: 6px;">
                                                    <div class="progress-bar bg-primary rounded-3" style="width: {{ ($opsi->jumlah_vote / ($voting->detail_voting_count ?: 1)) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="col-md-6 p-0 ps-md-3">
                                        <h6 class="fw-bold mb-3 text-uppercase small tracking-widest text-secondary d-flex align-items-center" style="font-size: 11px;">
                                            <i class="bi bi-calendar-event me-1.5 text-danger"></i>Pilihan Tanggal
                                        </h6>
                                        @foreach($opsiTanggal as $opsi)
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="small fw-medium text-dark">
                                                        {{ \Carbon\Carbon::parse($opsi->nilai_opsi)->locale('id')->translatedFormat('d F Y') }}
                                                    </span>
                                                    <span class="small text-muted" style="font-size: 11px;">{{ $opsi->jumlah_vote }} suara</span>
                                                </div>
                                                <div class="progress rounded-3 shadow-inner" style="height: 6px;">
                                                    <div class="progress-bar bg-danger rounded-3" style="width: {{ ($opsi->jumlah_vote / ($voting->detail_voting_count ?: 1)) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .max-width-container {
        max-width: 1060px;
        margin-left: auto;
        margin-right: auto;
    }

    .card-voting-wide {
        border-radius: 14px !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff;
    }
    
    .border-end-dashed {
        border-right: 1px dashed #e2e8f0;
    }

    /* KUSTOMISASI TOMBOL: Gradasi Biru Modern */
    .btn-blue-gradient {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border: none;
        font-size: 0.85rem;
    }

    .btn-blue-gradient:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .rounded-3 {
        border-radius: 8px !important;
    }

    .shadow-inner {
        background-color: #f1f5f9;
    }

    .tracking-tight { letter-spacing: -0.02em; }
    .tracking-widest { letter-spacing: 0.06em; }

    /* Media Query Responsif */
    @media (max-width: 991px) {
        .border-end-dashed {
            border-right: none;
            border-bottom: 1px dashed #e2e8f0;
        }
    }
</style>
@endsection