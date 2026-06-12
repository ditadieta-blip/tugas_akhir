@extends('layouts.main')

@section('content')
@php
\Carbon\Carbon::setLocale('id');
@endphp
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --soft-bg: #f8f9fc;
        --text-dark: #2e59d9;
        --text-normal: #5a5c69;
        --text-black: #000000;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--soft-bg);
        color: var(--text-normal);
    }

    .main-container {
        padding: 20px;
        min-height: 100vh;
    }

    .page-header { margin-bottom: 25px; }

    .page-title {
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.5px;
        margin-bottom: 0;
    }

    .card-modern {
        background: #ffffff;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 25px;
    }

    /* Statistik Cards */
    .card-stat {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        background: white;
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }

    /* Tabel Sesuai Menu User */
    .table-custom thead th {
        background: #f8f9fc !important;
        border: none !important;
        color: #4e73df !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 15px 20px !important;
        letter-spacing: 1px;
    }

    .table-custom tbody td {
        padding: 15px 20px !important;
        vertical-align: middle;
        color: #5a5c69;
        border-bottom: 1px solid #f1f3f9;
        font-size: 0.875rem;
    }

    /* Button Styling - No Underline */
    .btn-custom {
        height: 42px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 20px;
        border: none;
        text-decoration: none !important; /* Hapus underline */
        transition: 0.3s;
    }

    .btn-apply { background: var(--primary-gradient); color: white; }
    .btn-reset { background: #eaecf4; color: #5a5c69; }
    
    .btn-apply:hover { transform: translateY(-2px); color: white; opacity: 0.9; }
    .btn-reset:hover { background: #d1d3e2; color: var(--text-black); text-decoration: none; }

    /* PAGINATION MODERN */
.pagination {
    gap: 6px;
    margin-bottom: 0;
}

.pagination .page-item {
    display: none;
}

/* tampilkan prev next */
.pagination .page-item.previous,
.pagination .page-item.next,
.pagination .page-item.active {
    display: block;
}

/* tampilkan angka kiri & kanan active */
.pagination .page-item.active + .page-item,
.pagination .page-item:has(+ .active) {
    display: block;
}

.pagination .page-item .page-link {
    border: none;
    border-radius: 10px !important;
    color: #4e73df;
    font-weight: 600;
    padding: 8px 14px;
    font-size: 0.82rem;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    background: white;
}

.pagination .page-item.active .page-link {
    background: var(--primary-gradient) !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.25);
}

.pagination .page-item:not(.active):not(.disabled) .page-link:hover {
    background: #eef2ff;
    transform: translateY(-1px);
}

.pagination .page-item.disabled .page-link {
    background: #f8f9fc;
    color: #c0c4d6;
    box-shadow: none;
}

    .dataTables_filter input {
        border-radius: 10px !important;
        border: 1.5px solid #e3e6f0 !important;
        height: 42px;
        padding: 10px 15px !important;
    }
</style>

<div class="main-container">
    <div class="page-header">
        <h4 class="page-title">Laporan Pemasukan</h4>
        <p class="text-muted small mb-0">Manajemen histori transaksi masuk sistem</p>
    </div>

    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-stat">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box me-3" style="background: #4e73df"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <p class="small fw-bold mb-0 text-muted">TOTAL PEMASUKAN</p>
                        <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($total,0,',','.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-stat">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box me-3" style="background: #1cc88a"><i class="bi bi-cash-coin"></i></div>
                    <div>
                        <p class="small fw-bold mb-0 text-muted">TOTAL IURAN</p>
                        <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalIuran,0,',','.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-stat">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box me-3" style="background: #f6c23e"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <p class="small fw-bold mb-0 text-muted">TOTAL WISATA</p>
                        <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalWisata,0,',','.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-modern mb-4">
        <form method="GET" class="row g-3">
            <div class="col-lg-3 col-md-6">
                <label class="small fw-bold text-dark text-uppercase">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control shadow-sm border-0 bg-light" style="border-radius: 10px;">
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="small fw-bold text-dark text-uppercase">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control shadow-sm border-0 bg-light" style="border-radius: 10px;">
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="small fw-bold text-dark text-uppercase">Kegiatan</label>
                <select name="jenis" class="form-select shadow-sm border-0 bg-light" style="border-radius: 10px;">
                    <option value="">Semua Kegiatan</option>
                    <option value="Wisata" {{ request('jenis') == 'Wisata' ? 'selected' : '' }}>Wisata</option>
                    <option value="Iuran" {{ request('jenis') == 'Iuran' ? 'selected' : '' }}>Iuran</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 d-flex align-items-end gap-2">
                <button type="submit" class="btn-custom btn-apply w-100 shadow-sm">
                    <i class="bi bi-filter me-2"></i>Terapkan
                </button>
                <a href="{{ route('admin.laporan.keuangan') }}" class="btn-custom btn-reset w-100">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="card-modern">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-3">
            <div id="search-wrapper"></div>
            <div id="export-wrapper"></div>
        </div>

        <div class="table-responsive">
            <table id="laporanTable" class="table table-custom w-100">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Anggota</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $i => $item)
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $i + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $item['nama'] }}</td>
                        <td>
                            @if($item['jenis'] == 'Wisata')
                                <span class="badge" style="background: #fff4e5; color: #ff9800; border-radius: 8px; padding: 6px 12px;">WISATA</span>
                            @else
                                <span class="badge" style="background: #eef2ff; color: #4e73df; border-radius: 8px; padding: 6px 12px;">IURAN</span>
                            @endif
                        </td>
                        <td class="text-dark">{{ $item['keterangan'] }}</td>
                        <td class="fw-bold text-dark">Rp {{ number_format($item['jumlah'],0,',','.') }}</td>
                        <td class="text-dark">
                            {{ \Carbon\Carbon::parse($item['tanggal'])->locale('id')->isoFormat('D MMMM YYYY') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function () {
    // Parsing data filter aktif dari PHP Blade ke Javascript
    const fromDate = "{{ request('from') ? \Carbon\Carbon::parse(request('from'))->locale('id')->isoFormat('D MMMM YYYY') : '' }}";
    const toDate = "{{ request('to') ? \Carbon\Carbon::parse(request('to'))->locale('id')->isoFormat('D MMMM YYYY') : '' }}";
    const filterJenis = "{{ request('jenis') ?? 'Semua' }}";

    // Menyusun teks info rentang periode laporan
    let infoPeriode = "Periode: Semua Waktu";
    if (fromDate && toDate) {
        infoPeriode = `Periode: ${fromDate} s/d ${toDate}`;
    } else if (fromDate) {
        infoPeriode = `Periode: Sejak ${fromDate}`;
    } else if (toDate) {
        infoPeriode = `Periode: Sampa Dengan ${toDate}`;
    }

    let table = $('#laporanTable').DataTable({
        responsive: true,
        pageLength: 10,
        pagingType: "simple_numbers",
        dom: '<"top-controls"fB>rt<"mt-4 d-flex justify-content-between align-items-center"ip>',
        buttons: [
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF',
                className: 'btn-custom btn-apply shadow-sm',
                title: 'LAPORAN PEMASUKAN SENAM BSC',
                // Pengaturan Export Kolom Data Tabel
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                customize: function (doc) {
                    // 1. Ambil seluruh nilai data nominal baris tabel untuk ditotal secara real-time
                    let totalNominal = 0;
                    // Indeks ke-4 adalah posisi kolom 'Nominal'
                    doc.content[1].table.body.forEach(function (row, idx) {
                        if (idx > 0) { // Lewati bagian header baris ke-0
                            let textRaw = row[4].text || '';
                            // Hilangkan teks 'Rp' dan titik ribuan untuk diconvert ke Integer angka asli
                            let angka = parseInt(textRaw.replace(/[^0-9]/g, '')) || 0;
                            totalNominal += angka;
                        }
                    });

                    // Format angka hasil penjumlahan ke bentuk Rupiah (Contoh: Rp 1.500.000)
                    let totalFormatted = 'Rp ' + totalNominal.toLocaleString('id-ID');

                    // 2. Desain Layout Judul Utama Dokumen PDF
                    doc.styles.title = {
                        color: '#2e59d9',
                        fontSize: 16,
                        bold: true,
                        alignment: 'center',
                        margin: [0, 0, 0, 5]
                    };

                    // 3. Menambahkan Sub-Header metadata informasi Filter Periode dan Kegiatan
                    doc.content.splice(1, 0, {
                        text: [
                            { text: infoPeriode + '\n', bold: true },
                            { text: `Kategori Kegiatan: ${filterJenis}\n\n`, bold: false }
                        ],
                        alignment: 'center',
                        fontSize: 10,
                        color: '#5a5c69',
                        margin: [0, 0, 0, 15]
                    });

                    // 4. Menambahkan Row Total Akumulasi di bagian bawah Tabel Data
                    doc.content[2].table.body.push([
                        { text: 'TOTAL NOMINAL PEMASUKAN', colSpan: 4, bold: true, alignment: 'right', fillColor: '#eaecf4' },
                        {}, {}, {}, // Kosongkan kolom yang ter-merge colSpan
                        { text: totalFormatted, bold: true, color: '#224abe', alignment: 'left', fillColor: '#eef2ff' },
                        { text: '', fillColor: '#eaecf4' }
                    ]);

                    // 5. Penataan Styling Tampilan Table agar Rapih dan Bersih
                    let objLayout = {};
                    objLayout['hLineWidth'] = function(i) { return .5; };
                    objLayout['vLineWidth'] = function(i) { return .5; };
                    objLayout['hLineColor'] = function(i) { return '#d1d3e2'; };
                    objLayout['vLineColor'] = function(i) { return '#d1d3e2'; };
                    objLayout['paddingLeft'] = function(i) { return 8; };
                    objLayout['paddingRight'] = function(i) { return 8; };
                    objLayout['paddingTop'] = function(i) { return 6; };
                    objLayout['paddingBottom'] = function(i) { return 6; };
                    doc.content[2].layout = objLayout;

                    // Mengatur lebar kolom agar seimbang proporsional otomatis
                    doc.content[2].table.widths = ['8%', '20%', '15%', '25%', '17%', '15%'];
                    
                    // Merapikan perataan text header kolom data tabel
                    doc.content[2].table.body[0].forEach(function(cell) {
                        cell.fillColor = '#4e73df';
                        cell.color = '#ffffff';
                        cell.bold = true;
                    });
                }
            }
        ],
        language: {
            search: "",
            searchPlaceholder: "Cari transaksi...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                next: '<i class="bi bi-chevron-right"></i>',
                previous: '<i class="bi bi-chevron-left"></i>'
            }
        }
    });

    $('.dataTables_filter').appendTo('#search-wrapper');
    table.buttons().container().appendTo('#export-wrapper');

    $('.dataTables_filter input').css({
        width: '280px'
    });
});
</script>
@endsection