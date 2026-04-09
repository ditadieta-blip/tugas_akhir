@extends('layouts.main')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    body {
        overflow: hidden; /* Mencegah scroll pada body */
    }

    .main-container {
        padding: 15px;
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
        font-weight: 600;
        color: #4e73df;
        font-size: 0.85rem;
        margin-bottom: 4px;
    }

    .input-group-text {
        background-color: #f8f9fc;
        border: 1.5px solid #e3e6f0;
        color: #b7b9cc;
        padding: 8px 12px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 8px 12px;
        border: 1.5px solid #e3e6f0;
        font-size: 0.9rem;
    }

    /* Input Group Styling */
    .has-icon .input-left { border-radius: 10px 0 0 10px; border-right: none; }
    .has-icon .form-control { border-left: none; border-radius: 0 10px 10px 0; }
    
    .password-group .form-control { border-right: none; border-radius: 0; }
    .password-group .toggle-password { 
        border-radius: 0 10px 10px 0; 
        border-left: none; 
        cursor: pointer;
        background: white;
    }

    .mb-compact { margin-bottom: 12px !important; }

    .btn-update {
        background: var(--primary-gradient);
        border: none;
        border-radius: 10px;
        padding: 8px 25px;
        font-weight: 600;
        font-size: 0.9rem;
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
        padding: 8px 25px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    @media (max-height: 700px) {
        .card-header-custom p { display: none; }
        .mb-compact { margin-bottom: 8px !important; }
    }
</style>

<div class="main-container">
    <div class="card card-form">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Data Pengguna</h5>
            <p class="small mb-0 opacity-75 d-none d-sm-block">Mengubah informasi untuk pengguna: <strong>{{ $user->nama_user }}</strong></p>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('admin.user.update', $user->id_user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama_user" class="form-control" value="{{ $user->nama_user }}" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Alamat Email</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Nomor WhatsApp</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="no_hp" class="form-control" value="{{ $user->no_hp }}" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-compact">
                        <label class="form-label">Role Akses</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left"><i class="bi bi-shield-lock"></i></span>
                            <select name="id_role" class="form-select" style="border-left: none;" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id_role }}" {{ $user->id_role == $role->id_role ? 'selected' : '' }}>
                                        {{ $role->nama_role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mb-compact">
                        <label class="form-label">Alamat Tinggal</label>
                        <div class="input-group has-icon">
                            <span class="input-group-text input-left"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="alamat" class="form-control" value="{{ $user->alamat }}" required>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label">Password</label>
                            <small class="text-danger" style="font-size: 0.7rem;">*Kosongkan jika tidak ingin mengubah</small>
                        </div>
                        <div class="input-group has-icon password-group">
                            <span class="input-group-text input-left"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" id="passwordField" class="form-control" placeholder="Masukkan password baru jika perlu">
                            <span class="input-group-text toggle-password" onclick="togglePasswordVisibility()">
                                <i class="bi bi-eye-slash" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between border-top pt-3 mt-2">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-back border">
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

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        }
    }
</script>
@endsection