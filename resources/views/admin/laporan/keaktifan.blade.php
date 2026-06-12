@extends('layouts.main')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght=400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        --pdf-gradient: linear-gradient(135deg, #0076fe 0%, #00c6ff 100%);
        --soft-bg: #f8fafc;
        --text-dark: #1e293b;
    }
    body { font-family: 'Inter', sans-serif; background-color: var(--soft-bg); }
    .card-modern { background: #fff; border: none; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); padding: 24px; }
    
    .btn-custom { 
        height: 42px; 
        border-radius: 10px !important; 
        font-weight: 700 !important; 
        font-size: 0.85rem; 
        text-decoration: none !important; 
        transition: 0.3s; 
        display: inline-flex !important; 
        align-items: center; 
        justify-content: center; 
        padding: 0 20px !important; 
        border: none !important; 
    }
    .btn-custom:hover { transform: translateY(-2px); opacity: 0.9; }
    
    .table-custom thead th { background: #f8fafc !important; color: #475569 !important; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; border-bottom: 2px solid #e2e8f0 !important; padding: 14px !important; }
    .table-custom tbody td { padding: 14px !important; vertical-align: middle; }
    .badge-status { padding: 6px 12px; border-radius: 30px; font-size: 11px; font-weight: 700; display: inline-block; white-space: nowrap; }
    .progress { background: #e2e8f0; border-radius: 20px; height: 8px; }
    .riwayat-box { background: #f1f5f9; border-radius: 10px; padding: 12px; }
    .riwayat-item { padding: 4px 0; border-bottom: 1px dashed #cbd5e1; font-size: 13px; }
    .riwayat-item:last-child { border-bottom: none; }
    
    .dataTables_filter input { 
        border-radius: 10px !important; 
        border: 1.5px solid #e3e6f0 !important; 
        height: 42px; 
        padding: 10px 15px !important; 
    }
    .avatar-circle { width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-weight: 700; }

    .pagination { gap: 6px; margin-bottom: 0; }
    .pagination .page-item { display: none; }
    .pagination .page-item.previous, .pagination .page-item.next, .pagination .page-item.active { display: block; }
    .pagination .page-item.active + .page-item, .pagination .page-item:has(+ .active) { display: block; }
    .pagination .page-item .page-link { border: none; border-radius: 10px !important; color: #4f46e5; font-weight: 600; padding: 8px 14px; background: white; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
    .pagination .page-item.active .page-link { background: var(--primary-gradient) !important; color: white !important; }

    #pdf-button-container .dt-buttons, #pdf-button-container .dt-buttons .btn-group { width: 100%; display: block; }
    #pdf-button-container .buttons-pdf { width: 100% !important; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-dark)">Laporan Keaktifan Senam</h4>
            <small class="text-muted">Data partisipasi dan riwayat kehadiran seluruh anggota</small>
        </div>
    </div>

    <!-- CARDS SUMMARY -->
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card-modern border-start border-primary border-4 py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3 p-3 rounded-3 text-white" style="background:#4f46e5;"><i class="bi bi-people-fill fs-5"></i></div>
                    <div>
                        <small class="fw-bold text-muted text-uppercase d-block" style="font-size:11px;">Total Anggota</small>
                        <span class="h4 fw-bold mb-0 text-dark">{{ $totalAnggota }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-modern border-start border-success border-4 py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3 p-3 rounded-3 text-white" style="background:#10b981;"><i class="bi bi-star-fill fs-5"></i></div>
                    <div>
                        <small class="fw-bold text-muted text-uppercase d-block" style="font-size:11px;">Anggota Teraktif</small>
                        <span class="h5 fw-bold mb-0 text-success text-truncate d-block" style="max-width:200px;">{{ $topMemberNama }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-modern border-start border-info border-4 py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3 p-3 rounded-3 text-white" style="background:#06b6d4;"><i class="bi bi-calendar-event-fill fs-5"></i></div>
                    <div>
                        <small class="fw-bold text-muted text-uppercase d-block" style="font-size:11px;">Total Pertemuan</small>
                        <span class="h4 fw-bold mb-0 text-dark">{{ $keaktifan->first()->total_pertemuan ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER AREA -->
    <div class="card-modern mb-4">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted small fw-bold" style="border-radius: 10px 0 0 10px;">DARI</span>
                    <input type="date" id="filterFrom" name="from" value="{{ $from }}" class="form-control bg-light" style="height:42px; border-radius: 0 10px 10px 0;">
                </div>
            </div>
            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted small fw-bold" style="border-radius: 10px 0 0 10px;">HINGGA</span>
                    <input type="date" id="filterTo" name="to" value="{{ $to }}" class="form-control bg-light" style="height:42px; border-radius: 0 10px 10px 0;">
                </div>
            </div>
            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="row gx-2">
                    <div class="col-5">
                        <button type="submit" class="btn btn-custom text-white w-100" style="background: var(--primary-gradient);">
                            <i class="bi bi-funnel-fill me-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-2">
                        <a href="{{ request()->url() }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" style="width:100%; height:42px; border-radius:10px;">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                    <div class="col-5" id="pdf-button-container"></div>
                </div>
            </div>
        </form>
    </div>

    <!-- TABLE AREA -->
    <div class="card-modern">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-3">
            <div id="search-wrapper"></div>
        </div>

        <div class="table-responsive">
            <table id="tableKeaktifan" class="table table-hover align-middle table-custom w-100">
                <thead>
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Nama Anggota</th>
                        <th>Kehadiran</th>
                        <th width="150">Persentase</th>
                        <th class="text-center">Status</th>
                        <th>Riwayat Pertemuan</th>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($keaktifan as $i => $row)
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $i + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle">{{ strtoupper(substr($row->nama_user,0,1)) }}</div>
                                <div class="fw-semibold text-dark member-name-text">{{ $row->nama_user }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="px-2 py-1 rounded fw-bold member-attendance-text" style="background:#f1f5f9; color:#4f46e5; font-size:13px;">
                                {{ $row->jumlah_hadir }} / {{ $row->total_pertemuan }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" style="width: {{ $row->persentase }}%; background:#4f46e5;"></div>
                                </div>
                                <small class="fw-bold text-dark member-percentage-text">{{ $row->persentase }}%</small>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($row->status == 'Sangat Aktif')
                                <span class="badge-status member-status-text" style="background:#dcfce7; color:#15803d;">Sangat Aktif</span>
                            @elseif($row->status == 'Cukup Aktif')
                                <span class="badge-status member-status-text" style="background:#fef3c7; color:#b45309;">Cukup Aktif</span>
                            @else
                                <span class="badge-status member-status-text" style="background:#fee2e2; color:#b91c1c;">Kurang Aktif</span>
                            @endif
                        </td>
                        <td>
                            @php $textRiwayat = ''; @endphp
                            @forelse($row->riwayat as $riwayat)
                                @php $textRiwayat .= $riwayat->tanggal . ' (' . $riwayat->nama_senam . '), '; @endphp
                            @empty
                                @php $textRiwayat = 'Tidak ada riwayat'; @endphp
                            @endforelse
                            <span class="text-muted small text-truncate d-inline-block" style="max-width: 250px;">
                                {{ rtrim($textRiwayat, ', ') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary px-2 py-1" style="border-radius:8px; font-size:12px;" type="button" data-bs-toggle="modal" data-bs-target="#modalRiwayat{{ $i }}">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Tidak ada data keaktifan anggota</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL BOXES -->
@foreach($keaktifan as $i => $row)
<div class="modal fade" id="modalRiwayat{{ $i }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border:none;">
            <div class="modal-header border-0 pt-4 px-4">
                <h6 class="modal-title fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Detail Kehadiran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="p-3 bg-light rounded-3 mb-3">
                    <div class="fw-bold text-dark" style="font-size:15px;">{{ $row->nama_user }}</div>
                    <small class="text-muted">Status Keaktifan: <b class="text-primary">{{ $row->status }}</b></small>
                </div>
                <div class="riwayat-box shadow-sm" style="max-height:240px; overflow-y:auto;">
                    @forelse($row->riwayat as $riwayat)
                        <div class="riwayat-item px-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i><b>{{ $riwayat->tanggal }}</b> <span class="text-muted">({{ $riwayat->nama_senam }})</span>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted">Tidak ada riwayat kehadiran.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

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
    let table = $('#tableKeaktifan').DataTable({
        responsive: true,
        pageLength: 10,
        pagingType: "simple_numbers",
        dom: '<"top-controls"fB>rt<"mt-4 d-flex justify-content-between align-items-center"ip>',
        buttons: [
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh PDF',
                className: 'btn-custom text-white shadow-sm w-100',
                title: 'LAPORAN KEAKTIFAN ANGGOTA SENAM BSC',
                orientation: 'portrait',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    format: {
                        body: function (data, row, column, node) {
                            let $node = $(node);
                            if (column === 1) {
                                return $node.find('.member-name-text').text().trim() || data;
                            } else if (column === 2) {
                                return $node.find('.member-attendance-text').text().trim() || data;
                            } else if (column === 3) {
                                return $node.find('.member-percentage-text').text().trim() || data;
                            } else if (column === 4) {
                                return $node.find('.member-status-text').text().trim() || data;
                            }
                            return data;
                        }
                    }
                },
                customize: function (doc) {
                    let fromDate = $('#filterFrom').val();
                    let toDate = $('#filterTo').val();
                    let periodeText = "Periode Laporan: Semua Rentang Waktu";
                    
                    if (fromDate && toDate) {
                        periodeText = "Periode Laporan: " + fromDate + " s/d " + toDate;
                    } else if (fromDate) {
                        periodeText = "Periode Laporan Mulai: " + fromDate;
                    } else if (toDate) {
                        periodeText = "Periode Laporan Hingga: " + toDate;
                    }

                    // 1. Format Judul Utama
                    doc.content[0].text = doc.content[0].text.toUpperCase();
                    doc.content[0].fontSize = 14;
                    doc.content[0].bold = true;
                    doc.content[0].alignment = 'center';
                    doc.content[0].margin = [0, 10, 0, 4];

                    // 2. Format Teks Periode Laporan
                    doc.content.splice(1, 0, {
                        text: periodeText,
                        fontSize: 10,
                        italics: true,
                        alignment: 'center',
                        color: '#64748b',
                        margin: [0, 0, 0, 15] 
                    });

                    let topMember = "{{ $topMemberNama }}";
                    let infoCard = {
                        table: {
                            widths: ['100%'],
                            body: [
                                [
                                    {
                                        stack: [
                                            { text: 'INFORMASI UTAMA', fontSize: 8, bold: true, color: '#4f46e5', characterSpacing: 0.5 },
                                            { text: 'Anggota Teraktif Periode Ini: ' + topMember, fontSize: 11, bold: true, color: '#1e293b', margin: [0, 4, 0, 4] },
                                            { 
                                                text: '', 
                                                fontSize: 9, 
                                                color: '#475569',
                                                lineHeight: 1.3
                                            }
                                        ],
                                        paddingLeft: 15,
                                        paddingRight: 15,
                                        paddingTop: 12,
                                        paddingBottom: 12,
                                        fillColor: '#f8fafc',
                                        border: [true, true, true, true],
                                        borderColor: '#e2e8f0'
                                    }
                                ]
                            ]
                        },
                        margin: [0, 0, 0, 20] // Memberi jarak 20px sebelum masuk ke tabel utama
                    };

                    // Sisipkan elemen Card Info tepat di baris indeks ke-2 (di atas tabel)
                    doc.content.splice(2, 0, infoCard);

                    // 4. Atur Gaya dan Desain Tabel Utama (Sekarang posisinya bergeser ke indeks 3)
                    if (doc.content[3] && doc.content[3].table) {
                        doc.content[3].table.widths = ['8%', '37%', '15%', '20%', '20%'];
                        
                        let rowCount = doc.content[3].table.body.length;
                        for (let i = 0; i < rowCount; i++) {
                            doc.content[3].table.body[i][0].alignment = 'center';
                            doc.content[3].table.body[i][2].alignment = 'center';
                            doc.content[3].table.body[i][3].alignment = 'center';
                            doc.content[3].table.body[i][4].alignment = 'center';
                            
                            if (i === 0) {
                                for(let j = 0; j < 5; j++) {
                                    doc.content[3].table.body[0][j].fillColor = '#4f46e5';
                                    doc.content[3].table.body[0][j].color = '#ffffff';
                                    doc.content[3].table.body[0][j].bold = true;
                                }
                            }
                        }
                    }
                }
            }
        ],
        language: {
            search: "", 
            searchPlaceholder: "Cari anggota...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            emptyTable: "Tidak ada data",
            paginate: { 
                next: '<i class="bi bi-chevron-right"></i>', 
                previous: '<i class="bi bi-chevron-left"></i>' 
            }
        }
    });

    $('.dataTables_filter').appendTo('#search-wrapper');
    table.buttons().container().appendTo('#pdf-button-container');
    $('.dataTables_filter input').css({ width: '280px' });
    $('.buttons-pdf').attr('style', 'background: var(--pdf-gradient) !important;');
});
</script>
@endsection