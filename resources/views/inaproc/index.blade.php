@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 gap-4">
        <a href="{{ route('inaproc-accounts.index') }}" class="flex flex-col md:flex-row items-center space-y-3 md:space-y-0 md:space-x-4 text-center md:text-left hover:opacity-80 transition-opacity">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Coat_of_arms_of_West_Nusa_Tenggara.svg/500px-Coat_of_arms_of_West_Nusa_Tenggara.svg.png" class="h-12 md:h-14 w-auto" alt="Logo">
            <div>
                <h1 class="text-xl md:text-2xl font-black text-blue-800 uppercase tracking-tight">Manajemen Akun Inaproc</h1>
                <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">LPSE Provinsi Nusa Tenggara Barat</p>
            </div>
        </a>
        <div class="flex flex-wrap justify-center items-center gap-2">
            {{-- Tombol Grafik --}}
            <a href="{{ route('inaproc.grafik') }}" target="_blank" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white h-10 px-4 md:px-6 rounded-lg shadow-md shadow-indigo-100 transition-all font-bold text-xs md:text-sm">
                <svg class="w-4 h-4 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Grafik
            </a>

            {{-- Tombol Tambah Data Modern --}}
            <button type="button" onclick="openCreateModal()" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white h-10 px-4 md:px-6 rounded-lg shadow-md shadow-blue-100 transition-all font-bold text-xs md:text-sm">
                <svg class="w-4 h-4 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Data
            </button>
            
            {{-- Tombol Ganti Password --}}
            <button type="button" onclick="openPasswordModal()" class="inline-flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white h-10 px-4 md:px-6 rounded-lg shadow-md shadow-amber-100 transition-all font-bold text-xs md:text-sm">
                <svg class="w-4 h-4 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Ganti Password
            </button>
            
            {{-- Form Logout Modern --}}
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center bg-white text-red-600 border border-red-200 h-10 px-4 md:px-6 rounded-lg text-xs md:text-sm font-bold hover:bg-red-50 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- CARD 1: TOTAL KESELURUHAN --}}
        <div class="group bg-white border-l-4 border-blue-600 p-5 rounded-xl shadow-sm flex items-center justify-between transition-all duration-300 hover:shadow-lg hover:shadow-blue-100 hover:scale-[1.02]">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Semua Akun</p>
                <p class="text-3xl font-black text-blue-600">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-blue-50 group-hover:bg-blue-100 rounded-2xl p-3 transition-all duration-300">
                <svg class="w-10 h-10 text-blue-400 group-hover:text-blue-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
        </div>

        {{-- CARD 2: USER KATALOG V.6 --}}
        <div class="group relative bg-white border-l-4 border-orange-500 p-5 rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg hover:shadow-orange-100 hover:scale-[1.02] cursor-default">
            {{-- Gradient glow overlay on hover --}}
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">User Katalog v.6</p>
                    {{-- Penjumlahan Otomatis Katalog --}}
                    <span class="text-xl font-black text-slate-700 leading-none group-hover:text-orange-600 transition-colors duration-300">
                        {{ $stats['katalog_ppk'] + $stats['katalog_pp'] + $stats['katalog_bendahara'] + $stats['katalog_auditor'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-sm mt-3">
                    <div class="text-center border-r border-gray-100 group-hover:border-orange-100 pr-2 w-full transition-colors duration-300">
                        <span class="block font-bold text-gray-700 group-hover:text-orange-700 transition-colors duration-300">{{ $stats['katalog_ppk'] }}</span>
                        <span class="text-[9px] text-gray-400 group-hover:text-orange-400 uppercase font-bold transition-colors duration-300">PPK</span>
                    </div>
                    <div class="text-center border-r border-gray-100 group-hover:border-orange-100 pr-2 w-full transition-colors duration-300">
                        <span class="block font-bold text-gray-700 group-hover:text-orange-700 transition-colors duration-300">{{ $stats['katalog_pp'] }}</span>
                        <span class="text-[9px] text-gray-400 group-hover:text-orange-400 uppercase font-bold transition-colors duration-300">PP</span>
                    </div>
                    <div class="text-center border-r border-gray-100 group-hover:border-orange-100 pr-2 w-full transition-colors duration-300">
                        <span class="block font-bold text-gray-700 group-hover:text-orange-700 transition-colors duration-300">{{ $stats['katalog_bendahara'] }}</span>
                        <span class="text-[9px] text-gray-400 group-hover:text-orange-400 uppercase font-bold transition-colors duration-300">BENDAHARA</span>
                    </div>
                    <div class="text-center w-full">
                        <span class="block font-bold text-gray-700 group-hover:text-orange-700 transition-colors duration-300">{{ $stats['katalog_auditor'] }}</span>
                        <span class="text-[9px] text-gray-400 group-hover:text-orange-400 uppercase font-bold transition-colors duration-300">AUDITOR</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3: USER SPSE --}}
        <div class="group relative bg-white border-l-4 border-purple-600 p-5 rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg hover:shadow-purple-100 hover:scale-[1.02] cursor-default">
            {{-- Gradient glow overlay on hover --}}
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-indigo-50 to-violet-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wider">User SPSE</p>
                    {{-- Penjumlahan Otomatis SPSE --}}
                    <span class="text-xl font-black text-slate-700 leading-none group-hover:text-purple-600 transition-colors duration-300">
                        {{ $stats['spse_ppk'] + $stats['spse_pp'] + $stats['spse_pokja'] + $stats['spse_auditor'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-sm mt-3 text-center">
                    <div class="border-r border-gray-100 group-hover:border-purple-100 pr-1 w-full transition-colors duration-300">
                        <span class="block font-bold text-gray-700 group-hover:text-purple-700 transition-colors duration-300">{{ $stats['spse_ppk'] }}</span>
                        <span class="text-[9px] text-gray-400 group-hover:text-purple-400 uppercase font-bold transition-colors duration-300">PPK</span>
                    </div>
                    <div class="border-r border-gray-100 group-hover:border-purple-100 pr-1 w-full transition-colors duration-300">
                        <span class="block font-bold text-gray-700 group-hover:text-purple-700 transition-colors duration-300">{{ $stats['spse_pp'] }}</span>
                        <span class="text-[9px] text-gray-400 group-hover:text-purple-400 uppercase font-bold transition-colors duration-300">PP</span>
                    </div>
                    <div class="border-r border-gray-100 group-hover:border-purple-100 pr-1 w-full transition-colors duration-300">
                        <span class="block font-bold text-gray-700 group-hover:text-purple-700 transition-colors duration-300">{{ $stats['spse_pokja'] }}</span>
                        <span class="text-[9px] text-gray-400 group-hover:text-purple-400 uppercase font-bold transition-colors duration-300">POKJA</span>
                    </div>
                    <div class="w-full">
                        <span class="block font-bold text-gray-700 group-hover:text-purple-700 transition-colors duration-300">{{ $stats['spse_auditor'] }}</span>
                        <span class="text-[9px] text-gray-400 group-hover:text-purple-400 uppercase font-bold transition-colors duration-300">AUDITOR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & ACTION BUTTONS SECTION --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 py-2">
        {{-- TOMBOL AKSI --}}
        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            {{-- Tombol Export PDF --}}
            <div class="relative inline-block group">
                <button type="button" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center transition shadow-sm w-full justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Export PDF
                </button>
                
                <!-- Dropdown Menu -->
                <div class="absolute left-0 w-56 mt-2 origin-top-left bg-white border border-gray-100 divide-y divide-gray-50 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[999]">
                    <div class="py-2">
                        <div class="px-4 py-1 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 mb-1">Rekapitulasi (Grouped)</div>
                        <a href="{{ route('inaproc.export-pdf', array_merge(request()->query(), ['jenis' => 'Katalog v.6'])) }}" target="_blank" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-bold transition">
                            📄 Rekap Katalog v.6
                        </a>
                        <a href="{{ route('inaproc.export-pdf', array_merge(request()->query(), ['jenis' => 'SPSE'])) }}" target="_blank" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-bold transition">
                            📄 Rekap SPSE
                        </a>
                    </div>
                    <div class="py-2">
                        <div class="px-4 py-1 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 mb-1">Daftar Detail (New)</div>
                        <a href="{{ route('inaproc.export-pdf-detail', array_merge(request()->query(), ['jenis' => 'Katalog v.6'])) }}" target="_blank" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition">
                            📋 Detail Katalog v.6
                        </a>
                        <a href="{{ route('inaproc.export-pdf-detail', array_merge(request()->query(), ['jenis' => 'SPSE'])) }}" target="_blank" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition">
                            📋 Detail SPSE
                        </a>
                    </div>
                </div>
            </div>

            {{-- TOMBOL EXPORT XLSX --}}
            <a href="{{ route('inaproc.export-xlsx', request()->query()) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center transition shadow-sm w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </a>

            {{-- TOMBOL IMPORT XLSX/CSV --}}
            <form action="{{ route('inaproc.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center w-full sm:w-auto">
                @csrf
                <input type="file" name="csv_file" id="csv_file" class="hidden" onchange="this.form.submit()" accept=".xlsx,.xls,.csv">
                <button type="button" onclick="document.getElementById('csv_file').click()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center transition shadow-sm w-full justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Import Excel
                </button>
            </form>

            {{-- Tombol Download Template --}}
            <a href="{{ route('inaproc.download-template') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold flex items-center transition border border-slate-200 w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download Template
            </a>
        </div>

        {{-- FILTER FORM --}}
        <form id="auto-filter-form" action="{{ route('inaproc-accounts.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="flex flex-wrap items-center gap-1 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                <span class="text-[10px] font-black text-gray-400 uppercase mr-1 w-full sm:w-auto">Rentang:</span>
                <div class="flex items-center gap-1">
                    <select name="start_month" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold text-gray-600 focus:ring-0 cursor-pointer p-0">
                        <option value="">Semua Bulan</option>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{$i}}" {{ request('start_month') == $i ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$i,1)) }}</option>
                        @endfor
                    </select>
                    <span class="text-xs text-gray-400 font-bold">-</span>
                    <select name="end_month" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold text-gray-600 focus:ring-0 cursor-pointer p-0 ml-1">
                        <option value="">Semua Bulan</option>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{$i}}" {{ request('end_month') == $i ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$i,1)) }}</option>
                        @endfor
                    </select>
                </div>
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
        <div class="p-4 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex flex-wrap items-center gap-2 lg:gap-4 w-full md:w-auto">
                <div class="flex items-center space-x-2 bg-gray-50 px-2 py-1.5 rounded-lg border border-gray-200">
                    <span class="text-[10px] font-black text-gray-400 uppercase">Show:</span>
                    <select name="per_page" form="auto-filter-form" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold p-0 pr-6 focus:ring-0">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                        <option value="semua" {{ request('per_page') == 'semua' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2 bg-blue-50 px-2 py-1.5 rounded-lg border border-blue-100">
                    <span class="text-[10px] font-black text-gray-400 uppercase">Jenis Data:</span>
                    <select name="jenis_filter" form="auto-filter-form" onchange="this.form.submit()" 
                            class="bg-transparent border-none text-xs font-black text-blue-700 p-0 pr-6 focus:ring-0">
                        <option value="">Semua</option>
                        <option value="Katalog v.6" {{ request('jenis_filter') == 'Katalog v.6' ? 'selected' : '' }}>Katalog v.6</option>
                        <option value="SPSE" {{ request('jenis_filter') == 'SPSE' ? 'selected' : '' }}>SPSE</option>
                    </select>
                </div>
            </div>
            
            <div class="relative w-full md:w-auto">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="search-input" name="search" form="auto-filter-form" value="{{ request('search') }}" placeholder="Cari nama/OPD..." autocomplete="off" class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-xs font-bold w-full md:w-64 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[800px]">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-black tracking-widest border-b border-gray-100">
                        <th class="p-4 text-center w-10">
                            <input type="checkbox" id="select-all-checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" title="Pilih Semua">
                        </th>
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
                    <tr class="hover:bg-blue-50/30 transition-colors bulk-row {{ !$item->is_active ? 'bg-red-50/80' : '' }}">
                        <td class="p-4 text-center">
                            <input type="checkbox" class="row-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" value="{{ $item->id }}">
                        </td>
                        <td class="p-4 text-center text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="font-bold {{ !$item->is_active ? 'text-red-700' : 'text-gray-700' }} text-sm">{{ $item->nama }}</span>
                                <span class="text-[10px] {{ !$item->is_active ? 'text-red-500' : 'text-blue-500' }} font-bold italic">{{ $item->jabatan }}</span>
                                <span class="text-[9px] text-gray-400 mt-1 font-medium">Terdaftar: {{ \Carbon\Carbon::parse($item->tanggal_daftar)->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-xs font-bold text-gray-600">{{ $item->opd }}</td>
                        <td class="p-4">
                            <div class="flex flex-col items-start gap-1">
                                <span class="inline-block px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-black uppercase">{{ $item->status }}</span>
                                <span class="inline-block px-2 py-0.5 rounded-md {{ $item->jenis_data == 'SPSE' ? 'bg-purple-50 text-purple-600' : 'bg-orange-50 text-orange-600' }} text-[9px] font-bold">{{ $item->jenis_data }}</span>
                                @if(!$item->is_active)
                                    <span class="inline-block px-2 py-0.5 rounded-md bg-red-600 text-white text-[9px] font-black uppercase animate-pulse">Non-Aktif</span>
                                @endif
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
                                if (str_starts_with($cleanPhone, '62')) $cleanPhone = substr($cleanPhone, 2);
                                if (str_starts_with($cleanPhone, '0')) $cleanPhone = substr($cleanPhone, 1);
                                $cleanPhone = '62' . substr($cleanPhone, 0, 13);
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="inline-flex items-center text-xs font-bold text-green-600 hover:text-green-700">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.483 8.413-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.654zm6.757-4.791c1.512.897 2.997 1.347 4.737 1.348 5.399 0 9.792-4.393 9.795-9.792.001-2.612-1.015-5.068-2.862-6.916-1.847-1.847-4.303-2.861-6.913-2.862-5.397 0-9.791 4.393-9.793 9.792 0 1.832.484 3.623 1.399 5.204l-.934 3.41 3.506-.92zm9.961-6.221c-.303-.151-1.791-.882-2.069-.982-.278-.1-.482-.151-.683.151-.202.302-.782.982-.958 1.183-.176.201-.353.226-.656.076-.303-.151-1.278-.47-2.435-1.503-.9-.801-1.507-1.791-1.684-2.092-.176-.302-.019-.465.132-.615.136-.135.303-.353.454-.529.151-.177.202-.302.303-.504.101-.201.05-.378-.025-.529-.076-.151-.683-1.641-.936-2.25-.246-.593-.497-.513-.683-.523l-.582-.011c-.201 0-.529.075-.806.378-.277.302-1.058 1.033-1.058 2.52s1.083 2.92 1.234 3.121c.151.202 2.132 3.257 5.166 4.566.72.311 1.282.496 1.719.635.723.23 1.381.197 1.9.12.579-.085 1.791-.73 2.044-1.435.252-.706.252-1.31.176-1.435-.076-.126-.278-.202-.582-.353z"/></svg>
                                {{ $cleanPhone }}
                            </a>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center space-x-2">
                                <button type="button" onclick="openEditModal({{ $item->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                <button type="button" onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama) }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-100 text-rose-600 hover:bg-rose-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
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

{{-- MODAL NOTIFIKASI SUKSES --}}
@if(session('success'))
<div id="success-modal-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] flex items-center justify-center transition-opacity duration-300">
    <div id="success-modal" class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center transform scale-95 opacity-0 transition-all duration-300">
        {{-- Icon Animasi --}}
        <div class="mx-auto w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-emerald-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-2">Berhasil!</h3>
        <p class="text-sm text-gray-500 mb-6">{{ session('success') }}</p>
        <button onclick="closeSuccessModal()" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-lg shadow-emerald-100">
            OK, Mengerti
        </button>
    </div>
</div>
@endif

{{-- MODAL NOTIFIKASI ERROR (Duplikat CSV) --}}
@if(session('error'))
<div id="error-modal-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] flex items-center justify-center transition-opacity duration-300">
    <div id="error-modal" class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4 text-center transform scale-95 opacity-0 transition-all duration-300">
        {{-- Icon Error --}}
        <div class="mx-auto w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-2">Proses Import Selesai dengan Catatan</h3>
        <p class="text-sm text-gray-500 mb-4 bg-red-50 p-4 rounded-xl text-left font-mono break-words leading-relaxed max-h-40 overflow-y-auto">{{ session('error') }}</p>
        <button onclick="closeErrorModal()" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-lg shadow-red-100">
            Tutup
        </button>
    </div>
</div>
@endif

{{-- MODAL NOTIFIKASI IMPORT ERROR (Status Invalid / DB Error) --}}
@if(session('import_errors'))
<div id="import-error-modal-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] flex items-center justify-center transition-opacity duration-300">
    <div id="import-error-modal" class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4 text-center transform scale-95 opacity-0 transition-all duration-300">
        {{-- Icon Warning --}}
        <div class="mx-auto w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-2">Gagal Import Beberapa Data</h3>
        <p class="text-xs text-gray-400 mb-3">Data berikut tidak dapat diimport karena nilai kolom <span class="font-bold text-amber-600">Status</span> tidak valid. Status yang diizinkan: <span class="font-bold text-gray-600">PPK, PP, Bendahara, POKJA, Auditor, PA, KPA</span></p>
        <div class="text-left bg-amber-50 border border-amber-200 p-4 rounded-xl mb-5 max-h-52 overflow-y-auto">
            <ul class="space-y-2">
                @foreach(session('import_errors') as $err)
                <li class="flex items-start gap-2 text-sm text-amber-800">
                    <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                    </svg>
                    <span class="font-medium break-words">{{ $err }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        <p class="text-[10px] text-gray-400 mb-4 font-bold uppercase tracking-wider">Total gagal: {{ count(session('import_errors')) }} baris</p>
        <button onclick="closeImportErrorModal()" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-lg shadow-amber-100">
            Tutup & Perbaiki Data
        </button>
    </div>
</div>
@endif

{{-- MODAL TAMBAH AKUN --}}
@include('inaproc.partials.create-modal')

{{-- MODAL EDIT AKUN --}}
@include('inaproc.partials.edit-modal')

{{-- MODAL HAPUS AKUN --}}
{{-- MODAL BULK DELETE --}}
@include('inaproc.partials.bulk-delete-modal')

@include('inaproc.partials.delete-modal')

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

        // Animasi Modal Sukses & Error
        const successModal = document.getElementById('success-modal');
        if (successModal) {
            setTimeout(() => {
                successModal.classList.remove('scale-95', 'opacity-0');
                successModal.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        const errorModal = document.getElementById('error-modal');
        if (errorModal) {
            setTimeout(() => {
                errorModal.classList.remove('scale-95', 'opacity-0');
                errorModal.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        const importErrorModal = document.getElementById('import-error-modal');
        if (importErrorModal) {
            setTimeout(() => {
                importErrorModal.classList.remove('scale-95', 'opacity-0');
                importErrorModal.classList.add('scale-100', 'opacity-100');
            }, 50);
        }
    });

    function closeSuccessModal() {
        const modal = document.getElementById('success-modal');
        const overlay = document.getElementById('success-modal-overlay');
        modal.classList.remove('scale-100', 'opacity-100');
        modal.classList.add('scale-95', 'opacity-0');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.remove(), 300);
    }
    
    function closeErrorModal() {
        const modal = document.getElementById('error-modal');
        const overlay = document.getElementById('error-modal-overlay');
        modal.classList.remove('scale-100', 'opacity-100');
        modal.classList.add('scale-95', 'opacity-0');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.remove(), 300);
    }

    function closeImportErrorModal() {
        const modal = document.getElementById('import-error-modal');
        const overlay = document.getElementById('import-error-modal-overlay');
        modal.classList.remove('scale-100', 'opacity-100');
        modal.classList.add('scale-95', 'opacity-0');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.remove(), 300);
    }
</script>
    {{-- MODAL GANTI PASSWORD --}}
    <div id="passwordModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closePasswordModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-6 py-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Ganti Password Admin</h3>
                        <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <form id="passwordForm" action="{{ route('password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('put')
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all text-sm font-bold">
                            <span class="text-[10px] text-red-500 font-bold mt-1 hidden" id="error-current_password"></span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Password Baru</label>
                            <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all text-sm font-bold">
                            <span class="text-[10px] text-red-500 font-bold mt-1 hidden" id="error-password"></span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all text-sm font-bold">
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-3 rounded-xl shadow-lg shadow-amber-100 transition-all uppercase tracking-widest text-xs">
                                Simpan Password Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPasswordModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('passwordForm').reset();
            hidePasswordErrors();
        }

        function hidePasswordErrors() {
            document.querySelectorAll('[id^="error-"]').forEach(el => el.classList.add('hidden'));
        }

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerText;
            
            submitBtn.disabled = true;
            submitBtn.innerText = 'MEMPROSES...';
            hidePasswordErrors();

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(response => {
                if (response.status === 200 || response.status === 302 || response.redirected) {
                    // Berhasil
                    closePasswordModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Password Admin berhasil diperbarui.',
                        timer: 2500,
                        showConfirmButton: false
                    });
                } else if (response.status === 422) {
                    // Validasi Error
                    return response.json().then(data => {
                        const errors = data.errors || {};
                        for (const key in errors) {
                            const errorEl = document.getElementById('error-' + key);
                            if (errorEl) {
                                errorEl.innerText = errors[key][0];
                                errorEl.classList.remove('hidden');
                            }
                        }
                    });
                } else {
                    throw new Error('Terjadi kesalahan sistem.');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
            });
        });
    </script>
@endsection