@extends('layouts.main')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    body {
        overflow: hidden; /* Mencegah scroll pada body desktop */
        background-color: #f8f9fc;
    }

    .main-container {
        padding: 15px;
        height: calc(100vh - 70px); 
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-form {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        width: 100%;
        max-width: 850px;
        margin: auto;
    }

    .card-header-custom {
        background: var(--primary-gradient);
        color: white;
        padding: 15px 25px;
        border-radius: 15px 15px 0 0 !important;
    }

    .form-label {
        font-weight: 700;
        color: #4e73df;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .input-group-text {
        background-color: #f8f9fc;
        border: 1.5px solid #e3e6f0;
        color: #4e73df;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 12px;
        border: 1.5px solid #e3e6f0;
        font-size: 0.9rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
    }

    /* Input Group Styling */
    .has-icon .input-left { border-radius: 10px 0 0 10px; border-right: none; }
    .has-icon .form-control, .has-icon .form-select { border-left: none; border-radius: 0 10px 10px 0; }
    
    .mb-compact { margin-bottom: 12px !important; }

    .btn-update {
        background: var(--primary-gradient);
        border: none;
        border-radius: 10px;
        padding: 10px 30px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.2);
    }

    .btn-back {
        background: #ffffff;
        color: #858796;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
    }

    @media (max-height: 720px) {
        .mb-compact { margin-bottom: 8px !important; }
        .card-header-custom { padding: 10px 25px; }
    }
</style>

<div class="main-container">
    <div class="card card-form">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Jadwal Wisata</h5>
            <p class="small mb-0 opacity-75 d-none d-sm-block">Perbarui informasi paket wisata: <strong>{{ $data->nama_wisata }}</strong></p>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('admin.jwisata.update', $data->id_wisata) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-12 mb-compact">
                        <label class="form-label">Nama Paket Wisata</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left"><i class="bi bi-map"></i></span>
                            <input type="text" name="nama_wisata" class="form-control" 
                                   value="{{ old('nama_wisata', $data->nama_wisata) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Lokasi Wisata</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left">
                                <i class="bi bi-geo-alt"></i>
                            </span>
                            <input type="text"
                                name="lokasi_wisata"
                                class="form-control"
                                value="{{ old('lokasi_wisata', $data->lokasi_wisata) }}"
                                placeholder="Contoh: Gunung Bromo, Jawa Timur"
                                required>
                        </div>
                    </div>

                    <div class="col-md-3 mb-compact">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal_wisata" class="form-control text-center" 
                               value="{{ old('tanggal_wisata', $data->tanggal_wisata) }}" required>
                    </div>

                    <div class="col-md-3 mb-compact">
                        <label class="form-label">Biaya (Rp)</label>
                        <input type="number" name="biaya_wisata" class="form-control text-end" 
                               value="{{ old('biaya_wisata', $data->biaya_wisata) }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Keterangan / Deskripsi</label>
                        <textarea name="keterangan_wisata" class="form-control" rows="3" 
                                  placeholder="Masukkan detail perubahan paket...">{{ old('keterangan_wisata', $data->keterangan_wisata) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between border-top pt-3 mt-2">
                    <a href="{{ route('admin.jwisata.index') }}" class="btn btn-back border">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-update text-white shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false,
        customClass: { popup: 'rounded-15' }
    });
    @endif

    @if ($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan',
        html: '{!! implode("<br>", $errors->all()) !!}',
        confirmButtonColor: '#4e73df',
    });
    @endif
</script>
@endsection