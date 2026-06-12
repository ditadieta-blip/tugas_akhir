@extends('layouts.home')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<nav class="custom-navbar">
    <div class="nav-container">
        <a class="nav-brand" href="#home">
            <span class="brand-accent">BSC</span>
        </a>
        <div class="nav-menu">
            <a class="nav-link" href="#home">Home</a>
            <a class="nav-link" href="#visi-misi">Visi Misi</a>
            <a class="nav-link" href="#keunggulan">Keunggulan</a>
            <a href="{{ route('login') }}" class="btn-register">Login</a>
        </div>
    </div>
</nav>

<section id="home" class="hero-section">
    <div class="hero-overlay">
        <div class="hero-content">
            <h1>Sehat, Bugar & Percaya Diri</h1>
            <p>Bergabunglah bersama komunitas senam terbaik dengan instruktur profesional secara mandiri dan alami.</p>
            <a href="{{ route('register') }}" class="btn-hero">
                Daftar Jadi Anggota <i class="bi bi-arrow-right-circle ms-2"></i>
            </a>
        </div>
    </div>
</section>

<section id="visi-misi" class="visi-section">
    <div class="visi-container">

        <h2 class="visi-title">Visi & Misi Komunitas</h2>

        <div class="visi-misi-row">

            <div class="visi-card">
                <div class="icon-wrapper bg-light-blue">
                    <i class="bi bi-eye text-primary"></i>
                </div>
                <h3 class="visi-heading">Visi</h3>
                <h4>"Making People Healthy"</h4>
                <p>
                    Mewujudkan masyarakat yang sehat secara alami melalui terapi kesehatan,
                    peremajaan tubuh, dan peningkatan kebugaran.
                </p>
            </div>

            <div class="misi-card">
                <div class="icon-wrapper bg-light-green">
                    <i class="bi bi-rocket-takeoff text-success"></i>
                </div>
                <h3 class="misi-heading">Misi</h3>

                <ul>
                    <li>
                        <strong>Terapi Sehat Tanpa Obat, Alat, & Ragat</strong><br>
                        Mengandalkan gerakan fisik sebagai terapi olah tubuh alami.
                    </li>

                    <li>
                        <strong>Pembangkitan Tenaga Titik Nol</strong><br>
                        Mengaktifkan energi tubuh melalui gerakan empet-empet dan jinjit untuk mencharge "aki manusia".
                    </li>

                    <li>
                        <strong>Peremajaan Tubuh</strong><br>
                        Memperbaiki fungsi organ melalui gerakan sederhana dan rileks.
                    </li>

                    <li>
                        <strong>Sosialisasi Kesehatan Mandiri</strong><br>
                        Mengajak masyarakat menjaga kesehatan secara rutin dan mandiri.
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>

<section id="keunggulan" class="features-section">
    <div class="visi-container">
        <h2 class="visi-title" style="margin-bottom: 40px;">Mengapa Memilih Kami?</h2>
        <div class="features-row">
            <div class="feature-card">
                <i class="bi bi-lightning-charge-fill text-warning mb-3 d-block fs-3"></i>
                <h5>Energi Titik Nol</h5>
                <p class="text-muted small">Optimalisasi energi tubuh internal lewat gerakan terukur.</p>
            </div>
            <div class="feature-card">
                <i class="bi bi-shield-check text-success mb-3 d-block fs-3"></i>
                <h5>Aman & Alami</h5>
                <p class="text-muted small">Gerakan relaksasi yang ramah untuk segala usia dan persendian.</p>
            </div>
            <div class="feature-card">
                <i class="bi bi-capsule-extinguisher text-danger mb-3 d-block fs-3"></i>
                <h5>Tanpa Ketergantungan Obat</h5>
                <p class="text-muted small">Berfokus penuh pada regenerasi organ tubuh secara natural.</p>
            </div>
        </div>
    </div>
</section>

<footer class="info-footer">
    <div class="info-container">
        <p><i class="bi bi-geo-alt-fill text-danger me-1"></i> Lokasi: Lapangan Desa Bangsongan</p>
        <p><i class="bi bi-calendar3 text-primary me-1"></i> Jadwal Senam Rutin: Selasa, Kamis, dan Sabtu</p>
    </div>
</footer>

<style>
/* CSS ROOT & UMUM */
:root {
    --primary-color: #2563eb;
    --success-color: #16a34a;
}

