@extends('layouts.anggota') 

@section('content')

@php
\Carbon\Carbon::setLocale('id');
@endphp

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --soft-bg: #f8f9fc;
    }

    body {
        font-size: 0.875rem;
    }

    .main-container { 
        padding: 20px; 
        background-color: var(--soft-bg); 
        min-height: 100vh; 
    }
    
    .page-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 25px; 
    }
    
    .card-modern { 
        background: #ffffff; 
        border: none; 
        border-radius: 16px; 
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); 
        padding: 25px; 
    }

    .table-custom { 
        border-collapse: separate; 
        border-spacing: 0 10px; 
        font-size: 0.85rem; 
    }
    .table-custom thead th { 
        background: transparent; 
        border: none; 
        color: #abb3ba; 
        font-weight: 700; 
        text-transform: uppercase; 
        font-size: 0.7rem; 
        padding: 10px 20px; 
        letter-spacing: 0.5px;
    }
    .table-custom tbody tr { 
        background-color: #ffffff; 
        box-shadow: 0 2px 8px rgba(0,0,0,0.02); 
        transition: all 0.2s ease; 
    }
    .table-custom tbody tr:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
        background-color: #fcfdff;
    }
    .table-custom tbody td { 
        padding: 15px 20px; 
        border: none; 
        vertical-align: middle; 
    }
    .table-custom tbody td:first-child { border-radius: 10px 0 0 10px; }
    .table-custom tbody td:last-child { border-radius: 0 10px 10px 0; }

    .instructor-profile { display: flex; align-items: center; gap: 10px; }

    .avatar-icon { 
        width: 34px; height: 34px; 
        background: #eef2ff; color: #4e73df; 
        display: flex; align-items: center; justify-content: center; 
        border-radius: 8px; font-size: 1rem; 
    }

    .date-pill { 
        background: #f1f3f9; color: #5a5c69; 
        padding: 5px 12px; border-radius: 100px; 
        font-size: 0.75rem; font-weight: 600; 
        display: inline-flex; align-items: center; gap: 5px; 
    }

    .location-text { 
        color: #2e59d9; font-weight: 600; 
        display: flex; align-items: center; gap: 5px; font-size: 0.8rem; 
    }
    
    .search-wrapper { position: relative; width: 280px; }

    .search-wrapper input { 
        border-radius: 10px; padding-left: 35px; 
        border: 1.5px solid #e3e6f0; height: 40px; 
    }

    .search-wrapper i { 
        position: absolute; left: 12px; top: 50%; 
        transform: translateY(-50%); color: #b7b9cc; 
    }

    .pagination { gap: 6px; margin-bottom: 0; }

    .pagination .page-item .page-link {
        border: none; 
        border-radius: 8px !important;
        color: #4e73df; 
        font-weight: 600;
        padding: 8px 14px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        color: white !important;
    }
</style>

<div class="main-container">

    <div class="page-header">
        <div>
            <h4 class="fw-bold text-dark mb-0">Jadwal Senam</h4>
            <p class="text-muted small">Daftar lengkap agenda kegiatan senam mingguan</p>
        </div>
        
        <div class="search-wrapper">
            <form action="{{ request()->url() }}" method="GET">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control shadow-sm" 
                       placeholder="Cari lokasi atau instruktur..." value="{{ request('search') }}">
            </form>
        </div>
    </div>

    <div class="card-modern">

        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Instruktur</th>
                        <th>Waktu Pelaksanaan</th>
                        <th>Lokasi / Tempat</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jadwal as $index => $item)
                    <tr>
                        <td class="text-center">
                            <span class="text-muted fw-bold">
                                {{ $jadwal->firstItem() + $index }}
                            </span>
                        </td>

                        <td>
                            <div class="instructor-profile">
                                <div class="avatar-icon">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="fw-bold text-dark">
                                    {{ $item->user->nama_user ?? '-' }}
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="date-pill">
                                <i class="bi bi-calendar3"></i>

                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}

                            </div>
                        </td>

                        <td>
                            <div class="location-text">
                                <i class="bi bi-geo-alt-fill"></i>
                                {{ $item->tempat_senam }}
                            </div>
                        </td>

                        <td>
                            <span class="text-muted" style="font-size: 0.8rem;">
                                {{ $item->keterangan_senam ?? 'Tidak ada catatan tambahan' }}
                            </span>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-calendar-x display-4 text-light"></i>
                            <p class="text-muted mt-2">
                                Belum ada jadwal senam yang dirilis.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">

            <div class="text-muted fw-bold" style="font-size: 0.75rem;">
                Menampilkan {{ $jadwal->firstItem() ?? 0 }} - {{ $jadwal->lastItem() ?? 0 }} dari {{ $jadwal->total() }} jadwal
            </div>

            <nav class="mt-3 mt-md-0">
                {{ $jadwal->appends(request()->query())->links('pagination::bootstrap-5') }}
            </nav>

        </div>

    </div>
</div>

@endsection