<?php

namespace App\Http\Controllers;

use App\Models\InaprocAccount;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InaprocAccountController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil filter dari request
        $search = $request->get('search');
        $statusFilter = $request->get('status_filter');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $perPage = $request->get('per_page', 10); // Default 10

        // Query dasar
        $query = InaprocAccount::query();

        // Logika Filter
        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('user_id', 'like', "%{$search}%")
                ->orWhere('opd', 'like', "%{$search}%");
        }

        if ($statusFilter && $statusFilter != 'Semua Tipe') {
            $query->where('status', $statusFilter);
        }

        if ($bulan) {
            $query->whereMonth('created_at', $bulan);
        }

        if ($tahun) {
            $query->whereYear('created_at', $tahun);
        }

        $jenisFilter = $request->get('jenis_filter');
        if ($jenisFilter) {
            $query->where('jenis_data', $jenisFilter);
        }

        // Hitung Statistik untuk Cards
        $stats = [
            'total' => InaprocAccount::count(),
            // Katalog v.6
            'katalog_ppk' => InaprocAccount::where('jenis_data', 'Katalog v.6')->where('status', 'PPK')->count(),
            'katalog_pp' => InaprocAccount::where('jenis_data', 'Katalog v.6')->where('status', 'PP')->count(),
            'katalog_bendahara' => InaprocAccount::where('jenis_data', 'Katalog v.6')->where('status', 'Bendahara')->count(),
            // SPSE
            'spse_ppk' => InaprocAccount::where('jenis_data', 'SPSE')->where('status', 'PPK')->count(),
            'spse_pp' => InaprocAccount::where('jenis_data', 'SPSE')->where('status', 'PP')->count(),
            'spse_pokja' => InaprocAccount::where('jenis_data', 'SPSE')->where('status', 'POKJA')->count(),
            'spse_lainnya' => InaprocAccount::where('jenis_data', 'SPSE')->whereIn('status', ['PA', 'KPA', 'Auditor'])->count(),
        ];

        $accounts = ($perPage == 'semua') ? $query->latest()->get() : $query->latest()->paginate($perPage)->withQueryString();

        return view('inaproc.index', compact('accounts', 'stats'));
    }

    public function create()
    {
        return view('inaproc.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'opd' => 'required',
            'status' => 'required',
            'no_surat_permohonan' => 'required',
            'perihal_permohonan' => 'required',
            'no_sk' => 'required',
            'user_id' => 'required',
            'nik' => 'required|numeric|digits:16',
            'nip' => 'required|numeric|digits:18',
            'pangkat_gol' => 'required',
            'jabatan' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'sumber' => 'required',
            'jenis_data' => 'required|in:Katalog v.6,SPSE',
        ]);

        InaprocAccount::create($validated);

        return redirect()->route('inaproc-accounts.index')
                         ->with('success', 'Data Akun Berhasil Disimpan!');
    }

    // Menampilkan halaman form edit
    public function edit(InaprocAccount $inaprocAccount)
    {
        // Variabel $inaprocAccount otomatis mengambil data berdasarkan ID karena kita pakai Route Resource
        return view('inaproc.edit', compact('inaprocAccount'));
    }

    // Memproses perubahan data ke database
    public function update(Request $request, InaprocAccount $inaprocAccount)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'opd' => 'required',
            'status' => 'required',
            'no_surat_permohonan' => 'required',
            'perihal_permohonan' => 'required',
            'no_sk' => 'required',
            'user_id' => 'required',
            'nik' => 'required|numeric|digits:16',
            'nip' => 'required|numeric|digits:18',
            'pangkat_gol' => 'required',
            'jabatan' => 'required',
            'no_hp' => 'required|numeric',
            'alamat' => 'required',
            'sumber' => 'required',
            'jenis_data' => 'required',
        ]);

        $inaprocAccount->update($validated);

        return redirect()->route('inaproc-accounts.index')
                        ->with('success', 'Data Akun Berhasil Diperbarui!');
    }

    // Menghapus data
    public function destroy(InaprocAccount $inaprocAccount)
    {
        $inaprocAccount->delete();

        return redirect()->route('inaproc-accounts.index')
                        ->with('success', 'Data Akun Berhasil Dihapus!');
    }

    public function exportPdf(Request $request)
    {
        // 1. Tangkap semua filter yang aktif
        $jenis = $request->get('jenis');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun') ?? date('Y');
        $search = $request->get('search');
        $statusFilter = $request->get('status_filter');

        // 2. Bangun Query yang sama dengan Index
        $query = InaprocAccount::where('jenis_data', $jenis);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('opd', 'like', "%{$search}%")
                ->orWhere('user_id', 'like', "%{$search}%");
            });
        }

        if ($statusFilter && $statusFilter != 'Semua Tipe') {
            $query->where('status', $statusFilter);
        }

        if ($bulan) {
            $query->whereMonth('created_at', $bulan);
        }
        
        $query->whereYear('created_at', $tahun);

        // 3. Ambil data dan kelompokkan
        $data = $query->get()->groupBy('opd');

        // KOREKSI: Jika hasil filter kosong, kirim pesan atau tetap proses empty collection
        $namaBulan = $bulan ? date('F', mktime(0, 0, 0, $bulan, 1)) : '';

        $pdf = Pdf::loadView('inaproc.pdf_report', compact('data', 'namaBulan', 'tahun', 'jenis'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream("Rekapitulasi_{$jenis}.pdf");
    }
}