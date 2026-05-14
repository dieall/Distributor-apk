<?php

namespace App\Console\Commands;

use App\Services\PengeluaranAsetCicilanPoster;
use Illuminate\Console\Command;

class PostPengeluaranAsetCicilanCommand extends Command
{
    protected $signature = 'pengeluaran:post-aset-cicilan';

    protected $description = 'Posting cicilan penyusutan aset ke tabel pengeluaran (tanggal jatuh tempo <= hari ini)';

    public function handle(): int
    {
        $n = PengeluaranAsetCicilanPoster::postJatuhTempo();
        $this->info("Cicilan terposting: {$n}");

        return self::SUCCESS;
    }
}
