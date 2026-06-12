@extends('layouts.anggota')

@section('content')
@php
    $totalTagihan = $pendaftaran->jwisata->biaya_wisata ?? 0;
    
    // Pastikan 'cicilan' masuk dalam hitungan terbayar
    $pembayaranSukses = $pendaftaran->pembayaranWisata->whereIn('status', ['lunas','success','settlement','cicilan']);
    $totalTerbayar = $pembayaranSukses->sum('jumlah_bayar');
    $sisaTagihan = max(0, $totalTagihan - $totalTerbayar);

    $riwayatSemua = $pendaftaran->pembayaranWisata->sortByDesc('created_at');
@endphp

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --dark-blue: #2c3e50; /* Warna Biru Tua Midnight */
        --soft-bg: #f8f9fc;
    }

    .main-container {
        padding: 20px 15px;
        background-color: var(--soft-bg);
        min-height: calc(100vh - 70px);
    }

    .card-modern {
        background: #fff;
        border-radius: 20px;
        padding: clamp(15px, 5%, 30px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
        border: none;
    }

    .back-nav { margin-bottom: 20px; }
    .btn-back {
        text-decoration: none;
        color: #858796;
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
    }
    .btn-back:hover { color: #4e73df; }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 25px;
    }

    .info-box {
        background: #fff;
        border: 1px solid #edf0f5;
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        transition: 0.3s;
    }

    /* Warna Biru Tua Khusus Total Biaya */
    .info-box.total-biaya {
        background: var(--dark-blue);
        border: none;
    }
    .info-box.total-biaya small { color: rgba(255,255,255,0.7) !important; }
    .info-box.total-biaya h6 { color: #fff !important; }

    .info-box small { font-size: 0.7rem; font-weight: 600; }
    .info-box h6 { margin-bottom: 0; font-weight: 800; font-size: 0.95rem; }

    .price-box {
        background: #f4f7ff;
        border: 2px dashed #d1d9e6;
        border-radius: 15px;
        padding: 25px;
        margin: 20px 0;
        text-align: center;
    }

    .input-wrapper {
        position: relative;
        max-width: 280px;
        margin: 0 auto;
    }

    .currency-symbol {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 800;
        color: #4e73df;
    }

    #display-nominal {
        padding-left: 45px;
        font-size: 1.3rem;
        font-weight: 800;
        border: 2px solid #4e73df;
        border-radius: 10px;
        color: #2e59d9;
        height: 50px;
    }

    /* Button Bayar Proporsional */
    .btn-pay {
        background: var(--primary-gradient);
        border: none;
        padding: 10px 30px;
        border-radius: 8px;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        transition: 0.3s;
        min-width: 180px;
    }

    .btn-pay:disabled { background: #d1d3e2; }

    .status-badge {
        font-size: 0.72rem;
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 700;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
        .info-box { text-align: left; padding-left: 15px; border-left: 4px solid #4e73df; }
        .info-box.total-biaya { border-left: 4px solid #fff; }
    }
</style>

<div class="main-container">
    <div class="card-modern">
        <div class="back-nav">
            <a href="{{ route('anggota.pembayaran-wisata.index') }}" class="btn-back">
                <i class="bi bi-chevron-left me-1"></i> Kembali
            </a>
        </div>

        <div class="wisata-title-section mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold text-dark mb-0">{{ $pendaftaran->jwisata->nama_wisata }}</h4>
                    <small class="text-muted"><i class="bi bi-geo-alt text-danger"></i> {{ $pendaftaran->jwisata->lokasi_wisata }}</small>
                </div>
                <span class="badge {{ $sisaTagihan <= 0 ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
                    {{ $sisaTagihan <= 0 ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box total-biaya shadow-sm">
                <small class="d-block mb-1 text-uppercase">Total Biaya</small>
                <h6>Rp{{ number_format($totalTagihan, 0, ',', '.') }}</h6>
            </div>
            <div class="info-box" style="border-left: 4px solid #1cc88a;">
                <small class="text-success d-block mb-1 text-uppercase">Terbayar</small>
                <h6 class="text-success">Rp{{ number_format($totalTerbayar, 0, ',', '.') }}</h6>
            </div>
            <div class="info-box" style="border-left: 4px solid #e74a3b;">
                <small class="text-danger d-block mb-1 text-uppercase">Sisa Tagihan</small>
                <h6 class="text-danger">Rp{{ number_format($sisaTagihan, 0, ',', '.') }}</h6>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold text-dark mb-3 small text-uppercase"><i class="bi bi-clock-history me-2"></i>Riwayat Transaksi</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-history text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Tgl & Jam</th>
                            <th class="border-0">Nominal</th>
                            <th class="border-0">Metode</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatSemua as $row)
                        <tr>
                            <td class="text-muted small">{{ $row->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold">Rp{{ number_format($row->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($row->metode_pembayaran == 'cash')
                                    <span class="text-primary small fw-bold">Tunai</span>
                                @else
                                    <span class="text-muted small fw-bold">Midtrans</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(in_array($row->status, ['lunas', 'success', 'settlement', 'cicilan']))
                                    <span class="status-badge bg-success-subtle text-success">Berhasil</span>
                                @else
                                    <span class="status-badge bg-warning-subtle text-warning">Proses</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Hanya muncul jika metode 'cash' dan status transaksinya berhasil --}}
                                @if($row->metode_pembayaran == 'cash' && in_array($row->status, ['lunas', 'success', 'settlement', 'cicilan']))
                                    {{-- FIX WARNA MERAH (btn-outline-danger) & IKON PRINTER (bi-printer) --}}
                                    <a href="{{ route('anggota.pembayaran-wisata.nota', $row->id_pembayaran_wisata) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-dark d-inline-flex align-items-center justify-content-center" 
                                       style="width: 30px; height: 30px; border-radius: 6px;" 
                                       title="Cetak Nota">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted small py-4">Belum ada pembayaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($sisaTagihan > 0)
        <div class="price-box">
            <label class="fw-bold text-primary mb-3 d-block small text-uppercase">Nominal Bayar</label>
            <div class="input-wrapper mb-3">
                <span class="currency-symbol">Rp</span>
                <input type="text" id="display-nominal" class="form-control text-center shadow-sm" placeholder="0">
                <input type="hidden" id="raw-nominal" value="0">
            </div>
            <div id="error-msg" class="text-danger small mb-3 d-none">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Nominal melebihi!
            </div>
            <div>
                <button id="pay-button" class="btn btn-pay shadow-sm" disabled>
                    Bayar Sekarang
                </button>
            </div>
        </div>
        @else
        <div class="alert alert-success border-0 shadow-sm text-center py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-check-circle-fill me-2"></i> Tagihan Wisata Sudah Lunas</h6>
        </div>
        @endif
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
const displayInput = document.getElementById('display-nominal');
const rawInput = document.getElementById('raw-nominal');
const payBtn = document.getElementById('pay-button');
const errorMsg = document.getElementById('error-msg');
const sisaTagihan = {{ $sisaTagihan }};

displayInput?.addEventListener('input', function() {
    let value = this.value.replace(/[^0-9]/g, '');
    let numValue = parseInt(value) || 0;

    if (numValue > sisaTagihan) {
        this.style.borderColor = "#e74a3b";
        errorMsg.classList.remove('d-none');
        payBtn.disabled = true;
    } else if (numValue < 1000) {
        this.style.borderColor = "#4e73df";
        errorMsg.classList.add('d-none');
        payBtn.disabled = true;
    } else {
        this.style.borderColor = "#4e73df";
        errorMsg.classList.add('d-none');
        payBtn.disabled = false;
    }

    rawInput.value = numValue;
    this.value = numValue ? numValue.toLocaleString('id-ID') : '';
});

payBtn?.addEventListener('click', function() {
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>...';

    fetch("{{ route('anggota.pembayaran-wisata.bayar', $pendaftaran->id_daftar_wisata) }}", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: JSON.stringify({ jumlah_input: rawInput.value })
    })
    .then(res => res.json())
    .then(data => {
        if(data.error) {
            alert(data.error);
            location.reload();
            return;
        }
        
        snap.pay(data.snap_token, {
            onSuccess: () => location.reload(),
            onPending: () => location.reload(),
            onClose: () => {
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-wallet2 me-2"></i> Bayar Sekarang';
            }
        });
    });
});
</script>
@endsection