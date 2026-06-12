@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <!-- Header: Warna & Font diperbaiki -->
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-7 mb-3 mb-md-0">
            <!-- Font judul lebih besar dan warna Navy yang elegan -->
            <h2 class="fw-black text-indigo mb-1" style="letter-spacing: -1px; color: #2d336b;">Voting Wisata</h2>
            <p class="text-muted mb-0 small fw-medium text-uppercase tracking-widest">
                <i class="bi bi-geo-alt-fill me-1 text-primary"></i> Manajemen Aspirasi Liburan Anggota BSC
            </p>
        </div>
        <div class="col-12 col-md-5 text-md-end">
            <!-- Tombol dengan Gradient & Shadow yang serasi -->
            <a href="{{ route('admin.voting-wisata.create') }}" class="btn btn-gradient-primary rounded-pill px-4 py-2 shadow fw-bold btn-lg-mobile">
                <i class="bi bi-plus-circle-fill me-2"></i>Tambah Voting
            </a>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="row mb-4 g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-primary border-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary-soft text-primary me-3">
                        <i class="bi bi-clipboard-data-fill"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold">Total Voting</small>
                        <span class="h4 fw-bold mb-0 text-dark">{{ $votings->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Table -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-indigo-light">
                        <tr>
                            <th class="ps-4 py-3 text-indigo-dark uppercase-tracking">Judul Voting</th>
                            <th class="py-3 text-indigo-dark text-center uppercase-tracking">Status</th>
                            <th class="py-3 text-indigo-dark text-center uppercase-tracking">Partisipan</th>
                            <th class="py-3 text-indigo-dark uppercase-tracking">Tanggal Dibuat</th>
                            <th class="pe-4 py-3 text-indigo-dark text-center uppercase-tracking">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($votings as $voting)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape-modern bg-light text-primary rounded-3 me-3 d-none d-sm-flex border">
                                        <i class="bi bi-map-fill"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block mb-0" style="font-size: 1rem;">{{ $voting->judul_voting }}</span>
                                        <small class="text-muted d-sm-none">Dibuat: {{ $voting->created_at->locale('id')->translatedFormat('d M Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $statusClasses = [
                                        'aktif' => 'bg-success text-success',
                                        'selesai' => 'bg-secondary text-secondary',
                                        'draft' => 'bg-warning text-warning'
                                    ];
                                    $class = $statusClasses[$voting->status] ?? 'bg-light text-dark';
                                @endphp
                                <span class="badge rounded-pill {{ $class }} bg-opacity-10 px-3 py-2 border border-{{ explode(' ', $class)[1] }} border-opacity-25 fw-bold">
                                    {{ ucfirst($voting->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2 border">
                                    <i class="bi bi-people-fill text-primary me-2"></i>{{ $voting->total_pemilih ?? 0 }}
                                </span>
                            </td>
                            <td class="text-dark fw-semibold" style="font-size: 0.9rem;">
                                {{ $voting->created_at->locale('id')->translatedFormat('d M Y') }}
                            </td>
                            <td class="pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.voting-wisata.show', $voting->id_voting) }}" class="btn-action-modern btn-view" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.voting-wisata.edit', $voting->id_voting) }}" class="btn-action-modern btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <!-- Form diubah agar memicu Modal Kustom melalui JavaScript -->
                                    <form id="form-delete-{{ $voting->id_voting }}" action="{{ route('admin.voting-wisata.destroy', $voting->id_voting) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-action-modern btn-delete" onclick="triggerDelete('{{ $voting->id_voting }}', '{{ $voting->judul_voting }}')" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-cloud-slash display-2 text-light mb-3"></i>
                                    <p class="text-muted fw-medium">Ops! Data voting masih kosong nih.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS KUSTOM (Sistem Notifikasi Modern) -->
<div class="modal fade" id="systemDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-octagon-fill" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Hapus Data Voting?</h5>
                <p class="text-muted small mb-4">Apakah anda yakin ingin menghapus data voting <strong id="delete-target-name" class="text-dark"></strong> ini? Tindakan ini tidak dapat dibatalkan.</p>
                
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-modern-modal btn-back-custom px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnExecuteDelete" class="btn btn-modern-modal btn-danger px-4 text-white">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Color Palette */
    :root {
        --indigo-primary: #4e73df;
        --indigo-dark: #2d336b;
        --indigo-bg: #f8f9fc;
    }

    /* Font & Typography */
    .fw-black { font-weight: 900; }
    .uppercase-tracking {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 800;
        color: #5a5c69;
    }

    /* Header Background for Thead */
    .bg-indigo-light { background-color: #f1f3f9; }

    /* Button Gradient */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
        color: white;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-gradient-primary:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
    }

    /* Modal Button Uniform Styling */
    .btn-modern-modal {
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    .btn-back-custom {
        background: #ffffff;
        color: #858796;
        border: 1.5px solid #e3e6f0;
    }
    .btn-back-custom:hover {
        background: #f8f9fc;
        color: #6e707e;
    }

    /* Icon Styles */
    .icon-box {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .bg-primary-soft { background-color: rgba(78, 115, 223, 0.1); }

    .icon-shape-modern {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Action Buttons (Modern Soft Style) */
    .btn-action-modern {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e3e6f0;
        background: white;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-view { color: #4e73df; }
    .btn-edit { color: #f6c23e; }
    .btn-delete { color: #e74a3b; }

    .btn-action-modern:hover {
        background: #f8f9fc;
        transform: scale(1.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .btn-lg-mobile {
            width: 100%;
            display: block;
            text-align: center;
        }
        .text-indigo { font-size: 1.5rem; }
    }

    /* Table Decoration */
    .table-hover tbody tr:hover {
        background-color: #fbfcfe !important;
    }
</style>

<script>
    let activeDeleteFormId = null;

    function triggerDelete(id, judul) {
        activeDeleteFormId = 'form-delete-' + id;
        document.getElementById('delete-target-name').innerText = `"${judul}"`;
        
        // Membuka modal konfirmasi kustom Bootstrap
        const deleteModal = new bootstrap.Modal(document.getElementById('systemDeleteModal'));
        deleteModal.show();
    }

    // Eksekusi submit form asli saat tombol "Ya, Hapus" di dalam modal diklik
    document.getElementById('btnExecuteDelete').addEventListener('click', function() {
        if (activeDeleteFormId) {
            document.getElementById(activeDeleteFormId).submit();
        }
    });
</script>
@endsection