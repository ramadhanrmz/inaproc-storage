@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 gap-4">
        <a href="{{ route('inaproc-accounts.index') }}" class="flex items-center space-x-4 hover:opacity-80 transition-opacity">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Coat_of_arms_of_West_Nusa_Tenggara.svg/500px-Coat_of_arms_of_West_Nusa_Tenggara.svg.png" class="h-14 w-auto" alt="Logo">
            <div>
                <h1 class="text-2xl font-black text-blue-800 uppercase tracking-tight">Grafik Registrasi INAPROC</h1>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">LPSE Provinsi Nusa Tenggara Barat</p>
            </div>
        </a>
        <div class="flex items-center gap-3">
            {{-- Filter Tahun --}}
            <form action="{{ route('inaproc.grafik') }}" method="GET" class="flex items-center bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                <span class="text-xs font-black text-gray-400 uppercase mr-2">Tahun:</span>
                <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none text-sm font-bold text-gray-700 focus:ring-0 cursor-pointer p-0">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            
            <a href="{{ route('inaproc-accounts.index') }}" class="inline-flex items-center justify-center bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 h-10 px-6 rounded-lg shadow-sm transition-all font-bold text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- CHART KATALOG V.6 --}}
    <div id="export-area-katalog" class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 relative">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 border-b border-gray-100 pb-4">
            <div id="katalog-title" class="text-center md:text-left transition-all">
                <h2 class="text-2xl font-black text-[#0c4a6e] uppercase tracking-wide">Registrasi dan Aktivasi Akun INAPROC</h2>
                <h3 class="text-xl font-bold text-[#0c4a6e] uppercase">Non Penyedia Katalog V.6 Pemerintah Provinsi NTB</h3>
            </div>
            <form action="{{ route('inaproc.grafik') }}" method="GET" class="flex items-center flex-wrap gap-2 bg-gray-50/80 p-2 rounded-xl border border-gray-200" data-html2canvas-ignore="true">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                @if(request('spse_start') || request('spse_end'))
                    <input type="hidden" name="spse_start" value="{{ request('spse_start') }}">
                    <input type="hidden" name="spse_end" value="{{ request('spse_end') }}">
                @endif
                
                <span class="text-[10px] font-black text-gray-400 uppercase hidden sm:block">Rentang:</span>
                <select name="katalog_start" onchange="this.form.submit()" class="border-gray-200 bg-white rounded-lg text-xs font-bold text-gray-700 py-1.5 px-2 focus:ring-amber-500 shadow-sm w-28">
                    <option value="">Semua Bulan</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{$i}}" {{ request()->has('katalog_start') && request('katalog_start') == $i ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$i,1)) }}</option>
                    @endfor
                </select>
                <span class="text-[10px] font-black text-gray-400 uppercase">-</span>
                <select name="katalog_end" onchange="this.form.submit()" class="border-gray-200 bg-white rounded-lg text-xs font-bold text-gray-700 py-1.5 px-2 focus:ring-amber-500 shadow-sm w-28">
                    <option value="">Semua Bulan</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{$i}}" {{ request()->has('katalog_end') && request('katalog_end') == $i ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$i,1)) }}</option>
                    @endfor
                </select>

                <button type="button" onclick="exportChartPdf('export-area-katalog', 'Grafik_Katalog', 'katalogChart')" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center transition shadow-sm whitespace-nowrap ml-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    PDF
                </button>
            </form>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-center w-full">
            {{-- Bagian Chart (Kiri) --}}
            <div class="w-full lg:w-2/3 relative h-[400px]">
                <canvas id="katalogChart"></canvas>
            </div>

            {{-- Bagian Akumulasi (Kanan) --}}
            <div id="katalog-akumulasi-box" class="w-full lg:w-1/3 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                <h4 class="text-xl font-black text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Akumulasi :
                </h4>
                <div class="space-y-4">
                    @forelse($katalogData['akumulasi'] as $index => $akumulasi)
                        @php
                            $colors = ['bg-blue-600', 'bg-cyan-400', 'bg-indigo-600', 'bg-blue-500'];
                            $colorClass = $colors[$index % count($colors)];
                        @endphp
                        <div class="flex items-center gap-4 border-b border-gray-100 pb-2 last:border-0 last:pb-0">
                            <div class="{{ $colorClass }} text-white font-black text-xs px-4 py-2 rounded-full w-24 text-center shadow-sm flex-shrink-0">
                                {{ $akumulasi['bulan'] }}
                            </div>
                            <div class="text-xl font-bold text-gray-700">
                                <span class="text-gray-400 mr-1">:</span> {{ $akumulasi['total'] }} <span class="text-sm font-normal text-gray-400">Akun</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500 italic text-sm text-center py-4">Tidak ada data di rentang waktu ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- CHART SPSE --}}
    <div id="export-area-spse" class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 mt-8 relative">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 border-b border-gray-100 pb-4">
            <div id="spse-title" class="text-center md:text-left transition-all">
                <h2 class="text-2xl font-black text-[#0f4a76] uppercase tracking-wide">Rekapitulasi Registrasi dan Aktivasi Akun INAPROV</h2>
                <h3 class="text-xl font-bold text-[#0f4a76] uppercase">Non Penyedia SPSE Pemerintah Provinsi NTB</h3>
            </div>
            <form action="{{ route('inaproc.grafik') }}" method="GET" class="flex items-center flex-wrap gap-2 bg-orange-50/50 p-2 rounded-xl border border-orange-100" data-html2canvas-ignore="true">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                @if(request('katalog_start') || request('katalog_end'))
                    <input type="hidden" name="katalog_start" value="{{ request('katalog_start') }}">
                    <input type="hidden" name="katalog_end" value="{{ request('katalog_end') }}">
                @endif
                
                <span class="text-[10px] font-black text-orange-400 uppercase hidden sm:block">Rentang:</span>
                <select name="spse_start" onchange="this.form.submit()" class="border-orange-200 bg-white rounded-lg text-xs font-bold text-gray-700 py-1.5 px-2 focus:ring-orange-500 shadow-sm w-28">
                    <option value="">Semua Bulan</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{$i}}" {{ request()->has('spse_start') && request('spse_start') == $i ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$i,1)) }}</option>
                    @endfor
                </select>
                <span class="text-[10px] font-black text-orange-400 uppercase">-</span>
                <select name="spse_end" onchange="this.form.submit()" class="border-orange-200 bg-white rounded-lg text-xs font-bold text-gray-700 py-1.5 px-2 focus:ring-orange-500 shadow-sm w-28">
                    <option value="">Semua Bulan</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{$i}}" {{ request()->has('spse_end') && request('spse_end') == $i ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$i,1)) }}</option>
                    @endfor
                </select>

                <button type="button" onclick="exportChartPdf('export-area-spse', 'Grafik_SPSE', 'spseChart')" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center transition shadow-sm whitespace-nowrap ml-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    PDF
                </button>
            </form>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-center w-full">
            {{-- Bagian Chart (Kiri) --}}
            <div class="w-full lg:w-2/3 relative h-[400px]">
                <canvas id="spseChart"></canvas>
            </div>

            {{-- Bagian Akumulasi (Kanan) --}}
            <div id="spse-akumulasi-box" class="w-full lg:w-1/3 bg-orange-50/30 p-6 rounded-2xl border border-orange-100">
                <h4 class="text-xl font-black text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Akumulasi :
                </h4>
                <div class="space-y-4">
                    @forelse($spseData['akumulasi'] as $index => $akumulasi)
                        @php
                            $colors = ['bg-orange-600', 'bg-amber-400', 'bg-yellow-500', 'bg-orange-400'];
                            $colorClass = $colors[$index % count($colors)];
                        @endphp
                        <div class="flex items-center gap-4 border-b border-orange-100 pb-2 last:border-0 last:pb-0">
                            <div class="{{ $colorClass }} text-white font-black text-xs px-4 py-2 rounded-full w-24 text-center shadow-sm flex-shrink-0">
                                {{ $akumulasi['bulan'] }}
                            </div>
                            <div class="text-xl font-bold text-gray-700">
                                <span class="text-orange-300 mr-1">:</span> {{ $akumulasi['total'] }} <span class="text-sm font-normal text-gray-400">Akun</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 italic text-sm">Belum ada data akumulasi untuk tahun ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    function exportChartPdf(elementId, filename, chartId) {
        const element = document.getElementById(elementId);
        const canvas = document.getElementById(chartId);
        
        // Fetch specific elements to manipulate before export
        const akumulasiId = chartId === 'katalogChart' ? 'katalog-akumulasi-box' : 'spse-akumulasi-box';
        const titleId = chartId === 'katalogChart' ? 'katalog-title' : 'spse-title';
        const akumulasiBox = document.getElementById(akumulasiId);
        const titleBox = document.getElementById(titleId);
        const form = element.querySelector('form');
        
        // Convert Chart.js Canvas to a static Image so it scales predictably without layout jumping.
        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/png', 1.0);
        img.id = 'temp-img-' + chartId;
        img.style.width = '100%';
        img.style.minHeight = '300px'; 
        img.style.objectFit = 'contain';
        
        // Swap them instantly
        canvas.style.display = 'none';
        canvas.parentNode.appendChild(img);

        // Pre-export styling hacks to guarantee perfect centering & no cropping
        window.scrollTo(0,0); // Prevent html2canvas viewport scroll cutoff bugs!
        
        if (akumulasiBox) akumulasiBox.style.display = 'none'; // Hide akumulasi box
        if (form) form.style.display = 'none'; // Hide form completely so title has full width to center
        
        if (titleBox) {
            titleBox.classList.remove('md:text-left');
            titleBox.classList.add('text-center', 'w-full'); 
        }

        const chartWrapper = canvas.parentNode;
        const originalWidth = chartWrapper.style.width;
        chartWrapper.style.width = '100%';

        // We REMOVED windowWidth force so html2canvas reads the actual DOM width and doesn't push it off-screen!
        const opt = {
            margin:       [10, 10, 10, 10], 
            filename:     filename + '.pdf',
            image:        { type: 'jpeg', quality: 1 },
            html2canvas:  { scale: 2, useCORS: true, scrollY: 0, scrollX: 0 },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };

        // Render PDF directly to a new tab instead of downloading
        html2pdf().set(opt).from(element).toPdf().get('pdf').then(function(pdfObj) {
            
            // Restore visibility and sizes exactly as they were
            if (akumulasiBox) akumulasiBox.style.display = '';
            if (form) form.style.display = '';
            
            if (titleBox) {
                titleBox.classList.add('md:text-left');
                titleBox.classList.remove('text-center', 'w-full');
            }
            chartWrapper.style.width = originalWidth;
            
            // Remove the temp image and restore live canvas
            const tempImg = document.getElementById('temp-img-' + chartId);
            if (tempImg) tempImg.remove();
            canvas.style.display = '';
            
            const blobUrl = pdfObj.output('bloburl');
            window.open(blobUrl, '_blank');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(typeof ChartDataLabels !== 'undefined'){
             Chart.register(ChartDataLabels);
        }

        Chart.defaults.font.family = "'Figtree', 'Inter', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
        Chart.defaults.font.weight = 'bold';
        Chart.defaults.color = '#4b5563';

        const dataLabelsConfig = {
            anchor: 'end',
            align: 'top',
            formatter: Math.round,
            font: {
                weight: 'bold',
                size: 16
            },
            color: '#1e293b'
        };

        // --- CHART KATALOG V.6 ---
        const ctxKatalog = document.getElementById('katalogChart').getContext('2d');
        const katalogData = @json($katalogData);

        new Chart(ctxKatalog, {
            type: 'bar',
            data: {
                labels: katalogData.labels,
                datasets: [
                    {
                        label: 'PPK',
                        data: katalogData.ppk,
                        backgroundColor: '#2e5ea7', 
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    },
                    {
                        label: 'PP',
                        data: katalogData.pp,
                        backgroundColor: '#5cdde5', 
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    },
                    {
                        label: 'Bendahara',
                        data: katalogData.bendahara,
                        backgroundColor: '#4a478c', 
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    datalabels: dataLabelsConfig
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '20%', // Menambahkan extra space 20% di atas supaya tidak tumpang tindih dengan legend
                        ticks: {
                            stepSize: 2
                        },
                        grid: {
                            color: '#f3f4f6'
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        border: { display: false }
                    }
                },
                layout: {
                    padding: {
                        top: 25 
                    }
                }
            }
        });

        // --- CHART SPSE ---
        const ctxSpse = document.getElementById('spseChart').getContext('2d');
        const spseData = @json($spseData);

        new Chart(ctxSpse, {
            type: 'bar',
            data: {
                labels: spseData.labels,
                datasets: [
                    {
                        label: 'PPK',
                        data: spseData.ppk,
                        backgroundColor: '#f97316', 
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    },
                    {
                        label: 'PP',
                        data: spseData.pp,
                        backgroundColor: '#fcd34d', 
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    datalabels: dataLabelsConfig
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '20%', // Menambahkan extra space 20% di atas supaya tidak tumpang tindih dengan legend
                        ticks: {
                            stepSize: 5
                        },
                        grid: {
                            color: '#f3f4f6'
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        border: { display: false }
                    }
                },
                layout: {
                    padding: {
                        top: 25
                    }
                }
            }
        });
    });
</script>
@endpush
