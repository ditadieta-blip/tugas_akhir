@extends('layouts.home')

@section('content')

<!-- HERO SECTION -->
<section id="home" class="hero-section">
    <div class="hero-overlay">
        <div class="hero-content">
            <h1>Sehat, Bugar & Percaya Diri</h1>
            <p>Bergabunglah bersama komunitas senam terbaik dengan instruktur profesional.</p>
            <a href="{{ route('register') }}" class="btn-hero">Daftar</a>
        </div>
    </div>
</section>

<!-- VISI MISI SECTION -->
<section id="visi-misi" class="visi-section">
    <div class="visi-container">

        <h2 class="visi-title">Visi & Misi</h2>

        <div class="visi-misi-row">

            <!-- VISI -->
            <div class="visi-card">
                <h3 class="visi-heading">Visi</h3>
                <h4>"Making People Healthy"</h4>
                <p>
                    Mewujudkan masyarakat yang sehat secara alami melalui terapi kesehatan,
                    peremajaan tubuh, dan peningkatan kebugaran.
                </p>
            </div>

            <!-- MISI -->
            <div class="misi-card">
                <h3 class="misi-heading">Misi</h3>

                <ul>
                    <li>
                        <strong>Terapi Sehat Tanpa Obat, Tanpa Alat, Tanpa Ragat</strong><br>
                        Mengandalkan gerakan fisik sebagai terapi olah tubuh alami.
                    </li>

                    <li>
                        <strong>Pembangkitan Tenaga Titik Nol</strong><br>
                        Mengaktifkan energi tubuh melalui gerakan empet-empet dan jinjit
                        untuk mencharge "aki manusia".
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

<style>
/* HERO */
.hero-section {
    height: 100vh;
    background: url('{{ asset('images/background.jpg') }}') center/cover no-repeat;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-overlay {
    background: rgba(0,0,0,0.55);
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-content {
    text-align: center;
    color: white;
}

.hero-content h1 {
    font-size: 52px;
    font-weight: 700;
}

.hero-content p {
    margin: 20px 0;
    font-size: 18px;
}

.btn-hero {
    background: #2563eb;
    padding: 14px 40px;
    border-radius: 50px;
    color: white;
    text-decoration: none;
}
/* VISI MISI */
.visi-section {
    padding: 120px 5%;
    background: #f8fafc;
}

.visi-container {
    max-width: 1200px;
    margin: auto;
}

.visi-title {
    text-align: center;
    font-size: 40px;
    font-weight: 700;
    margin-bottom: 70px;
}

/* ROW 2 KOLOM */
.visi-misi-row {
    display: flex;
    gap: 40px;
}

/* CARD */
.visi-card,
.misi-card {
    flex: 1;
    background: white;
    padding: 50px 60px;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    transition: 0.3s ease;
}

.visi-card:hover,
.misi-card:hover {
    transform: translateY(-6px);
}

/* JUDUL WARNA */
.visi-heading {
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    color: #2563eb;
    margin-bottom: 15px;
}

.misi-heading {
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    color: #16a34a;
    margin-bottom: 20px;
}

/* SUBTITLE */
.visi-card h4 {
    text-align: center;
    font-size: 20px;
    font-style: italic;
    margin-bottom: 25px;
}

/* ISI LEBIH KECIL */
.visi-card p {
    text-align: center;
    font-size: 15px;
    line-height: 1.8;
}

.misi-card ul {
    list-style: none;
    padding: 0;
}

.misi-card li {
    font-size: 14px;
    margin-bottom: 20px;
    line-height: 1.7;
    padding-left: 25px;
    position: relative;
}

.misi-card li::before {
    content: "✔";
    position: absolute;
    left: 0;
    color: #16a34a;
    font-weight: bold;
}

/* RESPONSIVE HP */
@media (max-width: 768px) {
    .visi-misi-row {
        flex-direction: column;
    }

    .visi-card,
    .misi-card {
        padding: 40px 30px;
    }

    .visi-title {
        font-size: 30px;
    }
}


</style>

@endsection
