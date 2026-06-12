@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --primary-color: #4e73df;
        --dark-navy: #2d336b;
    }

    .main-container {
        padding: 20px 15px;
        background-color: #f8f9fc;
        min-height: 100vh;
    }

    .page-title {
        font-weight: 800;
        color: var(--dark-navy);
        font-size: 1.4rem;
        letter-spacing: -0.5px;
    }

    .card-modern {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(45, 51, 107, 0.05);
        padding: 24px;
        border: 1px solid rgba(226, 232, 240, 0.6);
    }

    /* Search Box Kapsul Modern */
    .search-box {
        position: relative;
        width: 100%;
    }

    .search-box i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .search-input {
        border-radius: 30px;
        padding: 10px 16px 10px 44px;
        border: 1px solid #e2e8f0;
        height: 42px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.25s ease-in-out;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.12);
        outline: none;
    }

    /* Table Custom Styling */
    .table-custom thead th {
        background: #f8fafc;
        border: none;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 14px 16px;
        letter-spacing: 0.75px;
    }

    .table-custom tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-custom tbody td {
        padding: 16px;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    /* Status Badge & Label */
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Action Buttons */
    .btn-action {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
        border: none;
        text-decoration: none;
    }

    .btn-edit { background: #fff4e5; color: #ff9800; }
    .btn-delete { background: #ffebee; color: #f44336; }
    .btn-edit:hover { background: #ff9800; color: white; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(255,152,0,0.2); }
    .btn-delete:hover { background: #f44336; color: white; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(244,67,54,0.2); }

    /* Custom Pagination */
    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }

    .pagination .page-item .page-link {
        border: 1px solid #e2e8f0;
        border-radius: 8px !important;
        padding: 8px 14px;
        font-size: 0.85rem;
        color: #475569;
        font-weight: 600;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        border-color: transparent;
        color: white !important;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.25);
    }

    /* Kustomisasi Tampilan Keren Modul SweetAlert */
    .swal2-popup-custom {
        border-radius: 16px !important;
        font-family: 'Nunito', system-ui, -apple-system, sans-serif !important;
    }
    .swal2-confirm-custom {
        border-radius: 30px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
    }
    .swal2-cancel-custom {
        border-radius: 30px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
    }

    /* Mengatur Pemetaan Responsif untuk HP */
    @media (max-width: 767.98px) {
        .header-action-group {
            width: 100%;
            flex-direction: column-reverse !important;
            gap: 12px !important;
        }
        .search-box {
            max-width: 100% !important;
        }
        .btn-add-wisata {
            width: 100%;
            justify-content: center;
            height: 42px;
        }
    }
</style>

<div class="main-container">
    {{-- Header Section --}}
    <div class="row align-items-center g-3 mb-4">
        <div class="col-12 col-md-6 text-start">
            <h4 class="page-title mb-1">
                Jadwal Wisata
            </h4>
            <p class="text-muted small mb-0">Kelola jadwal keberangkatan dan status pendaftaran wisata secara terpusat.</p>
        </div>
        
        <div class="col-12 col-md-6 d-flex justify-content-md-end">
            <div class="d-flex align-items-center gap-2 header-action-group w-100 justify-content-md-end">
                <div class="search-box" style="max-width: 260px;">
                    <form action="{{ route('admin.jwisata.index') }}" method="GET">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="form-control search-input shadow-sm" 
                               placeholder="Cari agenda wisata..." value="{{ request('search') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Utama Hasil Jadwal --}}
    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th>Wisata & Lokasi</th>
                        <th>Tanggal</th>
                        <th>Biaya</th>
                        <th>Kuota</th>
                        <th class="text-center">Pendaftaran</th>
                        <th width="160" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jwisata as $index => $item)
                    <tr>
                        <td class="text-center fw-bold text-secondary opacity-75">{{ $jwisata->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $item->nama_wisata }}</div>
                            <div class="small text-muted mt-1"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $item->lokasi_wisata }}</div>
                        </td>
                        <td>
                            <div class="small fw-bold text-primary bg-light px-2 py-1 d-inline-block rounded">
                                <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($item->tanggal_wisata)->locale('id')->translatedFormat('d M Y') }}
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-success" style="font-size: 0.9rem;">
                                Rp {{ number_format($item->biaya_wisata, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border px-2 py-1fw-semibold">
                                {{ $item->kuota }} Kursi
                            </span>
                        </td>
                        <td class="text-center">
                            @if($item->is_open)
                                <span class="status-badge bg-success bg-opacity-10 text-success border border-success border-opacity-20"><i class="bi bi-check-circle-fill me-1"></i> Dibuka</span>
                            @else
                                <span class="status-badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20"><i class="bi bi-x-circle-fill me-1"></i> Ditutup</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                {{-- TOGGLE STATUS --}}
                                <form action="{{ route('admin.jwisata.toggle', $item->id_wisata) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $item->is_open ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                            style="border-radius: 20px; font-size: 0.7rem; font-weight: 800; padding: 6px 12px; letter-spacing: 0.3px;">
                                        {{ $item->is_open ? 'TUTUP' : 'BUKA' }}
                                    </button>
                                </form>

                                {{-- EDIT --}}
                                <a href="{{ route('admin.jwisata.edit', $item->id_wisata) }}" class="btn-action btn-edit" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.jwisata.destroy', $item->id_wisata) }}" method="POST" id="delete-form-{{ $item->id_wisata }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" onclick="confirmDelete('{{ $item->id_wisata }}')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-calendar-x text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                <h6 class="text-dark fw-bold mt-3">Belum Ada Agenda Terjadwal</h6>
                                <p class="text-muted small">Silakan tambah agenda liburan baru dengan menekan tombol di atas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Paginasi Dinamis --}}
        <div class="mt-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 border-top pt-4">
            <div class="small text-secondary fw-semibold">
                Menampilkan <span class="text-primary fw-bold">{{ $jwisata->firstItem() ?? 0 }}</span> 
                sampai <span class="text-primary fw-bold">{{ $jwisata->lastItem() ?? 0 }}</span> 
                dari <span class="text-primary fw-bold">{{ $jwisata->total() }}</span> total data jadwal
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    {{-- PREVIOUS --}}
                    @if ($jwisata->onFirstPage())
                        <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $jwisata->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a></li>
                    @endif

                    {{-- NOMOR HALAMAN --}}
                    @php
                        $start = max($jwisata->currentPage() - 1, 1);
                        $end = min($start + 2, $jwisata->lastPage());
                        if (($end - $start) < 2) { $start = max($end - 2, 1); }
                    @endphp

                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $jwisata->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $jwisata->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- NEXT --}}
                    @if ($jwisata->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $jwisata->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>
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
            text: "Data jadwal yang terhapus tidak bisa dikembalikan semula!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Data!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'swal2-popup-custom',
                confirmButton: 'btn btn-danger swal2-confirm-custom mx-1',
                cancelButton: 'btn btn-light swal2-cancel-custom mx-1'
            },
            buttonsStyling: false
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
            timer: 2200,
            customClass: {
                popup: 'swal2-popup-custom'
            }
        });
    @endif
</script>
@endsection