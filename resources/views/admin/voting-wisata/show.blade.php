@extends('layouts.main')
@section('title', 'Detail Voting Wisata')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Utama -->
    <div class="row align-items-start mb-4 g-3">
        <!-- Kolom Kiri: Judul -->
        <div class="col-12 col-md-8">
            <h3 class="fw-bold text-dark mb-1" style="color: #2d336b !important;">
                Statistik Voting
            </h3>
            <p class="text-muted mb-0">Hasil sementara voting untuk <strong>{{ $voting->judul_voting }}</strong></p>
        </div>
        
        <!-- Kolom Kanan: Status & Tombol Kembali -->
        <div class="col-12 col-md-4 text-md-end d-flex flex-column align-items-start align-items-md-end gap-2">
            @php
                $statusBadge = $voting->status == 'aktif' ? 'bg-success text-success' : ($voting->status == 'draft' ? 'bg-warning text-warning' : 'bg-secondary text-secondary');
            @endphp
            <span class="badge {{ $statusBadge }} bg-opacity-10 border border-current rounded-pill px-3 py-2 fw-bold small">
                Status: {{ ucfirst($voting->status) }}
            </span>
            <a href="{{ route('admin.voting-wisata.index') }}" class="btn btn-white border rounded-pill px-4 shadow-sm btn-mobile-full">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Statistik Ringkas (Cards) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary-soft text-primary me-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase tracking-wider fs-7">Total Partisipan</small>
                        <span class="h4 fw-bold mb-0 text-dark">{{ $voting->detailVoting->count() }} <small class="fs-6 fw-normal text-muted">Orang</small></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-danger-soft text-danger me-3">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase tracking-wider fs-7">Kandidat Lokasi</small>
                        <span class="h4 fw-bold mb-0 text-dark">{{ $opsiLokasi->count() }} <small class="fs-6 fw-normal text-muted">Pilihan</small></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-dark border-4" style="border-left-color: #2d336b !important;">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-dark-soft text-dark me-3" style="color: #2d336b !important; background-color: rgba(45, 51, 107, 0.1);">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase tracking-wider fs-7">Kandidat Tanggal</small>
                        <span class="h4 fw-bold mb-0 text-dark">{{ $opsiTanggal->count() }} <small class="fs-6 fw-normal text-muted">Pilihan</small></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Visualisasi (Terpisah Opsi) & Ranking -->
    <div class="row g-4">
        <!-- Kolom Kiri: Memuat Dua Diagram Terpisah -->
        <div class="col-12 col-lg-7 col-xl-8 d-flex flex-column gap-4">
            
            <!-- Diagram Batang Kategori Lokasi -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-1">Visualisasi Hasil Opsi Lokasi</h5>
                    <p class="text-muted small mb-0">Perbandingan perolehan suara kandidat tempat wisata</p>
                </div>
                <div class="card-body px-2 px-sm-4 py-3">
                    <div id="lokasiVotingChart" style="min-height: 280px;"></div>
                </div>
            </div>

            <!-- Diagram Batang Kategori Tanggal -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-1">Visualisasi Hasil Opsi Tanggal</h5>
                    <p class="text-muted small mb-0">Perbandingan perolehan suara kandidat jadwal liburan</p>
                </div>
                <div class="card-body px-2 px-sm-4 py-3">
                    <div id="tanggalVotingChart" style="min-height: 280px;"></div>
                </div>
            </div>

        </div>

        <!-- Ranking List -->
        <div class="col-12 col-lg-5 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0">Ranking Sementara</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <!-- Kategori Lokasi -->
                        <div class="px-4 py-2 bg-light text-muted fw-bold tracking-widest style-category-title">LOKASI TERBANYAK</div>
                        @forelse($opsiLokasi as $index => $lokasi)
                        <div class="list-group-item px-4 py-3 border-0 item-hover-effect">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center me-2">
                                    <span class="badge-rank bg-primary-soft text-primary fw-bold me-3">{{ $index + 1 }}</span>
                                    <span class="fw-semibold text-dark text-truncate style-item-name">{{ $lokasi->nilai_opsi }}</span>
                                </div>
                                <span class="badge rounded-pill bg-light text-primary border fw-bold px-3 py-2 flex-shrink-0">{{ $lokasi->jumlah_vote }} Vote</span>
                            </div>
                        </div>
                        @empty
                        <div class="px-4 py-3 text-muted small">Belum ada pilihan lokasi.</div>
                        @endforelse {{-- <--- PERBAIKAN DI SINI (Sebelumnya @endforeach) --}}

                        <!-- Kategori Tanggal -->
                        <div class="px-4 py-2 bg-light text-muted fw-bold tracking-widest style-category-title">TANGGAL TERBANYAK</div>
                        @forelse($opsiTanggal as $index => $tanggal)
                        <div class="list-group-item px-4 py-3 border-0 item-hover-effect">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center me-2">
                                    <span class="badge-rank bg-dark-soft fw-bold me-3" style="color: #2d336b; background-color: rgba(45, 51, 107, 0.1);">{{ $index + 1 }}</span>
                                    <span class="fw-semibold text-dark text-truncate style-item-name">
                                        {{ \Carbon\Carbon::parse($tanggal->nilai_opsi)->locale('id')->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                                <span class="badge rounded-pill bg-light border fw-bold px-3 py-2 flex-shrink-0" style="color: #2d336b;">{{ $tanggal->jumlah_vote }} Vote</span>
                            </div>
                        </div>
                        @empty
                        <div class="px-4 py-3 text-muted small">Belum ada pilihan tanggal.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Library Pembuat Chart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Source Data Utama
        const lokasiLabels = {!! json_encode($opsiLokasi->pluck('nilai_opsi')) !!};
        const lokasiVotes = {!! json_encode($opsiLokasi->pluck('jumlah_vote')) !!};
        
        const tanggalRaw = {!! json_encode($opsiTanggal->pluck('nilai_opsi')) !!};
        const tanggalVotes = {!! json_encode($opsiTanggal->pluck('jumlah_vote')) !!};
        
        const bulanIndo = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

        const tanggalLabels = tanggalRaw.map(dateStr => {
            const date = new Date(dateStr);
            const day = date.getDate();
            const monthIndex = date.getMonth();
            return day + ' ' + bulanIndo[monthIndex];
        });

        // 1. CONFIGURATION CHART LOKASI (BIRU)
        const optionsLokasi = {
            series: [{
                name: 'Perolehan Vote Lokasi',
                data: lokasiVotes
            }],
            chart: {
                type: 'bar',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Nunito, system-ui, -apple-system, sans-serif'
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                    horizontal: false,
                    borderRadius: 0,
                    dataLabels: { position: 'top' }
                }
            },
            colors: lokasiLabels.map(() => '#4e73df'),
            dataLabels: {
                enabled: true,
                style: { 
                    colors: ['#4e73df'],
                    fontWeight: '700',
                    fontSize: '12px'
                },
                offsetY: -22,
            },
            xaxis: {
                categories: lokasiLabels,
                labels: {
                    show: true,
                    style: { colors: '#858796', fontSize: '11px', fontWeight: '600' }
                }
            },
            yaxis: {
                labels: {
                    show: true,
                    style: { colors: '#858796', fontSize: '12px' },
                    formatter: function (val) { return Math.floor(val); }
                },
                tickAmount: lokasiVotes.length > 0 ? Math.min(Math.max(...lokasiVotes), 5) : 1
            },
            grid: {
                borderColor: '#e3e6f0',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                theme: 'light',
                y: { formatter: function (val) { return val + " Suara" } }
            },
            legend: { show: false }
        };

        const chartLokasi = new ApexCharts(document.querySelector("#lokasiVotingChart"), optionsLokasi);
        chartLokasi.render();


        // 2. CONFIGURATION CHART TANGGAL (NAVY GELAP)
        const optionsTanggal = {
            series: [{
                name: 'Perolehan Vote Tanggal',
                data: tanggalVotes
            }],
            chart: {
                type: 'bar',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Nunito, system-ui, -apple-system, sans-serif'
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                    horizontal: false,
                    borderRadius: 0,
                    dataLabels: { position: 'top' }
                }
            },
            colors: tanggalLabels.map(() => '#2d336b'),
            dataLabels: {
                enabled: true,
                style: { 
                    colors: ['#2d336b'],
                    fontWeight: '700',
                    fontSize: '12px'
                },
                offsetY: -22,
            },
            xaxis: {
                categories: tanggalLabels,
                labels: {
                    show: true,
                    style: { colors: '#858796', fontSize: '11px', fontWeight: '600' }
                }
            },
            yaxis: {
                labels: {
                    show: true,
                    style: { colors: '#858796', fontSize: '12px' },
                    formatter: function (val) { return Math.floor(val); }
                },
                tickAmount: tanggalVotes.length > 0 ? Math.min(Math.max(...tanggalVotes), 5) : 1
            },
            grid: {
                borderColor: '#e3e6f0',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                theme: 'light',
                y: { formatter: function (val) { return val + " Suara" } }
            },
            legend: { show: false }
        };

        const chartTanggal = new ApexCharts(document.querySelector("#tanggalVotingChart"), optionsTanggal);
        chartTanggal.render();
    });
</script>

<style>
    /* Styling Dasar Box Statis */
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .bg-primary-soft { background-color: rgba(78, 115, 223, 0.1); }
    .bg-danger-soft { background-color: rgba(231, 74, 59, 0.1); }

    /* Penomoran List Rangking */
    .badge-rank {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    /* Kustomisasi Teks */
    .style-category-title {
        font-size: 0.7rem;
        letter-spacing: 1px;
        font-weight: 800;
        color: #858796 !important;
    }
    .style-item-name {
        font-size: 0.9rem;
        max-width: 180px;
    }
    .fs-7 { font-size: 0.75rem; }
    .btn-white { background: #fff; color: #4e73df; }
    .btn-white:hover { background: #f8f9fc; color: #224abe; }

    /* Efek Animasi List */
    .item-hover-effect {
        transition: background-color 0.2s ease;
    }
    .item-hover-effect:hover {
        background-color: #f8f9fc !important;
    }

    /* Responsivitas Layar Handphone */
    @media (max-width: 768px) {
        .btn-mobile-full {
            width: 100%;
            text-align: center;
        }
    }
    @media (max-width: 576px) {
        .style-item-name {
            max-width: 120px;
        }
    }
</style>
@endsection