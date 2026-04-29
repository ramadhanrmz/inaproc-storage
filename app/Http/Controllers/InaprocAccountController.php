<?php

namespace App\Http\Controllers;

use App\Models\InaprocAccount;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
            ? $query->orderBy('tanggal_daftar', 'desc')->orderBy('id', 'desc')->get() 
            : $query->orderBy('tanggal_daftar', 'desc')->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

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
            'user_id' => $request->status === 'PP' ? 'required' : 'required|unique:inaproc_accounts,user_id',
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
            'user_id.unique' => 'User ID ini sudah pernah didaftarkan.',
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

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'Data Akun Berhasil Disimpan!');
            return response()->json(['success' => true, 'message' => 'Data Akun Berhasil Disimpan!']);
        }

        return redirect()->route('inaproc-accounts.index')
                         ->with('success', 'Data Akun Berhasil Disimpan!');
    }

    // Mengembalikan data akun dalam format JSON untuk modal edit
    public function show(InaprocAccount $inaprocAccount)
    {
        return response()->json($inaprocAccount);
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
            'user_id' => $request->status === 'PP' ? 'required' : 'required|unique:inaproc_accounts,user_id,' . $inaprocAccount->id,
            'nik' => 'required|numeric|digits:16',
            'nip' => 'required|numeric|digits:18',
            'pangkat_gol' => 'required',
            'jabatan' => 'required',
            'no_hp' => 'required|numeric',
            'alamat' => 'required',
            'sumber' => 'required',
            'jenis_data' => 'required',
        ], [
            'user_id.unique' => 'User ID ini sudah pernah didaftarkan.',
        ]);

        // Format No HP menjadi awalan 62 secara otomatis
        $validated['no_hp'] = '62' . ltrim($validated['no_hp'], '0');

        $inaprocAccount->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'Data Akun Berhasil Diperbarui!');
            return response()->json(['success' => true, 'message' => 'Data Akun Berhasil Diperbarui!']);
        }

        return redirect()->route('inaproc-accounts.index')
                        ->with('success', 'Data Akun Berhasil Diperbarui!');
    }

    // Menghapus data
    public function destroy(InaprocAccount $inaprocAccount)
    {
        $userId = $inaprocAccount->user_id;
        $inaprocAccount->delete();

        return redirect()->route('inaproc-accounts.index')
                        ->with('success', "Data Akun $userId Berhasil Dihapus!");
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

    public function exportXlsx(Request $request)
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

        $data = $query->orderBy('tanggal_daftar', 'desc')->orderBy('id', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Inaproc');

        // Header kolom
        $columns = [
            'No.', 'Nama Lengkap', 'Perangkat Daerah', 'Status', 'No. Surat Permohonan', 'Perihal Permohonan', 
            'No SK', 'User ID', 'NIK', 'NIP', 'Pangkat/Gol', 'Jabatan', 
            'No. WhatsApp', 'Alamat', 'Sumber Data', 'Jenis Data', 'Tanggal Aktif'
        ];

        // Tulis header dengan styling
        $colLetters = range('A', 'Q');
        foreach ($columns as $colIdx => $colName) {
            $cellRef = $colLetters[$colIdx] . '1';
            $sheet->setCellValue($cellRef, $colName);
            $sheet->getStyle($cellRef)->getFont()->setBold(true);
        }

        // Set kolom NIK (I), NIP (J), No HP (M) sebagai format TEKS agar tidak jadi scientific notation
        $sheet->getStyle('I:I')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('J:J')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('M:M')->getNumberFormat()->setFormatCode('@');

        // Tulis data
        $rowNum = 2;
        $no = 1;
        foreach ($data as $row) {
            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $row->nama);
            $sheet->setCellValue("C{$rowNum}", $row->opd);
            $sheet->setCellValue("D{$rowNum}", $row->status);
            $sheet->setCellValue("E{$rowNum}", $row->no_surat_permohonan);
            $sheet->setCellValue("F{$rowNum}", $row->perihal_permohonan);
            $sheet->setCellValue("G{$rowNum}", $row->no_sk);
            $sheet->setCellValue("H{$rowNum}", $row->user_id);
            // NIK, NIP, No HP ditulis sebagai STRING eksplisit agar angka panjang tidak berubah
            $sheet->setCellValueExplicit("I{$rowNum}", $row->nik, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("J{$rowNum}", $row->nip, DataType::TYPE_STRING);
            $sheet->setCellValue("K{$rowNum}", $row->pangkat_gol);
            $sheet->setCellValue("L{$rowNum}", $row->jabatan);
            $sheet->setCellValueExplicit("M{$rowNum}", $row->no_hp, DataType::TYPE_STRING);
            $sheet->setCellValue("N{$rowNum}", $row->alamat);
            $sheet->setCellValue("O{$rowNum}", $row->sumber);
            $sheet->setCellValue("P{$rowNum}", $row->jenis_data);
            $sheet->setCellValue("Q{$rowNum}", Carbon::parse($row->tanggal_daftar)->format('d/m/Y'));
            $rowNum++;
        }

        // Auto-size kolom untuk kerapian
        foreach (range('A', 'Q') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Rekapitulasi_Inaproc_" . date('Y-m-d') . ".xlsx";
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file',
        ]);

        $file = $request->file('csv_file');
        $ext = strtolower($file->getClientOriginalExtension());

        // Jika file XLSX, gunakan PhpSpreadsheet untuk membaca
        if ($ext === 'xlsx' || $ext === 'xls') {
            return $this->importFromXlsx($file);
        }

        // Jika CSV/TXT, gunakan logika lama
        $handle = fopen($file->getRealPath(), "r");
        
        $header = fgetcsv($handle); 
        
        // Deteksi apakah ini format "Export CSV" atau "Template Baru"
        // Export CSV biasanya kolom pertamanya adalah "No." atau "No"
        $firstCol = strtolower(trim($header[0] ?? ''));
        $isExportFormat = ($firstCol === 'no.' || $firstCol === 'no');

        $count = 0;
        $skippedUserIds = [];
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            
            if (empty(array_filter($data))) {
                continue;
            }

            // Tentukan index berdasarkan format CSV
            $idx = $isExportFormat ? 1 : 0; // Jika export, semua index geser 1 karena ada kolom "No."
            
            $nama       = trim($data[$idx] ?? '');
            $opd        = trim($data[$idx + 1] ?? '');
            $status     = trim($data[$idx + 2] ?? '');
            $no_surat   = trim($data[$idx + 3] ?? '');
            $perihal    = trim($data[$idx + 4] ?? '');
            $no_sk      = trim($data[$idx + 5] ?? '');
            $user_id    = trim($data[$idx + 6] ?? '');
            
            // Bersihkan tanda petik (') dan format ="value" dari NIK, NIP, No HP
            $nik        = trim($data[$idx + 7] ?? '');
            $nip        = trim($data[$idx + 8] ?? '');
            $pangkat    = trim($data[$idx + 9] ?? '');
            $jabatan    = trim($data[$idx + 10] ?? '');
            $rawNoHp    = trim($data[$idx + 11] ?? '');
            $alamat     = trim($data[$idx + 12] ?? '');
            $sumber     = trim($data[$idx + 13] ?? 'Digital');
            $jenis_data = trim($data[$idx + 14] ?? 'Katalog v.6');
            $rawTanggal = trim($data[$idx + 15] ?? '');

            // Helper: bersihkan format ="value", tanda petik, dan scientific notation
            // Contoh: ="5201234567890123" -> 5201234567890123
            // Contoh: '5201234567890123 -> 5201234567890123
            // Contoh: 5.27E+15 -> 5270000000000000 (presisi hilang dari Excel)
            $cleanNumericField = function($val) {
                $val = trim($val);
                // Handle format Excel ="value"
                if (preg_match('/^="?(.+?)"?$/', $val, $m)) {
                    $val = $m[1];
                }
                // Hapus tanda petik di awal/akhir
                $val = trim($val, "'\"=");
                // Handle scientific notation (mis: 5.27E+15, 1.99E+17)
                if (preg_match('/^[\d.]+[eE][+\-]?\d+$/', $val)) {
                    $val = sprintf('%.0f', (float)$val);
                }
                // Hanya ambil angka murni
                return preg_replace('/[^0-9]/', '', $val);
            };

            $nik = $cleanNumericField($nik);
            $nip = $cleanNumericField($nip);
            $rawNoHp = $cleanNumericField($rawNoHp);

            // Formatter untuk Nomor HP: normalisasi ke format 62 + 11 digit mulai dari angka 8
            $no_hp = $rawNoHp; // Sudah dibersihkan oleh $cleanNumericField
            if (str_starts_with($no_hp, '62')) {
                $no_hp = substr($no_hp, 2); // Hapus prefix 62 dulu
            }
            if (str_starts_with($no_hp, '0')) {
                $no_hp = substr($no_hp, 1); // Hapus angka 0 di depan
            }
            // Pastikan dimulai dari angka 8, ambil 11 digit
            if (str_starts_with($no_hp, '8')) {
                $no_hp = '62' . substr($no_hp, 0, 11);
            } elseif (!empty($no_hp)) {
                $no_hp = '62' . $no_hp; // Fallback jika format tidak dikenali
            }

            // Pengecekan dibuat SANGAT KETAT hanya berdasarkan User ID, kecuali untuk status PP
            $exists = false;
            if (strtoupper($status) !== 'PP') {
                $exists = InaprocAccount::where('user_id', $user_id)->exists();
            } else {
                // Untuk PP: boleh duplikat user_id dan nama antar OPD yang berbeda.
                // Jika user_id sama DAN opd sama, maka dianggap duplikat (tidak boleh).
                // Jika user_id berbeda TETAPI opd sama, maka BOLEH (jangan dianggap duplikat).
                $exists = InaprocAccount::where('status', 'PP')
                                        ->where('user_id', $user_id)
                                        ->where('opd', $opd)
                                        ->exists();
            }

            if ($exists) {
                // Simpan keterangan yang gagal karena duplikat
                if (!empty($user_id)) {
                    if (strtoupper($status) === 'PP') {
                        $skippedUserIds[] = $user_id . ' (OPD: ' . $opd . ')';
                    } else {
                        $skippedUserIds[] = $user_id;
                    }
                }
                continue;
            }

            // --- LOGIKA KONVERSI TANGGAL MAS ROBI ---
            $tanggalDaftar = null;
            if (!empty($rawTanggal)) {
                try {
                    // Beberapa versi PHP/Excel bisa mengubah / menjadi -
                    $rawTanggal = str_replace('-', '/', $rawTanggal);
                    $tanggalDaftar = Carbon::createFromFormat('d/m/Y', $rawTanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggalDaftar = date('Y-m-d');
                }
            } else {
                $tanggalDaftar = date('Y-m-d');
            }
            // ----------------------------------------

            InaprocAccount::create([
                'nama'               => $nama,
                'opd'                => $opd,
                'status'             => $status,
                'no_surat_permohonan'=> $no_surat,
                'perihal_permohonan' => $perihal,
                'no_sk'              => $no_sk,
                'user_id'            => $user_id,
                'nik'                => $nik,
                'nip'                => $nip,
                'pangkat_gol'        => $pangkat,
                'jabatan'            => $jabatan,
                'no_hp'              => $no_hp,
                'alamat'             => $alamat,
                'sumber'             => $sumber,
                'jenis_data'         => $jenis_data,
                'tanggal_daftar'     => $tanggalDaftar,
            ]);
            $count++;
        }

        fclose($handle);
        
        $response = back()->with('success', "Berhasil mengimport $count data Inaproc!");
        
        // Jika ada data yang terlewat karena duplikat
        if (count($skippedUserIds) > 0) {
            $response->with('error', 'Gagal memproses beberapa akun karena User ID sudah terdaftar: ' . implode(', ', $skippedUserIds));
        }
        
        return $response;
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import');

        // Kolom sesuai urutan database
        $columns = [
            'nama', 'opd', 'status', 'no_surat_permohonan', 'perihal_permohonan', 
            'no_sk', 'user_id', 'nik', 'nip', 'pangkat_gol', 'jabatan', 
            'no_hp', 'alamat', 'sumber', 'jenis_data', 'tanggal_daftar'
        ];

        // Tulis header
        $colLetters = range('A', 'P');
        foreach ($columns as $colIdx => $colName) {
            $cellRef = $colLetters[$colIdx] . '1';
            $sheet->setCellValue($cellRef, $colName);
            $sheet->getStyle($cellRef)->getFont()->setBold(true);
        }

        // Set kolom NIK (H), NIP (I), No HP (L) sebagai teks (format template tanpa kolom No.)
        $sheet->getStyle('H:H')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('I:I')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('L:L')->getNumberFormat()->setFormatCode('@');

        // Contoh data dummy
        $sheet->setCellValue('A2', 'Nama Lengkap Contoh');
        $sheet->setCellValue('B2', 'Biro Pengadaan');
        $sheet->setCellValue('C2', 'PPK');
        $sheet->setCellValue('D2', '001/SRT/2026');
        $sheet->setCellValue('E2', 'Permohonan Akun');
        $sheet->setCellValue('F2', '821.29/123/2026');
        $sheet->setCellValue('G2', 'USERID_CONTOH');
        $sheet->setCellValueExplicit('H2', '5201234567890123', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('I2', '199303042023011001', DataType::TYPE_STRING);
        $sheet->setCellValue('J2', 'Penata - III/c');
        $sheet->setCellValue('K2', 'Pranata Komputer');
        $sheet->setCellValueExplicit('L2', '08123456789', DataType::TYPE_STRING);
        $sheet->setCellValue('M2', 'Jl. Pejanggik No. 1');
        $sheet->setCellValue('N2', 'Digital');
        $sheet->setCellValue('O2', 'Katalog v.6');
        $sheet->setCellValue('P2', '12/04/2026');

        // Auto-size
        foreach (range('A', 'P') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'tmpl_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, 'template_akun_inaproc.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Import data dari file XLSX menggunakan PhpSpreadsheet
     */
    private function importFromXlsx($file)
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            return back()->with('error', 'File XLSX kosong.');
        }

        // Ambil header dan deteksi format
        $header = array_shift($rows);
        $firstCol = strtolower(trim($header[0] ?? ''));
        $isExportFormat = ($firstCol === 'no.' || $firstCol === 'no');

        $count = 0;
        $skippedUserIds = [];
        $invalidRows = [];
        $allowedStatuses = ['PPK', 'PP', 'Bendahara', 'POKJA', 'Auditor', 'PA', 'KPA'];

        // Helper: bersihkan nilai numerik
        $cleanNumericField = function($val) {
            $val = trim((string)$val);
            if (preg_match('/^="?(.+?)"?$/', $val, $m)) {
                $val = $m[1];
            }
            $val = trim($val, "'\"=");
            if (preg_match('/^[\d.]+[eE][+\-]?\d+$/', $val)) {
                $val = sprintf('%.0f', (float)$val);
            }
            return preg_replace('/[^0-9]/', '', $val);
        };

        foreach ($rows as $data) {
            if (empty(array_filter($data))) {
                continue;
            }

            $idx = $isExportFormat ? 1 : 0;

            $nama       = trim((string)($data[$idx] ?? ''));
            $opd        = trim((string)($data[$idx + 1] ?? ''));
            $status     = trim((string)($data[$idx + 2] ?? ''));
            $no_surat   = trim((string)($data[$idx + 3] ?? ''));
            $perihal    = trim((string)($data[$idx + 4] ?? ''));
            $no_sk      = trim((string)($data[$idx + 5] ?? ''));
            $user_id    = trim((string)($data[$idx + 6] ?? ''));
            $nik        = $cleanNumericField($data[$idx + 7] ?? '');
            $nip        = $cleanNumericField($data[$idx + 8] ?? '');
            $pangkat    = trim((string)($data[$idx + 9] ?? ''));
            $jabatan    = trim((string)($data[$idx + 10] ?? ''));
            $rawNoHp    = $cleanNumericField($data[$idx + 11] ?? '');
            $alamat     = trim((string)($data[$idx + 12] ?? ''));
            $sumber     = trim((string)($data[$idx + 13] ?? '')) ?: 'Digital';
            $jenis_data = trim((string)($data[$idx + 14] ?? '')) ?: 'Katalog v.6';
            $rawTanggal = trim((string)($data[$idx + 15] ?? ''));

            // Formatter No HP
            $no_hp = $rawNoHp;
            if (str_starts_with($no_hp, '62')) {
                $no_hp = substr($no_hp, 2);
            }
            if (str_starts_with($no_hp, '0')) {
                $no_hp = substr($no_hp, 1);
            }
            if (str_starts_with($no_hp, '8')) {
                $no_hp = '62' . substr($no_hp, 0, 11);
            } elseif (!empty($no_hp)) {
                $no_hp = '62' . $no_hp;
            }

            // Cek duplikat — semua kolom harus sama agar dianggap duplikat
            $exists = InaprocAccount::where('nama', $nama)
                                    ->where('opd', $opd)
                                    ->where('status', $status)
                                    ->where('no_surat_permohonan', $no_surat)
                                    ->where('perihal_permohonan', $perihal)
                                    ->where('no_sk', $no_sk)
                                    ->where('user_id', $user_id)
                                    ->where('nik', $nik)
                                    ->where('nip', $nip)
                                    ->where('pangkat_gol', $pangkat)
                                    ->where('jabatan', $jabatan)
                                    ->where('no_hp', $no_hp)
                                    ->where('alamat', $alamat)
                                    ->where('sumber', $sumber)
                                    ->where('jenis_data', $jenis_data)
                                    ->exists();

            if ($exists) {
                $rowLabel = !empty($nama) ? $nama : (!empty($user_id) ? $user_id : 'Baris tanpa nama');
                $skippedUserIds[] = $rowLabel;
                continue;
            }

            // Validasi & normalisasi status terhadap ENUM (case-insensitive)
            $statusMap = array_combine(
                array_map('strtoupper', $allowedStatuses),
                $allowedStatuses
            ); // ['PPK'=>'PPK', 'PP'=>'PP', 'BENDAHARA'=>'Bendahara', 'POKJA'=>'POKJA', 'AUDITOR'=>'Auditor', 'PA'=>'PA', 'KPA'=>'KPA']

            $statusUpper = strtoupper($status);
            if (!isset($statusMap[$statusUpper])) {
                $rowLabel = !empty($nama) ? $nama : (!empty($user_id) ? $user_id : 'Baris tanpa nama');
                $invalidRows[] = $rowLabel . ' (Status: "' . ($status ?: 'kosong') . '")';
                continue;
            }
            $status = $statusMap[$statusUpper]; // Normalisasi ke format ENUM yang benar

            // Konversi tanggal
            $tanggalDaftar = null;
            if (!empty($rawTanggal)) {
                try {
                    $rawTanggal = str_replace('-', '/', $rawTanggal);
                    $tanggalDaftar = Carbon::createFromFormat('d/m/Y', $rawTanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggalDaftar = date('Y-m-d');
                }
            } else {
                $tanggalDaftar = date('Y-m-d');
            }

            try {
                InaprocAccount::create([
                    'nama'               => $nama,
                    'opd'                => $opd,
                    'status'             => $status,
                    'no_surat_permohonan'=> $no_surat,
                    'perihal_permohonan' => $perihal,
                    'no_sk'              => $no_sk,
                    'user_id'            => $user_id,
                    'nik'                => $nik,
                    'nip'                => $nip,
                    'pangkat_gol'        => $pangkat,
                    'jabatan'            => $jabatan,
                    'no_hp'              => $no_hp,
                    'alamat'             => $alamat,
                    'sumber'             => $sumber,
                    'jenis_data'         => $jenis_data,
                    'tanggal_daftar'     => $tanggalDaftar,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                $rowLabel = !empty($nama) ? $nama : (!empty($user_id) ? $user_id : 'Baris tanpa nama');
                $invalidRows[] = $rowLabel . ' (DB Error: ' . $e->getMessage() . ')';
                continue;
            }
            $count++;
        }

        $response = back();

        if ($count > 0) {
            $response->with('success', "Berhasil mengimport $count data Inaproc!");
        }
        if (count($skippedUserIds) > 0) {
            $response->with('error', 'Data duplikat terdeteksi (semua kolom sama), tidak diimport: ' . implode(', ', $skippedUserIds));
        }
        if (count($invalidRows) > 0) {
            $response->with('import_errors', $invalidRows);
        }
        if ($count === 0 && count($skippedUserIds) === 0 && count($invalidRows) === 0) {
            $response->with('error', 'Tidak ada data yang berhasil diimport. Pastikan file tidak kosong.');
        }
        return $response;
    }
}