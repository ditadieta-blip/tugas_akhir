@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --soft-bg: #f8f9fc;
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

    .page-title {
        font-weight: 800;
        color: #2e59d9;
        letter-spacing: -0.5px;
        margin-bottom: 0;
    }

    .card-modern {
        background: #ffffff;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 25px;
    }

    /* Table Styling */
    .table-custom thead th {
        background: #f8f9fc;
        border: none;
        color: #4e73df;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 15px 20px;
        letter-spacing: 1px;
    }

    .table-custom tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        color: #5a5c69;
        border-bottom: 1px solid #f1f3f9;
    }

    .role-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #eef2ff;
        color: #4e73df;
    }

    .search-input {
        border-radius: 10px;
        border: 1.5px solid #e3e6f0;
        padding-left: 40px;
        height: 42px;
    }

    .search-box { position: relative; }
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY( -50% );
        color: #b7b9cc;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: 0.3s;
        border: none;
    }

    .btn-edit-user { background: #fff4e5; color: #ff9800; }
    .btn-delete-user { background: #ffebee; color: #f44336; }
    .btn-edit-user:hover { background: #ff9800; color: white; transform: translateY(-2px); }
    .btn-delete-user:hover { background: #f44336; color: white; transform: translateY(-2px); }

    /* --- PERBAIKAN JARAK PAGINASI --- */
    .pagination {
        gap: 8px; /* Memberi jarak antar kotak angka */
        margin-bottom: 0;
    }

    .pagination .page-item .page-link {
        border: none;
        border-radius: 8px !important;
        color: #4e73df;
        font-weight: 600;
        padding: 8px 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }
    /* ------------------------------- */
</style>

<div class="main-container">
    <div class="page-header">
        <div>
            <h4 class="page-title">Kelola Pengguna</h4>
            <p class="text-muted small mb-0">Manajemen data user dan hak akses sistem</p>
        </div>
        
        <div class="d-flex gap-3 align-items-center">
            <form action="{{ route('admin.user.index') }}" method="GET" class="d-flex gap-2">
                
                <div style="width: 160px;">
                    <select name="role" class="form-select shadow-sm" style="border-radius: 10px; height: 42px; border: 1.5px solid #e3e6f0; font-size: 0.9rem;" onchange="this.form.submit()">
                        <option value="">-- Semua Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id_role }}" {{ request('role') == $role->id_role ? 'selected' : '' }}>
                                {{ ucfirst($role->nama_role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="search-box d-none d-md-block" style="width: 250px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control search-input shadow-sm" 
                           placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
                
            </form>

            <a href="{{ route('admin.user.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm" 
               style="border-radius: 10px; background: var(--primary-gradient); border: none; padding: 0 20px; height: 42px;">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah User
            </a>
        </div>
    </div>

    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th>Informasi User</th>
                        <th>Kontak</th>
                        <th>Role</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                    <tr>
                        <td class="text-center">
                            <span class="fw-bold text-muted">{{ $users->firstItem() + $index }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ $user->nama_user }}</span>
                                <span class="text-muted small">{{ $user->email }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-telephone text-primary small"></i>
                                <span>{{ $user->no_hp }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge">
                                {{ $user->role->nama_role ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.user.edit', $user->id_user) }}" 
                                   class="btn-action btn-edit-user" title="Edit Pengguna">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.user.destroy', $user->id_user) }}" 
                                      method="POST" id="delete-form-{{ $user->id_user }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete-user" 
                                            onclick="confirmDelete('{{ $user->id_user }}')" title="Hapus Pengguna">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-people text-light display-1"></i>
            <p class="text-muted mt-3">Tidak ada data pengguna ditemukan.</p>
        </div>
        @endif

        {{-- Footer Paginasi dengan Jarak yang Lebih Lega --}}
        <div class="mt-5 d-flex justify-content-between align-items-center border-top pt-4">
            <div class="small text-muted fw-bold">
                Menampilkan <span class="text-primary">{{ $users->firstItem() ?? 0 }}</span> 
                sampai <span class="text-primary">{{ $users->lastItem() ?? 0 }}</span> 
                dari <span class="text-primary">{{ $users->total() }}</span> user
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">

                    {{-- PREVIOUS --}}
                    @if ($users->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- NOMOR HALAMAN --}}
                    @php
                        $start = max($users->currentPage() - 1, 1);
                        $end = min($start + 2, $users->lastPage());

                        if (($end - $start) < 2) {
                            $start = max($end - 2, 1);
                        }
                    @endphp

                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $users->url($i) }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor

                    {{-- NEXT --}}
                    @if ($users->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}">
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
            title: 'Hapus Pengguna?',
            text: "Pengguna ini akan dihapus secara permanen dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4e73df', 
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus Sekarang',
            cancelButtonText: 'Batalkan',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif
</script>
@endsection