@extends('layouts.instruktur')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .view-wrapper {
        min-height: calc(100vh - 100px); 
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    .form-container {
        width: 100%;
        max-width: 900px;
        margin: auto;
    }

    .card-modern {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header-custom {
        background: var(--primary-gradient);
        padding: 15px 25px;
        color: white;
    }

    .form-body {
        padding: 20px 30px; 
    }

    .form-group-custom {
        margin-bottom: 12px; 
    }

    .form-label {
        font-weight: 600;
        color: #4e73df;
        font-size: 0.85rem;
        margin-bottom: 4px;
        display: block;
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
    }

    .input-group-modern i {
        padding: 0 12px;
        color: #b7b9cc;
        font-size: 0.9rem;
    }

    .input-group-modern .form-control {
        background: transparent;
        border: none;
        padding: 8px 10px 8px 0; 
        font-size: 0.9rem;
        color: #5a5c69;
        height: auto;
    }

    .input-group-modern textarea.form-control {
        min-height: 80px; 
    }

    .btn-save, .btn-cancel {
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .btn-save {
        background: var(--primary-gradient);
        border: none;
        color: white;
    }

    .last-row { margin-bottom: 0; }
</style>

<div class="view-wrapper">
    <div class="form-container">
        
        <div class="d-flex justify-content-between align-items-end mb-2">
            <div>
                <h4 class="fw-bold text-dark mb-0">Edit Jadwal</h4>
                <p class="text-muted small mb-0">Perbarui informasi instruktur & lokasi.</p>
            </div>
        </div>

        <div class="card-modern">
            <div class="card-header-custom d-flex align-items-center">
                <i class="bi bi-pencil-square me-2"></i>
                <span class="fw-bold">Formulir Perubahan Data</span>
            </div>

            <div class="form-body">
                <form action="{{ route('instruktur.jsenam.update', $jadwal->id_senam) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-12 form-group-custom">
                            <label class="form-label">Nama Instruktur</label>
                            <div class="input-group-modern">
                                <i class="bi bi-person-badge"></i>
                                <select name="id_user" class="form-control" required>
                                    @foreach($instruktur as $item)
                                        <option value="{{ $item->id_user }}"
                                            {{ old('id_user', $jadwal->id_user) == $item->id_user ? 'selected' : '' }}>
                                            {{ $item->nama_user }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 form-group-custom">
                            <label class="form-label">Tanggal</label>
                            <div class="input-group-modern">
                                <i class="bi bi-calendar-event"></i>
                                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $jadwal->tanggal) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 form-group-custom">
                            <label class="form-label">Lokasi</label>
                            <div class="input-group-modern">
                                <i class="bi bi-geo-alt"></i>
                                <input type="text" name="tempat_senam" class="form-control" value="{{ old('tempat_senam', $jadwal->tempat_senam) }}" required>
                            </div>
                        </div>

                        <div class="col-12 form-group-custom mb-0">
                            <label class="form-label">Keterangan</label>
                            <div class="input-group-modern align-items-start">
                                <i class="bi bi-chat-left-text mt-2"></i>
                                <textarea name="keterangan_senam" class="form-control" rows="3" required>{{ old('keterangan_senam', $jadwal->keterangan_senam) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <a href="{{ route('instruktur.jsenam.index') }}" class="btn btn-cancel">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>

                        <button type="submit" class="btn btn-save shadow-sm">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection