
@extends('layouts.main')
@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --soft-bg: #f8f9fc;
        --text-dark: #2e59d9;
        --danger-soft: #ffebee;
        --danger-text: #c62828;
    }

    body {
        font-size: .875rem;
    }

    .main-container {
        padding: clamp(15px, 3vw, 30px);
        background-color: var(--soft-bg);
        min-height: 100vh;
    }

    .page-title {
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    .card-modern {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        padding: clamp(15px, 2vw, 25px);
        margin-bottom: 25px;
    }
    .card-multi-tagihan {
        padding: 15px 20px !important;
    }
    
    .card-multi-tagihan .table-custom {
        font-size: 0.8rem !important; /* Font tabel lebih kecil */
    }

    .card-multi-tagihan .table-custom thead th {
        font-size: 0.68rem !important;
        padding: 6px 12px !important; /* Jarak header lebih tipis */
    }

    .card-multi-tagihan .table-custom tbody td {
        padding: 8px 12px !important; /* Menghemat tempat vertikal */
    }

    .card-multi-tagihan .table-custom tbody tr:hover {
        transform: none !important; /* Matikan efek melayang agar stabil saat dicheck */
    }

    /* Kontainer Scroll Khusus Tabel Multi Tagihan */
    .scrollable-tagihan-container {
        max-height: 180px; /* Membatasi tinggi agar tidak kepanjangan */
        overflow-y: auto;
        overflow-x: hidden;
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        padding: 5px 10px;
        margin-bottom: 15px;
        background: #ffffff;
    }

    /* Custom Scrollbar */
    .scrollable-tagihan-container::-webkit-scrollbar {
        width: 6px;
    }
    .scrollable-tagihan-container::-webkit-scrollbar-track {
        background: #f1f3f9;
        border-radius: 10px;
    }
    .scrollable-tagihan-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .scrollable-tagihan-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Table Styling Modern */
    .table-custom {
        border-collapse: separate;
        border-spacing: 0 8px;
        font-size: .85rem;
    }

    .table-custom thead th {
        background: transparent;
        border: none;
        color: #abb3ba;
        font-weight: 700;
        text-transform: uppercase;
        font-size: .72rem;
        padding: 10px 20px;
        letter-spacing: .5px;
    }

    .table-custom tbody tr {
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,.02);
        transition: .2s;
    }

    .table-custom tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,.05);
        background: #fcfdff;
    }

    .table-custom tbody td {
        padding: 15px 20px;
        border: none;
        vertical-align: middle;
        color: #5a5c69;
    }

    .table-custom tbody td:first-child {
        border-radius: 10px 0 0 10px;
    }

    .table-custom tbody td:last-child {
        border-radius: 0 10px 10px 0;
    }

    /* Form Elements & Inputs */
    .search-wrapper-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .search-box { 
        position: relative;
        width: 100%;
        max-width: 350px;
    }
    
    .search-input {
        border-radius: 12px;
        border: 1.5px solid #e3e6f0;
        padding-left: 42px;
        height: 42px;
        background-color: #fff;
        font-size: 0.85rem;
    }

    .search-box i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #b7b9cc;
    }

    .form-control-modern {
        border-radius: 12px;
        border: 1.5px solid #e3e6f0;
        padding: 10px 16px;
        font-size: 0.875rem;
    }
    .form-control-modern:focus {
        border-color: #4e73df;
        box-shadow: none;
    }

    /* Action Footer Bar */
    .action-footer-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
        padding: 10px 20px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 15px;
    }

    .total-badge-info {
        font-size: 0.85rem;
        color: #475569;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Buttons Style */
    .btn-back {
        border-radius: 12px;
        font-weight: 600;
        padding: 10px 20px;
        transition: 0.3s;
    }

    .btn-modern {
        border-radius: 12px;
        padding: 10px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-modern-primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
    }
    .btn-modern-primary:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(78, 115, 223, 0.3);
    }

    .btn-modern-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(28, 200, 138, 0.2);
    }
    .btn-modern-success:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(28, 200, 138, 0.3);
    }

    .btn-modern-reset {
        background: var(--danger-soft);
        color: var(--danger-text);
        border: none;
        height: 42px;
    }
    .btn-modern-reset:hover {
        background: #fcd4d4;
        color: var(--danger-text);
    }

    .btn-detail {
        background: var(--primary-gradient);
        border: none;
        color: white;
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-detail:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.25);
        color: white;
    }

    .attendance-badge {
        background: #eafaf1;
        color: #27ae60;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .form-check-input-custom {
        width: 1.15rem;
        height: 1.15rem;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .search-box {
            max-width: 100%;
        }
        .action-footer-bar {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .action-footer-bar .btn-modern {
            justify-content: center;
        }
        .total-badge-info {
            justify-content: center;
        }
    }

    /* Pagination Modern */
    .pagination {
        gap: 6px;
        margin-bottom: 0;
    }
    .pagination .page-item {
        display: none;
    }
    .pagination .page-item.previous,
    .pagination .page-item.next,
    .pagination .page-item.active,
    .pagination .page-item:first-child,
    .pagination .page-item:last-child {
        display: block;
    }
    .pagination .page-item.active + .page-item,
    .pagination .page-item:has(+ .active) {
        display: block;
    }
    .pagination .page-item .page-link {
        border: none;
        border-radius: 10px !important;
        margin: 0;
        color: #4e73df;
        font-weight: 600;
        padding: 8px 14px;
        font-size: 0.82rem;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        background: white;
    }
    .pagination .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.25);
    }
    .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #eef2ff;
        transform: translateY(-1px);
    }
    .pagination .page-item.disabled .page-link {
        background: #f8f9fc;
        color: #c0c4d6;
        box-shadow: none;
    }
