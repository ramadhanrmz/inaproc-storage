@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md mb-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Form Tambah Akun Inaproc</h2>

    <form action="{{ route('inaproc-accounts.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-bold mb-1">Nama Lengkap <span class="text-danger text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : '' }}" placeholder="Contoh: Muhajidin, S.Pd., MM" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Perangkat Daerah (OPD) <span class="text-danger text-red-500">*</span></label>
                <input list="list_opd" name="opd" value="{{ old('opd') }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 {{ $errors->has('opd') ? 'border-red-400 bg-red-50' : '' }}" placeholder="Ketik atau pilih OPD..." required>
                <datalist id="list_opd">
                    <option value="Badan Kesatuan Bangsa dan Politik Dalam Negeri">
                    <option value="Badan Kepegawaian Daerah">
                    <option value="Badan Keuangan dan Aset Daerah">
                    <option value="Badan Penanggulangan Bencana Daerah">
                    <option value="Badan Pendapatan Daerah">
                    <option value="Unit Pelayanan Pajak dan Retribusi Daerah Wilayah I">
                    <option value="Unit Pelayanan Pajak dan Retribusi Daerah Wilayah II">
                    <option value="Unit Pelayanan Pajak dan Retribusi Daerah Wilayah III">
                    <option value="Unit Pelayanan Pajak dan Retribusi Daerah Wilayah IV">
                    <option value="Unit Pelayanan Pajak dan Retribusi Daerah Wilayah V">
                    <option value="Badan Pengembangan Sumber Daya Manusia Daerah">
                    <option value="Badan Penghubung Daerah">
                    <option value="Badan Perencanaan Pembangunan Daerah">
                    <option value="Badan Riset dan Inovasi Daerah">
                    <option value="Biro Hukum dan Hak Asasi Manusia">
                    <option value="Biro Kesejahteraan Rakyat">
                    <option value="Biro Organisasi">
                    <option value="Biro Pemerintahan dan Otonomi Daerah">
                    <option value="Biro Perekonomian dan Administrasi Pembangunan">
                    <option value="Biro Pengadaan Barang dan Jasa">
                    <option value="Biro Umum dan Administrasi Pimpinan">
                    <option value="Dinas Energi dan Sumber Daya Mineral">
                    <option value="Dinas Kebudayaan">
                    <option value="Taman Budaya">
                    <option value="Museum Negeri">
                    <option value="Dinas Kelautan dan Perikanan">
                    <option value="Balai Pengembangan Perikanan Budidaya">
                    <option value="Balai Laboratorium Pengujian dan Penerapan Mutu Hasil Perikanan dan Kelautan">
                    <option value="Pelabuhan Perikanan Labuhan Lombok">
                    <option value="Pelabuhan Perikanan Wilayah Pulau Sumbawa">
                    <option value="Balai Pengelolaan Sumberdaya Kelautan dan Perikanan Wilayah Bima-Dompu">
                    <option value="Balai Pengelolaan Sumberdaya Kelautan dan Perikanan Wilayah Sumbawa-Sumbawa Barat">
                    <option value="Balai Pengelolaan Sumberdaya Kelautan dan Perikanan Wilayah Pulau Lombok">
                    <option value="Pelabuhan Perikanan Tanjung Luar">
                    <option value="Dinas Kesehatan">
                    <option value="Rumah Sakit Umum Daerah Provinsi NTB">
                    <option value="Rumah Sakit Mutiara Sukma">
                    <option value="Rumah Sakit H. L. Manambai Abdul Kadir">
                    <option value="Rumah Sakit Mandalika">
                    <option value="Rumah Sakit Mata">
                    <option value="Balai Laboratorium Kesehatan, Pengujian dan Kalibrasi">
                    <option value="Dinas Komunikasi, Informatika dan Statistik">
                    <option value="Dinas Koperasi Usaha Kecil dan Menengah">
                    <option value="Balai Pendidikan dan Pelatihan Koperasi Usaha Kecil dan Menengah">
                    <option value="Dinas Lingkungan Hidup dan Kehutanan">
                    <option value="Balai Laboratorium Lingkungan">
                    <option value="Balai KPH Wilayah I">
                    <option value="Balai KPH Wilayah II">
                    <option value="Balai KPH Wilayah III">
                    <option value="Balai KPH Wilayah IV">
                    <option value="Balai KPH Wilayah V">
                    <option value="Balai KPH Wilayah VI">
                    <option value="Balai KPH Wilayah VII">
                    <option value="Balai KPH Wilayah VIII">
                    <option value="Tempat Pemrosesan Akhir (TPA) Sampah Regional Provinsi NTB">
                    <option value="Dinas Pariwisata dan Ekonomi Kreatif">
                    <option value="UPTD Destinasi Pariwisata Unggulan Gili Tramena">
                    <option value="Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu">
                    <option value="Dinas Pekerjaan Umum, Penataan Ruang, Perumahan dan Kawasan Permukiman">
                    <option value="Balai Pemeliharaan Jalan Provinsi Pulau Lombok">
                    <option value="Balai Pemeliharaan Jalan Provinsi Pulau Sumbawa">
                    <option value="Balai Pengelolaan Sumber Daya Air Pulau Lombok">
                    <option value="Balai Pengelolaan Sumber Daya Air Pulau Sumbawa">
                    <option value="Balai Bina Jasa Konstruksi dan Pengujian Material">
                    <option value="Dinas Pendidikan, Pemuda dan Olahraga">
                    <option value="Cabang Dinas Pendidikan, Pemuda dan Olahraga Wilayah I">
                    <option value="Cabang Dinas Pendidikan, Pemuda dan Olahraga Wilayah II">
                    <option value="Cabang Dinas Pendidikan, Pemuda dan Olahraga Wilayah III">
                    <option value="Cabang Dinas Pendidikan, Pemuda dan Olahraga Wilayah IV">
                    <option value="Cabang Dinas Pendidikan, Pemuda dan Olahraga Wilayah V">
                    <option value="Cabang Dinas Pendidikan, Pemuda dan Olahraga Wilayah VI">
                    <option value="Balai Teknologi Informasi dan Data Pendidikan">
                    <option value="SMKN 3 Mataram">
                    <option value="SMKN 5 Mataram">
                    <option value="SMKN 1 Lingsar">
                    <option value="SMKN 2 Kuripan">
                    <option value="SMKN 1 Praya">
                    <option value="SMKN 1 Selong">
                    <option value="SMKN 1 Taliwang">
                    <option value="SMKN 2 Sumbawa">
                    <option value="SMKN 1 Dompu">
                    <option value="SMKN 2 Kota Bima">
                    <option value="SMKN 1 Donggo">
                    <option value="Dinas Pemberdayaan Masyarakat, Pemerintahan Desa, Kependudukan dan Catatan Sipil">
                    <option value="Dinas Perhubungan">
                    <option value="Dinas Perindustrian dan Perdagangan">
                    <option value="Balai Kemasan, Promosi dan Pemasaran Produk Daerah">
                    <option value="Dinas Perpustakaan dan Kearsipan">
                    <option value="Dinas Pertanian dan Ketahanan Pangan">
                    <option value="Balai Perlindungan Tanaman Pertanian (BPTP)">
                    <option value="Balai Pengawasan dan Sertifikasi Benih Pertanian (BPSB-P)">
                    <option value="Balai Benih Induk Pertanian (BBI-P)">
                    <option value="Balai Pelatihan Pertanian">
                    <option value="Sekolah Menengah Kejuruan Pembangunan Pertanian Negeri Mataram (SMKPPN Mataram)">
                    <option value="Sekolah Menengah Kejuruan Pembangunan Pertanian Negeri Bima (SMKPPN Bima)">
                    <option value="Balai Pengawasan Mutu dan Keamanan Pangan">
                    <option value="Dinas Peternakan dan Kesehatan Hewan">
                    <option value="Balai Pembibitan Ternak dan Hijauan Makanan Ternak Serading">
                    <option value="Balai Inseminasi Buatan dan Pengembangan Pakan Ternak">
                    <option value="Rumah Sakit Hewan dan Laboratorium Veteriner (BRSHLV)">
                    <option value="Dinas Sosial, Pemberdayaan Perempuan dan Perlindungan Anak">
                    <option value="Pusat Pelayanan Sosial Bina Remaja Karya Wanita dan Penyandang Disabilitas Mirah Adi">
                    <option value="Pusat Pelayanan Sosial Lanjut Usia Mandalika">
                    <option value="Perlindungan Perempuan dan Anak">
                    <option value="Pusat Pelayanan Sosial Bina Laras Muthmainnah">
                    <option value="Pusat Pelayanan Sosial Lanjut Usia Meci Angi">
                    <option value="Dinas Tenaga Kerja dan Transmigrasi">
                    <option value="Balai Latihan Kerja (Skill Center)">
                    <option value="Balai Pengawasan Ketenagakerjaan, Keselamatan, dan Kesehatan Kerja Pulau Lombok">
                    <option value="Balai Pengawasan Ketenagakerjaan, Keselamatan, dan Kesehatan Kerja Pulau Sumbawa">
                    <option value="Inspektorat">
                    <option value="Satuan Polisi Pamong Praja">
                    <option value="Sekretariat Dewan Perwakilan Rakyat Daerah">
                </datalist>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Status <span class="text-danger text-red-500">*</span></label>
                <select name="status" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400" required>
                    @foreach(['PPK', 'PP', 'Bendahara', 'POKJA', 'Auditor', 'PA', 'KPA'] as $st)
                        <option value="{{ $st }}" {{ old('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">User ID <span class="text-danger text-red-500">*</span></label>
                <input type="text" name="user_id" value="{{ old('user_id') }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 {{ $errors->has('user_id') ? 'border-red-400 bg-red-50' : '' }}" placeholder="Contoh: MUHAJIDINPPK" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">NIK <span class="text-danger text-red-500">*</span></label>
                <input type="number" name="nik" value="{{ old('nik') }}" oninput="if(this.value.length > 16) this.value = this.value.slice(0, 16);" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 {{ $errors->has('nik') ? 'border-red-400 bg-red-50' : '' }}" placeholder="Maksimal 16 angka" required>
                <small class="text-gray-400">Maksimal 16 angka.</small>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">NIP <span class="text-danger text-red-500">*</span></label>
                <input type="number" name="nip" value="{{ old('nip') }}" oninput="if(this.value.length > 18) this.value = this.value.slice(0, 18);" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 {{ $errors->has('nip') ? 'border-red-400 bg-red-50' : '' }}" placeholder="Maksimal 18 angka" required>
                <small class="text-gray-400">Maksimal 18 angka.</small>
            </div>

            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded mt-2">
                <div>
                    <label class="block text-gray-700 font-bold mb-1 text-sm">No. Surat Permohonan <span class="text-red-500">*</span></label>
                    <input type="text" name="no_surat_permohonan" value="{{ old('no_surat_permohonan') }}" class="w-full border rounded px-3 py-2 bg-white {{ $errors->has('no_surat_permohonan') ? 'border-red-400 bg-red-50' : '' }}" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-1 text-sm">Perihal <span class="text-red-500">*</span></label>
                    <select name="perihal_permohonan" class="w-full border rounded px-3 py-2 bg-white font-bold" required>
                        <option value="Penerbitan Akun" {{ old('perihal_permohonan') == 'Penerbitan Akun' ? 'selected' : '' }}>Penerbitan Akun</option>
                        <option value="Update Akun" {{ old('perihal_permohonan') == 'Update Akun' ? 'selected' : '' }}>Update Akun</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-1 text-sm">Nomor SK <span class="text-red-500">*</span></label>
                    <input type="text" name="no_sk" value="{{ old('no_sk') }}" class="w-full border rounded px-3 py-2 bg-white {{ $errors->has('no_sk') ? 'border-red-400 bg-red-50' : '' }}" required>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Pangkat/Gol <span class="text-danger text-red-500">*</span></label>
                <input type="text" name="pangkat_gol" value="{{ old('pangkat_gol') }}" class="w-full border rounded px-3 py-2 {{ $errors->has('pangkat_gol') ? 'border-red-400 bg-red-50' : '' }}" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Jabatan <span class="text-danger text-red-500">*</span></label>
                <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="w-full border rounded px-3 py-2 {{ $errors->has('jabatan') ? 'border-red-400 bg-red-50' : '' }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">No WhatsApp <span class="text-danger text-red-500">*</span></label>
                <input type="number" name="no_hp" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 {{ $errors->has('no_hp') ? 'border-red-400 bg-red-50' : '' }}" 
                    oninput="if(this.value.length > 12) this.value = this.value.slice(0, 12);" 
                    required value="{{ old('no_hp') }}" placeholder="Contoh: 087865xxxxxx">
                <small class="text-gray-500 text-xs">Maksimal 12 angka.</small>
            </div>

            <div class="mt-4">
                <x-input-label for="tanggal_daftar" :value="__('Tanggal Pengaktifan Akun')" />
                <x-text-input id="tanggal_daftar" class="block mt-1 w-full" 
                            type="date" 
                            name="tanggal_daftar" 
                            :value="old('tanggal_daftar', isset($account) ? $account->tanggal_daftar : date('Y-m-d'))" 
                            required />
                <x-input-error :messages="$errors->get('tanggal_daftar')" class="mt-2" />
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Sumber Data <span class="text-danger text-red-500">*</span></label>
                <div class="mt-2 space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="sumber" value="Fisik" class="form-radio text-blue-600" {{ old('sumber', 'Fisik') == 'Fisik' ? 'checked' : '' }} required>
                        <span class="ml-2">Fisik</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="sumber" value="Digital" class="form-radio text-blue-600" {{ old('sumber') == 'Digital' ? 'checked' : '' }} required>
                        <span class="ml-2">Digital</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Jenis Data <span class="text-red-500">*</span></label>
                <div class="mt-2 space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="jenis_data" value="Katalog v.6" class="form-radio text-blue-600" {{ old('jenis_data', 'Katalog v.6') == 'Katalog v.6' ? 'checked' : '' }} required>
                        <span class="ml-2">Katalog v.6</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="jenis_data" value="SPSE" class="form-radio text-blue-600" {{ old('jenis_data') == 'SPSE' ? 'checked' : '' }} required>
                        <span class="ml-2">SPSE</span>
                    </label>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-1">Alamat <span class="text-danger text-red-500">*</span></label>
                <textarea name="alamat" rows="3" class="w-full border rounded px-3 py-2 {{ $errors->has('alamat') ? 'border-red-400 bg-red-50' : '' }}" required>{{ old('alamat') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('inaproc-accounts.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow-lg font-bold">Simpan Data</button>
        </div>
    </form>
</div>

{{-- MODAL NOTIFIKASI ERROR VALIDASI --}}
@if($errors->any())
<div id="error-modal-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] flex items-center justify-center transition-opacity duration-300">
    <div id="error-modal" class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center transform scale-95 opacity-0 transition-all duration-300">
        {{-- Icon Error --}}
        <div class="mx-auto w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-2">Gagal Menyimpan!</h3>
        <p class="text-sm text-gray-500 mb-4">Mohon periksa kembali isian berikut:</p>
        
        <div class="bg-red-50 rounded-xl p-4 text-left mb-6 max-h-48 overflow-y-auto">
            <ul class="space-y-1.5">
                @foreach($errors->all() as $error)
                <li class="flex items-start text-sm text-red-600">
                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $error }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        
        <button onclick="closeErrorModal()" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-lg shadow-red-100">
            OK, Saya Perbaiki
        </button>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi Modal Error
        const errorModal = document.getElementById('error-modal');
        if (errorModal) {
            setTimeout(() => {
                errorModal.classList.remove('scale-95', 'opacity-0');
                errorModal.classList.add('scale-100', 'opacity-100');
            }, 50);
        }
    });

    function closeErrorModal() {
        const modal = document.getElementById('error-modal');
        const overlay = document.getElementById('error-modal-overlay');
        modal.classList.remove('scale-100', 'opacity-100');
        modal.classList.add('scale-95', 'opacity-0');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.remove(), 300);
    }
</script>
@endsection