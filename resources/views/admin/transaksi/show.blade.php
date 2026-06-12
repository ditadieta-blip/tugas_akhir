@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --soft-blue: #f8f9fc;
    }

    .main-container {
        padding: 20px;
        background-color: #f8f9fc;
        min-height: 100vh;
    }

    .page-title {
        font-weight: 800;
        color: #2e59d9;
        letter-spacing: -0.5px;
    }

    /* Tombol Kembali Modern */
    .btn-back-custom {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        background: #ffffff;
        color: #4e73df;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        font-size: 0.85rem;
    }

    .btn-back-custom:hover {
        background: #4e73df;
        color: #ffffff;
        transform: translateX(-5px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
    }

    /* Card Styling */
    .table-container {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    /* Table Styling */
    .table-custom thead th {
        background: #f8f9fc;
        border: none;
        color: #4e73df;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 15px;
    }

    .table-custom tbody td {
        padding: 15px;
        vertical-align: middle;
        color: #5a5c69;
        border-bottom: 1px solid #f1f3f9;
    }

    /* Search Box Responsive */
    .search-box { position: relative; min-width: 300px; }
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #b7b9cc;
    }
    .search-input {
        border-radius: 10px;
        border: 1.5px solid #e3e6f0;
        padding-left: 40px;
        height: 40px;
    }

    /* Input Bayar Group */
    .input-pay-group {
        display: flex;
        align-items: center;
        max-width: 200px;
    }
    
    .input-pay-group .input-group-text {
        background: #f8f9fc;
        border: 1.5px solid #4e73df;
        border-right: none;
        color: #4e73df;
        font-weight: 700;
        border-radius: 8px 0 0 8px;
        font-size: 0.8rem;
    }

    .input-pay-control {
        border: 1.5px solid #4e73df !important;
        border-radius: 0 !important;
        font-weight: 700;
        height: 32px;
        font-size: 0.8rem;
    }

    .btn-pay {
        background: var(--primary-gradient);
        color: white;
        border: none;
        font-weight: 700;
        padding: 0 12px;
        height: 32px;
        border-radius: 0 8px 8px 0;
        font-size: 0.7rem;
        transition: 0.3s;
    }

    .btn-pay:hover {
        opacity: 0.9;
        box-shadow: 0 2px 6px rgba(78, 115, 223, 0.4);
    }

    /* Tombol Riwayat Baru Lebih Minimalis */
    .btn-histori-new {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        font-size: 0.72rem;
        font-weight: 700;
        border-radius: 6px;
        transition: all 0.2s ease;
        height: 32px;
    }

    /* --- CUSTOM PAGINATION STYLING --- */
    .pagination {
        margin-bottom: 0;
        gap: 5px;
    }

    .pagination .page-item .page-link {
        border: none;
        padding: 8px 16px;
        color: #4e73df;
        font-weight: 600;
        border-radius: 8px !important;
        transition: all 0.2s ease;
        background: #f8f9fc;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    .pagination .page-item .page-link:hover {
        background: #eef2f7;
        color: #224abe;
        transform: translateY(-2px);
    }

    .pagination .page-item.disabled .page-link {
        background: #fdfdfd;
        color: #d1d3e2;
    }

    @media (max-width: 768px) {
        .header-section { flex-direction: column; align-items: flex-start !important; gap: 15px; }
        .search-box { min-width: 100%; }
        .footer-table { flex-direction: column; gap: 20px; text-align: center; }
        .table-custom thead { display: none; }
        .table-custom tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
            padding: 10px 15px;
            border-bottom: 1px solid #f1f3f9;
        }
        .table-custom tbody td::before {
            content: attr(data-label);
            font-weight: 800;
            color: #4e73df;
            font-size: 0.7rem;
            text-transform: uppercase;
        }
        .input-pay-group { margin-right: 0; width: 100%; max-width: 100%; }
        .table-custom tr { display: block; margin-bottom: 15px; border: 1px solid #e3e6f0; border-radius: 12px; overflow: hidden; }
    }
</style>

<div class="main-container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-end mb-4 header-section">
        <div>
            <a href="{{ route('admin.wisata.index') }}" class="btn-back-custom">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
            <h4 class="page-title mb-0 mt-3">Pembayaran: {{ $wisata->nama_wisata }}</h4>
            <p class="text-muted small mb-0">Kelola transaksi pembayaran peserta wisata.</p>
        </div>
        
        <div class="search-box">
            <form action="{{ url()->current() }}" method="GET">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control search-input shadow-sm" 
                       placeholder="Cari nama anggota..." value="{{ request('search') }}">
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-custom text-center align-middle">
                <thead>
                    <tr>
                        <th class="text-start border-0">Anggota</th>
                        <th class="border-0">Total Tagihan</th>
                        <th class="border-0">Terbayar</th>
                        <th class="border-0">Sisa</th>
                        <th class="border-0">Status</th>
                        <th class="border-0" width="300">Aksi Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        @php
                            $total = $item->jwisata->biaya_wisata;
                            $terbayar = $item->pembayaranWisata->sum('jumlah_bayar');
                            $sisa = $total - $terbayar;
                        @endphp
                        <tr>
                            <td data-label="Anggota" class="text-start">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 35px; height: 35px; font-weight: 700; font-size: 0.8rem;">
                                        {{ strtoupper(substr($item->user->nama_user, 0, 2)) }}
                                    </div>
                                    <div class="fw-bold text-dark">{{ $item->user->nama_user }}</div>
                                </div>
                            </td>
                            <td data-label="Total" class="fw-bold">Rp{{ number_format($total, 0, ',', '.') }}</td>
                            <td data-label="Terbayar" class="text-primary TYPE_UNSPECIFIED fw-bold">Rp{{ number_format($terbayar, 0, ',', '.') }}</td>
                            <td data-label="Sisa" class="text-danger fw-bold">Rp{{ number_format($sisa, 0, ',', '.') }}</td>
                            <td data-label="Status">
                                @if($sisa <= 0)
                                    <span class="badge bg-success text-white px-3 py-2" style="border-radius: 20px; font-size: 0.65rem;">LUNAS</span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 20px; font-size: 0.65rem;">BELUM LUNAS</span>
                                @endif
                            </td>
                            <td data-label="Aksi">
                                <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap flex-md-nowrap">
                                    @if($sisa > 0)
                                        <form action="{{ route('admin.wisata.bayar', $item->id_daftar_wisata) }}" method="POST" class="form-bayar-admin m-0">
                                            @csrf
                                            <div class="input-pay-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                                <span class="input-group-text border-0 py-0 px-2" style="height: 32px; font-size: 0.75rem;">Rp</span>
                                                <input type="number" name="jumlah_bayar" class="form-control input-pay-control border-0" 
                                                       max="{{ $sisa }}" 
                                                       min="1"
                                                       oninput="if(this.value > {{ $sisa }}) this.value = {{ $sisa }};"
                                                       placeholder="{{ $sisa }}" required>
                                                <button class="btn-pay" type="submit">BAYAR</button>
                                            </div>
                                        </form>
                                    @else
                                        <span class="text-success fw-bold small p-1 d-inline-flex align-items-center" style="height: 32px;"><i class="bi bi-check-all me-1 fs-5"></i>SELESAI</span>
                                    @endif

                                    <button type="button" class="btn btn-outline-secondary btn-histori btn-histori-new shadow-sm" 
                                            data-url="{{ route('admin.wisata.histori', $item->id_daftar_wisata) }}"
                                            data-nama="{{ $item->user->nama_user }}"
                                            title="Lihat Histori Pembayaran">
                                        <i class="bi bi-clock-history"></i> Riwayat
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-5 text-muted">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="mt-4 d-flex justify-content-between align-items-center border-top pt-3 footer-table">
            <div class="small text-muted fw-bold">
                Menampilkan <span class="text-primary">{{ $data->firstItem() ?? 0 }}</span> 
                ke <span class="text-primary">{{ $data->lastItem() ?? 0 }}</span> 
                dari <span class="text-primary">{{ $data->total() }}</span> peserta
            </div>
            <nav aria-label="Page navigation">
                {{ $data->appends(['search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>

{{-- Modal Histori --}}
<div class="modal fade" id="modalHistori" tabindex="-1" aria-labelledby="modalHistoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="modal-title fw-bold text-dark text-uppercase d-flex align-items-center" id="modalHistoriLabel" style="letter-spacing: 0.5px; font-size: 0.9rem;">
                    <i class="bi bi-clock-history me-2"></i> RIWAYAT TRANSAKSI : <span id="namaAnggotaModal" class="text-primary ms-1"></span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table align-middle text-center" style="font-size: 0.85rem;">
                        <thead>
                            <tr class="table-light text-dark" style="font-weight: 700; background-color: #f8f9fa;">
                                <th class="py-3 border-0">Tgl & Jam</th>
                                <th class="py-3 border-0">Nominal</th>
                                <th class="py-3 border-0">Metode</th>
                                <th class="py-3 border-0">Status</th>
                                <th class="py-3 border-0">Nota</th>
                            </tr>
                        </thead>
                        <tbody id="tabelHistoriBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // --- NOTIFIKASI BERHASIL INPUT BAYAR (SweetAlert2) ---
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                customClass: { popup: 'border-radius-12' }
            });
        @endif

        // Efek loading saat form di-submit manual
        $('.form-bayar-admin').on('submit', function() {
            Swal.fire({
                title: 'Memproses Pembayaran...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        });

        // --- AJAX MODAL HISTORI ---
        $('.btn-histori').on('click', function() {
            var urlHistori = $(this).data('url'); 
            var namaAnggota = $(this).data('nama');
            
            $('#namaAnggotaModal').text(namaAnggota);
            $('#tabelHistoriBody').html('<tr><td colspan="4" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat data...</td></tr>');
            
            var myModal = new bootstrap.Modal(document.getElementById('modalHistori'));
            myModal.show();
            
            $.ajax({
                url: urlHistori,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var html = '';
                    
                    if(data && data.length > 0) {
                        $.each(data, function(index, row) {
                            var dateObj = new Date(row.created_at);
                            var tgl = String(dateObj.getDate()).padStart(2, '0') + '/' + 
                                      String(dateObj.getMonth() + 1).padStart(2, '0') + '/' + 
                                      dateObj.getFullYear();
                            var jam = String(dateObj.getHours()).padStart(2, '0') + ':' + 
                                      String(dateObj.getMinutes()).padStart(2, '0');
                            var formatWaktu = tgl + ' ' + jam;
                            
                            var nominal = '<span class="fw-bold text-dark">Rp' + parseInt(row.jumlah_bayar).toLocaleString('id-ID') + '</span>';
                            
                            // =========================================================
                            // PENYELARASAN: Hanya kunci pada 'cash' sama seperti Anggota
                            // =========================================================
                            var metode = 'Midtrans';
                            var checkMetode = row.metode_pembayaran ? String(row.metode_pembayaran).toLowerCase().trim() : '';
                            
                            if (checkMetode === 'cash') {
                                metode = 'Tunai';
                            }
                            
                            var statusBadge = '';
                            var statusDb = $.trim(String(row.status).toLowerCase());
                            
                            if (statusDb === 'berhasil' || 
                                statusDb === 'lunas' || 
                                statusDb === 'cicilan' || 
                                statusDb === 'settlement' || 
                                statusDb === 'success') {
                                statusBadge = '<span class="badge bg-success-subtle text-success px-3 py-1" style="border-radius: 6px; background-color: #d1e7dd; color: #0f5132 !important;">Berhasil</span>';
                            } else if (statusDb === 'pending' || statusDb === 'challenge') {
                                statusBadge = '<span class="badge bg-warning-subtle text-warning px-3 py-1" style="border-radius: 6px; background-color: #fff3cd; color: #664d03 !important;">Pending</span>';
                            } else {
                                statusBadge = '<span class="badge bg-danger-subtle text-danger px-3 py-1" style="border-radius: 6px; background-color: #f8d7da; color: #842029 !important;">Gagal</span>';
                            }

                            var tombolNota = '-';

                            if (checkMetode === 'cash') {
                                var urlNota = "{{ url('/admin/transaksi/wisata/nota') }}/" + row.id_pembayaran_wisata;

                                tombolNota =
                                    '<a href="' + urlNota + '" ' +
                                    'class="btn btn-sm btn-outline-dark" ' +
                                    'target="_blank" ' +
                                    'title="Lihat Nota">' +
                                    '<i class="bi bi-printer"></i>' +
                                    '</a>';
                            }

                            html += '<tr style="border-bottom: 1px solid #efefef;">';
                            html += '<td class="text-muted py-3">' + formatWaktu + '</td>';
                            html += '<td class="py-3">' + nominal + '</td>';
                            html += '<td class="py-3 fw-bold small ' + (metode === 'Tunai' ? 'text-primary' : 'text-muted') + '">' + metode + '</td>';
                            html += '<td class="py-3">' + statusBadge + '</td>';
                            html += '<td class="py-3">' + tombolNota + '</td>';
                            html += '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat transaksi.</td></tr>';
                    }
                    
                    $('#tabelHistoriBody').html(html);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    $('#tabelHistoriBody').html('<tr><td colspan="4" class="text-center py-4 text-danger fw-bold">Gagal memuat data.</td></tr>');
                }
            });
        });
    });
</script>
@endsection