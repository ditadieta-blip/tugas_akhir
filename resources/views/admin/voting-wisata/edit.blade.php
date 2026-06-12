@extends('layouts.main')
@section('title', 'Edit Voting Wisata')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --primary-color: #4e73df;
    }

    /* Sinkronisasi Font & Form dari Halaman Sebelumnya */
    .form-label {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 0.75rem;
        margin-bottom: 4px;
        letter-spacing: 0.05em;
    }

    .form-control, .form-select, .input-group-text {
        border-radius: 8px;
        padding: 8px 12px;
        border: 1.5px solid #e3e6f0;
        font-size: 0.85rem;
        background-color: #ffffff;
    }

    .form-control:focus, .form-select:focus {
        background-color: #fff;
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }

    /* Card Layout Customization */
    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        background: #ffffff;
    }

    /* Tombol Modern & Responsif */
    .btn-modern {
        border-radius: 8px;
        padding: 8px 25px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .btn-save-custom {
        background: var(--primary-gradient);
        border: none;
        color: white;
    }

    .btn-save-custom:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: white;
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

    /* Lingkaran Tombol Tambah Opsi (+ )*/
    .btn-add-option {
        background: var(--primary-gradient);
        border: none;
        color: white;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }
    
    .btn-add-option:hover {
        transform: scale(1.1);
        color: white;
    }

    /* Input Group Border Radius Handler */
    .custom-group-item .input-group-text {
        border-radius: 8px 0 0 8px !important;
        border-right: none;
    }

    .custom-group-item .form-control {
        border-radius: 0 !important;
        border-right: none;
    }

    .custom-group-item .btn-delete-option {
        border-radius: 0 8px 8px 0 !important;
        border: 1.5px solid #e3e6f0;
        border-left: none;
        background: #ffffff;
    }

    /* Animasi */
    .fade-in { 
        animation: fadeIn 0.25s ease-in-out; 
    }
    @keyframes fadeIn { 
        from { opacity: 0; transform: translateY(-5px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
</style>

<div class="container-fluid py-4">
    <!-- Header Page: Compact & Responsive -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Edit Voting Wisata</h4>
            <p class="text-muted mb-0 small">Sesuaikan judul, status, dan pilihan destinasi wisata.</p>
        </div>
        <div>
            <a href="{{ route('admin.voting-wisata.index') }}" class="btn btn-modern btn-back-custom w-100 w-sm-auto">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.voting-wisata.update', $voting->id_voting) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Kolom Kiri: Informasi Utama -->
            <div class="col-lg-4">
                <div class="card card-custom h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-4 border-bottom pb-2">Informasi Utama</h6>
                        
                        <div class="mb-3">
                            <label class="form-label text-uppercase">Judul Voting</label>
                            <input type="text" name="judul_voting" class="form-control" 
                                   value="{{ old('judul_voting', $voting->judul_voting) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-uppercase">Status</label>
                            <select name="status" class="form-select fw-semibold">
                                <option value="draft" {{ $voting->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <<option value="aktif" {{ $voting->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ $voting->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="alert alert-warning border-0 rounded-3 small mb-0 p-3 mt-4">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-triangle-fill me-2 text-warning fs-5"></i>
                                <span>Menghapus opsi yang sudah memiliki suara (vote) dapat mempengaruhi keakuratan data.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Pilihan Opsi (Dinamis) -->
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Kelola Opsi Lokasi -->
                            <div class="col-md-6 border-end-md">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label text-uppercase mb-0"><i class="bi bi-get-front me-1 text-danger"></i> Opsi Lokasi</label>
                                    <button type="button" onclick="addOpsi('lokasi')" class="btn btn-add-option btn-sm rounded-circle p-0">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                <div id="container-lokasi">
                                    @foreach($voting->opsi->where('jenis_opsi', 'lokasi') as $opsi)
                                        <div class="input-group mb-2 custom-group-item">
                                            <span class="input-group-text"><i class="bi bi-geo-alt text-danger"></i></span>
                                            <input type="hidden" name="existing_opsi_id[]" value="{{ $opsi->id_opsi }}">
                                            <input type="text" name="existing_opsi_nilai[{{ $opsi->id_opsi }}]" 
                                                   class="form-control" value="{{ $opsi->nilai_opsi }}" required>
                                            <button type="button" class="btn btn-delete-option text-danger btn-hapus-opsi" onclick="konfirmasiHapus(this, 'lokasi')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Kelola Opsi Tanggal -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label text-uppercase mb-0"><i class="bi bi-calendar-week me-1 text-primary"></i> Opsi Tanggal</label>
                                    <button type="button" onclick="addOpsi('tanggal')" class="btn btn-add-option btn-sm rounded-circle p-0">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                <div id="container-tanggal">
                                    @foreach($voting->opsi->where('jenis_opsi', 'tanggal') as $opsi)
                                        <div class="input-group mb-2 custom-group-item">
                                            <span class="input-group-text"><i class="bi bi-calendar-event text-primary"></i></span>
                                            <input type="hidden" name="existing_opsi_id[]" value="{{ $opsi->id_opsi }}">
                                            <input type="date" name="existing_opsi_nilai[{{ $opsi->id_opsi }}]" 
                                                   class="form-control" value="{{ $opsi->nilai_opsi }}" required>
                                            <button type="button" class="btn btn-delete-option text-danger btn-hapus-opsi" onclick="konfirmasiHapus(this, 'tanggal')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Reposisi Tombol Simpan (Lebar Proporsional & Icon Simpel Baru) -->
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-modern btn-save-custom shadow-sm d-inline-flex align-items-center">
                                 Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL KONFIRMASI KUSTOM (Notifikasi Sistem Modern) -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-octagon-fill" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Hapus Opsi Pilihan?</h5>
                <p class="text-muted small mb-4">Menghapus hasil opsi yang sudah ada dapat menghapus data voting anggota secara <strong>permanen</strong> dari sistem.</p>
                
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-modern btn-back-custom px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnConfirmDeleteExecute" class="btn btn-modern btn-danger px-4 text-white">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let elementYangAkanDihapus = null;

    // Cek status tombol hapus saat halaman pertama kali dibuka
    document.addEventListener("DOMContentLoaded", function() {
        updateTombolHapusVisibility('lokasi');
        updateTombolHapusVisibility('tanggal');
    });

    function addOpsi(jenis) {
        const container = document.getElementById('container-' + jenis);
        const currentInputs = container.getElementsByClassName('custom-group-item').length;

        // Maksimal batas atas input opsi adalah 3
        if (currentInputs < 3) {
            const type = jenis === 'lokasi' ? 'text' : 'date';
            const name = jenis === 'lokasi' ? 'new_opsi_lokasi[]' : 'new_opsi_tanggal[]';
            const placeholder = jenis === 'lokasi' ? 'Nama lokasi baru...' : '';
            const icon = jenis === 'lokasi' ? 'bi-geo-alt text-danger' : 'bi-calendar-event text-primary';

            const div = document.createElement('div');
            div.className = 'input-group mb-2 fade-in custom-group-item';
            div.innerHTML = `
                <span class="input-group-text"><i class="bi ${icon}"></i></span>
                <input type="${type}" name="${name}" class="form-control" placeholder="${placeholder}" required>
                <button type="button" class="btn btn-delete-option text-danger btn-hapus-opsi" onclick="konfirmasiHapus(this, '${jenis}')">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            container.appendChild(div);
            updateTombolHapusVisibility(jenis);
        }
    }

    function konfirmasiHapus(btn, jenis) {
        elementYangAkanDihapus = btn.parentElement;
        
        // Membuka Modal Kustom Bootstrap
        const myModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        myModal.show();

        // Eksekusi jika menekan tombol konfirmasi merah di modal
        document.getElementById('btnConfirmDeleteExecute').onclick = function() {
            elementYangAkanDihapus.remove();
            myModal.hide();
            updateTombolHapusVisibility(jenis);
        };
    }

    // Fungsi Logika Pembatasan Minimal: Sembunyikan tombol sampah jika data hanya sisa 2
    function updateTombolHapusVisibility(jenis) {
        const container = document.getElementById('container-' + jenis);
        const opsiItems = container.getElementsByClassName('custom-group-item');
        
        for (let i = 0; i < opsiItems.length; i++) {
            const btnHapus = opsiItems[i].querySelector('.btn-hapus-opsi');
            if (opsiItems.length <= 2) {
                btnHapus.style.display = 'none';
            } else {
                btnHapus.style.display = 'block';
            }
        }
    }
</script>
@endsection