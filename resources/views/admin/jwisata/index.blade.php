@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .main-container {
        padding: 20px;
        background-color: #f8f9fc;
        min-height: 100vh;
    }

    .page-title {
        font-weight: 800;
        color: #2e59d9;
        letter-spacing: -0.5px;
    }

    .card-modern {
        background: #ffffff;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    /* Table Styling */
    .table-custom thead th {
        background: #f8f9fc;
        border: none;
        color: #4e73df;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 15px;
    }

    .table-custom tbody td {
        padding: 15px;
        vertical-align: middle;
        color: #5a5c69;
        border-bottom: 1px solid #f1f3f9;
    }

    /* Badge & Status */
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: 0.3s;
        border: none;
        text-decoration: none;
    }

    .btn-edit { background: #fff4e5; color: #ff9800; }
    .btn-delete { background: #ffebee; color: #f44336; }
    .btn-edit:hover { background: #ff9800; color: white; transform: translateY(-2px); }
    .btn-delete:hover { background: #f44336; color: white; transform: translateY(-2px); }

    .search-input {
        border-radius: 10px;
        border: 1.5px solid #e3e6f0;
        padding-left: 40px;
    }

    .search-box { position: relative; }
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #b7b9cc;
    }

    /* CUSTOM PAGINATION */
    .pagination {
        margin-bottom: 0;
        gap: 5px;
    }

    .page-item .page-link {
        border: none;
        border-radius: 8px !important;
        color: #4e73df;
        font-weight: 600;
        padding: 8px 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    .page-item.disabled .page-link {
        background-color: #f8f9fc;
        color: #d1d3e2;
    }

    .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #eaecf4;
        color: #224abe;
        transform: translateY(-2px);
    }
</style>

<div class="main-container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="page-title mb-0">Jadwal Wisata</h4>
            <p class="text-muted small">Kelola jadwal keberangkatan dan status pendaftaran wisata.</p>
        </div>
        
        <div class="d-flex gap-2">
            <div class="search-box">
                <form action="{{ route('admin.jwisata.index') }}" method="GET">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control search-input shadow-sm" 
                           placeholder="Cari wisata..." value="{{ request('search') }}">
                </form>
            </div>
            <a href="{{ route('admin.jwisata.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm" 
               style="border-radius: 10px; background: var(--primary-gradient); border: none; padding: 0 20px;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Wisata
            </a>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Wisata & Lokasi</th>
                        <th>Tanggal</th>
                        <th>Biaya</th>
                        <th class="text-center">Pendaftaran</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jwisata as $index => $item)
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $jwisata->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->nama_wisata }}</div>
                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $item->lokasi_wisata }}</div>
                        </td>
                        <td>
                            <div class="small fw-bold text-primary">
                                <i class="bi bi-calendar3 me-2"></i>{{ \Carbon\Carbon::parse($item->tanggal_wisata)->format('d M Y') }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-success border px-3 py-2" style="font-size: 0.85rem; border-radius: 8px;">
                                Rp {{ number_format($item->biaya_wisata, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($item->is_open)
                                <span class="status-badge bg-success text-white"><i class="bi bi-check-circle me-1"></i> Dibuka</span>
                            @else
                                <span class="status-badge bg-secondary text-white"><i class="bi bi-x-circle me-1"></i> Ditutup</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                {{-- TOGGLE STATUS --}}
                                <form action="{{ route('admin.jwisata.toggle', $item->id_wisata) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $item->is_open ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                            style="border-radius: 8px; font-size: 0.7rem; font-weight: 700; min-width: 60px;">
                                        {{ $item->is_open ? 'TUTUP' : 'BUKA' }}
                                    </button>
                                </form>

                                {{-- EDIT --}}
                                <a href="{{ route('admin.jwisata.edit', $item->id_wisata) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.jwisata.destroy', $item->id_wisata) }}" method="POST" id="delete-form-{{ $item->id_wisata }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" onclick="confirmDelete('{{ $item->id_wisata }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-calendar-x text-light display-1"></i>
                            <p class="text-muted mt-3">Belum ada jadwal wisata tersedia.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION PERBAIKAN --}}
        <div class="mt-4 d-flex justify-content-between align-items-center border-top pt-3">
            <div class="small text-muted fw-bold">
                Menampilkan <span class="text-primary">{{ $jwisata->firstItem() ?? 0 }}</span> 
                sampai <span class="text-primary">{{ $jwisata->lastItem() ?? 0 }}</span> 
                dari <span class="text-primary">{{ $jwisata->total() }}</span> data
            </div>
            <nav>
                {{ $jwisata->onEachSide(1)->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Jadwal?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f44336',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
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
            customClass: { popup: 'rounded-15' }
        });
    @endif
</script>
@endsection