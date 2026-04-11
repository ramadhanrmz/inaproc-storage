<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekapitulasi</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 14px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 2px solid black; padding: 8px; }
        
        .bg-maroon { background-color: #990000; color: white; text-align: center; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        .footer { margin-top: 30px; float: right; width: 250px; text-align: center; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Rekapitulasi Registrasi dan Aktivasi Akun INAPROC Non Penyedia {{ strtoupper($jenis) }}</h2>
        <h2>Pemerintah Provinsi NTB</h2>
        @if($namaBulan) <h3>Bulan: {{ $namaBulan }} {{ $tahun }}</h3> @endif
    </div>

    <table>
        <thead>
            <tr class="bg-maroon">
                <th rowspan="2" style="width: 30px;">NO</th>
                <th rowspan="2">NAMA PERANGKAT DAERAH</th>
                <th colspan="{{ $jenis == 'SPSE' ? 2 : 3 }}">AKUN</th>
                <th rowspan="2">KETERANGAN</th>
            </tr>
            <tr class="bg-maroon">
                <th style="width: 50px;">PPK</th>
                <th style="width: 50px;">PP</th>
                @if($jenis == 'Katalog v.6')
                    <th style="width: 80px;">BENDAHARA</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php 
                $no = 1; 
                $totalPPK = 0; $totalPP = 0; $totalBendahara = 0;
            @endphp
            
            @forelse($data as $opd => $accounts)
                @php
                    $countPPK = $accounts->where('status', 'PPK')->count();
                    $countPP = $accounts->where('status', 'PP')->count();
                    $countBendahara = $accounts->where('status', 'Bendahara')->count();
                    
                    // Logika: Hanya tampilkan jika setidaknya ada 1 akun di kategori manapun
                    $hasData = ($countPPK + $countPP + $countBendahara) > 0;
                @endphp

                @if($hasData)
                    @php
                        $totalPPK += $countPPK;
                        $totalPP += $countPP;
                        $totalBendahara += $countBendahara;
                    @endphp
                    <tr>
                        <td class="text-center font-bold">{{ $no++ }}</td>
                        <td class="font-bold">{{ $opd }}</td>
                        <td class="text-center">{{ $countPPK ?: '' }}</td>
                        <td class="text-center">{{ $countPP ?: '' }}</td>
                        @if($jenis == 'Katalog v.6')
                            <td class="text-center">{{ $countBendahara ?: '' }}</td>
                        @endif
                        <td class="text-center">
                            @if($jenis == 'SPSE' && $countPPK > 0 && $countPP > 0)
                                Lengkap
                            @else
                                @endif
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ $jenis == 'Katalog v.6' ? 6 : 5 }}" class="text-center" style="padding: 20px;">
                        Tidak ada data ditemukan untuk kriteria filter ini.
                    </td>
                </tr>
            @endforelse

            @if($no > 1) {{-- Hanya tampilkan jumlah jika ada data --}}
            <tr class="bg-maroon font-bold">
                <td colspan="2">JUMLAH</td>
                <td class="text-center">{{ $totalPPK }}</td>
                <td class="text-center">{{ $totalPP }}</td>
                @if($jenis == 'Katalog v.6')
                    <td class="text-center">{{ $totalBendahara }}</td>
                @endif
                <td></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Mataram, {{ date('d F Y') }}<br>
        Kepala Bagian LPSE
        <div class="signature-space"></div>
        <span class="font-bold" style="text-decoration: underline;">Lalu Majemuk, S.Sos</span><br>
        NIP. 19711231 199402 1 015
    </div>

</body>
</html>