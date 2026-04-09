@extends('layouts.main')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    body {
        overflow: hidden; /* Tetap mencegah scroll global */
    }

    .main-container {
        padding: 10px;
        background-color: #f8f9fc;
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
        max-width: 800px; /* Dikecilkan sedikit agar lebih compact */
    }

    .card-header-custom {
        background: var(--primary-gradient);
        color: white;
        padding: 10px 20px;
        border-radius: 15px 15px 0 0 !important;
    }

    .form-label {
        font-weight: 600;
        color: #4e73df;
        font-size: 0.75rem; /* Font label dikecilkan */
        margin-bottom: 2px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 6px 10px;
        border: 1.5px solid #e3e6f0;
        font-size: 0.85rem;
    }

    /* Error Message Styling - Lebih kecil dan tidak memakan tempat */
    .small-error {
        font-size: 0.7rem;
        color: #e74a3b;
        margin-top: 2px;
        display: block;
    }

    .alert-compact {
        padding: 5px 15px;
        font-size: 0.75rem;
        margin-bottom: 10px;
        border-radius: 8px;
    }

    .mb-compact { margin-bottom: 8px !important; }

    .btn-save, .btn-back {
        border-radius: 8px;
        padding: 6px 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .btn-save { background: var(--primary-gradient); border: none; }
    .btn-back { background: #ffffff; color: #858796; }

    /* Agar input group icon tetap sinkron dengan ukuran input baru */
    .input-group-text { padding: 6px 10px; font-size: 0.85rem; }
</style>

<div class="main-container">
    <div class="card card-form">
        <div class="card-header-custom text-center">
            <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna</h6>
        </div>
        
        <div class="card-body p-3">
            {{-- Alert Ringkas (Hanya muncul jika ada error umum) --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-compact py-1 text-center">
                <i class="bi bi-exclamation-circle-fill"></i> Mohon periksa kembali data yang diinputkan.
            </div>
            @endif

            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama_user" class="form-control @error('nama_user') is-invalid @enderror" 
                                   value="{{ old('nama_user') }}" placeholder="Nama lengkap" required>
                        </div>
                        @error('nama_user') <span class="small-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" placeholder="email@mail.com" required>
                        </div>
                        @error('email') <span class="small-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" 
                                   value="{{ old('no_hp') }}" placeholder="0812xxx" required>
                        </div>
                        @error('no_hp') <span class="small-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Role Akses</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                            <select name="id_role" class="form-select @error('id_role') is-invalid @enderror" required>
                                <option value="" selected disabled>Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id_role }}" {{ old('id_role') == $role->id_role ? 'selected' : '' }}>
                                        {{ $role->nama_role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_role') <span class="small-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-12 mb-compact">
                        <label class="form-label">Alamat Tinggal</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                   value="{{ old('alamat') }}" placeholder="Alamat lengkap..." required>
                        </div>
                        @error('alamat') <span class="small-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-12 mb-2">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" id="passwordField" class="form-control @error('password') is-invalid @enderror" placeholder="********" required>
                            <span class="input-group-text" onclick="togglePasswordVisibility()" style="cursor: pointer; background: white;">
                                <i class="bi bi-eye-slash" id="eyeIcon"></i>
                            </span>
                        </div>
                        @error('password') <span class="small-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between border-top pt-2 mt-2">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-back border">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success btn-save text-white">
                        <i class="bi bi-check-circle"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }
</script>
@endsection