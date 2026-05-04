<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $permintaan->no_permintaan }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #111; background: #fff; }
        .page { width: 210mm; min-height: 297mm; margin: 0 auto; padding: 20mm 18mm; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 12px; border-bottom: 3px solid #166534; margin-bottom: 16px; }
        .company-name { font-size: 20px; font-weight: 800; color: #166534; letter-spacing: .5px; }
        .company-sub { font-size: 11px; color: #555; margin-top: 2px; }
        .doc-title { text-align: right; }
        .doc-title h2 { font-size: 22px; font-weight: 800; color: #166534; letter-spacing: 2px; text-transform: uppercase; }
        .doc-title .inv-no { font-size: 13px; font-weight: 700; color: #111; margin-top: 4px; }
        .doc-title .inv-date { font-size: 11px; color: #555; margin-top: 2px; }

        /* Billing */
        .billing-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px; }
        .billing-box { border: 1px solid #d0d5dd; border-radius: 6px; padding: 10px 14px; }
        .billing-box h4 { font-size: 9px; text-transform: uppercase; color: #888; letter-spacing: .8px; font-weight: 700; margin-bottom: 6px; }
        .billing-box .name { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 3px; }
        .billing-box .sub { font-size: 11px; color: #555; margin-bottom: 2px; }
        .billing-box .meta-row { display: flex; gap: 8px; font-size: 10px; color: #555; margin-top: 6px; }
        .meta-item { background: #f0fdf4; border-radius: 4px; padding: 2px 6px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead tr { background-color: #166534; }
        thead tr th { color: #fff; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; padding: 8px 10px; text-align: left; }
        thead tr th.right { text-align: right; }
        tbody tr td { padding: 8px 10px; font-size: 11px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:nth-child(even) td { background-color: #f9fafb; }
        .td-right { text-align: right; }
        tfoot tr.subtotal td { border-top: 1px solid #d0d5dd; padding: 6px 10px; font-size: 11px; }
        tfoot tr.total-row td { border-top: 2px solid #166534; padding: 9px 10px; font-size: 14px; font-weight: 800; color: #166534; }

        /* Notes / Terms */
        .footer-section { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 8px; }
        .terms-box { border: 1px solid #d0d5dd; border-radius: 6px; padding: 10px 14px; }
        .terms-box h4 { font-size: 9px; text-transform: uppercase; color: #888; letter-spacing: .8px; font-weight: 700; margin-bottom: 6px; }
        .terms-box p { font-size: 10px; color: #555; line-height: 1.6; }

        /* TTD */
        .ttd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 24px; }
        .ttd-box { border: 1px solid #d0d5dd; border-radius: 6px; padding: 10px 14px; text-align: center; }
        .ttd-box .role { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #166534; margin-bottom: 46px; }
        .ttd-box .line { border-top: 1px solid #999; padding-top: 6px; font-size: 10px; color: #555; }

        @media print {
            body { margin: 0; }
            .page { padding: 12mm 12mm; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
@php
    $c = config('company');
@endphp
<div class="page">

    <div class="no-print" style="text-align:right; margin-bottom:12px;">
        <button onclick="window.print()" style="background:#166534;color:#fff;border:none;padding:8px 20px;border-radius:6px;font-size:12px;cursor:pointer;font-weight:600;">
            &#128438; Cetak / Print
        </button>
        <button onclick="window.close()" style="background:#6b7280;color:#fff;border:none;padding:8px 14px;border-radius:6px;font-size:12px;cursor:pointer;margin-left:6px;">
            Tutup
        </button>
    </div>

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="company-name">{{ $c['legal_name'] }}</div>
            <div class="company-sub">{{ $c['tagline'] }}</div>
            <div class="company-sub">{{ $c['address'] }}@if(! empty($c['phone'])) | Telp: {{ $c['phone'] }}@endif</div>
        </div>
        <div class="doc-title">
            <h2>Invoice</h2>
            <div class="inv-no">{{ $permintaan->no_permintaan }}</div>
            <div class="inv-date">Tanggal: {{ $permintaan->tanggal_request->format('d F Y') }}</div>
        </div>
    </div>

    {{-- Billing --}}
    <div class="billing-grid">
        <div class="billing-box">
            <h4>Tagihan Kepada</h4>
            <div class="name">{{ $permintaan->pelanggan->name }}</div>
            @if($permintaan->pelanggan->phone ?? null)
            <div class="sub">{{ $permintaan->pelanggan->phone }}</div>
            @endif
            @if($permintaan->pelanggan->address ?? null)
            <div class="sub">{{ $permintaan->pelanggan->address }}</div>
            @endif
            <div class="meta-row">
                @if($permintaan->suratJalan)
                <span class="meta-item">SJ: {{ $permintaan->suratJalan->no_sj }}</span>
                @endif
                <span class="meta-item">Status: {{ $permintaan->status_label }}</span>
            </div>
        </div>
        <div class="billing-box">
            <h4>Informasi Invoice</h4>
            <div class="sub" style="margin-bottom:4px;"><strong>No. Permintaan:</strong> <span style="font-family:monospace;">{{ $permintaan->no_permintaan }}</span></div>
            @if($permintaan->tanggal_dibutuhkan)
            <div class="sub"><strong>Tgl. Dibutuhkan:</strong> {{ $permintaan->tanggal_dibutuhkan->format('d F Y') }}</div>
            @endif
            <div class="sub"><strong>Diproses Oleh:</strong> {{ $permintaan->diprosesOleh->name ?? '—' }}</div>
            @if($permintaan->suratJalan)
            <div class="sub"><strong>Driver:</strong> {{ $permintaan->suratJalan->driver ?? '—' }}</div>
            @endif
        </div>
    </div>

    {{-- Tabel item --}}
    @php
        $items = $permintaan->detail->where('is_checked', true);
        $subtotal = $items->sum('subtotal_jual');
    @endphp
    <table>
        <thead>
            <tr>
                <th style="width:28px;">#</th>
                <th>Nama Barang</th>
                <th>Kode</th>
                <th>Satuan</th>
                <th class="right">Qty</th>
                <th class="right" style="width:110px;">Harga Satuan</th>
                <th class="right" style="width:120px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $d)
            <tr>
                <td style="color:#888;">{{ $i + 1 }}</td>
                <td><strong>{{ $d->barang->nama }}</strong></td>
                <td style="font-family:monospace;font-size:10px;color:#555;">{{ $d->barang->kode }}</td>
                <td>{{ $d->barang->satuan }}</td>
                <td class="td-right">{{ format_qty_id($d->jumlah_disetujui) }}</td>
                <td class="td-right">Rp {{ number_format($d->harga_jual, 0, ',', '.') }}</td>
                <td class="td-right"><strong>Rp {{ number_format($d->subtotal_jual, 0, ',', '.') }}</strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="subtotal">
                <td colspan="6" style="text-align:right; color:#555;">Subtotal</td>
                <td class="td-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr class="subtotal">
                <td colspan="6" style="text-align:right; color:#555;">Diskon</td>
                <td class="td-right">Rp 0</td>
            </tr>
            <tr class="total-row">
                <td colspan="6" style="text-align:right;">TOTAL</td>
                <td class="td-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Footer --}}
    <div class="footer-section">
        <div class="terms-box">
            <h4>Catatan</h4>
            <p>{{ $permintaan->catatan ?: 'Terima kasih atas kepercayaan Anda menggunakan layanan kami.' }}</p>
        </div>
        <div class="terms-box">
            <h4>Informasi Pembayaran</h4>
            <p>Bank : <strong>BCA / Mandiri</strong><br>
            No. Rek : <strong>123-456-789</strong><br>
            Atas Nama : <strong>{{ $c['legal_name'] }}</strong><br>
            Mohon sertakan nomor permintaan saat transfer.</p>
        </div>
    </div>

    {{-- TTD --}}
    <div class="ttd-grid">
        <div class="ttd-box">
            <div class="role">Hormat Kami</div>
            <div class="line">{{ $c['legal_name'] }}</div>
        </div>
        <div class="ttd-box">
            <div class="role">Penerima / Pelanggan</div>
            <div class="line">{{ $permintaan->pelanggan->name }}</div>
        </div>
    </div>

    <p style="margin-top:18px; font-size:10px; color:#999; text-align:center;">
        Invoice ini diterbitkan pada {{ now()->format('d F Y, H:i') }} WIB &mdash; {{ $c['legal_name'] }}
    </p>
</div>
</body>
</html>
