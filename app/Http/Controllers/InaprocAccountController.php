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
        $startMonth = $request->get('start_month');
        $endMonth = $request->get('end_month');
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

        if ($startMonth || $endMonth) {
            $s = $startMonth ? (int)$startMonth : 1;
            $e = $endMonth ? (int)$endMonth : 12;
            if ($s > $e) $e = $s;
            $query->whereMonth('tanggal_daftar', '>=', $s)
                  ->whereMonth('tanggal_daftar', '<=', $e);
        }
        
        if ($tahun) $query->whereYear('tanggal_daftar', $tahun);
        if ($jenisFilter) $query->where('jenis_data', $jenisFilter);


        // 3. LOGIKA STATISTIK (Cards) - Sekarang dibuat sangat sensitif terhadap semua filter
        $baseDetail = InaprocAccount::query();

        // Filter Waktu
        if ($startMonth || $endMonth) {
            $s = $startMonth ? (int)$startMonth : 1;
            $e = $endMonth ? (int)$endMonth : 12;
            if ($s > $e) $e = $s;
            $baseDetail->whereMonth('tanggal_daftar', '>=', $s)
                       ->whereMonth('tanggal_daftar', '<=', $e);
        }
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
            ? $query->orderBy('id', 'desc')->get() 
            : $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

        return view('inaproc.index', compact('accounts', 'stats'));
    }

    public function grafik(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        $katalogStart = $request->get('katalog_start');
        $katalogEnd = $request->get('katalog_end');
        $spseStart = $request->get('spse_start');
        $spseEnd = $request->get('spse_end');

        // Set default filter values to full year if not set
        $kStart = $katalogStart ? (int)$katalogStart : 1;
        $kEnd = $katalogEnd ? (int)$katalogEnd : 12;
        if ($kStart > $kEnd) $kEnd = $kStart; // fallback if start > end

        $sStart = $spseStart ? (int)$spseStart : 1;
        $sEnd = $spseEnd ? (int)$spseEnd : 12;
        if ($sStart > $sEnd) $sEnd = $sStart; 

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // === KATALOG V.6 DATA ===
        $katalogLabels = [];
        $katalogPpk = [];
        $katalogPp = [];
        $katalogBendahara = [];
        $katalogAkumulasi = [];
        $katalogRunningTotal = 0;
        $katalogTotalFilter = 0;

        for ($m = 1; $m <= 12; $m++) {
            $ppk = InaprocAccount::where('jenis_data', 'Katalog v.6')
                ->where('status', 'PPK')
                ->whereMonth('tanggal_daftar', $m)
                ->whereYear('tanggal_daftar', $tahun)
                ->count();

            $pp = InaprocAccount::where('jenis_data', 'Katalog v.6')
                ->where('status', 'PP')
                ->whereMonth('tanggal_daftar', $m)
                ->whereYear('tanggal_daftar', $tahun)
                ->count();

            $bdh = InaprocAccount::where('jenis_data', 'Katalog v.6')
                ->where('status', 'Bendahara')
                ->whereMonth('tanggal_daftar', $m)
                ->whereYear('tanggal_daftar', $tahun)
                ->count();

            $totalBulan = $ppk + $pp + $bdh;

            if ($totalBulan > 0) {
                $katalogRunningTotal += $totalBulan;
                
                if ($m >= $kStart && $m <= $kEnd) {
                    $katalogTotalFilter += $totalBulan;
                    $katalogLabels[] = $bulanNames[$m];
                    $katalogPpk[] = $ppk;
                    $katalogPp[] = $pp;
                    $katalogBendahara[] = $bdh;
                    $katalogAkumulasi[] = [
                        'bulan' => $bulanNames[$m],
                        'total' => $katalogRunningTotal,
                    ];
                }
            }
        }

        $katalogData = [
            'labels' => $katalogLabels,
            'ppk' => $katalogPpk,
            'pp' => $katalogPp,
            'bendahara' => $katalogBendahara,
            'akumulasi' => $katalogAkumulasi,
            'total_filter' => $katalogTotalFilter,
        ];

        // === SPSE DATA ===
        $spseLabels = [];
        $spsePpk = [];
        $spsePp = [];
        $spseAkumulasi = [];
        $spseRunningTotal = 0;
        $spseTotalFilter = 0;

        for ($m = 1; $m <= 12; $m++) {
            $ppk = InaprocAccount::where('jenis_data', 'SPSE')
                ->where('status', 'PPK')
                ->whereMonth('tanggal_daftar', $m)
                ->whereYear('tanggal_daftar', $tahun)
                ->count();

            $pp = InaprocAccount::where('jenis_data', 'SPSE')
                ->where('status', 'PP')
                ->whereMonth('tanggal_daftar', $m)
                ->whereYear('tanggal_daftar', $tahun)
                ->count();

            $totalBulan = $ppk + $pp;

            if ($totalBulan > 0) {
                $spseRunningTotal += $totalBulan;
                
                if ($m >= $sStart && $m <= $sEnd) {
                    $spseTotalFilter += $totalBulan;
                    $spseLabels[] = $bulanNames[$m];
                    $spsePpk[] = $ppk;
                    $spsePp[] = $pp;
                    $spseAkumulasi[] = [
                        'bulan' => $bulanNames[$m],
                        'total' => $spseRunningTotal,
                    ];
                }
            }
        }

        $spseData = [
            'labels' => $spseLabels,
            'ppk' => $spsePpk,
            'pp' => $spsePp,
            'akumulasi' => $spseAkumulasi,
            'total_filter' => $spseTotalFilter,
        ];

        return view('inaproc.grafik', compact('katalogData', 'spseData', 'tahun'));
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
        ], [
            'nama.required' => 'Nama Lengkap wajib diisi.',
            'opd.required' => 'Perangkat Daerah (OPD) wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'no_surat_permohonan.required' => 'No. Surat Permohonan wajib diisi.',
            'perihal_permohonan.required' => 'Perihal Permohonan wajib dipilih.',
            'no_sk.required' => 'Nomor SK wajib diisi.',
            'user_id.required' => 'User ID wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits' => 'NIK harus tepat 16 digit.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.numeric' => 'NIP harus berupa angka.',
            'nip.digits' => 'NIP harus tepat 18 digit.',
            'pangkat_gol.required' => 'Pangkat/Golongan wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'no_hp.required' => 'No. WhatsApp wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'sumber.required' => 'Sumber Data wajib dipilih.',
            'jenis_data.required' => 'Jenis Data wajib dipilih.',
            'jenis_data.in' => 'Jenis Data harus Katalog v.6 atau SPSE.',
            'tanggal_daftar.required' => 'Tanggal Pengaktifan wajib diisi.',
            'tanggal_daftar.date' => 'Format tanggal tidak valid.',
        ]);

        // Format No HP menjadi awalan 62 secara otomatis
        $validated['no_hp'] = '62' . ltrim($validated['no_hp'], '0');

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

        // Format No HP menjadi awalan 62 secara otomatis
        $validated['no_hp'] = '62' . ltrim($validated['no_hp'], '0');

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
        // Urutkan berdasarkan nama OPD secara abjad A-Z agar laporan PDF rapi
        $data = $query->orderBy('opd', 'asc')->get()->groupBy('opd');

        // Sisanya tetap sama...
        $namaBulan = $bulan ? date('F', mktime(0, 0, 0, $bulan, 1)) : '';

        $pdf = Pdf::loadView('inaproc.pdf_report', compact('data', 'namaBulan', 'tahun', 'jenis'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream("Rekapitulasi_{$jenis}.pdf");
    }

    public function exportCsv(Request $request)
    {
        $search = $request->get('search');
        $statusFilter = $request->get('status_filter');
        $startMonth = $request->get('start_month');
        $endMonth = $request->get('end_month');
        $tahun = $request->get('tahun', date('Y'));
        $jenisFilter = $request->get('jenis_filter');

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

        if ($startMonth || $endMonth) {
            $s = $startMonth ? (int)$startMonth : 1;
            $e = $endMonth ? (int)$endMonth : 12;
            if ($s > $e) $e = $s;
            $query->whereMonth('tanggal_daftar', '>=', $s)
                  ->whereMonth('tanggal_daftar', '<=', $e);
        }
        
        if ($tahun) {
            $query->whereYear('tanggal_daftar', $tahun);
        }

        if ($jenisFilter) {
            $query->where('jenis_data', $jenisFilter);
        }

        $data = $query->orderBy('id', 'desc')->get();

        $filename = "Rekapitulasi_Inaproc_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'No.', 'Nama Lengkap', 'Perangkat Daerah', 'Status', 'No. Surat Permohonan', 'Perihal Permohonan', 
            'No SK', 'User ID', 'NIK', 'NIP', 'Pangkat/Gol', 'Jabatan', 
            'No. WhatsApp', 'Alamat', 'Sumber Data', 'Jenis Data', 'Tanggal Aktif'
        ];

        $callback = function() use($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            $no = 1;
            foreach ($data as $row) {
                fputcsv($file, [
                    $no++,
                    $row->nama,
                    $row->opd,
                    $row->status,
                    $row->no_surat_permohonan,
                    $row->perihal_permohonan,
                    $row->no_sk,
                    $row->user_id,
                    "'" . $row->nik, // Format teks agar angka panjang tidak berantakan di Excel
                    "'" . $row->nip,
                    $row->pangkat_gol,
                    $row->jabatan,
                    "'" . $row->no_hp,
                    $row->alamat,
                    $row->sumber,
                    $row->jenis_data,
                    \Carbon\Carbon::parse($row->tanggal_daftar)->format('d/m/Y')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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