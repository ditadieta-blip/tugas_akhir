@extends('layouts.main')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    body {
        overflow: hidden; /* Mencegah scroll pada body */
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
        padding: 12px 25px;
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
        padding: 8px 12px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 8px 12px;
        border: 1.5px solid #e3e6f0;
        font-size: 0.9rem;
    }

    /* Penyesuaian radius untuk input group */
    .has-icon .input-left { border-radius: 10px 0 0 10px; border-right: none; }
    .has-icon .form-control, .has-icon .form-select { border-left: none; border-radius: 0 10px 10px 0; }
    
    .mb-compact { margin-bottom: 12px !important; }

    .btn-save {
        background: var(--primary-gradient);
        border: none;
        border-radius: 10px;
        padding: 8px 25px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.2);
    }

    .btn-back {
        background: #ffffff;
        color: #858796;
        border-radius: 10px;
        padding: 8px 25px;
        font-weight: 600;
    }

    .btn-back:hover {
        background: #f1f3f9;
        color: #4e73df;
    }

    @media (max-height: 700px) {
        .card-header-custom p { display: none; }
        .mb-compact { margin-bottom: 8px !important; }
    }
</style>

<div class="main-container">
    <div class="card card-form">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2"></i> Tambah Jadwal Wisata</h5>
            <p class="small mb-0 opacity-75 d-none d-sm-block">Lengkapi formulir untuk menambah paket perjalanan baru.</p>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('admin.jwisata.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8 mb-compact">
                        <label class="form-label">Nama Paket Wisata</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left"><i class="bi bi-map"></i></span>
                            <input type="text" name="nama_wisata" class="form-control" value="{{ old('nama_wisata') }}" placeholder="Contoh: Explore Bromo Sunrise" required>
                        </div>
                    </div>

                    <div class="col-md-4 mb-compact">
                        <label class="form-label">Kode Wisata (ID)</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left"><i class="bi bi-hash"></i></span>
                            <input type="number" name="id_wisata" class="form-control" value="{{ old('id_wisata') }}" placeholder="000" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Lokasi Wisata</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="lokasi_wisata" class="form-control"
                                value="{{ old('lokasi_wisata') }}"
                                placeholder="Contoh: Gunung Bromo, Jawa Timur" required>
                        </div>
                    </div>

                    <div class="col-md-3 mb-compact">
                        <label class="form-label">Tanggal Wisata</label>
                        <input type="date" name="tanggal_wisata" class="form-control text-center" value="{{ old('tanggal_wisata') }}" required>
                    </div>

                    <div class="col-md-3 mb-compact">
                        <label class="form-label">Biaya (Rp)</label>
                        <input type="number" name="biaya_wisata" class="form-control text-end" value="{{ old('biaya_wisata') }}" placeholder="0" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Deskripsi & Keterangan</label>
                        <textarea name="keterangan_wisata" class="form-control" rows="2" placeholder="Masukkan detail fasilitas, itinerary singkat, atau info tambahan lainnya...">{{ old('keterangan_wisata') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between border-top pt-3 mt-2">
                    <a href="{{ route('admin.jwisata.index') }}" class="btn btn-back border">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-save text-white shadow-sm">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Alert Sukses
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            customClass: { popup: 'rounded-20' }
        });
    @endif

    // Alert Error
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Opps!',
            html: '{!! implode("<br>", $errors->all()) !!}',
            confirmButtonColor: '#4e73df',
            customClass: { popup: 'rounded-20' }
        });
    @endif
</script>
@endsection