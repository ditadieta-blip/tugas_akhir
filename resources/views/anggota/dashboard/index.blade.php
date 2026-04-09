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
</style>

<div class="row">

    <div class="col-md-6 mb-4">
        <div class="card custom-card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="icon-box bg-danger-soft" style="background-color: #fff5f5;">
                    <i class="bi bi-file-earmark-medical text-danger fs-4"></i>
                </div>
                <h5 class="fw-bold text-dark">SOP Senam Terapi</h5>
                <p class="text-muted mb-4">
                    Panduan keselamatan dan prosedur penting sebelum serta saat sesi senam terapi dimulai.
                </p>
                <button class="btn btn-danger w-100 py-2 fw-semibold" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalSOP">
                    <i class="bi bi-eye me-2"></i> Lihat Detail SOP
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card custom-card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">Jadwal Terdekat</h5>
                    <span class="badge bg-primary-soft text-primary px-3 py-2" style="background-color: #e7f1ff;">Sesi Mendatang</span>
                </div>

                @if($senam)
                    @php
                        // Set locale ke Indonesia secara eksplisit untuk baris ini
                        $tgl = \Carbon\Carbon::parse($senam->tanggal)->locale('id');
                        $tgl->settings(['formatFunction' => 'translatedFormat']);
                    @endphp

                    <div class="p-3 schedule-border rounded-end">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3 text-center bg-white shadow-sm p-2 rounded" style="min-width: 70px; border: 1px solid #eee;">
                                <span class="d-block fw-bold text-primary fs-4" style="line-height: 1;">
                                    {{ $tgl->format('d') }}
                                </span>
                                <span class="d-block text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                                    {{ $tgl->translatedFormat('M Y') }}
                                </span>
                            </div>
                            
                            <div>
                                <h6 class="mb-0 fw-bold text-capitalize">{{ $tgl->translatedFormat('l') }}</h6>
                                <small class="badge {{ $tgl->isToday() ? 'bg-success' : 'bg-info' }}">
                                    {{ $tgl->isToday() ? 'Hari Ini' : ($tgl->isTomorrow() ? 'Besok' : 'Mendatang') }}
                                </small>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted d-block">Instruktur</small>
                                <span class="fw-semibold small"><i class="bi bi-person-circle me-1 text-primary"></i> {{ $senam->user->nama_user ?? '-' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Lokasi</small>
                                <span class="fw-semibold small"><i class="bi bi-geo-alt me-1 text-danger"></i> {{ $senam->tempat_senam }}</span>
                            </div>
                            <div class="col-12 mt-2">
                                <small class="text-muted d-block">Keterangan</small>
                                <p class="small mb-0 text-dark">{{ $senam->keterangan_senam }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x fs-1 text-muted opacity-50"></i>
                        <p class="text-muted mt-2">Belum ada jadwal</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

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
                        <h6 class="fw-bold text-danger text-uppercase mb-3"><i class="bi bi-arrow-right-circle me-2"></i>Sebelum Senam Terapi</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0 py-1 text-muted"><i class="bi bi-check2-circle text-success me-2"></i> Tidur / istirahat cukup</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted"><i class="bi bi-check2-circle text-success me-2"></i> Dalam keadaan sehat</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted"><i class="bi bi-check2-circle text-success me-2"></i> Makan secukupnya</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted"><i class="bi bi-check2-circle text-success me-2"></i> Hindari aktivitas berat</li>
                            <li class="list-group-item border-0 px-0 py-1 text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Tunda jika kurang sehat</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary text-uppercase mb-3"><i class="bi bi-arrow-right-circle me-2"></i>Saat Senam Terapi</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0 py-1 text-muted"><i class="bi bi-info-circle me-2"></i> Bernapas dengan normal</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted"><i class="bi bi-info-circle me-2"></i> Berhenti jika merasa pusing/mual</li>
                            <li class="list-group-item border-0 px-0 py-1 text-muted"><i class="bi bi-info-circle me-2"></i> Segera minta bantuan jika perlu</li>
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
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Saya Mengerti</button>
            </div>
        </div>
    </div>
</div>
@endsection