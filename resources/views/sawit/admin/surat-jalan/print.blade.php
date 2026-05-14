<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan {{ $suratJalan->no_surat_jalan }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #333; padding-bottom: 15px; }
        .header h1 { font-size: 20px; margin-bottom: 5px; letter-spacing: 2px; }
        .header p { font-size: 11px; color: #666; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .info-row div { width: 48%; }
        .info-item { margin-bottom: 8px; }
        .info-item .label { font-size: 10px; color: #666; text-transform: uppercase; font-weight: bold; }
        .info-item .value { font-size: 13px; font-weight: bold; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #333; padding: 10px; text-align: left; }
        th { background: #f0f0f0; font-size: 11px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .highlight { background: #f9f9f9; font-weight: bold; font-size: 14px; }
        .footer { margin-top: 50px; display: flex; justify-content: space-between; }
        .footer div { text-align: center; width: 30%; }
        .footer .line { border-bottom: 1px solid #333; margin-top: 70px; margin-bottom: 5px; }
        .note { margin-top: 20px; padding: 10px; border: 1px dashed #999; font-size: 11px; color: #666; }
        @media print { body { padding: 0; } @page { margin: 15mm; } }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>SURAT JALAN</h1>
        <p>{{ config('app.name', 'Sistem Sawit') }}</p>
    </div>

    <div class="info-row">
        <div>
            <div class="info-item">
                <p class="label">No Surat Jalan</p>
                <p class="value" style="font-size: 16px;">{{ $suratJalan->no_surat_jalan }}</p>
            </div>
            <div class="info-item">
                <p class="label">No Invoice</p>
                <p class="value">{{ $suratJalan->penjualan->no_invoice }}</p>
            </div>
            <div class="info-item">
                <p class="label">Tanggal</p>
                <p class="value">{{ $suratJalan->tanggal->format('d F Y') }}</p>
            </div>
        </div>
        <div>
            <div class="info-item">
                <p class="label">Tujuan / Perusahaan</p>
                <p class="value">{{ $suratJalan->nama_perusahaan }}</p>
            </div>
            <div class="info-item">
                <p class="label">Alamat Tujuan</p>
                <p class="value">{{ $suratJalan->alamat_tujuan }}</p>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th>Jenis Sawit</th>
                <th class="text-right">Total Kg</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>{{ $suratJalan->jenis_sawit }}</td>
                <td class="text-right highlight">{{ number_format($suratJalan->total_kg, 2) }} Kg</td>
                <td>{{ $suratJalan->keterangan ?: '-' }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr><th colspan="2">Informasi Kendaraan & Supir</th></tr>
        </thead>
        <tbody>
            <tr><td style="width:30%">Jenis Kendaraan</td><td>{{ $suratJalan->jenis_kendaraan }}</td></tr>
            <tr><td>No Mobil / Plat</td><td><strong>{{ $suratJalan->no_mobil }}</strong></td></tr>
            <tr><td>Nama Supir</td><td><strong>{{ $suratJalan->nama_supir }}</strong></td></tr>
        </tbody>
    </table>

    @if($suratJalan->keterangan)
    <div class="note">
        <strong>Catatan:</strong> {{ $suratJalan->keterangan }}
    </div>
    @endif

    <div class="footer">
        <div>
            <p style="font-weight:bold; font-size:11px;">Pengirim</p>
            <div class="line"></div>
            <p>Admin Sawit</p>
        </div>
        <div>
            <p style="font-weight:bold; font-size:11px;">Supir</p>
            <div class="line"></div>
            <p>{{ $suratJalan->nama_supir }}</p>
        </div>
        <div>
            <p style="font-weight:bold; font-size:11px;">Penerima</p>
            <div class="line"></div>
            <p>{{ $suratJalan->nama_perusahaan }}</p>
        </div>
    </div>
</body>
</html>
