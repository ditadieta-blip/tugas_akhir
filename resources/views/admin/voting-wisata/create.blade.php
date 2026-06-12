@extends('layouts.main')
@section('title', 'Tambah Voting Wisata')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --primary-color: #4e73df;
    }

    /* Sinkronisasi Font & Form dari Halaman User */
    .form-label {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 0.75rem;
        margin-bottom: 4px;
        letter-spacing: 0.05em;
    }

    .form-control, .input-group-text {
        border-radius: 8px;
        padding: 8px 12px;
        border: 1.5px solid #e3e6f0;
        font-size: 0.85rem;
        background-color: #ffffff;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }

    /* Modern Card Customization */
    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        background: #ffffff;
    }

    /* Tombol Modern & Responsif */
    .btn-modern {
        border-radius: 8px;
        padding: 8px 20px;
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
    .custom-group .input-group-text {
        border-radius: 8px 0 0 8px !important;
        border-right: none;
    }

    .custom-group .form-control {
        border-radius: 0 8px 8px 0 !important;
    }

    /* Khusus input yang memiliki tombol hapus di kanannya */
    .custom-group .form-control.has-action {
        border-radius: 0 !important;
    }

    .custom-group .btn-delete-option {
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
    <!-- Header Page: Dibikin Compact & Responsive -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
        <div>
            <!-- Ukuran Teks Judul diselaraskan agar rapi dan tidak kebesaran -->
            <h4 class="fw-bold text-dark mb-1">Tambah Voting Wisata</h4>
            <p class="text-muted mb-0 small">Kelola pemungutan suara untuk tujuan wisata berikutnya.</p>
        </div>
        <div>
            <a href="{{ route('admin.voting-wisata.index') }}" class="btn btn-modern btn-back-custom w-100 w-sm-auto">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="card card-custom">
                <div class="card-body p-4">
                    <form action="{{ route('admin.voting-wisata.store') }}" method="POST">
                        @csrf

                        <!-- Judul Sesi Voting -->
                        <div class="mb-4">
                            <label class="form-label text-uppercase">Judul Sesi Voting</label>
                            <div class="input-group custom-group">
                                <span class="input-group-text text-muted"><i class="bi bi-pencil-square"></i></span>
                                <input type="text" name="judul_voting" 
                                       class="form-control @error('judul_voting') is-invalid @enderror"
                                       value="{{ old('judul_voting') }}" 
                                       placeholder="Misal: Voting Wisata Akhir Tahun Ke Bali" required>
                            </div>
                            @error('judul_voting')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Opsi Pendukung Dinamis -->
                        <div class="row g-4">
                            <!-- Kolom Lokasi -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-uppercase mb-0">Opsi Lokasi <span class="text-muted fw-normal text-lowercase">(Maks. 3)</span></label>
                                    <button type="button" id="btn-add-lokasi" onclick="addInput('lokasi-container', 'opsi_lokasi[]', 'text', 'Nama lokasi...')" class="btn btn-add-option btn-sm rounded-circle p-0">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                <div id="lokasi-container">
                                    <div class="input-group mb-2 custom-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt text-danger"></i></span>
                                        <input type="text" name="opsi_lokasi[]" class="form-control" placeholder="Nama lokasi..." required>
                                    </div>
                                    <div class="input-group mb-2 custom-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt text-danger"></i></span>
                                        <input type="text" name="opsi_lokasi[]" class="form-control" placeholder="Nama lokasi..." required>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Tanggal -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-uppercase mb-0">Opsi Tanggal <span class="text-muted fw-normal text-lowercase">(Maks. 3)</span></label>
                                    <button type="button" id="btn-add-tanggal" onclick="addInput('tanggal-container', 'opsi_tanggal[]', 'date', '')" class="btn btn-add-option btn-sm rounded-circle p-0">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                <div id="tanggal-container">
                                    <div class="input-group mb-2 custom-group">
                                        <span class="input-group-text"><i class="bi bi-calendar-event text-primary"></i></span>
                                        <input type="date" name="opsi_tanggal[]" class="form-control" required>
                                    </div>
                                    <div class="input-group mb-2 custom-group">
                                        <span class="input-group-text"><i class="bi bi-calendar-event text-primary"></i></span>
                                        <input type="date" name="opsi_tanggal[]" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Form: Reset dan Submit Button -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2">
                                <button type="reset" class="btn btn-modern btn-back-custom w-100 w-sm-auto">Reset</button>
                                <button type="submit" class="btn btn-modern btn-save-custom w-100 w-sm-auto">
                                    <i class="bi bi-check2-circle me-2"></i>Simpan 
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addInput(containerId, name, type, placeholder) {
        const container = document.getElementById(containerId);
        const currentInputs = container.getElementsByClassName('input-group').length;

        if (currentInputs < 3) {
            const icon = type === 'text' ? 'bi-geo-alt text-danger' : 'bi-calendar-event text-primary';
            const div = document.createElement('div');
            div.className = 'input-group mb-2 fade-in custom-group';
            div.innerHTML = `
                <span class="input-group-text"><i class="bi ${icon}"></i></span>
                <input type="${type}" name="${name}" class="form-control has-action" placeholder="${placeholder}" required>
                <button class="btn btn-delete-option text-danger" type="button" onclick="removeInput(this, '${containerId}')">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            
            container.appendChild(div);
        }
        checkLimit(containerId);
    }

    function removeInput(btn, containerId) {
        btn.parentElement.remove();
        checkLimit(containerId);
    }

    function checkLimit(containerId) {
        const container = document.getElementById(containerId);
        const currentInputs = container.getElementsByClassName('input-group').length;
        const btnId = containerId === 'lokasi-container' ? 'btn-add-lokasi' : 'btn-add-tanggal';
        const btnAdd = document.getElementById(btnId);

        btnAdd.style.visibility = (currentInputs >= 3) ? 'hidden' : 'visible';
    }
</script>
@endsection