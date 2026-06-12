@extends('layouts.main')

@section('content')
<!-- Tambahkan link ikon jika belum ada di layout utama -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --success-gradient: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        --dark-blue: #2e59d9;
    }

    .main-wrapper {
        padding: 20px;
        background-color: #f8f9fc;
        min-height: 100vh;
    }

    /* Responsivitas Header */
    .header-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 40px;
    }

    @media (min-width: 768px) {
        .header-container {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }

    .page-title {
        font-weight: 800;
        color: var(--dark-blue);
        letter-spacing: -0.5px;
        margin-bottom: 0;
    }

    /* Button Back Style */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #858796;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        padding: 8px 16px;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        width: fit-content;
    }

    .btn-back:hover {
        color: var(--dark-blue);
        transform: translateX(-5px);
        background: #f1f3f9;
    }

    /* Modern Card Design */
    .card-custom {
        border: none;
        border-radius: 24px;
        background: #ffffff;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .card-custom:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08) !important;
    }

    /* Icon Styling */
    .icon-box {
        width: 80px;
        height: 80px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2.2rem;
    }

    .bg-light-primary { background: #eef2ff; color: #4e73df; }
    .bg-light-success { background: #e6fffa; color: #1cc88a; }

    /* Button Modern */
    .btn-modern {
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: none;
        transition: 0.3s;
    }

    .btn-wisata { background: var(--primary-gradient); color: white; }
    .btn-iuran { background: var(--success-gradient); color: white; }

    .btn-modern:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        color: white;
    }

    /* Decorative background shape */
    .card-custom::after {
        content: "";
        position: absolute;
        top: -30px;
        right: -30px;
        width: 100px;
        height: 100px;
        background: rgba(0,0,0,0.02);
        border-radius: 50%;
    }
</style>

<div class="main-wrapper">
    <div class="container-fluid container-lg">
        
        {{-- Header & Back Button --}}
        <div class="header-container">
            <div class="order-2 order-md-1">
                <h3 class="page-title">Transaksi Pembayaran</h3>
                <p class="text-muted small mb-0">Pilih kategori pembayaran.</p>
            </div>
            <div class="order-1 order-md-2">
            </div>
        </div>

        <div class="row g-4">
            {{-- Pembayaran Wisata --}}
            <div class="col-12 col-md-6">
                <div class="card card-custom shadow-sm p-4 p-lg-5 text-center">
                    <div class="icon-box bg-light-primary">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Pembayaran Wisata</h4>
                    <p class="text-muted mb-4 small">
                        Kelola administrasi pembayaran wisata, cicilan, dan konfirmasi pelunasan anggota.
                    </p>
                    <a href="{{ route('admin.wisata.index') }}" class="btn btn-modern btn-wisata">
                        Pembayaran Wisata
                    </a>
                </div>
            </div>

            {{-- Pembayaran Iuran --}}
            <div class="col-12 col-md-6">
                <div class="card card-custom shadow-sm p-4 p-lg-5 text-center">
                    <div class="icon-box bg-light-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Pembayaran Iuran</h4>
                    <p class="text-muted mb-4 small">
                        Kelola pembayaran tunai iuran senam anggota.
                    </p>
                    <a href="{{ route('admin.tunai.index') }}" class="btn btn-modern btn-iuran">
                        Pembayaran Iuran
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection