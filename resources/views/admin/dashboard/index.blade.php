@extends('layouts.main')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root{
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        --dark-color: #0f172a;
        --muted-color: #64748b;
        --border-color: #f1f5f9;
    }

    body{
        background:#f8fafc;
    }

    .dashboard-header{
        margin-bottom: 28px;
    }

    .dashboard-header h3{
        font-weight: 800;
        color: var(--dark-color);
        margin-bottom: 5px;
        font-size: 1.9rem;
    }

    .dashboard-header p{
        color: var(--muted-color);
        margin-bottom: 0;
        font-size: .92rem;
    }

    /* ===== STATS CARD ===== */
    .stats-card{
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 24px;
        color: white;
        min-height: 175px;
        transition: .35s ease;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08);
    }

    .stats-card:hover{
        transform: translateY(-5px);
    }

    .stats-card::before{
        content:'';
        position:absolute;
        width:170px;
        height:170px;
        border-radius:50%;
        background: rgba(255,255,255,.08);
        top:-60px;
        right:-60px;
    }

    .stats-card::after{
        content:'';
        position:absolute;
        width:90px;
        height:90px;
        border-radius:50%;
        background: rgba(255,255,255,.05);
        bottom:-20px;
        right:20px;
    }

    .top-content{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        position:relative;
        z-index:2;
    }

    .stats-title{
        font-size:.82rem;
        font-weight:600;
        opacity:.92;
        letter-spacing:.3px;
    }

    .stats-value{
        font-size:2rem;
        font-weight:800;
        margin-top:10px;
        line-height:1.1;
    }

    .stats-icon{
        font-size:3.1rem;
        opacity:.16;
    }

    .stats-footer{
        position:absolute;
        bottom:20px;
        left:24px;
        font-size:.78rem;
        opacity:.9;
        z-index:2;
    }

    .gradient-primary{
        background: var(--primary-gradient);
    }

    .gradient-success{
        background: var(--success-gradient);
    }

    .gradient-warning{
        background: var(--warning-gradient);
    }

    .gradient-danger{
        background: var(--danger-gradient);
    }

    /* ===== CARD ===== */
    .modern-card{
        background:#fff;
        border-radius:28px;
        padding:24px;
        box-shadow:0 10px 30px rgba(15,23,42,.05);
        border:1px solid #f1f5f9;
    }

    .modern-card h5{
        font-weight:800;
        color:var(--dark-color);
        margin-bottom:4px;
        font-size:1rem;
    }

    .modern-card p{
        color:var(--muted-color);
        font-size:.82rem;
        margin-bottom:0;
    }

    /* ===== CHART ===== */
    .chart-wrapper{
        position:relative;
        width:100%;
        height:350px;
    }

    .chart-wrapper-flexible {
        position: relative;
        width: 100%;
        flex-grow: 1;
        min-height: 420px;
    }

    .chart-wrapper-small{
        height:240px;
    }

    .chart-stats{
        display:flex;
        gap:14px;
        flex-wrap:wrap;
        margin-top:20px;
    }

    .chart-stat-item{
        background:#f8fafc;
        border-radius:18px;
        padding:16px 18px;
        flex:1;
        border:1px solid #eef2f7;
    }

    .chart-stat-label{
        color:var(--muted-color);
        font-size:.75rem;
        margin-bottom:6px;
    }

    .chart-stat-value{
        font-size:1.25rem;
        font-weight:800;
        color:var(--dark-color);
    }

    /* ===== SCHEDULE ===== */
    .schedule-item{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:18px 0;
        border-bottom:1px solid var(--border-color);
        gap:12px;
    }

    .schedule-item:last-child{
        border-bottom:none;
        padding-bottom:0;
    }

    .schedule-left{
        display:flex;
        gap:14px;
        align-items:center;
        min-width:0;
    }

    .schedule-icon{
        width:48px;
        height:48px;
        border-radius:16px;
        background:#eef2ff;
        color:#4f46e5;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.1rem;
        flex-shrink:0;
    }

    .schedule-title{
        font-weight:700;
        color:var(--dark-color);
        margin-bottom:2px;
        font-size:.92rem;
    }

    .schedule-date{
        font-size:.78rem;
        color:var(--muted-color);
    }

    .schedule-badge{
        background:#ecfdf5;
        color:#10b981;
        padding:7px 12px;
        border-radius:12px;
        font-size:.7rem;
        font-weight:700;
        white-space:nowrap;
    }

    /* ===== VOTING ===== */
    .voting-card{
        padding:20px !important;
    }

    .voting-item{
        background:#f8fafc;
        border:1px solid #eef2f7;
        border-radius:18px;
        padding:14px;
        margin-bottom:12px;
    }

    .voting-item:last-child{
        margin-bottom:0;
    }

    .voting-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:10px;
        margin-bottom:10px;
    }

    .voting-name{
        font-size:.84rem;
        font-weight:700;
        color:var(--dark-color);
        line-height:1.4;
        word-break:break-word;
    }

    .voting-type{
        font-size:.68rem;
        color:var(--muted-color);
        margin-top:2px;
        text-transform:capitalize;
    }

    .voting-count{
        font-size:.72rem;
        font-weight:700;
        color:#4f46e5;
        background:#eef2ff;
        padding:5px 10px;
        border-radius:10px;
        flex-shrink:0;
    }

    .voting-percent{
        font-size:.7rem;
        color:var(--muted-color);
        margin-top:6px;
        text-align:right;
        font-weight:600;
    }

    .progress{
        height:8px;
        border-radius:20px;
        background:#e2e8f0;
        overflow:hidden;
    }

    .progress-bar{
        border-radius:20px;
        background: linear-gradient(135deg,#6366f1,#8b5cf6);
    }

    /* ===== EMPTY ===== */
    .empty-state{
        text-align:center;
        padding:35px 10px;
        color:#94a3b8;
    }

    .empty-state i{
        font-size:2.2rem;
        margin-bottom:10px;
        display:block;
    }

    @media(max-width:1200px){
        .voting-card{
            margin-top:0;
        }
    }

    @media(max-width:768px){
        .stats-card{
            min-height:auto;
        }

        .stats-value{
            font-size:1.6rem;
        }

        .modern-card{
            padding:20px;
        }

        .schedule-item{
            flex-direction:column;
            align-items:flex-start;
        }

        .schedule-badge{
            margin-left:60px;
        }

        .chart-wrapper{
            height:280px;
        }

        .chart-wrapper-flexible{
            min-height: 280px;
        }

        .chart-wrapper-small{
            height:220px;
        }

        .chart-stat-item{
            flex:1 1 100%;
        }

        .voting-top{
            flex-direction:column;
            align-items:flex-start;
        }
    }
</style>

<div class="dashboard-header">
    <h3>Dashboard Admin</h3>
    <p>Ringkasan statistik sistem senam dan wisata.</p>
</div>

<div class="row g-4">

    {{-- TOTAL ANGGOTA --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card gradient-primary">
            <div class="top-content">
                <div>
                    <div class="stats-title">Jumlah Anggota</div>
                    <div class="stats-value">{{ $jumlahAnggota }}</div>
                </div>
                <i class="bi bi-people-fill stats-icon"></i>
            </div>
            <div class="stats-footer">
                Total seluruh anggota aktif
            </div>
        </div>
    </div>

    {{-- INSTRUKTUR --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card gradient-success">
            <div class="top-content">
                <div>
                    <div class="stats-title">Instruktur</div>
                    <div class="stats-value">{{ $jumlahInstruktur }}</div>
                </div>
                <i class="bi bi-person-badge-fill stats-icon"></i>
            </div>
            <div class="stats-footer">
                Instruktur senam aktif
            </div>
        </div>
    </div>

    {{-- WISATA --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card gradient-warning">
            <div class="top-content">
                <div>
                    <div class="stats-title">Wisata</div>
                    <div class="stats-value">{{ $totalWisata }}</div>
                </div>
                <i class="bi bi-airplane-fill stats-icon"></i>
            </div>
            <div class="stats-footer">
                Jadwal wisata tersedia
            </div>
        </div>
    </div>

    {{-- PEMASUKAN (OTOMATIS RESET TIAP BULAN BARU) --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card gradient-danger">
            <div class="top-content">
                <div>
                    <div class="stats-title">Total Pemasukan Iuran</div>
                    <div class="stats-value" style="font-size:1.25rem;">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </div>
                </div>
                <i class="bi bi-cash-stack stats-icon"></i>
            </div>
            <div class="stats-footer">
                Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
            </div>
        </div>
    </div>

    {{-- GRAFIK KEHADIRAN --}}
    <div class="col-12 col-xl-8">
        <div class="modern-card d-flex flex-column h-100 justify-content-between">
            <div class="d-flex flex-column flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h5>Statistik Kehadiran Anggota</h5>
                        <p>Grafik jumlah anggota hadir setiap pertemuan senam</p>
                    </div>
                    <div class="badge rounded-pill text-bg-light px-3 py-2 fw-semibold">
                        {{ count($pertemuanLabels) }} Pertemuan
                    </div>
                </div>

                <div class="chart-wrapper-flexible">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <div class="chart-stats mt-3">
                <div class="chart-stat-item">
                    <div class="chart-stat-label">Kehadiran Tertinggi</div>
                    <div class="chart-stat-value">
                        {{ count($totalKehadiran) ? max($totalKehadiran) : 0 }}
                    </div>
                </div>
                <div class="chart-stat-item">
                    <div class="chart-stat-label">Kehadiran Terendah</div>
                    <div class="chart-stat-value">
                        {{ count($totalKehadiran) ? min($totalKehadiran) : 0 }}
                    </div>
                </div>
                <div class="chart-stat-item">
                    <div class="chart-stat-label">Rata-rata Hadir</div>
                    <div class="chart-stat-value">
                        {{ count($totalKehadiran) ? round(array_sum($totalKehadiran)/count($totalKehadiran)) : 0 }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SIDEBAR JADWAL & VOTING --}}
    <div class="col-12 col-xl-4">
        <div class="d-flex flex-column gap-4">

            {{-- JADWAL WISATA --}}
            <div class="modern-card">
                <div class="mb-4">
                    <h5>Jadwal Wisata</h5>
                    <p>Wisata terdekat yang akan berlangsung</p>
                </div>

                @forelse($wisataTerdekat as $item)
                    <div class="schedule-item">
                        <div class="schedule-left">
                            <div class="schedule-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <div class="schedule-title">{{ $item->nama_wisata }}</div>
                                <div class="schedule-date">{{ $item->tanggal_indonesia }}</div>
                            </div>
                        </div>
                        <div class="schedule-badge">
                            {{ $item->pendaftaran_count }} Peserta
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        Belum ada jadwal wisata
                    </div>
                @endforelse
            </div>

            {{-- HASIL VOTING --}}
            <div class="modern-card voting-card">
                <div class="mb-4">
                    <h5>Hasil Voting Sementara</h5>
                    <p>Voting wisata yang sedang berlangsung</p>
                </div>

                @if($votingAktif && count($hasilVoting))
                    @foreach($hasilVoting as $item)
                        <div class="voting-item">
                            <div class="voting-top">
                                <div style="min-width:0;">
                                    <div class="voting-name">{{ $item['nama'] }}</div>
                                    <div class="voting-type">{{ $item['jenis'] }}</div>
                                </div>
                                <div class="voting-count">{{ $item['vote'] }} vote</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ $item['persen'] }}%;"></div>
                            </div>
                            <div class="voting-percent">{{ $item['persen'] }}%</div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="bi bi-bar-chart-line"></i>
                        Belum ada voting aktif
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- GRAFIK WISATA --}}
    <div class="col-12">
        <div class="modern-card">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h5>Statistik Pendaftaran Wisata</h5>
                    <p>Grafik jumlah peserta pada setiap wisata</p>
                </div>
                <div class="badge rounded-pill text-bg-light px-3 py-2 fw-semibold">
                    {{ count($wisataLabels) }} Wisata
                </div>
            </div>

            <div class="chart-wrapper chart-wrapper-small">
                <canvas id="wisataChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script>
    // GRAFIK KEHADIRAN
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceGradient = attendanceCtx.createLinearGradient(0, 0, 0, 400);
    attendanceGradient.addColorStop(0, 'rgba(99,102,241,0.35)');
    attendanceGradient.addColorStop(1, 'rgba(99,102,241,0.02)');

    new Chart(attendanceCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($pertemuanLabels) !!},
            datasets: [{
                data: {!! json_encode($totalKehadiran) !!},
                borderColor: '#6366f1',
                backgroundColor: attendanceGradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: '#64748b' },
                    grid: { color: '#eef2f7' },
                    border: { display: false }
                },
                x: {
                    ticks: { color: '#64748b' },
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });

    // GRAFIK WISATA
    const wisataCtx = document.getElementById('wisataChart').getContext('2d');
    const wisataGradient = wisataCtx.createLinearGradient(0, 0, 0, 400);
    wisataGradient.addColorStop(0, 'rgba(16,185,129,0.35)');
    wisataGradient.addColorStop(1, 'rgba(16,185,129,0.02)');

    new Chart(wisataCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($wisataLabels) !!},
            datasets: [{
                data: {!! json_encode($totalPesertaWisata) !!},
                borderColor: '#10b981',
                backgroundColor: wisataGradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#059669',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: '#64748b' },
                    grid: { color: '#eef2f7' },
                    border: { display: false }
                },
                x: {
                    ticks: { color: '#64748b' },
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });
</script>

@endsection