/* STYLING NAVBAR MODERN */
.custom-navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 1000;
    padding: 15px 5%;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand {
    font-size: 22px;
    font-weight: 800;
    text-decoration: none;
    letter-spacing: -0.5px;
}
.brand-accent { color: var(--primary-color); }
.brand-text { color: #1e293b; }

.nav-menu {
    display: flex;
    align-items: center;
    gap: 25px;
}

.nav-link {
    color: #475569;
    text-decoration: none;
    font-weight: 600;
    font-size: 15px;
    transition: color 0.2s;
}

.nav-link:hover {
    color: var(--primary-color);
}

.btn-login {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 700;
    font-size: 15px;
}

.btn-register {
    background: var(--primary-color);
    color: white !important;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    padding: 10px 22px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    transition: 0.3s;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
}

/* HERO SECTION */
.hero-section {
    height: 100vh;
    background: url('{{ asset('images/background.jpg') }}') center/cover no-repeat;
    position: relative;
    display: block;
}

.hero-overlay {
    background: rgba(0,0,0,0.6);
    width: 100%;
    height: 100%;
    display: block;
    padding-top: 38vh;
}

.hero-content {
    text-align: center;
    color: white;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-content h1 {
    font-size: 56px;
    font-weight: 800;
    letter-spacing: -1px;
    margin-bottom: 15px;
}

.hero-content p {
    margin: 20px 0 35px 0;
    font-size: 20px;
    opacity: 0.9;
}

.btn-hero {
    background: var(--primary-color);
    padding: 16px 45px;
    border-radius: 50px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    transition: 0.3s ease;
    box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
}

.btn-hero:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(37, 99, 235, 0.5);
    color: white;
    text-decoration: none;
}

/* VISI MISI */
.visi-section {
    padding: 100px 5% 60px 5%;
    background: #f8fafc;
}

.visi-container {
    max-width: 1200px;
    margin: auto;
}

.visi-title {
    text-align: center;
    font-size: 36px;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 60px;
    position: relative;
}

.visi-title::after {
    content: "";
    width: 60px;
    height: 4px;
    background: var(--primary-color);
    position: absolute;
    bottom: -15px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
}

/* DEKORASI ICON WRAPPER */
.icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 50px;
    margin: 0 auto 20px auto;
    text-align: center;
    line-height: 60px;
    font-size: 24px;
}

.bg-light-blue { background-color: #eff6ff; }
.bg-light-green { background-color: #f0fdf4; }

/* ROW 2 KOLOM */
.visi-misi-row {
    display: block;
    text-align: center;
}

/* CARD VISI MISI */
.visi-card,
.misi-card {
    width: 47%;
    display: inline-block;
    vertical-align: top;
    background: white;
    padding: 40px;
    margin: 0 1%;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    border: 1px solid #e2e8f0;
    transition: 0.3s ease;
    text-align: left;
}

.visi-card:hover,
.misi-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.07);
}

.visi-heading, .misi-heading {
    text-align: center;
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 15px;
}

.visi-heading { color: var(--primary-color); }
.misi-heading { color: var(--success-color); }

.visi-card h4 {
    text-align: center;
    font-size: 18px;
    font-style: italic;
    color: #475569;
    margin-bottom: 25px;
}

.visi-card p {
    text-align: center;
    font-size: 15px;
    line-height: 1.8;
    color: #475569;
}

.misi-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.misi-card li {
    font-size: 14px;
    margin-bottom: 18px;
    line-height: 1.7;
    padding-left: 28px;
    position: relative;
    color: #475569;
}

.misi-card li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: var(--success-color);
    font-weight: bold;
    font-size: 16px;
}

/* KEUNGGULAN SECTION */
.features-section {
    padding: 60px 5% 100px 5%;
    background: #ffffff;
}

.features-row {
    text-align: center;
    margin-top: 40px;
}

.feature-card {
    width: 29%;
    display: inline-block;
    vertical-align: top;
    margin: 0 1.5%;
    background: #f8fafc;
    padding: 30px 20px;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
}

.feature-card h5 {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
}

/* FOOTER STYLING */
.info-footer {
    background: #f1f5f9;
    padding: 25px 5%;
    border-top: 1px solid #e2e8f0;
    text-align: center;
}

.info-container {
    max-width: 1200px;
    margin: 0 auto;
}

.info-footer p {
    margin: 4px 0;
    color: #64748b;
    font-size: 13.5px;
    font-weight: 500;
    display: inline-block;
    padding: 0 15px;
}

/* RESPONSIVE UNTUK SMARTPHONE */
@media (max-width: 768px) {
    .custom-navbar {
        padding: 15px 20px;
    }
    .nav-menu {
        display: none; 
    }

    .hero-content h1 {
        font-size: 36px;
    }
    
    .hero-content p {
        font-size: 16px;
    }

    .visi-card, .misi-card {
        width: 100%;
        margin: 0 0 30px 0;
        padding: 30px 20px;
    }

    .feature-card {
        width: 100%;
        margin: 0 0 20px 0;
    }

    .info-footer p {
        display: block;
        padding: 5px 0;
    }
}
</style>

@endsection