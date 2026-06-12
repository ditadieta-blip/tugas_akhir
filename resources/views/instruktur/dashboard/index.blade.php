@extends('layouts.instruktur')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
        --emerald-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
        --violet-gradient: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%);
        --dark-color: #0f172a;
        --muted-color: #64748b;
        --border-color: #f1f5f9;
    }

    body {
        background: #f8fafc;
    }

    /* ===== WELCOME BANNER ===== */
    .welcome-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
        border-radius: 24px;
        padding: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }

    /* ===== STATS CARD ===== */
    .stats-card {
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.03);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.4rem;
    }

    .bg-gradient-blue { background: var(--primary-gradient); }
    .bg-gradient-emerald { background: var(--emerald-gradient); }
    .bg-gradient-violet { background: var(--violet-gradient); }

    .stats-label {
        font-size: 0.82rem;
        color: var(--muted-color);
        font-weight: 600;
        margin-bottom: 2px;
    }

    .stats-number {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--dark-color);
        line-height: 1.2;
    }

    /* ===== MODERN CARD ===== */
    .modern-card {
        background: #fff;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        border: 1px solid #f1f5f9;
    }

    .modern-card h5 {
        font-weight: 800;
        color: var(--dark-color);
        font-size: 1.1rem;
    }

    .modern-card p {
        color: var(--muted-color);
        font-size: .88rem;
    }

    .chart-container {
        position: relative;
        width: 100%;
        height: 380px;
        margin-top: 20px;
    }
</style>

<!-- <div class="row mb-4">
    <div class="col-12">
        <div class="welcome-banner">
            <h3 class="fw-bold mb-1">Halo, Instruktur {{ auth()->user()->nama_user }}!</h3>
            <p class="mb-0 text-white-50" style="font-size: 0.95rem;">Siap melatih hari ini? Pantau perkembangan kehadiran peserta senam Anda di bawah ini.</p>
        </div>
    </div>
</div> -->

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="stats-card">
            <div class="icon-box bg-gradient-blue">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div>
                <div class="stats-label">Total Sesi Pertemuan</div>
                <div class="stats-number">
                    {{ $totalSesi }} Sesi
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-4">
        <div class="stats-card">
            <div class="icon-box bg-gradient-emerald">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stats-label">Rata-rata Hadir</div>
                <div class="stats-number">
                    {{ count($totalKehadiran) > 0 ? round(array_sum($totalKehadiran) / count($totalKehadiran)) : 0 }} Anggota
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="stats-card">
            <div class="icon-box bg-gradient-violet">
                <i class="bi bi-award-fill"></i>
            </div>
            <div>
                <div class="stats-label">Kehadiran Tertinggi</div>
                <div class="stats-number">
                    {{ count($totalKehadiran) > 0 ? max($totalKehadiran) : 0 }} Orang
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="modern-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5>Kehadiran Peserta Senam</h5>
                    <p class="mb-0">Grafik partisipasi anggota pada tiap sesi pertemuan aktif yang sudah berjalan.</p>
                </div>
                <span class="badge rounded-pill bg-light text-dark px-3 py-2 fw-semibold border">
                    <i class="bi bi-graph-up text-success me-1"></i> Ter-update otomatis
                </span>
            </div>

            <div class="chart-container">
                <canvas id="presenceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('presenceChart').getContext('2d');
        
        const chartGradient = ctx.createLinearGradient(0, 0, 0, 350);
        chartGradient.addColorStop(0, 'rgba(16, 185, 129, 0.35)'); 
        chartGradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)'); 

        const labels = {!! json_encode($pertemuanLabels) !!}; 
        const dataKehadiran = {!! json_encode($totalKehadiran) !!};

        new Chart(ctx, {
            type: 'line', 
            data: {
                labels: labels,
                datasets: [{
                    label: 'Anggota Hadir',
                    data: dataKehadiran,
                    borderColor: '#10b981', 
                    backgroundColor: chartGradient,
                    fill: true,
                    tension: 0.4, 
                    pointBackgroundColor: '#059669',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    borderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 10,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#64748b',
                            font: { weight: '500' }
                        },
                        grid: { color: '#f1f5f9' },
                        border: { display: false }
                    },
                    x: {
                        ticks: {
                            color: '#64748b',
                            font: { weight: '500' }
                        },
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>

@endsection