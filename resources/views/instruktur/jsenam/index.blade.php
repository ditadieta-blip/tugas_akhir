@extends('layouts.instruktur')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --soft-bg: #f8f9fc;
        --accent-blue: #4e73df;
    }

    /* Ukuran Font Global Dikecilkan Sedikit */
    body {
        font-size: 0.875rem; /* Standar 14px */
    }

    .main-container { padding: 20px; background-color: var(--soft-bg); min-height: 100vh; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    
    .card-modern { 
        background: #ffffff; 
        border: none; 
        border-radius: 16px; 
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); 
        padding: 25px; 
    }

    /* Table Styling dengan Font Lebih Kecil */
    .table-custom { border-collapse: separate; border-spacing: 0 10px; font-size: 0.85rem; }
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
    .table-custom tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .table-custom tbody td { padding: 15px 20px; border: none; vertical-align: middle; }
    .table-custom tbody td:first-child { border-radius: 10px 0 0 10px; }
    .table-custom tbody td:last-child { border-radius: 0 10px 10px 0; }

    /* Components */
    .instructor-profile { display: flex; align-items: center; gap: 10px; }
    .avatar-icon { width: 34px; height: 34px; background: #eef2ff; color: #4e73df; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 1rem; }
    .date-pill { background: #f1f3f9; color: #5a5c69; padding: 5px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; }
    .location-text { color: #2e59d9; font-weight: 600; display: flex; align-items: center; gap: 5px; font-size: 0.8rem; }
    
    /* Buttons */
    .btn-edit, .btn-delete { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: 0.2s; border: none; text-decoration: none; }
    .btn-edit { background: #fff4e5; color: #ff9800; }
    .btn-delete { background: #ffebee; color: #f44336; }
    .btn-edit:hover { background: #ff9800; color: white; }
    .btn-delete:hover { background: #f44336; color: white; }

    .btn-absensi {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e3f2fd;
        color: #2196f3;
        border: none;
    }
    .btn-absensi:hover {
        background: #2196f3;
        color: white;
    }

    .badge {
        padding: 6px 12px;
        font-size: 0.7rem;
        border-radius: 20px;
        font-weight: 600;
    }
    .bg-success {
        background: linear-gradient(135deg, #1cc88a, #17a673) !important;
    }
    .bg-secondary {
        background: #858796 !important;
    }

    /* Search Box */
    .search-wrapper { position: relative; width: 260px; }
    .search-wrapper input { border-radius: 10px; padding-left: 35px; border: 1.5px solid #e3e6f0; height: 38px; font-size: 0.8rem; }
    .search-wrapper i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #b7b9cc; font-size: 0.9rem; }

    /* CUSTOM PAGINATION - MODERN STYLE */
    .pagination { margin-bottom: 0; gap: 4px; }
    .pagination .page-item .page-link {
        border: none;
        border-radius: 8px !important;
        color: #4e73df;
        font-weight: 600;
        padding: 6px 12px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }
    .pagination .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.2);
    }
    .pagination .page-item.disabled .page-link { background: transparent; color: #d1d3e2; }
    .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #eaecf4;
        transform: translateY(-1px);
    }

    /* SweetAlert Custom */
    .rounded-swal { border-radius: 20px !important; }
    .btn-swal-confirm { background: var(--primary-gradient) !important; border-radius: 10px !important; padding: 8px 20px !important; font-size: 0.85rem !important; }
    .btn-swal-cancel { background: #f8f9fc !important; color: #858796 !important; border: 1px solid #e3e6f0 !important; border-radius: 10px !important; padding: 8px 20px !important; font-size: 0.85rem !important; }
</style>

<div class="main-container">
    <div class="page-header">
        <div>
            <h4 class="fw-bold text-dark mb-0">Jadwal Senam</h4>
            <p class="text-muted small">Kelola jadwal aktivitas instruktur</p>
        </div>
        <div class="d-flex gap-2">
            <div class="search-wrapper d-none d-md-block">
                <form action="{{ route('instruktur.jsenam.index') }}" method="GET">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control" placeholder="Cari jadwal..." value="{{ request('search') }}">
                </form>
            </div>
            <a href="{{ route('instruktur.jsenam.create') }}" class="btn btn-primary shadow-sm px-3 d-flex align-items-center" style="border-radius: 10px; background: var(--primary-gradient); border: none; font-size: 0.85rem;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Jadwal
            </a>
        </div>
    </div>

    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Instruktur</th>
                        <th>Tanggal</th>
                        <th>Lokasi/Tempat</th>
                        <th>Catatan</th>
                        <th>Kehadiran</th>
                        <th width="120" class="text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $index => $item)
                    <tr>
                        <td class="text-center">
                            <span class="text-muted fw-bold">{{ $jadwal->firstItem() + $index }}</span>
                        </td>
                        <td>
                            <div class="instructor-profile">
                                <div class="avatar-icon"><i class="bi bi-person"></i></div>
                                <div class="fw-bold text-dark">{{ $item->user->nama_user ?? '-' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="date-pill">

                                {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}
                            </div>
                        </td>
                        <td>
                            <div class="location-text">
                                <i class="bi bi-geo-alt-fill"></i>
                                {{ $item->tempat_senam }}
                            </div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size: 0.75rem;">
                                {{ Str::limit($item->keterangan_senam ?? 'Tidak ada keterangan', 35) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $item->total_hadir > 0 ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->total_hadir > 0 ? $item->total_hadir . ' Hadir' : 'Belum Ada' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('instruktur.jsenam.edit', $item->id_senam) }}" class="btn-edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('instruktur.jsenam.destroy', $item->id_senam) }}" method="POST" id="delete-form-{{ $item->id_senam }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-delete" onclick="confirmDelete('{{ $item->id_senam }}')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <!-- <a href="{{ route('instruktur.absensi.show', $item->id_senam) }}"
                                class="btn-absensi"
                                title="Kelola Absensi">
                                    <i class="bi bi-clipboard-check"></i>
                                </a> -->
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-folder-x display-4 text-light"></i>
                                <p class="mt-2 small">Data jadwal tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- FOOTER PAGINASI --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
            <div class="text-muted fw-bold" style="font-size: 0.75rem;">
                Menampilkan <span class="text-primary">{{ $jadwal->firstItem() ?? 0 }}</span> - <span class="text-primary">{{ $jadwal->lastItem() ?? 0 }}</span> dari <span class="text-primary">{{ $jadwal->total() }}</span> data
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">

                    {{-- PREVIOUS --}}
                    @if ($jadwal->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $jadwal->previousPageUrl() }}">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    @endif
                    {{-- NOMOR HALAMAN --}}
                    @php
                        $start = max($jadwal->currentPage() - 1, 1);
                        $end = min($start + 2, $jadwal->lastPage());
                        if (($end - $start) < 2) {
                            $start = max($end - 2, 1);
                        }
                    @endphp
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $jadwal->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $jadwal->url($i) }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor
                    {{-- NEXT --}}
                    @if ($jadwal->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $jadwal->nextPageUrl() }}">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        </li>
                    @endif

                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Jadwal?',
            text: "Data yang dihapus tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4e73df',
            cancelButtonColor: '#ffffff',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-swal',
                confirmButton: 'btn-swal-confirm',
                cancelButton: 'btn-swal-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            customClass: { popup: 'rounded-swal' }
        });
    @endif
</script>
@endsection