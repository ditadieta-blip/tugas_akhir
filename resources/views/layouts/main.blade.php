<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SIS BSC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --sidebar-bg: #2b59c3;
        --sidebar-hover: rgba(255, 255, 255, 0.1); 
        --text-muted: rgba(255, 255, 255, 0.6);
    }

    body {
        background: #f1f5f9;
        font-family: 'Inter', sans-serif;
        font-size: 0.875rem; 
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
        width: 240px; 
        min-height: 100vh;
        background-color: var(--sidebar-bg);
        padding: 25px 14px;
        position: fixed;
        display: flex;
        flex-direction: column;
        z-index: 1000;
        height: 100vh;
        overflow-y: auto; 
        transition: all 0.3s ease;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
    }

    .brand-section {
        padding: 10px 0 30px 0;
        display: flex;
        justify-content: center;
        width: 100%;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 20px;
    }

    .brand-name {
        font-size: 1.2rem; 
        font-weight: 800;
        color: white; 
        letter-spacing: 2px;
        margin: 0;
        text-align: center;
    }

    .nav-label {
        font-size: 0.65rem; 
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 700;
        letter-spacing: 1px;
        margin: 15px 12px 8px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        padding: 10px 14px; 
        margin-bottom: 4px;
        text-decoration: none;
        color: rgba(255, 255, 255, 0.8); 
        border-radius: 10px;
        transition: all 0.2s ease;
        font-size: 0.8rem; 
        font-weight: 500;
    }

    .sidebar a i {
        font-size: 1.1rem;
        margin-right: 12px;
    }

    .sidebar a:hover {
        background: var(--sidebar-hover);
        color: white;
        transform: translateX(4px);
    }

    .sidebar a.active {
        background: white;
        color: var(--sidebar-bg);
        font-weight: 600;
    }

    .sidebar a.disabled {
        opacity: 0.4;
        pointer-events: none;
    }

    .sidebar-footer {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-logout {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .btn-logout:hover {
        color: white !important;
    }

    /* ===== HEADER FIXED ===== */
    .topbar {
        position: fixed;
        top: 0;
        left: 240px;
        width: calc(100% - 240px);
        height: 65px; 
        background: #ffffff; 
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        z-index: 999;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #334155; 
        font-weight: 600;
    }

    .menu-toggle {
        display: none; /* Sembunyikan default di desktop */
        font-size: 1.5rem;
        cursor: pointer;
        padding: 4px 8px;
        background: #f1f5f9;
        border-radius: 8px;
        color: #334155;
    }

    .topbar-title {
        font-size: 0.95rem;
        letter-spacing: 1px;
    }

    .profile-topbar {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #334155; 
    }

    .profile-topbar span {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .profile-topbar .profile-img {
        width: 38px;
        height: 38px;
        border-radius: 10px; 
        background: #f1f5f9;
        color: #2b59c3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border: 1px solid #e2e8f0;
    }

    /* ===== MODAL MODERN STYLE ===== */
    .modal-content {
        border-radius: 28px;
        border: none;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .modal-header-custom {
        padding: 20px 30px;
        border-bottom: 1px solid #f1f5f9;
    }

    .modal-body {
        padding: 30px !important;
    }

    .avatar-upload-wrapper {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto;
    }

    .profile-avatar-modal {
        width: 100%;
        height: 100%;
        border-radius: 40px; 
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 700;
        color: #2b59c3;
        border: 4px solid #fff;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        object-fit: cover;
    }

    .btn-camera-float {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #2b59c3;
        color: white;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #fff;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-camera-float:hover {
        background: #1e44a3;
        transform: scale(1.1);
    }

    .modal .form-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 8px;
    }

    .modal .form-control {
        border-radius: 14px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .modal .form-control:focus {
        background-color: #fff;
        border-color: #2b59c3;
        box-shadow: 0 0 0 4px rgba(43, 89, 195, 0.1);
    }

    .invalid-feedback {
        font-size: 0.75rem;
        font-weight: 500;
        margin-left: 5px;
    }

    .modal-footer-custom {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .btn-save-modal {
        background: #2b59c3;
        color: white;
        border: none;
        border-radius: 14px;
        padding: 12px;
        font-weight: 600;
        flex: 2;
    }

    .btn-cancel-modal {
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 14px;
        padding: 12px;
        font-weight: 600;
        flex: 1;
    }

    /* ===== EFEK INTERAKTIF BUTTON ===== */
    .btn-save-modal, .btn-cancel-modal, .btn-primary, .btn-logout {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-save-modal:hover {
        background: #1e44a3; 
        transform: translateY(-2px); 
        box-shadow: 0 8px 15px rgba(43, 89, 195, 0.3); 
        letter-spacing: 0.5px; 
    }

    .btn-save-modal:active {
        transform: translateY(0);
        box-shadow: 0 4px 6px rgba(43, 89, 195, 0.2);
    }

    .btn-cancel-modal:hover {
        background: #e2e8f0;
        color: #334155;
        transform: translateY(-2px);
    }

    .sidebar a:hover {
        background: var(--sidebar-hover);
        color: white;
        padding-left: 20px; 
    }

    /* ===== CONTENT ===== */
    .main-content {
        margin-left: 240px;
        width: calc(100% - 240px);
        padding: 80px 25px 25px; 
        transition: all 0.3s ease;
    }

    .content-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px;
        min-height: 85vh;
    }

    .collapse .bg-light {
        background-color: rgba(255, 255, 255, 0.05) !important;
        margin: 0 10px !important;
        border-radius: 12px !important;
    }

    .dropdown-item {
        color: rgba(255, 255, 255, 0.7) !important;
        padding: 8px 20px !important;
        border-radius: 8px;
    }

    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }

    .nav-link-collapse[aria-expanded="true"] .bi-chevron-down {
        transform: rotate(180deg);
    }
    .bi-chevron-down {
        transition: transform 0.3s ease;
    }

    /* ===== PERBAIKAN RESPONSIVE SIDEBAR (MOBILE) ===== */
    @media (max-width: 768px) {
        .sidebar {
            left: -240px; /* Sembunyikan sidebar ke kiri luar layar HP */
        }
        .sidebar.active {
            left: 0; /* Munculkan kembali saat toggle diklik */
        }
        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
        }
        .topbar {
            left: 0 !important;
            width: 100% !important;
            padding: 0 20px;
        }
        .menu-toggle {
            display: block !important; /* Tampilkan tombol hamburger hanya di layar HP */
        }
    }
</style>
</head>
<body>

<div class="d-flex">
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="brand-section text-center">
            <h5 class="brand-name text-uppercase">SIS BSC</h5>
        </div>
        
        <div class="nav-label">Admin Menu</div>
        
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.user.index') }}"
           class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Kelola Pengguna</span>
        </a>

        <a href="{{ route('admin.voting-wisata.index') }}"
        class="{{ request()->routeIs('admin.voting-wisata.*') ? 'active' : '' }}">
            <i class="bi bi-patch-check-fill"></i>
            <span>Kelola Voting Wisata</span>
        </a>

        <a href="{{ route('admin.jwisata.index') }}"
           class="{{ request()->routeIs('admin.jwisata.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event-fill"></i>
            <span>Jadwal Wisata</span>
        </a>

        <a href="{{ route('admin.pendaftaran.index') }}"
           class="{{ request()->routeIs('admin.pendaftaran.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check-fill"></i>
            <span>Pendaftar Wisata</span>
        </a>
        <a href="{{ route('admin.transaksi.index') }}"
        class="{{ request()->routeIs('admin.transaksi.*') || request()->routeIs('admin.wisata.*') || request()->routeIs('admin.tunai.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card-fill"></i>
            <span>Transaksi Pembayaran</span>
        </a>

        <!-- MENU LAPORAN -->
        <div class="nav-item">
            <a href="#collapseLaporan" 
            class="nav-link-collapse d-flex align-items-center justify-content-between {{ request()->is('admin/laporan*') ? 'active' : '' }}" 
            data-bs-toggle="collapse" 
            role="button" 
            aria-expanded="{{ request()->is('admin/laporan*') ? 'true' : 'false' }}">

                <div>
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span>Laporan</span>
                </div>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div id="collapseLaporan" class="collapse {{ request()->is('admin/laporan*') ? 'show' : '' }}">
                <div class="py-2">
                    {{-- PEMASUKAN --}}
                    <a class="dropdown-item small {{ request()->routeIs('admin.laporan.keuangan') ? 'text-white fw-bold' : '' }}" 
                    href="{{ route('admin.laporan.keuangan') }}">
                        Pemasukan
                    </a>

                    {{-- KEAKTIFAN --}}
                    <a class="dropdown-item small {{ request()->routeIs('admin.laporan.keaktifan') ? 'text-white fw-bold' : '' }}" 
                    href="{{ route('admin.laporan.keaktifan') }}">
                        Keaktifan Anggota
                    </a>

                    {{-- TAGIHAN --}}
                    <a class="dropdown-item small {{ request()->routeIs('admin.laporan.tagihan') ? 'text-white fw-bold' : '' }}" 
                    href="{{ route('admin.laporan.tagihan') }}">
                        Tagihan Iuran
                    </a>
                </div>
            </div>
        </div>

        <!-- FOOTER LOGOUT -->
        <div class="sidebar-footer mt-auto mb-4">
            <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i> 
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <main class="main-content">
        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <!-- Hamburger menu aktif otomatis di mobile -->
                <i class="bi bi-list menu-toggle" id="toggleMenu"></i>
            </div>

            <div class="profile-topbar">
                <span>Hi, {{ auth()->user()->nama_user }}</span>
                <div class="profile-img" data-bs-toggle="modal" data-bs-target="#modalProfil" style="cursor:pointer; overflow:hidden;">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/foto/' . auth()->user()->foto) }}" style="width:38px; height:38px; border-radius:10px; object-fit:cover;">
                    @else
                        {{ strtoupper(substr(auth()->user()->nama_user, 0, 1)) }}
                    @endif
                </div>
            </div>
        </div>

        <div class="content-card shadow-sm">
            @yield('content')
        </div>
    </main>
</div>

<!-- MODAL LOGOUT -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="max-width: 320px; margin: auto; border-radius: 16px; border:none;">
            <div class="modal-body text-center p-4">
                <div class="mb-3 text-warning">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 2.5rem;"></i>
                </div>
                <h6 class="fw-bold mb-1">Konfirmasi Logout</h6>
                <p class="text-muted" style="font-size: 0.75rem;">Apakah anda yakin ingin mengakhiri sesi ini?</p>

                <div class="d-grid gap-2 mt-4">
                    <form action="{{ route('logout') }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-primary py-2 fw-semibold" style="border-radius: 8px; font-size: 0.85rem; background-color: #2b59c3; border: none;">
                            Ya, Keluar
                        </button>
                    </form>
                    <button type="button" class="btn btn-light py-2 fw-semibold text-muted" 
                            data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function previewFoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e){
                const previewImg = document.getElementById('preview-avatar');
                const placeholder = document.getElementById('preview-placeholder');
                
                if (previewImg) {
                    previewImg.src = e.target.result;
                } else if (placeholder) {
                    const newImg = document.createElement('img');
                    newImg.id = 'preview-avatar';
                    newImg.className = 'profile-avatar-modal';
                    newImg.src = e.target.result;
                    placeholder.replaceWith(newImg);
                }
            }
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->profil->any() || session('success_profil'))
            var myModal = new bootstrap.Modal(document.getElementById('modalProfil'));
            myModal.show();
        @endif
    });
