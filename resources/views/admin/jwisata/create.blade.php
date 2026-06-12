@extends('layouts.main')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    body {
        background-color: #f8f9fc;
    }

    .main-container {
        padding: 30px 15px;
        min-height: calc(100vh - 70px); 
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-form {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(78, 115, 223, 0.08);
        background: #ffffff;
        width: 100%;
        max-width: 800px;
    }

    .card-header-custom {
        background: var(--primary-gradient);
        color: white;
        padding: 18px 25px;
        border-radius: 16px 16px 0 0 !important;
    }

    .form-label {
        font-weight: 700;
        color: #4e73df;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
    }

    .text-danger-star {
        color: #e11d48;
        font-weight: bold;
        margin-left: 3px;
    }

    .input-group-text {
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        color: #4e73df;
        padding: 8px 14px;
    }

    .is-invalid-custom {
        border-color: #e11d48 !important;
    }
    .is-invalid-custom:focus {
        box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1) !important;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
    }

    .has-icon .input-left { border-radius: 10px 0 0 10px; border-right: none; }
    .has-icon .form-control { border-left: none; border-radius: 0 10px 10px 0; }
    
    .mb-compact { margin-bottom: 20px !important; }

    .error-feedback {
        color: #e11d48;
        font-size: 0.78rem;
        font-weight: 600;
        margin-top: 5px;
        display: block;
    }

    .btn-save {
        background: var(--primary-gradient);
        border: none;
        border-radius: 10px;
        padding: 10px 28px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.25);
    }

    .btn-back {
        background: #ffffff;
        color: #64748b;
        border-radius: 10px;
        padding: 10px 28px;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-back:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
</style>

<div class="main-container">
    <div class="card card-form">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2"></i> Buat Jadwal Wisata Baru</h5>
            <p class="small mb-0 opacity-75 d-none d-sm-block mt-1">Isi data di bawah ini dengan lengkap untuk menerbitkan paket.</p>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('admin.jwisata.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Nama Paket Wisata --}}
                    <div class="col-md-12 mb-compact">
                        <label class="form-label">Nama Paket Wisata<span class="text-danger-star">*</span></label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left @error('nama_wisata') is-invalid-custom @enderror"><i class="bi bi-map"></i></span>
                            <input type="text" name="nama_wisata" class="form-control @error('nama_wisata') is-invalid-custom @enderror" value="{{ old('nama_wisata') }}" placeholder="Contoh: Explore Wisata Bromo Sunrise">
                        </div>
                        @error('nama_wisata')
                            <span class="error-feedback"><i class="bi bi-exclamation-circle me-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Lokasi Wisata --}}
                    <div class="col-md-12 mb-compact">
                        <label class="form-label">Lokasi Wisata<span class="text-danger-star">*</span></label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left @error('lokasi_wisata') is-invalid-custom @enderror"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="lokasi_wisata" class="form-control @error('lokasi_wisata') is-invalid-custom @enderror" value="{{ old('lokasi_wisata') }}" placeholder="Contoh: Gunung Bromo, Pasuruan, Jawa Timur">
                        </div>
                        @error('lokasi_wisata')
                            <span class="error-feedback"><i class="bi bi-exclamation-circle me-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tanggal Wisata --}}
                    <div class="col-md-4 mb-compact">
                        <label class="form-label">Tanggal Keberangkatan<span class="text-danger-star">*</span></label>
                        <input type="date" name="tanggal_wisata" class="form-control @error('tanggal_wisata') is-invalid-custom @enderror" value="{{ old('tanggal_wisata') }}">
                        @error('tanggal_wisata')
                            <span class="error-feedback"><i class="bi bi-exclamation-circle me-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Biaya Wisata --}}
                    <div class="col-md-4 mb-compact">
                        <label class="form-label">Biaya / Harga Tiket (Rp)<span class="text-danger-star">*</span></label>
                        <input type="text" id="biaya_tampilan" class="form-control fw-bold text-success @error('biaya_wisata') is-invalid-custom @enderror" placeholder="0">
                        
                        {{-- Input Hidden --}}
                        <input type="hidden" id="biaya_asli" name="biaya_wisata" value="{{ old('biaya_wisata') }}">
                        
                        @error('biaya_wisata')
                            <span class="error-feedback"><i class="bi bi-exclamation-circle me-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kuota Wisata --}}
                    <div class="col-md-4 mb-compact">
                        <label class="form-label">Maksimal Kuota (Orang)<span class="text-danger-star">*</span></label>
                        <input type="number" name="kuota" class="form-control @error('kuota') is-invalid-custom @enderror" value="{{ old('kuota') }}" placeholder="0" min="1">
                        @error('kuota')
                            <span class="error-feedback"><i class="bi bi-exclamation-circle me-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Keterangan / Deskripsi --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Deskripsi & Fasilitas Perjalanan</label>
                        <textarea name="keterangan_wisata" class="form-control @error('keterangan_wisata') is-invalid-custom @enderror" rows="3" placeholder="Masukkan detail penawaran fasilitas...">{{ old('keterangan_wisata') }}</textarea>
                        @error('keterangan_wisata')
                            <span class="error-feedback"><i class="bi bi-exclamation-circle me-1"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Group Tombol --}}
                <div class="d-flex justify-content-between border-top pt-3 mt-2">
                    <a href="{{ route('admin.jwisata.index') }}" class="btn btn-back border">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary btn-save text-white shadow-sm">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const biayaTampilan = document.getElementById('biaya_tampilan');
    const biayaAsli = document.getElementById('biaya_asli');
    
    biayaTampilan.addEventListener('input', function(e) {
        let nilaiBersih = this.value.replace(/[^\d]/g, ''); 
        biayaAsli.value = nilaiBersih; 
        this.value = formatRupiah(nilaiBersih);
    });

    function formatRupiah(angka) {
        if (!angka) return '';
        var number_string = angka.toString(),
            sisa   = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    if (biayaAsli.value) {
        biayaTampilan.value = formatRupiah(biayaAsli.value);
    }
</script>
@endsection