@extends('layouts.main')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --dark-blue: #2e59d9;
    }

    .main-container {
        padding: 2rem 15px;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    /* Button Back Style Modern di Kiri Atas */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #858796;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        padding: 8px 16px;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        width: fit-content;
        margin-bottom: 20px;
    }

    .btn-back:hover {
        color: var(--dark-blue);
        transform: translateX(-5px);
        background: #f1f3f9;
        text-decoration: none;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e3e6f0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .info-header {
        background: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 5px solid #4e73df;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }

    .table thead th {
        background-color: #f8f9fc;
        color: #4e73df;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        border-top: none;
        padding: 15px;
    }

    .table tbody td {
        padding: 14px 15px;
        vertical-align: middle;
        color: #5a5c69;
    }

    .search-group {
        position: relative;
        max-width: 350px;
    }

    .search-group i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #b7b9cc;
    }

    .search-input {
        padding-left: 35px;
        border-radius: 8px;
    }

    .btn-save {
        background-color: #1cc88a;
        border: none;
        font-weight: 600;
        padding: 10px 25px;
        border-radius: 8px;
        color: white;
        transition: 0.3s;
    }

    .btn-save:hover {
        background-color: #17a673;
        transform: translateY(-1px);
    }

    .saldo-box {
        font-size: 0.85rem;
        font-weight: 600;
        background: #eef4ff;
        color: #224abe;
        padding: 6px 10px;
        border-radius: 8px;
        display: inline-block;
        margin-top: 6px;
    }

    .input-bayar {
        min-width: 160px;
        border-radius: 8px;
    }

    .badge-custom {
        font-size: 0.75rem;
        padding: 7px 14px;
        border-radius: 50px;
    }

    .hint-text {
        font-size: 0.72rem;
        color: #858796;
        margin-top: 5px;
        display: block;
    }
</style>
<div class="main-container">
    <div class="container">
        <div class="text-start">
            <a href="{{ route('admin.tunai.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="info-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    🧾 Detail Pembayaran Iuran Tunai
                </h4>
                <p class="text-muted mb-0">
                    Tempat:
                    <strong>{{ $senam->tempat_senam }}</strong>
                    |
                    Tanggal:
                    <strong>
                        {{ \Carbon\Carbon::parse($senam->tanggal)->locale('id')->translatedFormat('d F Y') }}
                    </strong>
                </p>
            </div>

            <div class="text-md-end mt-2 mt-md-0">
                <span class="badge bg-primary px-3 py-2 shadow-sm">
                    Total: {{ count($anggota) }} Anggota
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm border-0">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('admin.tunai.store') }}" method="POST">
            @csrf
            <input type="hidden"
                   name="id_senam"
                   value="{{ $senam->id_senam }}">
            <div class="table-card shadow-sm">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="search-group w-100 w-md-auto">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               id="searchInput"
                               class="form-control search-input shadow-none"
                               placeholder="Cari nama atau email...">
                    </div>
                    <div>
                        <small class="text-muted fw-bold">
                            Iuran per hadir:
                            Rp 2.500
                        </small>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0"
                           id="memberTable">
                        <thead>
                            <tr>
                                <th>Informasi Anggota</th>
                                <th width="180" class="text-center">
                                    Status
                                </th>
                                <th width="260">
                                    Pembayaran Tunai
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggota as $item)
                            @php
                                $sudah = in_array($item->id_user, $sudahBayar);
                            @endphp
                            <tr class="member-row">
                                <td>
                                    <div class="fw-bold text-dark member-name">
                                        {{ $item->user->nama_user }}
                                    </div>
                                    <div class="text-muted small member-email">
                                        <i class="bi bi-envelope me-1"></i>
                                        {{ $item->user->email ?? '-' }}
                                    </div>
                                    <div class="saldo-box">
                                        Saldo:
                                        Rp {{ number_format($item->user->saldo_iuran ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($sudah)
                                        <span class="badge bg-success badge-custom">
                                            Sudah Bayar
                                        </span>
                                    @else
                                        <span class="badge bg-danger badge-custom">
                                            Belum Bayar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$sudah)
                                        <input type="number"
                                               name="nominal[{{ $item->id_user }}]"
                                               class="form-control input-bayar shadow-none"
                                               min="2500"
                                               step="500"
                                               placeholder="Contoh: 10000">
                                        <small class="hint-text">
                                            Minimal Rp2.500.
                                            Jika lebih, otomatis menjadi saldo.
                                        </small>
                                    @else
                                        <span class="text-success fw-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3"
                                    class="text-center py-5 text-muted">
                                    <i class="bi bi-people mb-2 d-block fs-2"></i>
                                    Tidak ada anggota yang tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3 bg-light d-flex justify-content-between align-items-center flex-wrap gap-3 rounded-bottom">
                    <div class="pagination-container">
                        @if(method_exists($anggota, 'links'))
                            {{ $anggota->links('pagination::bootstrap-5') }}
                        @else
                            <small class="text-muted fw-bold"
                                   style="font-size: 0.7rem;">
                                MENAMPILKAN SEMUA DATA
                            </small>
                        @endif
                    </div>
                    <button type="submit"
                            class="btn btn-save shadow-sm px-4">
                        <i class="bi bi-check2-circle me-2"></i>
                        SIMPAN PEMBAYARAN
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    // SEARCH
    document.getElementById('searchInput')
        .addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.member-row');
        rows.forEach(row => {
            let name = row.querySelector('.member-name')
                .textContent.toLowerCase();
            let email = row.querySelector('.member-email')
                .textContent.toLowerCase();
            if (
                name.includes(filter) ||
                email.includes(filter)
            ) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endsection