</style>

<div class="main-container">
    
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="page-title mb-1">Pembayaran Iuran Tunai</h4>
            <p class="text-muted small mb-0">Kelola iuran anggota berdasarkan sesi jadwal senam.</p>
        </div>
        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary btn-back border-0 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card-modern card-multi-tagihan shadow-sm">
        <div class="mb-3">
            <h6 class="mb-1 fw-bold text-primary" style="font-size: 1rem;">Multi Tagihan Anggota</h6>
            <p class="text-muted small mb-0">Cari anggota untuk melihat seluruh daftar tagihan iuran yang belum terselesaikan.</p>
        </div>

        <form method="GET" action="{{ route('admin.tunai.index') }}" class="mb-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-7">
                    <label class="form-label fw-semibold text-secondary small mb-1">Nama Anggota</label>
                    <input type="text" name="anggota" class="form-control form-control-modern py-2 text-sm" 
                           placeholder="Masukkan nama anggota..." value="{{ $namaAnggota ?? '' }}">
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-modern btn-modern-primary flex-grow-1 justify-content-center py-2">
                        <i class="bi bi-search"></i> Cari Tagihan
                    </button>
                    @if(!empty($namaAnggota))
                        <a href="{{ route('admin.tunai.index') }}" class="btn btn-modern btn-modern-reset px-3" title="Reset Pencarian Anggota">
                            <i class="bi bi-arrow-counterclockwise fs-5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        @if(!empty($namaAnggota))
            <hr class="my-3" style="border-color: #e3e6f0;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="fw-bold text-dark small">Hasil Pencarian: <span class="text-primary">{{ $namaAnggota }}</span></span>
            </div>

            @if($tagihanMulti->count())
            <form method="POST" action="{{ route('admin.tunai.multi-bayar') }}">
                @csrf
                
                <div class="scrollable-tagihan-container">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th width="50" class="text-center">
                                    <input type="checkbox" id="checkAll" class="form-check-input form-check-input-custom">
                                </th>
                                <th>Nama Anggota</th>
                                <th>Tanggal Agenda</th>
                                <th>Lokasi / Tempat</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tagihanMulti as $item)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input form-check-input-custom tagihan-check" 
                                           name="tagihan[]" value="{{ $item->id_user }}|{{ $item->id_senam }}">
                                </td>
                                <td><span class="fw-bold text-dark">{{ $item->user->nama_user }}</span></td>
                                <td>
                                    <div class="badge bg-light text-secondary border px-2 py-1 fw-semibold" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($item->senam->tanggal)->format('d-m-Y') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="small text-muted"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $item->senam->tempat_senam }}</span>
                                </td>
                                <td class="text-end fw-bold text-primary">Rp 2.500</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="action-footer-bar">
                    <p class="total-badge-info">
                        <i class="bi bi-info-circle-fill text-primary fs-6"></i>
                        <span>Total Tagihan: <strong class="text-dark fs-6 ms-1">Rp {{ number_format($tagihanMulti->count() * 2500,0,',','.') }}</strong></span>
                    </p>
                    <button type="submit" class="btn btn-modern btn-modern-success py-1.5 px-3">
                        <i class="bi bi-check-circle-fill"></i> Lunasi Terpilih
                    </button>
                </div>
            </form>
            @else
                <div class="alert alert-warning border-0 rounded-3 d-flex align-items-center gap-2 mt-2 mb-0 py-2 px-3 small">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>Tidak ditemukan data tagihan iuran yang belum dibayar untuk nama tersebut.</span>
                </div>
            @endif
        @endif
    </div>

    <div class="card-modern shadow-sm">
        <div class="search-wrapper-row">
            <div>
                <h6 class="fw-bold text-dark mb-0">Daftar Pertemuan Jadwal Senam</h6>
            </div>
            <div class="d-flex gap-2 w-100 max-sm-100 justify-content-md-end" style="max-width: 410px;">
                <div class="search-box flex-grow-1">
                    <form action="" method="GET" class="m-0">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="form-control search-input shadow-none" 
                               placeholder="Cari lokasi atau tanggal..." value="{{ request('search') }}">
                    </form>
                </div>
                @if(request('search'))
                    <a href="{{ request()->url() }}" class="btn btn-modern btn-modern-reset px-3" title="Reset Pencarian Jadwal">
                        <i class="bi bi-arrow-counterclockwise fs-5"></i>
                    </a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th>Tanggal Senam</th>
                        <th>Lokasi / Tempat</th>
                        <th class="text-center">Kehadiran</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($senam as $index => $item)
                    <tr>
                        <td class="text-center fw-bold text-muted">
                            {{ ($senam->currentPage() - 1) * $senam->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 d-none d-sm-block">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                </div>
                                <span class="fw-bold text-dark">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                <span>{{ $item->tempat_senam }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="attendance-badge">
                                <i class="bi bi-people-fill me-1"></i> {{ $item->jumlah_hadir }} Hadir
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.tunai.detail', $item->id_senam) }}" class="btn btn-detail">
                                Detail 
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-clipboard-x display-1 text-light"></i>
                            <p class="text-muted mt-3 mb-0">Data tidak ditemukan atau belum ada jadwal senam.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 gap-3">
            <div class="small text-muted fw-bold">
                Menampilkan <span class="text-primary">{{ $senam->firstItem() ?? 0 }}</span> 
                - <span class="text-primary">{{ $senam->lastItem() ?? 0 }}</span> 
                dari <span class="text-primary">{{ $senam->total() }}</span> data
            </div>
            <div>
                {{ $senam->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let checkAll = document.getElementById('checkAll');
    if(checkAll){
        checkAll.addEventListener('change', function(){
            document.querySelectorAll('.tagihan-check').forEach(item => {
                item.checked = this.checked;
            });
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Pelunasan Berhasil',
            text: "{{ session('success') }}",
            confirmButtonColor: '#4e73df'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Transaksi Gagal',
            text: "{{ session('error') }}",
            confirmButtonColor: '#e74a3b'
        });
    @endif
});
</script>
@endsection