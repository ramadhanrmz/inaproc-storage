@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- HEADER SECTION --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Coat_of_arms_of_West_Nusa_Tenggara.svg/500px-Coat_of_arms_of_West_Nusa_Tenggara.svg.png" class="h-14 w-auto" alt="Logo">
            <div>
                <h1 class="text-2xl font-black text-blue-800 uppercase tracking-tight">Manajemen Akun Inaproc</h1>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">LPSE Provinsi Nusa Tenggara Barat</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            {{-- Tombol Tambah Data Modern --}}
            <a href="{{ route('inaproc-accounts.create') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white h-10 px-6 rounded-lg shadow-md shadow-blue-100 transition-all font-bold text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Data
            </a>
            
            {{-- Form Logout Modern --}}
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center bg-white text-red-600 border border-red-200 h-10 px-6 rounded-lg text-sm font-bold hover:bg-red-50 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- CARD 1: TOTAL KESELURUHAN --}}
        <div class="bg-white border-l-4 border-blue-600 p-5 rounded-xl shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Semua Akun</p>
                <p class="text-3xl font-black text-blue-600">{{ $stats['total'] }}</p>
            </div>
            <div class="text-blue-100">
                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a7 7 0 00-7 7v1h11v-1a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>

        {{-- CARD 2: USER KATALOG V.6 --}}
        <div class="bg-white border-l-4 border-orange-500 p-5 rounded-xl shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <p class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">User Katalog v.6</p>
                {{-- Penjumlahan Otomatis Katalog --}}
                <span class="text-xl font-black text-slate-700 leading-none">
                    {{ $stats['katalog_ppk'] + $stats['katalog_pp'] + $stats['katalog_bendahara'] }}
                </span>
            </div>
            <div class="flex justify-between items-center text-sm mt-3">
                <div class="text-center border-r border-gray-100 pr-4 w-full">
                    <span class="block font-bold text-gray-700">{{ $stats['katalog_ppk'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase font-bold">PPK</span>
                </div>
                <div class="text-center border-r border-gray-100 pr-4 w-full">
                    <span class="block font-bold text-gray-700">{{ $stats['katalog_pp'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase font-bold">PP</span>
                </div>
                <div class="text-center w-full">
                    <span class="block font-bold text-gray-700">{{ $stats['katalog_bendahara'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase font-bold">BDH</span>
                </div>
            </div>
        </div>

        {{-- CARD 3: USER SPSE --}}
        <div class="bg-white border-l-4 border-purple-600 p-5 rounded-xl shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wider">User SPSE</p>
                {{-- Penjumlahan Otomatis SPSE --}}
                <span class="text-xl font-black text-slate-700 leading-none">
                    {{ $stats['spse_ppk'] + $stats['spse_pp'] + $stats['spse_pokja'] + $stats['spse_lainnya'] }}
                </span>
            </div>
            <div class="flex justify-between items-center text-sm mt-3 text-center">
                <div class="border-r border-gray-100 pr-2 w-full">
                    <span class="block font-bold text-gray-700">{{ $stats['spse_ppk'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase font-bold">PPK</span>
                </div>
                <div class="border-r border-gray-100 pr-2 w-full">
                    <span class="block font-bold text-gray-700">{{ $stats['spse_pp'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase font-bold">PP</span>
                </div>
                <div class="border-r border-gray-100 pr-2 w-full">
                    <span class="block font-bold text-gray-700">{{ $stats['spse_pokja'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase font-bold">PKJ</span>
                </div>
                <div class="w-full">
                    <span class="block font-bold text-gray-700">{{ $stats['spse_lainnya'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase font-bold">KPA</span>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    {{-- Ganti bagian tombol Export PDF di index.blade.php dengan ini --}}
    <div class="flex items-center gap-2">
        {{-- Tombol Export PDF --}}
        <div class="relative inline-block group">
            <button type="button" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Export PDF
            </button>
            
            <!-- Dropdown Menu -->
            <div class="absolute left-0 w-48 mt-2 origin-top-left bg-white border border-gray-100 divide-y divide-gray-50 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[999]">
                <div class="py-2">
                    <a href="{{ route('inaproc.export-pdf', array_merge(request()->query(), ['jenis' => 'Katalog v.6'])) }}" target="_blank" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-bold transition">
                        📄 Katalog v.6
                    </a>
                    <a href="{{ route('inaproc.export-pdf', array_merge(request()->query(), ['jenis' => 'SPSE'])) }}" target="_blank" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-bold transition">
                        📄 SPSE
                    </a>
                </div>
            </div>
        </div>

        {{-- TOMBOL IMPORT CSV BARU --}}
        <form action="{{ route('inaproc.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center">
            @csrf
            <input type="file" name="csv_file" id="csv_file" class="hidden" onchange="this.form.submit()" accept=".csv">
            <button type="button" onclick="document.getElementById('csv_file').click()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Import CSV
            </button>
        </form>

        {{-- Tombol Download Template --}}
        <a href="{{ route('inaproc.download-template') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold flex items-center transition border border-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download Template
        </a>
    </div>

        <form id="auto-filter-form" action="{{ route('inaproc-accounts.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                <span class="text-[10px] font-black text-gray-400 uppercase mr-2">Filter:</span>
                <select name="bulan" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold text-gray-600 focus:ring-0 cursor-pointer p-0">
                    <option value="">Semua Bulan</option>
                    @for($i=1; $i<=12; $i++) 
                        <option value="{{$i}}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,1)) }}</option> 
                    @endfor
                </select>
            </div>

            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold text-gray-600 focus:ring-0 cursor-pointer p-0">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y'); $y >= date('Y') - 1; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex items-center bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                <select name="status_filter" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-black text-blue-700 focus:ring-0 cursor-pointer p-0 uppercase">
                    <option value="">Semua Tipe</option>
                    @foreach(['PPK', 'PP', 'Bendahara', 'POKJA', 'PA', 'KPA'] as $s)
                        <option value="{{$s}}" {{ request('status_filter') == $s ? 'selected' : '' }}>{{$s}}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
        </form>
    </div>

    {{-- TABLE SECTION --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-4">
        <div class="p-4 border-b border-gray-50 flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase">Show:</span>
                    <select name="per_page" form="auto-filter-form" onchange="this.form.submit()" class="border-gray-200 rounded-lg text-xs font-bold p-1 focus:ring-blue-500">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                        <option value="semua" {{ request('per_page') == 'semua' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>

                {{-- FILTER JENIS DATA YANG TADI HILANG --}}
                <div class="flex items-center space-x-2 border-l border-gray-100 pl-4">
                    <span class="text-[10px] font-black text-gray-400 uppercase">Jenis:</span>
                    <select name="jenis_filter" form="auto-filter-form" onchange="this.form.submit()" 
                            class="border-gray-200 rounded-lg text-xs font-black text-blue-700 p-1 focus:ring-blue-500 bg-blue-50/50">
                        <option value="">Semua</option>
                        <option value="Katalog v.6" {{ request('jenis_filter') == 'Katalog v.6' ? 'selected' : '' }}>Katalog v.6</option>
                        <option value="SPSE" {{ request('jenis_filter') == 'SPSE' ? 'selected' : '' }}>SPSE</option>
                    </select>
                </div>
            </div>
            
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="search-input" name="search" form="auto-filter-form" value="{{ request('search') }}" placeholder="Cari nama/OPD..." autocomplete="off" class="pl-10 pr-4 py-2 border-gray-200 rounded-xl text-xs font-bold w-64 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-black tracking-widest border-b border-gray-100">
                        <th class="p-4 text-center">No</th>
                        <th class="p-4">Nama Lengkap</th>
                        <th class="p-4">Satuan Kerja</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">SK & User ID</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($accounts as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4 text-center text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-700 text-sm">{{ $item->nama }}</span>
                                <span class="text-[10px] text-blue-500 font-bold italic">{{ $item->jabatan }}</span>
                                <span class="text-[9px] text-gray-400 mt-1 font-medium">Terdaftar: {{ \Carbon\Carbon::parse($item->tanggal_daftar)->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-xs font-bold text-gray-600">{{ $item->opd }}</td>
                        <td class="p-4">
                            <div class="flex flex-col items-start gap-1">
                                <span class="inline-block px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-black uppercase">{{ $item->status }}</span>
                                <span class="inline-block px-2 py-0.5 rounded-md {{ $item->jenis_data == 'SPSE' ? 'bg-purple-50 text-purple-600' : 'bg-orange-50 text-orange-600' }} text-[9px] font-bold">{{ $item->jenis_data }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="text-[10px] leading-relaxed">
                                <p class="text-gray-400 uppercase">SK: <span class="text-gray-700 font-bold">{{ $item->no_sk }}</span></p>
                                <p class="text-blue-400 uppercase">ID: <span class="text-blue-700 font-black">{{ $item->user_id }}</span></p>
                            </div>
                        </td>
                        <td class="p-4">
                             @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $item->no_hp);
                                if (str_starts_with($cleanPhone, '0')) $cleanPhone = '62' . substr($cleanPhone, 1);
                            @endphp
                            <a href="https://wa.me/62{{ $cleanPhone }}" target="_blank" class="inline-flex items-center text-xs font-bold text-green-600 hover:text-green-700">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.483 8.413-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.654zm6.757-4.791c1.512.897 2.997 1.347 4.737 1.348 5.399 0 9.792-4.393 9.795-9.792.001-2.612-1.015-5.068-2.862-6.916-1.847-1.847-4.303-2.861-6.913-2.862-5.397 0-9.791 4.393-9.793 9.792 0 1.832.484 3.623 1.399 5.204l-.934 3.41 3.506-.92zm9.961-6.221c-.303-.151-1.791-.882-2.069-.982-.278-.1-.482-.151-.683.151-.202.302-.782.982-.958 1.183-.176.201-.353.226-.656.076-.303-.151-1.278-.47-2.435-1.503-.9-.801-1.507-1.791-1.684-2.092-.176-.302-.019-.465.132-.615.136-.135.303-.353.454-.529.151-.177.202-.302.303-.504.101-.201.05-.378-.025-.529-.076-.151-.683-1.641-.936-2.25-.246-.593-.497-.513-.683-.523l-.582-.011c-.201 0-.529.075-.806.378-.277.302-1.058 1.033-1.058 2.52s1.083 2.92 1.234 3.121c.151.202 2.132 3.257 5.166 4.566.72.311 1.282.496 1.719.635.723.23 1.381.197 1.9.12.579-.085 1.791-.73 2.044-1.435.252-.706.252-1.31.176-1.435-.076-.126-.278-.202-.582-.353z"/></svg>
                                +62{{ $item->no_hp }}
                            </a>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('inaproc-accounts.edit', $item->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>

                                <form action="{{ route('inaproc-accounts.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Mas Robi yakin ingin menghapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-100 text-rose-600 hover:bg-rose-600 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($accounts instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="p-4 bg-gray-50/50 border-t border-gray-100 flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            <span>Showing {{ $accounts->firstItem() }} to {{ $accounts->lastItem() }} of {{ $accounts->total() }} entries</span>
            <div>{{ $accounts->links() }}</div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const filterForm = document.getElementById('auto-filter-form');
        let timer;

        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                filterForm.submit();
            }, 500); 
        });

        const val = searchInput.value;
        searchInput.value = '';
        searchInput.focus();
        searchInput.value = val;
    });
</script>
@endsection