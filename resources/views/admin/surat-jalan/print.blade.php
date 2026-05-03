<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan {{ $suratJalan->no_sj }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #111; background: #fff; }
        .page { width: 210mm; min-height: 297mm; margin: 0 auto; padding: 20mm 18mm; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 10px; border-bottom: 3px solid #1e3a5f; margin-bottom: 14px; }
        .company-name { font-size: 20px; font-weight: 800; color: #1e3a5f; letter-spacing: .5px; }
        .company-sub { font-size: 11px; color: #555; margin-top: 2px; }
        .doc-title { text-align: right; }
        .doc-title h2 { font-size: 17px; font-weight: 700; color: #1e3a5f; letter-spacing: 1px; text-transform: uppercase; }
        .doc-title p { font-size: 11px; color: #555; margin-top: 2px; }

        /* Info grid */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
        .info-box { border: 1px solid #d0d5dd; border-radius: 6px; padding: 10px 12px; }
        .info-box h4 { font-size: 9px; text-transform: uppercase; color: #888; letter-spacing: .8px; font-weight: 700; margin-bottom: 6px; }
        .info-row { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px; }
        .info-row .lbl { color: #555; }
        .info-row .val { font-weight: 600; text-align: right; max-width: 60%; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead tr th { background-color: #1e3a5f; color: #fff; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; padding: 7px 10px; text-align: left; }
        thead tr th.right { text-align: right; }
        tbody tr td { padding: 8px 10px; font-size: 11px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:nth-child(even) td { background-color: #f9fafb; }
        .td-right { text-align: right; }
        tfoot tr td { padding: 8px 10px; font-size: 12px; font-weight: 700; border-top: 2px solid #1e3a5f; }

        /* Footer section */
        .ttd-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 32px; }
        .ttd-box { border: 1px solid #d0d5dd; border-radius: 6px; padding: 10px 12px; text-align: center; }
        .ttd-box .role { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #1e3a5f; margin-bottom: 46px; }
        .ttd-box .line { border-top: 1px solid #999; padding-top: 6px; font-size: 10px; color: #555; }

        /* Note */
        .note-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 8px 12px; margin-bottom: 16px; font-size: 11px; }

        @media print {
            body { margin: 0; }
            .page { padding: 12mm 12mm; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Print button (hidden on print) --}}
    <div class="no-print" style="text-align:right; margin-bottom:12px;">
        <button onclick="window.print()" style="background:#1e3a5f;color:#fff;border:none;padding:8px 20px;border-radius:6px;font-size:12px;cursor:pointer;font-weight:600;">
            &#128438; Cetak / Print
        </button>
        <button onclick="window.close()" style="background:#6b7280;color:#fff;border:none;padding:8px 14px;border-radius:6px;font-size:12px;cursor:pointer;margin-left:6px;">
            Tutup
        </button>
    </div>

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="company-name">CV. DISTRIBUTOR APK</div>
            <div class="company-sub">Distributor Alat &amp; Perlengkapan Kebutuhan</div>
            <div class="company-sub">Jl. Contoh No. 123, Kota Anda | Telp: (021) 000-0000</div>
        </div>
        <div class="doc-title">
            <h2>Surat Jalan</h2>
            <p>No: <strong>{{ $suratJalan->no_sj }}</strong></p>
            <p>Tanggal: {{ $suratJalan->tanggal->format('d F Y') }}</p>
        </div>
    </div>

    {{-- Info --}}
    <div class="info-grid">
        <div class="info-box">
            <h4>Informasi Pelanggan</h4>
            <div class="info-row"><span class="lbl">Kepada</span><span class="val">{{ $suratJalan->permintaan->pelanggan->name }}</span></div>
            @if($suratJalan->permintaan->pelanggan->phone ?? null)
            <div class="info-row"><span class="lbl">Telepon</span><span class="val">{{ $suratJalan->permintaan->pelanggan->phone }}</span></div>
            @endif
            <div class="info-row"><span class="lbl">No. Permintaan</span><span class="val" style="font-family:monospace;font-size:10px;">{{ $suratJalan->permintaan->no_permintaan }}</span></div>
        </div>
        <div class="info-box">
            <h4>Informasi Pengiriman</h4>
            <div class="info-row"><span class="lbl">Driver</span><span class="val">{{ $suratJalan->driver ?? '—' }}</span></div>
            <div class="info-row"><span class="lbl">No. Kendaraan</span><span class="val">{{ $suratJalan->no_kendaraan ?? '—' }}</span></div>
            <div class="info-row"><span class="lbl">Alamat Tujuan</span><span class="val">{{ $suratJalan->alamat_tujuan ?? '—' }}</span></div>
            <div class="info-row"><span class="lbl">Status</span><span class="val">{{ $suratJalan->status_label }}</span></div>
        </div>
    </div>

    @if($suratJalan->catatan)
    <div class="note-box"><strong>Catatan:</strong> {{ $suratJalan->catatan }}</div>
    @endif

    {{-- Tabel barang --}}
    <table>
        <thead>
            <tr>
                <th style="width:32px;">#</th>
                <th>Nama Barang</th>
                <th>Kode</th>
                <th>Satuan</th>
                <th class="right" style="width:80px;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratJalan->detail as $i => $d)
            <tr>
                <td style="color:#888;">{{ $i + 1 }}</td>
                <td><strong>{{ $d->barang->nama }}</strong></td>
                <td style="font-family:monospace;font-size:10px;color:#555;">{{ $d->barang->kode }}</td>
                <td>{{ $d->barang->satuan }}</td>
                <td class="td-right"><strong>{{ number_format($d->jumlah, 0, ',', '.') }}</strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">Total Item</td>
                <td class="td-right">{{ $suratJalan->detail->count() }} jenis</td>
            </tr>
        </tfoot>
    </table>

    {{-- TTD --}}
    <div class="ttd-grid">
        <div class="ttd-box">
            <div class="role">Dibuat Oleh</div>
            <div class="line">{{ $suratJalan->dibuatOleh->name ?? '—' }}</div>
        </div>
        <div class="ttd-box">
            <div class="role">Driver / Pengirim</div>
            <div class="line">{{ $suratJalan->driver ?? '(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)' }}</div>
        </div>
        <div class="ttd-box">
            <div class="role">Penerima</div>
            <div class="line">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
        </div>
    </div>

    <p style="margin-top:20px; font-size:10px; color:#999; text-align:center;">
        Dokumen ini dicetak pada {{ now()->format('d F Y, H:i') }} WIB &mdash; CV. Distributor APK
    </p>
</div>
<script>
    // auto print jika halaman ini dibuka via target="_blank"
    // window.onload = () => window.print();
</script>
</body>
</html>
