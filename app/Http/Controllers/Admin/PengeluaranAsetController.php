<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengeluaranAset;
use App\Models\PengeluaranAsetCicilan;
use App\Services\PengeluaranAsetCicilanPoster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranAsetController extends Controller
{
    public function index()
    {
        $aset = PengeluaranAset::with('dibuatOleh')
            ->withCount([
                'cicilans as cicilan_terposting' => fn ($q) => $q->whereNotNull('pengeluaran_id'),
                'cicilans as cicilan_total',
            ])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.pengeluaran.aset.index', compact('aset'));
    }

    public function edit(PengeluaranAset $pengeluaranAset)
    {
        $pengeluaranAset->load(['cicilans' => fn ($q) => $q->orderBy('urutan')->with('pengeluaran'), 'dibuatOleh']);
        $readonly = auth()->user()->isDirektur();

        return view('admin.pengeluaran.aset.edit', ['aset' => $pengeluaranAset, 'readonly' => $readonly]);
    }

    public function update(Request $request, PengeluaranAset $pengeluaranAset)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');

        $aktif = $request->input('aktif', []);

        DB::transaction(function () use ($pengeluaranAset, $aktif) {
            foreach ($pengeluaranAset->cicilans()->whereNull('pengeluaran_id')->orderBy('urutan')->lockForUpdate()->get() as $c) {
                $key = (string) $c->id;
                $isAktif = isset($aktif[$key]) && (string) $aktif[$key] === '1';
                $c->update(['is_aktif' => $isAktif]);
            }
        });

        PengeluaranAsetCicilanPoster::postJatuhTempo();

        return redirect()
            ->route(fin_route_n('pengeluaran.aset.edit'), $pengeluaranAset)
            ->with('success', 'Jadwal cicilan aset diperbarui. Hanya bulan yang dicentang yang akan diposting (jika tanggalnya sudah lewat).');
    }
}
