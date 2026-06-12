@extends('layouts.anggota')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .topup-wrapper {
        min-height: calc(100vh - 120px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .topup-card {
        max-width: 520px;
        width: 100%;
        background: #ffffff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.05);
        padding: 35px 40px !important;
        position: relative;
    }

    .saldo-info-box {
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .saldo-info-box .title {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        margin: 0;
    }

    .saldo-info-box .amount {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .input-group-text-custom {
        background-color: #f8fafc;
        border-right: none;
        color: #64748b;
        font-weight: 600;
        border-radius: 10px 0 0 10px;
        padding-left: 18px;
        padding-right: 12px;
    }

    .form-control-custom {
        border-left: none;
        border-radius: 0 10px 10px 0;
        padding: 14px 18px;
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
    }

    .form-control-custom:focus {
        border-color: #ced4da;
        box-shadow: none;
    }

    .input-group-custom:focus-within .input-group-text-custom,
    .input-group-custom:focus-within .form-control-custom {
        border-color: #2563eb;
    }

    .live-separator {
        font-size: 13px;
        font-weight: 600;
        color: #2563eb;
        background-color: #eff6ff;
        display: inline-block;
        padding: 4px 12px;
        border-radius: 6px;
        margin-top: 8px;
        min-height: 25px;
    }

    .btn-topup-submit {
        background-color: #2563eb;
        border: none;
        padding: 14px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.2s ease;
        margin-top: 10px;
    }

    .btn-topup-submit:hover {
        background-color: #1d4ed8;
        transform: translateY(-1px);
    }

    .btn-back-link {
        color: #64748b;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 25px;
        transition: color 0.2s;
    }

    .btn-back-link:hover {
        color: #1e293b;
    }
</style>

<div class="topup-wrapper">
    <div class="topup-card">
        
        <a href="{{ route('anggota.jsenam.index') }}" class="btn-back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali
        </a>

        <h4 class="fw-bold text-dark mb-1">Isi Saldo</h4>
        <p class="text-muted small mb-4">Tambahkan saldo Anda dengan metode pembayaran Midtrans</p>

        <div class="saldo-info-box">
            <div>
                <p class="title">Saldo Anda Saat Ini</p>
            </div>
            <div>
                <p class="amount">Rp {{ number_format(auth()->user()->saldo_iuran ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="mb-4">
            <label for="nominal" class="form-label small fw-semibold text-secondary mb-2">Nominal Top Up Baru</label>
            <div class="input-group input-group-custom">
                <span class="input-group-text input-group-text-custom">Rp</span>
                <input type="text" id="nominal_mask" class="form-control form-control-custom" placeholder="Contoh: 50.000">
            </div>
            <div id="separator_preview" class="live-separator" style="display: none;"></div>
        </div>

        <button class="btn btn-primary w-100 btn-topup-submit" id="btnTopup">
            Bayar
        </button>

    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
const inputNominal = document.getElementById('nominal_mask');
const previewSeparator = document.getElementById('separator_preview');

inputNominal.addEventListener('input', function(e) {
    let value = this.value.replace(/[^,\d]/g, '').toString();
    let split = value.split(',');
    let sisa  = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    this.value = rupiah;

    if (value && value > 0) {
        previewSeparator.style.display = 'inline-block';
        previewSeparator.innerText = 'Konfirmasi Nominal: Rp ' + rupiah;
    } else {
        previewSeparator.style.display = 'none';
    }
});

document.getElementById('btnTopup').addEventListener('click', function () {
    let nominalRaw = inputNominal.value.replace(/\./g, '');
    let nominal = parseInt(nominalRaw);
    
    if (!nominal || nominal <= 0) {
        Swal.fire({ icon: 'warning', title: 'Nominal Tidak Valid', text: 'Silakan masukkan jumlah nominal top up yang benar.', confirmButtonColor: '#2563eb' });
        return;
    }

    Swal.fire({ title: 'Memproses...', text: 'Sedang membuat token pembayaran', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch("{{ route('anggota.saldo.topup') }}", {
        method: "POST",
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ nominal: nominal })
    })
    .then(res => res.json())
    .then(data => {
        Swal.close();
        snap.pay(data.snap_token, {
            onSuccess: function(result){
                Swal.fire({ icon: 'success', title: 'Top Up Berhasil!', text: 'Saldo Anda berhasil ditambahkan.', confirmButtonColor: '#2563eb' }).then(() => { location.reload(); });
            },
            onPending: function(result){
                Swal.fire({ icon: 'info', title: 'Menunggu Pembayaran', text: 'Silakan selesaikan transaksi Anda sesuai instruksi Midtrans.', confirmButtonColor: '#2563eb' });
            },
            onError: function(result){
                Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', text: 'Transaksi dibatalkan atau terjadi gangguan.', confirmButtonColor: '#2563eb' });
            }
        });
    })
    .catch(error => {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Sistem Sibuk', text: 'Gagal terhubung ke server. Coba beberapa saat lagi.', confirmButtonColor: '#2563eb' });
    });
});
</script>
@endsection