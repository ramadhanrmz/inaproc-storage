@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Coat_of_arms_of_West_Nusa_Tenggara.svg/500px-Coat_of_arms_of_West_Nusa_Tenggara.svg.png" class="h-12 w-auto" alt="Logo">
            <div>
                <h1 class="text-2xl font-bold text-blue-700 uppercase">Akun Inaproc Storage</h1>
                <p class="text-sm text-gray-500 font-semibold">Provinsi Nusa Tenggara Barat</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('inaproc-accounts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow font-bold text-sm">+ Tambah Data</a>
            <button class="bg-red-50 text-red-600 border border-red-200 px-4 py-2 rounded text-sm font-bold">Logout</button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        
        <div class="bg-white border-l-4 border-blue-600 p-4 rounded shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Semua Akun</p>
                <p class="text-3xl font-black text-blue-600">{{ $stats['total'] }}</p>
            </div>
            <div class="text-blue-100">
                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a7 7 0 00-7 7v1h11v-1a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>

        <div class="bg-white border-l-4 border-orange-500 p-4 rounded shadow-sm">
            <p class="text-[10px] font-bold text-orange-500 uppercase tracking-wider mb-2">User Katalog v.6</p>
            <div class="flex justify-between items-center text-sm">
                <div class="text-center border-r pr-4">
                    <span class="block font-bold text-gray-700">{{ $stats['katalog_ppk'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase">PPK</span>
                </div>
                <div class="text-center border-r pr-4">
                    <span class="block font-bold text-gray-700">{{ $stats['katalog_pp'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase">PP</span>
                </div>
                <div class="text-center">
                    <span class="block font-bold text-gray-700">{{ $stats['katalog_bendahara'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase">BENDAHARA</span>
                </div>
            </div>
        </div>

        <div class="bg-white border-l-4 border-purple-600 p-4 rounded shadow-sm">
            <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wider mb-2">User SPSE</p>
            <div class="flex justify-between items-center text-sm text-center">
                <div class="border-r pr-2">
                    <span class="block font-bold text-gray-700">{{ $stats['spse_ppk'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase">PPK</span>
                </div>
                <div class="border-r pr-2">
                    <span class="block font-bold text-gray-700">{{ $stats['spse_pp'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase">PP</span>
                </div>
                <div class="border-r pr-2">
                    <span class="block font-bold text-gray-700">{{ $stats['spse_pokja'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase">POKJA</span>
                </div>
                <div>
                    <span class="block font-bold text-gray-700">{{ $stats['spse_lainnya'] }}</span>
                    <span class="text-[9px] text-gray-400 uppercase">PA/KPA</span>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white p-4 rounded shadow-sm flex flex-wrap justify-between items-center gap-4">
        <div class="relative inline-block group">
            <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs font-bold flex items-center transition shadow-sm outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Export PDF
                <svg class="w-3 h-3 ml-1 transition-transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                </svg>
            </button>

            <div class="absolute left-0 w-44 mt-0 origin-top-left bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[999]">
                <div class="py-1">
                    <a href="{{ route('inaproc.export-pdf', array_merge(request()->query(), ['jenis' => 'Katalog v.6'])) }}" 
                    target="_blank" 
                    class="flex items-center px-4 py-2 text-[11px] text-gray-700 hover:bg-blue-50 hover:text-blue-700 italic font-medium transition">
                        <span class="mr-2">📄</span> Laporan Katalog v.6
                    </a>
                    <a href="{{ route('inaproc.export-pdf', array_merge(request()->query(), ['jenis' => 'SPSE'])) }}" 
                    target="_blank" 
                    class="flex items-center px-4 py-2 text-[11px] text-gray-700 hover:bg-blue-50 hover:text-blue-700 italic font-medium transition border-t border-gray-50">
                        <span class="mr-2">📄</span> Laporan SPSE
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <form id="auto-filter-form" action="{{ route('inaproc-accounts.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-gray-600 uppercase">Filter:</span>
            
            <select name="bulan" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm outline-none focus:ring-1 focus:ring-blue-400">
                <option value="">Semua Bulan</option>
                @for($i=1; $i<=12; $i++) 
                    <option value="{{$i}}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ date('F', mktime(0,0,0,$i,1)) }}
                    </option> 
                @endfor
            </select>

            <select name="tahun" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm outline-none focus:ring-1 focus:ring-blue-400">
                <option value="">Semua Tahun</option>
                @php $currentYear = date('Y'); @endphp
                @for($y = $currentYear; $y >= $currentYear - 1; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <select name="status_filter" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm outline-none focus:ring-1 focus:ring-blue-400 font-bold text-blue-700">
                <option value="">Semua Tipe</option>
                @foreach(['PPK', 'PP', 'Bendahara', 'POKJA', 'PA', 'KPA'] as $s)
                    <option value="{{$s}}" {{ request('status_filter') == $s ? 'selected' : '' }}>{{$s}}</option>
                @endforeach
            </select>

            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
        </form>
    </div>

    <div class="bg-white p-6 rounded shadow-sm mt-4">
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm text-gray-600">
                Show 
                <select name="per_page" form="auto-filter-form" onchange="this.form.submit()" class="border rounded mx-1 p-1">
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                    <option value="semua" {{ request('per_page') == 'semua' ? 'selected' : '' }}>Semua</option>
                </select>
                entries
            </div>

            <div class="flex items-center space-x-3">
                <div class="flex items-center">
                    <span class="mr-2 text-sm font-semibold text-gray-600">Jenis Data:</span>
                    <select name="jenis_filter" form="auto-filter-form" onchange="this.form.submit()" 
                            class="border rounded px-2 py-1 text-sm outline-none focus:ring-1 focus:ring-blue-400 font-bold text-gray-700">
                        <option value="">Semua Jenis</option>
                        <option value="Katalog v.6" {{ request('jenis_filter') == 'Katalog v.6' ? 'selected' : '' }}>Katalog v.6</option>
                        <option value="SPSE" {{ request('jenis_filter') == 'SPSE' ? 'selected' : '' }}>SPSE</option>
                    </select>
                </div>

                <!-- Search Form -->
                <div class="flex items-center">
                    <span class="mr-2 text-sm">Search:</span>
                    <input type="text" 
                        id="search-input"
                        name="search" 
                        form="auto-filter-form" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nama/OPD..."
                        autocomplete="off"
                        class="border rounded px-3 py-1 text-sm outline-none focus:ring-1 focus:ring-blue-400">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto text-xs md:text-sm">
            <table class="w-full border-collapse border border-gray-100">
                <thead>
                    <tr class="bg-gray-50 text-gray-700">
                        <th class="border p-2 text-left">No</th>
                        <th class="border p-2 text-left">Nama</th>
                        <th class="border p-2 text-left">Jabatan</th>
                        <th class="border p-2 text-left">Perangkat Daerah</th>
                        <th class="border p-2 text-left">Status</th>
                        <th class="border p-2 text-left">Perihal</th>
                        <th class="border p-2 text-left">Nomor SK</th>
                        <th class="border p-2 text-left text-blue-600 italic">User ID</th>
                        <th class="border p-2 text-left">Nomor HP</th>
                        <th class="border p-2 text-left">Jenis Data</th>
                        <th class="border p-2 text-center text-red-500 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2 text-center">{{ $accounts instanceof \Illuminate\Pagination\LengthAwarePaginator ? $accounts->firstItem() + $index : $index + 1 }}</td>
                        <td class="border p-2 font-bold">{{ $item->nama }}</td>
                        <td class="border p-2 text-gray-500">{{ $item->jabatan }}</td>
                        <td class="border p-2">{{ $item->opd }}</td>
                        <td class="border p-2 font-semibold text-blue-700">{{ $item->status }}</td>
                        <td class="border p-2 italic">{{ $item->perihal_permohonan }}</td>
                        <td class="border p-2">{{ $item->no_sk }}</td>
                        <td class="border p-2 font-mono">{{ $item->user_id }}</td>
                        <td class="border p-2">
                            @php
                                // Membersihkan nomor hp agar hanya angka untuk link WA
                                $cleanPhone = preg_replace('/[^0-9]/', '', $item->no_hp);
                                // Jika nomor diawali '0', ubah ke format internasional '62'
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-blue-600 font-bold hover:underline flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.483 8.413-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.654zm6.757-4.791c1.512.897 2.997 1.347 4.737 1.348 5.399 0 9.792-4.393 9.795-9.792.001-2.612-1.015-5.068-2.862-6.916-1.847-1.847-4.303-2.861-6.913-2.862-5.397 0-9.791 4.393-9.793 9.792 0 1.832.484 3.623 1.399 5.204l-.934 3.41 3.506-.92zm9.961-6.221c-.303-.151-1.791-.882-2.069-.982-.278-.1-.482-.151-.683.151-.202.302-.782.982-.958 1.183-.176.201-.353.226-.656.076-.303-.151-1.278-.47-2.435-1.503-.9-.801-1.507-1.791-1.684-2.092-.176-.302-.019-.465.132-.615.136-.135.303-.353.454-.529.151-.177.202-.302.303-.504.101-.201.05-.378-.025-.529-.076-.151-.683-1.641-.936-2.25-.246-.593-.497-.513-.683-.523l-.582-.011c-.201 0-.529.075-.806.378-.277.302-1.058 1.033-1.058 2.52s1.083 2.92 1.234 3.121c.151.202 2.132 3.257 5.166 4.566.72.311 1.282.496 1.719.635.723.23 1.381.197 1.9.12.579-.085 1.791-.73 2.044-1.435.252-.706.252-1.31.176-1.435-.076-.126-.278-.202-.582-.353z"/></svg>
                                {{ $item->no_hp }}
                            </a>
                        </td>
                        <td class="border p-2 text-center">
                            <span class="px-2 py-1 {{ $item->jenis_data == 'SPSE' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }} rounded text-xs font-bold">
                                {{ $item->jenis_data }}
                            </span>
                        </td>
                        <td class="border p-2 text-center">
                            <div class="flex justify-center space-x-1">
                                <a href="{{ route('inaproc-accounts.edit', $item->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded text-xs font-bold transition">
                                    Edit
                                </a>

                                <form action="{{ route('inaproc-accounts.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Mas Robi yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-bold transition">
                                        Hapus
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
        <div class="mt-4 flex justify-between items-center text-sm text-gray-500">
            <span>Showing {{ $accounts->firstItem() }} to {{ $accounts->lastItem() }} of {{ $accounts->total() }} entries</span>
            <div>{{ $accounts->links() }}</div>
        </div>
        @endif
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const filterForm = document.getElementById('auto-filter-form');
        let timer;

        searchInput.addEventListener('input', function() {
            // Hapus timer sebelumnya setiap kali Mas Robi mengetik huruf baru
            clearTimeout(timer);

            // Set timer baru: Form akan submit otomatis setelah 500ms berhenti mengetik
            timer = setTimeout(() => {
                filterForm.submit();
            }, 500); 
        });

        // Posisikan kursor di akhir teks setelah auto-submit (agar nyaman mengetik lanjut)
        const val = searchInput.value;
        searchInput.value = '';
        searchInput.focus();
        searchInput.value = val;
    });
</script>