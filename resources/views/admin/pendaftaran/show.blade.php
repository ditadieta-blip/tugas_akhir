@extends('layouts.main')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --primary-color: #4e73df;
    }

    .main-container {
        padding: 15px;
        background-color: #f8f9fc;
        min-height: 100vh;
    }

    .page-title {
        font-weight: 700;
        color: #2e59d9;
        font-size: 1.25rem;
        letter-spacing: -0.3px;
    }

    /* Button Kembali Style */
    .btn-back {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #fff;
        color: #4e73df;
        border: 1px solid #e3e6f0;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #f1f3f9;
        color: #2e59d9;
        transform: translateX(-3px);
    }

    .card-modern {
        background: #ffffff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        padding: 15px;
    }

    /* Search Box Kapsul */
    .search-box {
        position: relative;
        max-width: 280px;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 0.85rem;
    }

    .search-input {
        border-radius: 20px;
        padding: 8px 15px 8px 40px;
        border: 1px solid #e2e8f0;
        height: 38px;
        font-size: 0.8rem;
    }

    /* Tabel Styling */
    .table-custom thead th {
        background: #f8f9fc;
        border: none;
        color: #4e73df;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        padding: 15px 12px;
    }

    .table-custom tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        color: #5a5c69;
        border-bottom: 1px solid #f1f3f9;
        font-size: 0.85rem;
    }

    /* Status Badges */
    .badge-status {
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-block;
    }

    /* Button Actions */
    .btn-delete { background: #f8f9fc; color: #6e707e; border: none; }
    .btn-delete:hover { background: #5a5c69; color: #fff; }

    /* Pagination Styling */
    .pagination { gap: 6px; margin-bottom: 0; }
    .pagination .page-link {
        border: none;
        border-radius: 8px !important;
        padding: 8px 14px;
        font-size: 0.8rem;
        background-color: #f8f9fc;
        color: #5a5c69;
        font-weight: 600;
    }
    .pagination .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        color: white !important;
        box-shadow: 0 3px 10px rgba(78, 115, 223, 0.3);
    }
</style>

<div class="main-container">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.pendaftaran.index') }}" class="btn-back shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="page-title mb-0">Detail Pendaftar Wisata</h4>
                <p class="text-muted small mb-0">Daftar peserta wisata yang telah mendaftar.</p>
            </div>
        </div>

        <div class="search-box">
            <form action="{{ url()->current() }}" method="GET">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control search-input shadow-sm" 
                       placeholder="Cari nama pendaftar..." value="{{ request('search') }}">
            </form>
        </div>
    </div>

    {{-- Content Card --}}
    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th>User & Kontak</th>
                        <th>Destinasi</th>
                        <th class="text-center">Tgl Daftar</th>
                        <th class="text-center">Status</th>
                        <th width="180" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftaran as $index => $item)
                    <tr>
                        <td class="text-center fw-bold text-muted">
                            {{ $pendaftaran->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->user->nama_user ?? '-' }}</div>
                            <div class="text-muted small">{{ $item->user->email ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $item->jwisata->nama_wisata ?? '-' }}</div>
                            <div class="small text-muted">
                                <i class="bi bi-calendar3 me-1"></i>{{ $item->jwisata->tanggal_wisata ?? '-' }}
                            </div>
                        </td>
                        <td class="text-center small">
                            {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($item->status_daftar == 'menunggu_pembayaran')
                                <span class="badge-status bg-warning text-dark">
                                    Menunggu Pembayaran
                                </span>

                            @elseif($item->status_daftar == 'menunggu_perjalanan')
                                <span class="badge-status bg-info text-white">
                                    Menunggu Perjalanan
                                </span>

                            @elseif($item->status_daftar == 'selesai')
                                <span class="badge-status bg-success text-white">
                                    Selesai
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.pendaftaran.destroy', $item->id_daftar_wisata) }}"
                                method="POST"
                                id="form-hapus-{{ $item->id_daftar_wisata }}">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-sm btn-delete p-2 px-3 fw-bold shadow-sm"
                                        onclick="confirmDelete('{{ $item->id_daftar_wisata }}')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox text-light display-4"></i>
                            <p class="text-muted mt-2 small">Data pendaftaran tidak ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Paginasi --}}
        <div class="mt-5 d-flex justify-content-between align-items-center border-top pt-4">
            <div class="small text-muted fw-bold">
                Menampilkan <span class="text-primary">{{ $pendaftaran->firstItem() ?? 0 }}</span> 
                sampai <span class="text-primary">{{ $pendaftaran->lastItem() ?? 0 }}</span> 
                dari <span class="text-primary">{{ $pendaftaran->total() }}</span> pendaftar
            </div>
            <nav>
                {{ $pendaftaran->onEachSide(1)->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>

{{-- SweetAlert Scripts Tetap Sama --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data pendaftaran akan dihapus permanen!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-' + id).submit();
            }
        })
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