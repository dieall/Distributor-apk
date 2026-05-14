<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

/**
 * Export ke format HTML yang dibuka Excel sebagai .xls (tanpa ext-zip / PhpSpreadsheet).
 */
class HtmlExcelExport
{
    public static function safeFilename(string $stem): string
    {
        $s = preg_replace('/[^a-zA-Z0-9._-]+/', '_', $stem);

        return $s !== '' ? $s : 'export';
    }

    public static function pengeluaran(Collection $data, string $filenameStem): Response
    {
        $filename = self::safeFilename($filenameStem).'.xls';
        $total = (int) $data->sum('nominal');

        $rows = '';
        foreach ($data as $i => $item) {
            $rows .= '<tr>'
                .'<td class="c-num">'.($i + 1).'</td>'
                .'<td class="c-date" style="mso-number-format:\'\\@\'">'.e(self::tanggalTeksExcel($item->tanggal)).'</td>'
                .'<td class="c-text">'.e($item->kategori).'</td>'
                .'<td class="c-text">'.e($item->keterangan).'</td>'
                .'<td class="c-rp">'.e(number_format((float) $item->nominal, 0, ',', '.')).'</td>'
                .'<td class="c-text">'.e($item->pembuat->name ?? '—').'</td>'
                .'</tr>';
        }

        $html = self::wrapTable(
            title: 'Laporan Pengeluaran Operasional',
            subtitle: 'Diekspor: '.now()->format('d/m/Y H:i'),
            head: ['No', 'Tanggal (YYYY-MM-DD)', 'Kategori', 'Keterangan', 'Nominal (Rp)', 'Dibuat Oleh'],
            colgroupWidths: ['48px', '118px', '120px', '300px', '110px', '140px'],
            bodyRows: $rows,
            footRow: '<tr class="row-total">'
                .'<td colspan="4" class="c-label-total">TOTAL</td>'
                .'<td class="c-rp">'.e(number_format($total, 0, ',', '.')).'</td>'
                .'<td></td>'
                .'</tr>'
        );

        return self::response($html, $filename);
    }

    public static function invoicePelanggan(Collection $data, string $filenameStem): Response
    {
        $filename = self::safeFilename($filenameStem).'.xls';
        $grand = (float) $data->sum(fn ($item) => (float) $item->detail->sum('subtotal_jual'));

        $rows = '';
        $lineNo = 0;
        foreach ($data as $item) {
            $tgl = self::tanggalTeksExcel($item->tanggal_request);
            $pelanggan = e($item->pelanggan->name ?? '—');
            $noSj = e(optional($item->suratJalan)->no_sj ?? '—');
            $status = e($item->status_label);
            $noPermintaan = e($item->no_permintaan);
            $noPoCust = e($item->no_po_customer ?? '');

            $details = $item->detail instanceof \Illuminate\Support\Collection
                ? $item->detail
                : collect($item->detail);
            $nRows = $details->count();

            if ($nRows === 0) {
                $lineNo++;
                $rows .= '<tr>'
                    .'<td class="c-num">'.$lineNo.'</td>'
                    .'<td class="c-text">'.$noPermintaan.'</td>'
                    .'<td class="c-text">'.$pelanggan.'</td>'
                    .'<td class="c-date" style="mso-number-format:\'\\@\'">'.e($tgl).'</td>'
                    .'<td class="c-text">'.$noPoCust.'</td>'
                    .'<td class="c-text">'.$noSj.'</td>'
                    .'<td class="c-text">'.$status.'</td>'
                    .'<td class="c-text">—</td>'
                    .'<td class="c-text">—</td>'
                    .'<td class="c-text">—</td>'
                    .'<td class="c-num">—</td>'
                    .'<td class="c-rp">—</td>'
                    .'<td class="c-rp">0</td>'
                    .'</tr>';

                continue;
            }

            $rsAttr = ' rowspan="'.(int) $nRows.'"';
            $first = true;
            foreach ($details as $d) {
                $lineNo++;
                $b = $d->barang;
                $kode = e($b->kode ?? '—');
                $nama = e($b->nama ?? '—');
                $satuan = e($b->satuan ?? '—');
                $qty = function_exists('format_qty_id')
                    ? e(format_qty_id($d->jumlah_disetujui))
                    : e(number_format((float) $d->jumlah_disetujui, 2, ',', '.'));
                $harga = (float) $d->harga_jual;
                $sub = (float) $d->subtotal_jual;

                $itemCells = '<td class="c-text">'.$kode.'</td>'
                    .'<td class="c-text">'.$nama.'</td>'
                    .'<td class="c-text">'.$satuan.'</td>'
                    .'<td class="c-num">'.$qty.'</td>'
                    .'<td class="c-rp">'.e(number_format($harga, 0, ',', '.')).'</td>'
                    .'<td class="c-rp">'.e(number_format($sub, 0, ',', '.')).'</td>';

                if ($first) {
                    $rows .= '<tr>'
                        .'<td class="c-num">'.$lineNo.'</td>'
                        .'<td class="c-text c-vmiddle"'.$rsAttr.'>'.$noPermintaan.'</td>'
                        .'<td class="c-text c-vmiddle"'.$rsAttr.'>'.$pelanggan.'</td>'
                        .'<td class="c-date c-vmiddle"'.$rsAttr.' style="mso-number-format:\'\\@\'">'.e($tgl).'</td>'
                        .'<td class="c-text c-vmiddle"'.$rsAttr.'>'.$noPoCust.'</td>'
                        .'<td class="c-text c-vmiddle"'.$rsAttr.'>'.$noSj.'</td>'
                        .'<td class="c-text c-vmiddle"'.$rsAttr.'>'.$status.'</td>'
                        .$itemCells
                        .'</tr>';
                    $first = false;
                } else {
                    $rows .= '<tr>'
                        .'<td class="c-num">'.$lineNo.'</td>'
                        .$itemCells
                        .'</tr>';
                }
            }
        }

        $html = self::wrapTable(
            title: 'Laporan Invoice / Permintaan Pelanggan (per barang)',
            subtitle: 'Diekspor: '.now()->format('d/m/Y H:i').' · Data permintaan digabung per baris barang · Status: Siap kirim dan Selesai',
            head: [
                'No', 'No. Permintaan', 'Pelanggan', 'Tanggal (YYYY-MM-DD)', 'No. PO Customer',
                'No. Surat Jalan', 'Status', 'Kode Barang', 'Nama Barang', 'Satuan',
                'Qty Disetujui', 'Harga Satuan (Rp)', 'Subtotal (Rp)',
            ],
            colgroupWidths: [
                '40px', '118px', '140px', '108px', '100px', '108px', '88px',
                '88px', '220px', '56px', '88px', '100px', '200px',
            ],
            bodyRows: $rows,
            footRow: '<tr class="row-total">'
                .'<td colspan="11" class="c-label-total">TOTAL (semua subtotal baris)</td>'
                .'<td colspan="2" class="c-rp c-rp-total">'.e(number_format($grand, 0, ',', '.')).'</td>'
                .'</tr>'
        );

        return self::response($html, $filename);
    }

    /**
     * Export PO pembelian: satu baris per detail barang; kolom header PO digabung (rowspan).
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Pembelian>  $data
     */
    public static function pembelianPo(Collection $data, string $filenameStem): Response
    {
        $filename = self::safeFilename($filenameStem).'.xls';
        $grand = (float) $data->sum(fn ($po) => (float) $po->detail->sum('subtotal'));

        $rows = '';
        $lineNo = 0;
        foreach ($data as $po) {
            $noPo = e($po->no_po);
            $supplier = e($po->supplier->name ?? '—');
            $tglPo = self::tanggalTeksExcel($po->tanggal);
            $tglEst = self::tanggalTeksExcel($po->tanggal_kirim_estimasi);
            $status = e($po->status_label);
            $dibuat = e($po->dibuatOleh->name ?? '—');

            $details = $po->detail instanceof \Illuminate\Support\Collection
                ? $po->detail
                : collect($po->detail);
            $nRows = $details->count();

            if ($nRows === 0) {
                $lineNo++;
                $rows .= '<tr>'
                    .'<td class="c-num">'.$lineNo.'</td>'
                    .'<td class="c-text">'.$noPo.'</td>'
                    .'<td class="c-text">'.$supplier.'</td>'
                    .'<td class="c-date" style="mso-number-format:\'\\@\'">'.e($tglPo).'</td>'
                    .'<td class="c-date" style="mso-number-format:\'\\@\'">'.e($tglEst).'</td>'
                    .'<td class="c-text">'.$status.'</td>'
                    .'<td class="c-text">'.$dibuat.'</td>'
                    .'<td class="c-text">—</td>'
                    .'<td class="c-text">—</td>'
                    .'<td class="c-text">—</td>'
                    .'<td class="c-num">—</td>'
                    .'<td class="c-rp">—</td>'
                    .'<td class="c-rp">0</td>'
                    .'</tr>';

                continue;
            }

            $rsAttr = ' rowspan="'.(int) $nRows.'"';
            $first = true;
            foreach ($details as $d) {
                $lineNo++;
                $b = $d->barang;
                $kode = e($b->kode ?? '—');
                $nama = e($b->nama ?? '—');
                $satuan = e($b->satuan ?? '—');
                $qty = function_exists('format_qty_id')
                    ? e(format_qty_id($d->jumlah))
                    : e(number_format((float) $d->jumlah, 2, ',', '.'));
                $harga = (float) $d->harga_satuan;
                $sub = (float) $d->subtotal;

                $itemCells = '<td class="c-text">'.$kode.'</td>'
                    .'<td class="c-text">'.$nama.'</td>'
                    .'<td class="c-text">'.$satuan.'</td>'
                    .'<td class="c-num">'.$qty.'</td>'
                    .'<td class="c-rp">'.e(number_format($harga, 0, ',', '.')).'</td>'
                    .'<td class="c-rp">'.e(number_format($sub, 0, ',', '.')).'</td>';

                if ($first) {
                    $rows .= '<tr>'
                        .'<td class="c-num">'.$lineNo.'</td>'
                        .'<td class="c-text c-vmiddle"'.$rsAttr.'>'.$noPo.'</td>'
                        .'<td class="c-text c-vmiddle"'.$rsAttr.'>'.$supplier.'</td>'
                        .'<td class="c-date c-vmiddle"'.$rsAttr.' style="mso-number-format:\'\\@\'">'.e($tglPo).'</td>'
                        .'<td class="c-date c-vmiddle"'.$rsAttr.' style="mso-number-format:\'\\@\'">'.e($tglEst).'</td>'
                        .'<td class="c-text c-vmiddle"'.$rsAttr.'>'.$status.'</td>'
                        .'<td class="c-text c-vmiddle"'.$rsAttr.'>'.$dibuat.'</td>'
                        .$itemCells
                        .'</tr>';
                    $first = false;
                } else {
                    $rows .= '<tr>'
                        .'<td class="c-num">'.$lineNo.'</td>'
                        .$itemCells
                        .'</tr>';
                }
            }
        }

        $html = self::wrapTable(
            title: 'Laporan Purchase Order (per barang)',
            subtitle: 'Diekspor: '.now()->format('d/m/Y H:i').' · Blok PO digabung per baris barang',
            head: [
                'No', 'No. PO', 'Supplier', 'Tanggal PO (YYYY-MM-DD)', 'Estimasi Tiba (YYYY-MM-DD)',
                'Status', 'Dibuat Oleh', 'Kode Barang', 'Nama Barang', 'Satuan',
                'Qty', 'Harga Satuan (Rp)', 'Subtotal (Rp)',
            ],
            colgroupWidths: [
                '40px', '118px', '160px', '108px', '108px', '100px', '120px',
                '88px', '220px', '56px', '72px', '100px', '200px',
            ],
            bodyRows: $rows,
            footRow: '<tr class="row-total">'
                .'<td colspan="11" class="c-label-total">TOTAL (semua subtotal baris)</td>'
                .'<td colspan="2" class="c-rp c-rp-total">'.e(number_format($grand, 0, ',', '.')).'</td>'
                .'</tr>'
        );

        return self::response($html, $filename);
    }

    private static function wrapTable(
        string $title,
        string $subtitle,
        array $head,
        array $colgroupWidths,
        string $bodyRows,
        string $footRow
    ): string {
        $colgroup = '';
        foreach ($colgroupWidths as $w) {
            $colgroup .= '<col style="width:'.e($w).'">';
        }

        $th = '';
        foreach ($head as $h) {
            $th .= '<th>'.e($h).'</th>';
        }

        return '<!DOCTYPE html><html xmlns:x="urn:schemas-microsoft-com:office:excel">'
            .'<head><meta charset="UTF-8">'
            .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'
            .'<style>'
            .'body{font-family:Calibri,Arial,sans-serif;font-size:11pt;margin:12px;color:#111;}'
            .'h1{font-size:16pt;margin:0 0 4px;font-weight:700;}'
            .'p.sub{margin:0 0 14px;color:#444;font-size:10pt;}'
            .'table.tbl{border-collapse:collapse;table-layout:fixed;width:100%;max-width:1480px;}'
            .'th{background:#4472C4;color:#fff;font-weight:700;padding:8px 10px;border:1px solid #2f5597;text-align:center;vertical-align:middle;}'
            .'td{padding:6px 10px;border:1px solid #c5c5c5;vertical-align:top;}'
            .'td.c-vmiddle{vertical-align:middle;}'
            .'td.c-num{text-align:center;}'
            .'td.c-date{text-align:left;white-space:nowrap;mso-number-format:\'\\@\';}'
            .'td.c-text{text-align:left;word-wrap:break-word;}'
            .'td.c-rp{text-align:right;white-space:nowrap;font-variant-numeric:tabular-nums;}'
            .'td.c-label-total{text-align:right;font-weight:700;background:#E9EEF5;}'
            .'tr.row-total td{background:#D6DCE5;font-weight:700;}'
            .'tr.row-total td.c-rp-total{mso-number-format:\'\\@\';text-align:right;min-width:160px;white-space:normal;}'
            .'</style></head><body>'
            .'<h1>'.e($title).'</h1>'
            .'<p class="sub">'.e($subtitle).'</p>'
            .'<table class="tbl" border="0">'
            .'<colgroup>'.$colgroup.'</colgroup>'
            .'<thead><tr>'.$th.'</tr></thead>'
            .'<tbody>'.$bodyRows.$footRow.'</tbody>'
            .'</table>'
            .'</body></html>';
    }

    private static function response(string $html, string $filename): Response
    {
        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    /**
     * Tanggal sebagai teks ISO — hindari Excel salah parse dd/mm (jadi ####).
     */
    private static function tanggalTeksExcel(null|Carbon|\DateTimeInterface|string $value): string
    {
        if ($value === null || $value === '') {
            return '—';
        }
        if (! $value instanceof Carbon) {
            $value = Carbon::parse($value);
        }

        return $value->format('Y-m-d');
    }
}
