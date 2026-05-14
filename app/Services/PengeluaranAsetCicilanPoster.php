<?php

namespace App\Services;

use App\Models\Pengeluaran;
use App\Models\PengeluaranAsetCicilan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PengeluaranAsetCicilanPoster
{
    /**
     * Buat baris pengeluaran untuk setiap cicilan aset yang sudah jatuh tempo, aktif, dan belum terposting.
     *
     * @return int jumlah cicilan yang berhasil diposting
     */
    public static function postJatuhTempo(?Carbon $sampaiTanggal = null): int
    {
        $sampai = ($sampaiTanggal ?? now())->toDateString();
        $posted = 0;

        $ids = PengeluaranAsetCicilan::query()
            ->where('is_aktif', true)
            ->whereNull('pengeluaran_id')
            ->whereDate('tanggal', '<=', $sampai)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->pluck('id');

        foreach ($ids as $id) {
            $done = DB::transaction(function () use ($id) {
                $c = PengeluaranAsetCicilan::query()->whereKey($id)->lockForUpdate()->first();
                if (! $c || $c->pengeluaran_id || ! $c->is_aktif) {
                    return false;
                }

                $aset = $c->aset()->lockForUpdate()->first();
                if (! $aset) {
                    return false;
                }

                $ket = $aset->keterangan.' — Cicilan ke-'.$c->urutan.'/'.$aset->jumlah_bulan;

                $p = Pengeluaran::create([
                    'tanggal' => $c->tanggal->toDateString(),
                    'kategori' => 'Aset',
                    'keterangan' => $ket,
                    'nominal' => $c->nominal,
                    'bukti_foto' => null,
                    'dibuat_oleh' => $aset->dibuat_oleh,
                    'pengeluaran_aset_cicilan_id' => $c->id,
                ]);

                $c->update(['pengeluaran_id' => $p->id]);

                return true;
            });

            if ($done) {
                $posted++;
            }
        }

        return $posted;
    }

    /**
     * Pecah total ke N bagian (rupiah bulat); sisa pembulatan ditambahkan ke cicilan terakhir.
     *
     * @return list<array{urutan:int, nominal:float}>
     */
    public static function bagiNominalPerBulan(float $total, int $bulan): array
    {
        $bulan = max(1, $bulan);
        $totalSen = (int) round($total * 100);
        $baseSen = intdiv($totalSen, $bulan);
        $sisaSen = $totalSen - ($baseSen * $bulan);
        $out = [];
        for ($i = 1; $i <= $bulan; $i++) {
            $extra = ($i === $bulan) ? $sisaSen : 0;
            $out[] = [
                'urutan' => $i,
                'nominal' => ($baseSen + $extra) / 100,
            ];
        }

        return $out;
    }

    /**
     * Tanggal cicilan ke-i (1-based), sama tanggal kalender jika memungkinkan.
     */
    public static function tanggalCicilan(Carbon $mulai, int $urutan): Carbon
    {
        return $mulai->copy()->startOfDay()->addMonths($urutan - 1);
    }
}
