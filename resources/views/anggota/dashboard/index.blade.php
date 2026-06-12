@extends('layouts.anggota')

@section('content')
<style>
    .custom-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border-radius: 15px;
        overflow: hidden;
    }
    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-box {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    .schedule-border {
        border-left: 4px solid #0d6efd;
        background: #f8f9fa;
    }
    .mini-stat-card {
        border-radius: 16px;
        border: none;
        transition: all 0.3s ease;
    }
    .mini-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* Tombol Biru Gradasi Kecil & Serasi */
    .btn-blue-gradient-sm {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white !important;
        font-size: 0.8rem;
        padding: 6px 16px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
    }
    .btn-blue-gradient-sm:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
</style>

<div class="container-fluid px-0">

    <!-- ================= 1. ROW WIDGET RINGKASAN ATAS (DINAMIS) ================= -->
    <div class="row mb-4">
        <!-- Widget Status Vote -->
        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card mini-stat-card shadow-sm p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block mb-1">Status Voting</small>
                        @if($votingAktif)
                            @if($sudahVoting)
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold">Sudah Memilih</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning fw-bold">Belum Memilih</span>
                            @endif
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">Tidak Ada Voting</span>
                        @endif
                    </div>
                    <div class="mini-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-ballot fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget Status Pembayaran -->
        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card mini-stat-card shadow-sm p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block mb-1">Pembayaran Wisata</small>
                        
                        @if($statusPembayaran == 'lunas')
                            <span class="badge bg-success bg-opacity-10 text-success fw-bold">Lunas</span>
                        @elseif($statusPembayaran == 'cicilan')
                            <!-- Badge khusus untuk yang pembayarannya masih dicicil -->
                            <span class="badge bg-info bg-opacity-10 text-info fw-bold">Belum Lunas (Dicicil)</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger fw-bold">Belum Bayar</span>
                        @endif

                    </div>
                    <div class="mini-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget Kehadiran Senam -->
        <div class="col-12 col-lg-3 mb-3 mb-lg-0">
            <div class="card mini-stat-card shadow-sm p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block mb-1">Status Keanggotaan</small>
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">Anggota Aktif</span>
                    </div>
                    <div class="mini-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-person-check fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget Jadwal Hari Ini -->
        <div class="col-12 col-lg-3">
            <div class="card mini-stat-card shadow-sm p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block mb-1">Sesi Senam Terdekat</small>
                        @if($senam)
                            <span class="fw-bold text-dark small d-block text-truncate" style="max-width: 140px;">
                                {{ \Carbon\Carbon::parse($senam->tanggal)->locale('id')->translatedFormat('l, d M') }}
                            </span>
                        @else
                            <span class="fw-semibold text-muted small d-block">Belum Tersedia</span>
                        @endif
                    </div>
                    <div class="mini-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ================= 2. ROW UTAMA (SOP & JADWAL SENAM DINAMIS) ================= -->
    <div class="row mb-4">
        <!-- Card SOP -->
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card custom-card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-box" style="background-color: #fff5f5;">
                            <i class="bi bi-file-earmark-medical text-danger fs-4"></i>
                        </div>
                        <h5 class="fw-bold text-dark">SOP Senam Terapi</h5>
                        <p class="text-muted small">
                            Panduan keselamatan dan prosedur penting sebelum serta saat sesi senam terapi dimulai demi kenyamanan bersama.
                        </p>
                    </div>
                    <button class="btn btn-danger w-100 py-2 fw-semibold mt-3" style="border-radius: 10px; font-size: 0.9rem;" data-bs-toggle="modal" data-bs-target="#modalSOP">
                        <i class="bi bi-eye me-2"></i> Lihat Detail SOP
                    </button>
                </div>
            </div>
        </div>

        <!-- Card Jadwal Terdekat (Dinamis: Ada/Tidak Tetap Presisi) -->
        <div class="col-md-6">
            <div class="card custom-card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">Jadwal Terdekat</h5>
                            @if($senam)
                                <span class="badge text-primary px-3 py-2" style="background-color: #e7f1ff; font-size: 0.75rem;">Sesi Mendatang</span>
                            @endif
                        </div>

                        @if($senam)
                            @php
                                $tgl = \Carbon\Carbon::parse($senam->tanggal)->locale('id');
                                $tgl->settings(['formatFunction' => 'translatedFormat']);
                            @endphp

                            <div class="p-3 schedule-border rounded-end">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="me-3 text-center bg-white shadow-sm p-2 rounded" style="min-width: 70px; border: 1px solid #eee;">
                                        <span class="d-block fw-bold text-primary fs-4" style="line-height: 1;">
                                            {{ $tgl->format('d') }}
                                        </span>
                                        <span class="d-block text-uppercase fw-bold text-muted" style="font-size: 0.65rem;">
                                            {{ $tgl->translatedFormat('M Y') }}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <h6 class="mb-0 fw-bold text-capitalize" style="font-size: 0.95rem;">{{ $tgl->translatedFormat('l') }}</h6>
                                        <small class="badge {{ $tgl->isToday() ? 'bg-success' : 'bg-info' }}" style="font-size: 0.7rem;">
                                            {{ $tgl->isToday() ? 'Hari Ini' : ($tgl->isTomorrow() ? 'Besok' : 'Mendatang') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Instruktur</small>
                                        <span class="fw-semibold small text-dark"><i class="bi bi-person-circle me-1 text-primary"></i> {{ $senam->user->nama_user ?? 'Belum Ditentukan' }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Lokasi</small>
                                        <span class="fw-semibold small text-dark"><i class="bi bi-geo-alt me-1 text-danger"></i> {{ $senam->tempat_senam }}</span>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Keterangan</small>
                                        <p class="small mb-0 text-dark text-truncate">{{ $senam->keterangan_senam ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Tampilan Tetap Di Tempat Saat Jadwal Kosong -->
                            <div class="text-center py-4 my-auto">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-calendar-x text-muted fs-3"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Jadwal Belum Tersedia</h6>
                                <p class="text-muted small mb-0">Belum ada jadwal senam terapi baru yang dikonfirmasi oleh admin.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ================= 3. ROW BANNER AKTIVITAS WISATA (DINAMIS & SEIMBANG) ================= -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card border-0 shadow-sm p-4 bg-white">
                <div class="row align-items-center">
                    @if($votingAktif)
                        <!-- Jika Ada Agenda Voting Terbuka -->
                        <div class="col-md-9 mb-3 mb-md-0">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 0.75rem;">Voting Wisata Aktif</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">{{ $votingAktif->judul_voting }}</h5>
                            <p class="text-muted small mb-0">Suara Anda menentukan ke mana tujuan wisata periode ini. Silakan berikan pilihan Anda.</p>
                        </div>
                        <div class="col-md-3 text-md-end">
                            @if($sudahVoting)
                                <a href="{{ route('anggota.vote-wisata.index') }}" class="btn btn-outline-primary btn-sm rounded-3 px-3 fw-semibold" style="font-size: 0.8rem;">
                                    <i class="bi bi-bar-chart-line me-1"></i> Lihat Hasil
                                </a>
                            @else
                                <a href="{{ route('anggota.vote-wisata.show', $votingAktif->id_voting) }}" class="btn btn-blue-gradient-sm shadow-sm">
                                    Ikut Voting <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            @endif
                        </div>
                    @else
                        <!-- Tampilan Tetap Di Tempat Saat Agenda Kosong -->
                        <div class="col-12 text-center py-2">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="bi bi-balloon text-muted fs-5"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Belum Ada Agenda Wisata</h6>
                                <p class="text-muted small mb-0">Saat ini tidak ada pemungutan suara atau agenda wisata kelompok yang sedang aktif berjalan.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ================= MODAL SOP (Tetap Sama) ================= -->
<div class="modal fade" id="modalSOP" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-danger text-white p-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-shield-check me-2"></i> Standar Operasional Prosedur (SOP)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h6 class="fw-bold text-danger text-uppercase mb-3" style="font-size: 0.85rem;"><i class="bi bi-arrow-right-circle me-2"></i>Sebelum Senam Terapi</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0 py-1 text-muted small"><i class="bi bi-check2-circle text-success me-2"></i> Tidur / istirahat cukup</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted small"><i class="bi bi-check2-circle text-success me-2"></i> Dalam keadaan sehat</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted small"><i class="bi bi-check2-circle text-success me-2"></i> Makan secukupnya</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted small"><i class="bi bi-check2-circle text-success me-2"></i> Hindari aktivitas berat</li>
                            <li class="list-group-item border-0 px-0 py-1 text-danger fw-bold small"><i class="bi bi-exclamation-triangle-fill me-2"></i> Tunda jika kurang sehat</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary text-uppercase mb-3" style="font-size: 0.85rem;"><i class="bi bi-arrow-right-circle me-2"></i>Saat Senam Terapi</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0 py-1 text-muted small"><i class="bi bi-info-circle me-2"></i> Bernapas dengan normal</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted small"><i class="bi bi-info-circle me-2"></i> Berhenti jika merasa pusing/mual</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted small"><i class="bi bi-info-circle me-2"></i> Segera minta bantuan jika perlu</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-4 p-3 rounded bg-light border-start border-danger border-4">
                    <p class="text-danger small mb-0 fw-bold">
                        ⚠️ PERHATIAN: Semua risiko yang muncul selama atau setelah senam terapi menjadi tanggung jawab masing-masing peserta.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-secondary px-4 btn-sm" data-bs-dismiss="modal">Saya Mengerti</button>
            </div>
        </div>
    </div>
</div>
@endsection