</script>

<div class="modal fade" id="modalProfil" tabindex="-1" aria-labelledby="modalProfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-0">
                        <div class="col-md-4 bg-light p-5 text-center d-flex flex-column justify-content-center border-end">
                            <div class="avatar-upload-wrapper mb-3">
                                @if(auth()->user()->foto)
                                    <img id="preview-avatar" class="profile-avatar-modal" 
                                         src="{{ asset('storage/foto/' . auth()->user()->foto) }}">
                                @else
                                    <div id="preview-placeholder" class="profile-avatar-modal">
                                        {{ strtoupper(substr(auth()->user()->nama_user, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <label for="foto-input" class="btn-camera-float">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" name="foto" id="foto-input" hidden onchange="previewFoto(event)">
                            </div>
                            <h5 class="fw-bold mb-1">{{ auth()->user()->nama_user }}</h5>
                            <p class="text-muted small mb-3">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="col-md-8 p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold m-0" id="modalProfilLabel">Edit Profil</h5>
                            </div>
                            @if(session('success_profil'))
                                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" style="border-radius: 12px; font-size: 0.85rem;">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <div>{{ session('success_profil') }}</div>
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_user" class="form-control @error('nama_user', 'profil') is-invalid @enderror"
                                           value="{{ old('nama_user', auth()->user()->nama_user) }}" required>
                                    @error('nama_user', 'profil')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @error('email', 'profil') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email', 'profil')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nomor WhatsApp</label>
                                    <input type="text" name="no_hp" class="form-control @error('no_hp', 'profil') is-invalid @enderror"
                                           value="{{ old('no_hp', auth()->user()->no_hp) }}" placeholder="08xxxx">
                                    @error('no_hp', 'profil')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" name="alamat" class="form-control @error('alamat', 'profil') is-invalid @enderror"
                                        value="{{ old('alamat', auth()->user()->alamat) }}">
                                    @error('alamat', 'profil')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <hr class="my-2 opacity-50">
                                    <label class="form-label">Ganti Password (Opsional)</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password', 'profil') is-invalid @enderror"
                                            placeholder="Masukkan password baru jika ingin diubah">
                                        <button type="button" class="btn btn-outline-secondary" onclick="lihatPassword()">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('password', 'profil')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer-custom">
                                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn-save-modal shadow-sm">
                                    <i class="bi bi-shield-check me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Logic Toggle Sidebar Mobile
    document.getElementById('toggleMenu').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    // Menutup sidebar jika klik di luar area sidebar saat mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleMenu');
        if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target) && window.innerWidth <= 768) {
            sidebar.classList.remove('active');
        }
    });
</script>
<script>
function lihatPassword() {
    let input = document.getElementById("password");
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>
</body>
</html>