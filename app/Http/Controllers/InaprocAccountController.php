<?php

namespace App\Http\Controllers;

use App\Models\InaprocAccount;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InaprocAccountController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua filter dari request
        $search = $request->get('search');
        $statusFilter = $request->get('status_filter');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $jenisFilter = $request->get('jenis_filter');
        $perPage = $request->get('per_page', 10);

        // 2. Query untuk Tabel (Data Utama)
        $query = InaprocAccount::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('user_id', 'like', "%{$search}%")
                ->orWhere('opd', 'like', "%{$search}%");
            });
        }

        if ($statusFilter && $statusFilter != 'Semua Tipe') {
            $query->where('status', $statusFilter);
        }

        if ($bulan) $query->whereMonth('tanggal_daftar', $bulan);
        if ($tahun) $query->whereYear('tanggal_daftar', $tahun);
        if ($jenisFilter) $query->where('jenis_data', $jenisFilter);


        // 3. LOGIKA STATISTIK (Cards) - Sekarang dibuat sangat sensitif terhadap semua filter
        $baseDetail = InaprocAccount::query();

        // Filter Waktu
        if ($bulan) $baseDetail->whereMonth('tanggal_daftar', $bulan);
        if ($tahun) $baseDetail->whereYear('tanggal_daftar', $tahun);

        // FILTER BARU: Masukkan filter status ke baseDetail agar angka detail ikut nol jika tidak sesuai
        if ($statusFilter && $statusFilter != 'Semua Tipe') {
            $baseDetail->where('status', $statusFilter);
        }

        // Filter Jenis Data (Jika ada)
        if ($jenisFilter) $baseDetail->where('jenis_data', $jenisFilter);

        $stats = [
            // Total utama (Sama dengan baseDetail karena filternya sudah lengkap)
            'total' => (clone $baseDetail)->count(),
            
            // Statistik Katalog (Otomatis jadi 0 kalau statusFilter-nya bukan PPK/PP/BDH)
            'katalog_ppk' => (clone $baseDetail)->where('jenis_data', 'Katalog v.6')->where('status', 'PPK')->count(),
            'katalog_pp' => (clone $baseDetail)->where('jenis_data', 'Katalog v.6')->where('status', 'PP')->count(),
            'katalog_bendahara' => (clone $baseDetail)->where('jenis_data', 'Katalog v.6')->where('status', 'Bendahara')->count(),
            
            // Statistik SPSE (Otomatis jadi 0 kalau statusFilter-nya bukan PPK/PP/PKJ/KPA)
            'spse_ppk' => (clone $baseDetail)->where('jenis_data', 'SPSE')->where('status', 'PPK')->count(),
            'spse_pp' => (clone $baseDetail)->where('jenis_data', 'SPSE')->where('status', 'PP')->count(),
            'spse_pokja' => (clone $baseDetail)->where('jenis_data', 'SPSE')->where('status', 'POKJA')->count(),
            'spse_lainnya' => (clone $baseDetail)->where('jenis_data', 'SPSE')->whereIn('status', ['PA', 'KPA', 'Auditor'])->count(),
        ];

        // 4. Eksekusi Query Tabel
        $accounts = ($perPage == 'semua') 
            ? $query->orderBy('tanggal_daftar', 'desc')->get() 
            : $query->orderBy('tanggal_daftar', 'desc')->paginate($perPage)->withQueryString();

        return view('inaproc.index', compact('accounts', 'stats'));
    }

    // Menampilkan halaman form tambah data
    public function create()
    {
        return view('inaproc.create');
    }

    // Memproses data yang dikirim dari form tambah ke database
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
            'tanggal_daftar' => 'required|date',
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
        // 1. Tangkap semua filter yang aktif (Sudah benar)
        $jenis = $request->get('jenis');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun') ?? date('Y');
        $search = $request->get('search');
        $statusFilter = $request->get('status_filter');

        // 2. Bangun Query
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

        // --- KOREKSI DI SINI: Ganti created_at menjadi tanggal_daftar ---
        if ($bulan) {
            $query->whereMonth('tanggal_daftar', $bulan);
        }
        
        if ($tahun) {
            $query->whereYear('tanggal_daftar', $tahun);
        }
        // ----------------------------------------------------------------

        // 3. Ambil data dan kelompokkan
        // Michelle tambahkan orderBy agar di PDF urutannya rapi berdasarkan tanggal
        $data = $query->orderBy('tanggal_daftar', 'asc')->get()->groupBy('opd');

        // Sisanya tetap sama...
        $namaBulan = $bulan ? date('F', mktime(0, 0, 0, $bulan, 1)) : '';

        $pdf = Pdf::loadView('inaproc.pdf_report', compact('data', 'namaBulan', 'tahun', 'jenis'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream("Rekapitulasi_{$jenis}.pdf");
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), "r");
        
        fgetcsv($handle); 

        $count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            
            // --- LOGIKA KONVERSI TANGGAL MAS ROBI ---
            $tanggalDaftar = null;
            if (!empty($data[15])) {
                try {
                    // Mengubah format 13/01/2026 menjadi format database 2026-01-13
                    $tanggalDaftar = Carbon::createFromFormat('d/m/Y', trim($data[15]))->format('Y-m-d');
                } catch (\Exception $e) {
                    // Jika formatnya aneh/salah, pakai tanggal hari ini
                    $tanggalDaftar = date('Y-m-d');
                }
            } else {
                $tanggalDaftar = date('Y-m-d');
            }
            // ----------------------------------------

            InaprocAccount::create([
                'nama'               => $data[0],
                'opd'                => $data[1],
                'status'             => $data[2],
                'no_surat_permohonan'=> $data[3],
                'perihal_permohonan' => $data[4],
                'no_sk'              => $data[5],
                'user_id'            => $data[6],
                'nik'                => $data[7],
                'nip'                => $data[8],
                'pangkat_gol'        => $data[9],
                'jabatan'            => $data[10],
                'no_hp'              => $data[11],
                'alamat'             => $data[12],
                'sumber'             => $data[13] ?? 'Digital',
                'jenis_data'         => $data[14] ?? 'Katalog v.6',
                'tanggal_daftar'     => $tanggalDaftar, // Pakai variabel yang sudah dikonversi
            ]);
            $count++;
        }

        fclose($handle);
        return back()->with('success', "Berhasil mengimport $count data Inaproc!");
    }

    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_akun_inaproc.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Kolom sesuai urutan database Mas Robi
        $columns = [
            'nama', 'opd', 'status', 'no_surat_permohonan', 'perihal_permohonan', 
            'no_sk', 'user_id', 'nik', 'nip', 'pangkat_gol', 'jabatan', 
            'no_hp', 'alamat', 'sumber', 'jenis_data', 'tanggal_daftar'
        ];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Berikan satu contoh data dummy agar Mas Robi tidak bingung cara isinya
            fputcsv($file, [
                'Nama Lengkap Contoh', 'Biro Pengadaan', 'PPK', '001/SRT/2026', 'Permohonan Akun', 
                '821.29/123/2026', 'USERID_CONTOH', '520123456789', '19930304202301', 'Penata - III/c', 'Pranata Komputer', 
                '08123456789', 'Jl. Pejanggik No. 1', 'Digital', 'Katalog v.6', '2026-04-12'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}