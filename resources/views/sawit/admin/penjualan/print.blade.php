<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $penjualan->no_invoice }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { font-size: 18px; margin-bottom: 5px; }
        .header p { font-size: 11px; color: #666; }
        .invoice-info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .invoice-info div { width: 48%; }
        .label { font-weight: bold; color: #555; font-size: 10px; text-transform: uppercase; }
        .value { font-size: 12px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; font-size: 11px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background: #f9f9f9; }
        .footer { margin-top: 40px; display: flex; justify-content: space-between; }
        .footer div { text-align: center; width: 30%; }
        .footer .line { border-bottom: 1px solid #333; margin-top: 60px; margin-bottom: 5px; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .status-done { background: #d4edda; color: #155724; }
        .status-draft { background: #fff3cd; color: #856404; }
        @media print { body { padding: 0; } @page { margin: 15mm; } }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>INVOICE PENJUALAN SAWIT</h1>
        <p>{{ config('app.name', 'Sistem Sawit') }}</p>
    </div>

    <div class="invoice-info">
        <div>
            <p class="label">No Invoice</p>
            <p class="value" style="font-size:14px; font-weight:bold;">{{ $penjualan->no_invoice }}</p>
            <p class="label">Tanggal</p>
            <p class="value">{{ $penjualan->tanggal->format('d F Y') }}</p>
            <p class="label">Status</p>
            <p class="value"><span class="status {{ $penjualan->status==='done'?'status-done':'status-draft' }}">{{ strtoupper($penjualan->status) }}</span></p>
        </div>
        <div>
            <p class="label">Perusahaan</p>
            <p class="value" style="font-weight:bold;">{{ $penjualan->perusahaan->nama_perusahaan }}</p>
            <p class="label">Jenis Sawit</p>
            <p class="value">{{ $penjualan->barang->nama_sawit }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-right">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>QTY / Total Kg (Timbangan)</td><td class="text-right">{{ number_format($penjualan->qty_timbangan, 2) }} Kg</td></tr>
            <tr><td>Pengurangan / Refaksi</td><td class="text-right">{{ number_format($penjualan->refaksi, 2) }} Kg</td></tr>
            <tr class="total-row"><td>Total Kg Setelah Refaksi</td><td class="text-right">{{ number_format($penjualan->total_kg_setelah_refaksi, 2) }} Kg</td></tr>
            <tr><td>Harga per Kg</td><td class="text-right">Rp {{ number_format($penjualan->harga_per_kg, 0, ',', '.') }}</td></tr>
            <tr class="total-row"><td><strong>Total Harga</strong></td><td class="text-right"><strong>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong></td></tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr><th colspan="2">Informasi Pembayaran</th></tr>
        </thead>
        <tbody>
            <tr><td>Tanggal Bayar</td><td class="text-right">{{ $penjualan->tanggal_bayar ? $penjualan->tanggal_bayar->format('d/m/Y') : '-' }}</td></tr>
            <tr><td>Pembayaran Invoice</td><td class="text-right">Rp {{ number_format($penjualan->pembayaran_invoice, 0, ',', '.') }}</td></tr>
            <tr><td>Potongan</td><td class="text-right">Rp {{ number_format($penjualan->potongan, 0, ',', '.') }}</td></tr>
            <tr class="total-row"><td>Selisih Pembayaran</td><td class="text-right">Rp {{ number_format($penjualan->selisih_pembayaran, 0, ',', '.') }}</td></tr>
            @if($penjualan->keterangan_potongan)<tr><td>Keterangan Potongan</td><td>{{ $penjualan->keterangan_potongan }}</td></tr>@endif
        </tbody>
    </table>

    <table>
        <thead><tr><th colspan="2">Informasi Kendaraan</th></tr></thead>
        <tbody>
            <tr><td>Jenis Kendaraan</td><td>{{ $penjualan->jenis_kendaraan ?: '-' }}</td></tr>
            <tr><td>No Mobil / Plat</td><td>{{ $penjualan->no_mobil ?: '-' }}</td></tr>
            <tr><td>Nama Supir</td><td>{{ $penjualan->nama_supir ?: '-' }}</td></tr>
        </tbody>
    </table>

    <div class="footer">
        <div><p class="label">Dibuat Oleh</p><div class="line"></div><p>Admin Sawit</p></div>
        <div><p class="label">Diketahui</p><div class="line"></div><p>Manager</p></div>
        <div><p class="label">Penerima</p><div class="line"></div><p>{{ $penjualan->perusahaan->nama_perusahaan }}</p></div>
    </div>
</body>
</html>
