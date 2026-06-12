@extends('layouts.anggota')

@section('content')

<style>
:root{
    --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    --soft-bg: #f8f9fc;
    --text-dark: #3a3b45;
    --text-muted: #858796;
}

body, html {
    margin: 0;
    min-height: 100%;
    background-color: var(--soft-bg);
}

.main-container {
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 70px);
    position: relative;
}

/* Tombol Kembali di Pojok Kiri Atas */
.top-left-back {
    position: absolute;
    top: 20px;
    left: 20px;
}

.btn-back-link {
    color: var(--text-muted);
    text-decoration: none;
    font-size: .9rem;
    font-weight: 600;
    transition: .3s;
    display: flex;
    align-items: center;
    gap: 5px;
}

.btn-back-link:hover {
    color: var(--primary-gradient);
}

.card-modern {
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,.05);
    width: 100%;
    /* Dibuat lebih lebar agar tidak terlalu sempit */
    max-width: 600px; 
    border: none;
}

.info-group {
    background: #fcfdff;
    border: 1px solid #edf0f5;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.info-item:last-child { margin-bottom: 0; }

.label { font-size: .9rem; color: var(--text-muted); }
.value { font-weight: 600; font-size: .9rem; color: var(--text-dark); }

.price-box {
    background: #f4f7ff;
    border: 1.5px dashed #d1d9e6;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    text-align: center;
}

.total-amount {
    font-size: 2rem;
    font-weight: 800;
    color: #2e59d9;
}

.saldo-box {
    background: #fff7e6;
    border: 1px solid #ffe58f;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: center;
}

.saldo-label { font-size: .8rem; color: #ad6800; font-weight: 600; }
.saldo-value { font-size: 1.1rem; font-weight: 700; color: #d48806; }

.btn-pay {
    background: var(--primary-gradient);
    border: none;
    border-radius: 10px;
    padding: 12px 20px;
    font-weight: 700;
    color: white;
    transition: .3s;
}

.btn-pay:hover { opacity: .9; }

/* State Abu-abu untuk tombol setelah bayar/berhasil */
.btn-paid-status {
    background: #d1d3e2 !important;
    color: #5a5c69 !important;
    cursor: not-allowed;
    border: none;
}

.badge-custom {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: .75rem;
    font-weight: 700;
}

@media (max-width: 576px) {
    .card-modern { padding: 20px; }
    .top-left-back { top: 10px; left: 10px; }
}
</style>

<div class="main-container">
    
    <!-- Tombol Kembali di sisi kiri atas -->
    <div class="top-left-back">
        <a href="{{ route('anggota.jsenam.index') }}" class="btn-back-link">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-modern">
        @php
            $saldoUser = auth()->user()->saldo_iuran ?? 0;
            $cukupSaldo = $saldoUser >= 2500;
        @endphp

        <div class="text-center mb-4">
            <h4 class="fw-bold">Rincian Pembayaran</h4>
        </div>

        <div class="info-group">
            <div class="info-item">
                <span class="label">Tempat</span>
                <span class="value">{{ $senam->tempat_senam }}</span>
            </div>
            <div class="info-item">
                <span class="label">Instruktur</span>
                <span class="value">{{ $senam->user->nama_user ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="label">Jadwal</span>
                <span class="value">
                    {{ \Carbon\Carbon::parse($senam->tanggal)->translatedFormat('d M Y') }}
                </span>
            </div>
            <div class="info-item mt-2">
                <span class="label">Status Kehadiran</span>
                @if($absen && $absen->status == 'hadir')
                    <span class="badge-custom bg-success text-white">HADIR</span>
                @else
                    <span class="badge-custom bg-danger text-white">TIDAK HADIR</span>
                @endif
            </div>
        </div>

        <div class="price-box">
            <div class="label">Total Iuran</div>
            <div class="total-amount">Rp 2.500</div>
        </div>

        <div class="saldo-box">
            <div class="saldo-label">Sisa Saldo Anda</div>
            <div class="saldo-value">Rp {{ number_format($saldoUser,0,',','.') }}</div>
        </div>

        {{-- AREA TOMBOL --}}
        <div class="text-center">
            @if($absen && $absen->status == 'hadir')
                @if($cukupSaldo)
                    <button class="btn btn-paid-status w-100 py-3" disabled>
                        Otomatis Potong Saldo
                    </button>
                    <small class="text-muted d-block mt-2">Iuran akan otomatis dibayar menggunakan saldo</small>
                @else
                    <button id="pay-button" class="btn btn-pay w-100 py-3 shadow-sm">
                        Bayar via Midtrans
                    </button>
                    @if($saldoUser > 0)
                        <small class="text-muted d-block mt-2">Saldo akan digunakan terlebih dahulu</small>
                    @endif
                @endif
            @else
                <button class="btn btn-secondary w-100 py-3 disabled" style="border-radius:10px;">
                    Belum Absen
                </button>
            @endif
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
document.getElementById('pay-button')?.addEventListener('click', function(){
    const btn = this;
    
    // Disable tombol agar tidak double click, tapi teks tetap sama (tidak Loading)
    btn.disabled = true;

    fetch("{{ route('anggota.iuran.bayar', $senam->id_senam) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.error){
            alert(data.error);
            btn.disabled = false;
            return;
        }

        snap.pay(data.snap_token, {
            onSuccess: function(result){

                window.location.href =
                    "{{ route('anggota.jsenam.index') }}";

            },
            onPending: function(result){
                location.reload();
            },
            onError: function(result){
                alert("Pembayaran gagal");
                btn.disabled = false;
            },
            onClose: function(){
                btn.disabled = false;
            }
        });
    })
    .catch(err => {
        btn.disabled = false;
    });
});
</script>

@endsection