<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran Wisata Senam BSC</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px 20px;
            color: #333333;
        }
        .nota {
            max-width: 450px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 5px 0;
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 13px;
            color: #777777;
            margin: 0;
        }
        .divider {
            border: none;
            border-top: 2px dashed #e2e8f0;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 7px 0;
            font-size: 14px;
            vertical-align: top;
        }
        .label {
            color: #64748b;
            width: 40%;
        }
        .value {
            color: #1e293b;
            font-weight: 500;
            text-align: right;
        }
        /* Rincian Harga */
        .ringkasan-pembayaran td {
            padding: 9px 0;
        }
        .ringkasan-pembayaran .label {
            color: #1e293b;
        }
        .ringkasan-pembayaran .total-bayar {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }
        .ringkasan-pembayaran .sisa-tagihan {
            color: #df2c2c;
            font-weight: 600;
        }
        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 25px;
            font-style: italic;
            line-height: 1.5;
        }
        
        /* Tombol Aksi */
        .btn-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 25px;
        }
        .btn-print, .btn-back {
            flex: 1;
            padding: 11px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }
        .btn-print {
            background-color: #2563eb;
            color: white;
            border: none;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }
        .btn-print:hover {
            background-color: #1d4ed8;
        }
        .btn-back {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .btn-back:hover {
            background-color: #e2e8f0;
            color: #334155;
        }

        /* Pengaturan Cetak (Print) */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
                margin: 0;
            }
            .nota {
                box-shadow: none;
                padding: 10px;
                max-width: 100%;
            }
            .btn-container {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<div class="nota">
    <div class="header">
        <h2>STRUK PEMBAYARAN</h2>
        <p>Bukti Transaksi Pembayaran Wisata Tunai</p>
    </div>
    <div class="divider"></div>
    <table>
        <tr>
            <td class="label">Nama Anggota</td>
            <td class="value">{{ $pembayaran->pendaftaranWisata->user->nama_user }}</td>
        </tr>
        <tr>
            <td class="label">Nama Wisata</td>
            <td class="value">{{ $pembayaran->pendaftaranWisata->jwisata->nama_wisata }}</td>
        </tr>
        <tr>
            <td class="label">Waktu/Tanggal</td>
            <td class="value">{{ $pembayaran->created_at->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Cicilan Ke</td>
            <td class="value">#{{ $pembayaran->cicilan_ke }}</td>
        </tr>
        <tr>
            <td class="label">Metode Bayar</td>
            <td class="value">
                {{ strtolower($pembayaran->metode_pembayaran ?? '') == 'cash' ? 'Tunai' : 'Midtrans' }}
            </td>
        </tr>
    </table>

    <div class="divider"></div>
    <table class="ringkasan-pembayaran">
        <tr>
            <td class="label total-bayar">Jumlah Bayar</td>
            <td class="value total-bayar">
                Rp {{ number_format($pembayaran->jumlah_bayar,0,',','.') }}
            </td>
        </tr>
        <tr>
            <td class="label">Total Terbayar</td>
            <td class="value">
                Rp {{ number_format($pembayaran->total_terbayar,0,',','.') }}
            </td>
        </tr>
        <tr>
            <td class="label sisa-tagihan">Sisa Tagihan</td>
            <td class="value sisa-tagihan">
                Rp {{ number_format($pembayaran->sisa_tagihan,0,',','.') }}
            </td>
        </tr>
    </table>

    <div class="divider"></div>
    <p class="footer-text">
        Bukti pembayaran wisata via tunai Senam BSC. <br> Terima kasih atas pembayaran Anda.
    </p>
    
    <div class="btn-container">
        <a href="{{ route('admin.wisata.show', $pembayaran->pendaftaranWisata->id_wisata) }}" class="btn-back">
            Kembali
        </a>
        <button onclick="window.print()" class="btn-print">
            Cetak Nota
        </button>
    </div>
</div>
</body>
</html>