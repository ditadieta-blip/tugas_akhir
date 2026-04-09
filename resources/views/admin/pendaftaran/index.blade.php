@extends('layouts.main')

@section('content')
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

    /* Tabel Custom */
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

    /* Status Badges */
    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .search-box {
        position: relative;
        max-width: 300px;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #b7b9cc;
    }

    .search-input {
        border-radius: 10px;
        padding-left: 40px;
        border: 1.5px solid #e3e6f0;
    }

    /* Button Styles */
    .btn-approve { background: #e8f5e9; color: #2e7d32; border: none; transition: 0.3s; }
    .btn-reject { background: #fff5f5; color: #e53e3e; border: none; transition: 0.3s; }
    .btn-delete { background: #f1f3f9; color: #6e707e; border: none; transition: 0.3s; }
    
    .btn-approve:hover { background: #2e7d32; color: #fff; transform: translateY(-2px); }
    .btn-reject:hover { background: #e53e3e; color: #fff; transform: translateY(-2px); }
    .btn-delete:hover { background: #3a3b45; color: #fff; transform: translateY(-2px); }

    /* Custom Pagination Styling */
    .pagination { margin-bottom: 0; gap: 5px; }
    .page-item .page-link {
        border: none;
        border-radius: 8px !important;
        color: #4e73df;
        font-weight: 600;
        padding: 8px 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        color: white !important;
    }
</style>

<div class="main-container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="page-title mb-0">Data Pendaftaran Wisata</h4>
            <p class="text-muted small">Verifikasi dan kelola data pendaftar wisata.</p>
        </div>

        <div class="search-box">
            <form action="{{ route('admin.pendaftaran.index') }}" method="GET">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control search-input shadow-sm" 
                       placeholder="Cari pendaftar..." value="{{ request('search') }}">
            </form>
        </div>
    </div>

    {{-- Tabel Card --}}
    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>User & Kontak</th>
                        <th>Destinasi Wisata</th>
                        <th class="text-center">Tanggal Daftar</th>
                        <th class="text-center">Status</th>
                        <th width="200" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftaran as $index => $item)
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $pendaftaran->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->user->nama_user ?? '-' }}</div>
                            <div class="small text-muted">{{ $item->user->email ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $item->jwisata->nama_wisata ?? '-' }}</div>
                            <div class="small text-muted">
                                <i class="bi bi-calendar-event me-1"></i>{{ $item->jwisata->tanggal_wisata ?? '-' }}
                            </div>
                        </td>
                        <td class="text-center small">
                            {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($item->status_daftar == 'menunggu')
                                <span class="badge-status bg-warning text-dark">Menunggu</span>
                            @elseif($item->status_daftar == 'diterima')
                                <span class="badge-status bg-success text-white">Diterima</span>
                            @else
                                <span class="badge-status bg-danger text-white">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                @if($item->status_daftar == 'menunggu')
                                    {{-- Terima --}}
                                    <form action="{{ route('admin.pendaftaran.updateStatus', $item->id_daftar_wisata) }}" method="POST" id="form-terima-{{ $item->id_daftar_wisata }}">
                                        @csrf
                                        <input type="hidden" name="status_daftar" value="diterima">
                                        <button type="button" class="btn btn-sm btn-approve p-2 px-3 fw-bold shadow-sm" 
                                                onclick="confirmStatus('terima', '{{ $item->id_daftar_wisata }}')" title="Terima">
                                            <i class="bi bi-check2-circle"></i>
                                        </button>
                                    </form>

                                    {{-- Tolak --}}
                                    <form action="{{ route('admin.pendaftaran.updateStatus', $item->id_daftar_wisata) }}" method="POST" id="form-tolak-{{ $item->id_daftar_wisata }}">
                                        @csrf
                                        <input type="hidden" name="status_daftar" value="ditolak">
                                        <button type="button" class="btn btn-sm btn-reject p-2 px-3 fw-bold shadow-sm" 
                                                onclick="confirmStatus('tolak', '{{ $item->id_daftar_wisata }}')" title="Tolak">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Hapus --}}
                                <form action="{{ route('admin.pendaftaran.destroy', $item->id_daftar_wisata) }}" method="POST" id="form-hapus-{{ $item->id_daftar_wisata }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-delete p-2 px-3 fw-bold shadow-sm" 
                                            onclick="confirmDelete('{{ $item->id_daftar_wisata }}')" title="Hapus Data">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox text-light display-1"></i>
                            <p class="text-muted mt-3">Tidak ada data pendaftar yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Paginasi --}}
        <div class="mt-4 d-flex justify-content-between align-items-center border-top pt-3">
            <div class="small text-muted fw-bold">
                Menampilkan <span class="text-primary">{{ $pendaftaran->firstItem() ?? 0 }}</span> 
                sampai <span class="text-primary">{{ $pendaftaran->lastItem() ?? 0 }}</span> 
                dari <span class="text-primary">{{ $pendaftaran->total() }}</span> pendaftar
            </div>
            <nav aria-label="Page navigation">
                {{ $pendaftaran->onEachSide(1)->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi Terima/Tolak
    function confirmStatus(action, id) {
        const isApprove = action === 'terima';
        Swal.fire({
            title: isApprove ? 'Terima Pendaftaran?' : 'Tolak Pendaftaran?',
            text: isApprove ? "User akan dinyatakan terdaftar dalam perjalanan ini." : "User akan mendapatkan status ditolak.",
            icon: isApprove ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isApprove ? '#2e7d32' : '#e53e3e',
            cancelButtonColor: '#858796',
            confirmButtonText: isApprove ? 'Ya, Terima!' : 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(isApprove ? 'form-terima-' + id : 'form-tolak-' + id).submit();
            }
        })
    }

    // Konfirmasi Hapus
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data Pendaftaran?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#c62828',
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