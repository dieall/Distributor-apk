<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\PengeluaranAset;
use App\Models\PengeluaranAsetCicilan;
use App\Services\HtmlExcelExport;
use App\Services\PengeluaranAsetCicilanPoster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('pembuat')->latest('tanggal')->latest();

        if ($request->filled('bulan')) {
            [$year, $month] = explode('-', $request->bulan);
            $query->whereYear('tanggal', $year)->whereMonth('tanggal', $month);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kategori', 'like', "%{$request->search}%")
                  ->orWhere('keterangan', 'like', "%{$request->search}%");
            });
        }

        $pengeluaran = $query->paginate(15)->withQueryString();
        $total = (clone $query)->sum('nominal');

        return view('admin.pengeluaran.index', compact('pengeluaran', 'total'));
    }

    public function show(Pengeluaran $pengeluaran)
    {
        $pengeluaran->load('pembuat', 'asetCicilan.aset');

        return view('admin.pengeluaran.show', compact('pengeluaran'));
    }

    public function buktiFoto(string $path)
    {
        $normalizedPath = str_replace('\\', '/', ltrim($path, '/'));

        if (str_starts_with($normalizedPath, 'storage/')) {
            $normalizedPath = substr($normalizedPath, 8);
        }

        if (str_starts_with($normalizedPath, 'public/')) {
            $normalizedPath = substr($normalizedPath, 7);
        }

        abort_unless(str_starts_with($normalizedPath, 'bukti-pengeluaran/'), 404);
        abort_unless(Storage::disk('public')->exists($normalizedPath), 404);

        return response()->file(storage_path('app/public/' . $normalizedPath));
    }

    public function create()
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        return view('admin.pengeluaran.create');
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');

        if ($request->input('kategori') === 'Aset') {
            $data = $request->validate([
                'tanggal' => 'required|date',
                'keterangan' => 'required|string|max:255',
                'total_nilai' => 'required|numeric|min:1',
                'jumlah_bulan' => 'required|integer|min:2|max:120',
                'bukti_foto' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            $bukti = null;
            if ($request->hasFile('bukti_foto')) {
                $bukti = $request->file('bukti_foto')->store('bukti-pengeluaran', 'public');
            }

            $mulai = \Illuminate\Support\Carbon::parse($data['tanggal'])->startOfDay();
            $parts = PengeluaranAsetCicilanPoster::bagiNominalPerBulan((float) $data['total_nilai'], (int) $data['jumlah_bulan']);

            $aset = DB::transaction(function () use ($data, $bukti, $mulai, $parts) {
                $aset = PengeluaranAset::create([
                    'keterangan' => $data['keterangan'],
                    'total_nilai' => $data['total_nilai'],
                    'jumlah_bulan' => $data['jumlah_bulan'],
                    'tanggal_mulai' => $mulai->toDateString(),
                    'bukti_foto' => $bukti,
                    'dibuat_oleh' => auth()->id(),
                ]);

                foreach ($parts as $row) {
                    $tgl = PengeluaranAsetCicilanPoster::tanggalCicilan($mulai, $row['urutan']);
                    PengeluaranAsetCicilan::create([
                        'pengeluaran_aset_id' => $aset->id,
                        'urutan' => $row['urutan'],
                        'tanggal' => $tgl->toDateString(),
                        'nominal' => $row['nominal'],
                        'is_aktif' => true,
                    ]);
                }

                return $aset;
            });

            PengeluaranAsetCicilanPoster::postJatuhTempo();

            return redirect()->route(fin_route_n('pengeluaran.aset.edit'), $aset)
                ->with('success', 'Jadwal penyusutan aset tersimpan. Nominal per bulan otomatis dibagi rata; cicilan yang sudah lewat tanggal akan langsung masuk ke pengeluaran. Jadwal harian (06:00) juga mem-posting cicilan baru.');
        }

        $data = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:80',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'bukti_foto' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti-pengeluaran', 'public');
        }

        $data['dibuat_oleh'] = auth()->id();
        $pengeluaran = Pengeluaran::create($data);

        return redirect()->route(fin_route_n('pengeluaran.show'), $pengeluaran)->with('success', 'Pengeluaran berhasil disimpan.');
    }

    public function export(Request $request)
    {
        $query = Pengeluaran::with('pembuat')->latest('tanggal')->latest();

        if ($request->filled('bulan')) {
            [$year, $month] = explode('-', $request->bulan);
            $query->whereYear('tanggal', $year)->whereMonth('tanggal', $month);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kategori', 'like', "%{$request->search}%")
                  ->orWhere('keterangan', 'like', "%{$request->search}%");
            });
        }

        $data = $query->get();

        $stem = 'pengeluaran';
        if ($request->filled('bulan')) {
            $stem .= '_'.$request->bulan;
        } else {
            $stem .= '_semua';
        }

        return HtmlExcelExport::pengeluaran($data, $stem);
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        if ($pengeluaran->pengeluaran_aset_cicilan_id) {
            PengeluaranAsetCicilan::whereKey($pengeluaran->pengeluaran_aset_cicilan_id)->update(['pengeluaran_id' => null]);
        }
        if ($pengeluaran->bukti_foto) {
            Storage::disk('public')->delete($pengeluaran->bukti_foto);
        }
        $pengeluaran->delete();

        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
