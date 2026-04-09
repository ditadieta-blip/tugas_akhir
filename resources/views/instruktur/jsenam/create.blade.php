@extends('layouts.instruktur')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    /* Wrapper untuk memposisikan card di tengah layar tanpa scroll */
    .view-wrapper {
        min-height: calc(100vh - 120px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    .form-container {
        width: 100%;
        max-width: 850px;
        margin: auto;
    }

    .card-modern {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header-custom {
        background: var(--primary-gradient);
        padding: 18px 25px;
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-body {
        padding: 25px 35px;
    }

    .form-group-custom {
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: 700;
        color: #4e73df;
        font-size: 0.85rem;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-group-modern {
        display: flex;
        align-items: center;
        background: #f8f9fc;
        border: 1.5px solid #e3e6f0;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .input-group-modern:focus-within {
        border-color: #4e73df;
        background: #fff;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
    }

    .input-group-modern i {
        padding: 0 15px;
        color: #b7b9cc;
        font-size: 1rem;
    }

    .input-group-modern .form-control {
        background: transparent;
        border: none;
        padding: 10px 15px 10px 0;
        font-size: 0.95rem;
        color: #5a5c69;
    }

    .input-group-modern .form-control:focus {
        box-shadow: none;
    }

    /* Button Styling */
    .btn-save {
        background: var(--primary-gradient);
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
        color: white;
    }

    .btn-cancel {
        background: #f8f9fc;
        color: #858796;
        border: 1.5px solid #e3e6f0;
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: 600;
    }

    .btn-cancel:hover {
        background: #eaecf4;
        color: #5a5c69;
    }

    /* Alert Styling */
    .alert-modern {
        border-radius: 10px;
        border: none;
        border-left: 5px solid #1cc88a;
        background: #f0fff4;
        color: #155724;
        font-size: 0.9rem;
    }
</style>

<div class="view-wrapper">
    <div class="form-container">
        
        <div class="mb-3">
            <h4 class="fw-bold text-dark mb-1">Tambah Jadwal Baru</h4>
            <p class="text-muted small">Lengkapi formulir untuk menambahkan sesi senam baru ke sistem.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-modern alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-modern">
            <div class="card-header-custom">
                <div class="bg-white bg-opacity-25 rounded-circle p-2">
                    <i class="bi bi-calendar-plus text-white fs-5"></i>
                </div>
                <h6 class="mb-0 fw-bold">Data Jadwal Senam</h6>
            </div>

            <div class="form-body">
                <form action="{{ route('instruktur.jsenam.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-12 form-group-custom">
                            <label class="form-label">Pilih Instruktur</label>
                            <div class="input-group-modern">
                                <i class="bi bi-person-badge"></i>
                                <select name="id_user" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Instruktur Tersedia --</option>
                                    @foreach($instruktur as $item)
                                        <option value="{{ $item->id_user }}" {{ old('id_user') == $item->id_user ? 'selected' : '' }}>
                                            {{ $item->nama_user }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 form-group-custom">
                            <label class="form-label">Tanggal Pelaksanaan</label>
                            <div class="input-group-modern @error('tanggal') border-danger @enderror">
                                <i class="bi bi-calendar-event"></i>
                                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                            </div>
                            @error('tanggal')
                                <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group-custom">
                            <label class="form-label">Lokasi / Tempat</label>
                            <div class="input-group-modern @error('tempat_senam') border-danger @enderror">
                                <i class="bi bi-geo-alt"></i>
                                <input type="text" name="tempat_senam" class="form-control" placeholder="Misal: Aula Utama" value="{{ old('tempat_senam') }}" required>
                            </div>
                            @error('tempat_senam')
                                <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12 form-group-custom">
                            <label class="form-label">Keterangan Singkat</label>
                            <div class="input-group-modern align-items-start @error('keterangan_senam') border-danger @enderror">
                                <i class="bi bi-chat-left-dots mt-2"></i>
                                <textarea name="keterangan_senam" class="form-control" rows="3" placeholder="Tambahkan informasi tambahan jika ada..." required>{{ old('keterangan_senam') }}</textarea>
                            </div>
                            @error('keterangan_senam')
                                <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <a href="{{ route('instruktur.jsenam.index') }}" class="btn btn-cancel shadow-sm">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>

                        <button type="submit" class="btn btn-save shadow-sm px-4">
                            <i class="bi bi-plus-circle me-1"></i> Simpan Jadwal
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection