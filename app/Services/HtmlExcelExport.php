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
        $grand = (int) $data->sum(fn ($item) => $item->detail->sum('subtotal_jual'));

        $rows = '';
        foreach ($data as $i => $item) {
            $total = (int) $item->detail->sum('subtotal_jual');
            $tgl = self::tanggalTeksExcel($item->tanggal_request);
            $rows .= '<tr>'
                .'<td class="c-num">'.($i + 1).'</td>'
                .'<td class="c-text">'.e($item->no_permintaan).'</td>'
                .'<td class="c-text">'.e($item->pelanggan->name ?? '—').'</td>'
                .'<td class="c-date" style="mso-number-format:\'\\@\'">'.e($tgl).'</td>'
                .'<td class="c-text">'.e(optional($item->suratJalan)->no_sj ?? '—').'</td>'
                .'<td class="c-text">'.e($item->status_label).'</td>'
                .'<td class="c-rp">'.e(number_format($total, 0, ',', '.')).'</td>'
                .'</tr>';
        }

        $html = self::wrapTable(
            title: 'Laporan Invoice / Permintaan Pelanggan',
            subtitle: 'Diekspor: '.now()->format('d/m/Y H:i').' · Status: Siap kirim dan Selesai',
            head: ['No', 'No. Permintaan', 'Pelanggan', 'Tanggal (YYYY-MM-DD)', 'No. Surat Jalan', 'Status', 'Total (Rp)'],
            colgroupWidths: ['44px', '120px', '160px', '118px', '120px', '100px', '110px'],
            bodyRows: $rows,
            footRow: '<tr class="row-total">'
                .'<td colspan="6" class="c-label-total">TOTAL</td>'
                .'<td class="c-rp">'.e(number_format($grand, 0, ',', '.')).'</td>'
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
            .'table.tbl{border-collapse:collapse;table-layout:fixed;width:100%;max-width:1200px;}'
            .'th{background:#4472C4;color:#fff;font-weight:700;padding:8px 10px;border:1px solid #2f5597;text-align:center;vertical-align:middle;}'
            .'td{padding:6px 10px;border:1px solid #c5c5c5;vertical-align:top;}'
            .'td.c-num{text-align:center;}'
            .'td.c-date{text-align:left;white-space:nowrap;mso-number-format:\'\\@\';}'
            .'td.c-text{text-align:left;word-wrap:break-word;}'
            .'td.c-rp{text-align:right;white-space:nowrap;font-variant-numeric:tabular-nums;}'
            .'td.c-label-total{text-align:right;font-weight:700;background:#E9EEF5;}'
            .'tr.row-total td{background:#D6DCE5;font-weight:700;}'
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
