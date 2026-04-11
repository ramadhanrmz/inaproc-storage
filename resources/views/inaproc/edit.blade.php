@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md mb-10 border-t-4 border-yellow-400">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Akun Inaproc: {{ $inaprocAccount->nama }}</h2>
    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow">
        <p class="font-bold">Waduh Mas Robi, ada yang salah input nih:</p>
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('inaproc-accounts.update', $inaprocAccount->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-bold mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $inaprocAccount->nama) }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Perangkat Daerah (OPD) <span class="text-red-500">*</span></label>
                <input list="list_opd" name="opd" value="{{ old('opd', $inaprocAccount->opd) }}" class="w-full border rounded px-3 py-2" required>
                </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    @foreach(['PPK', 'PP', 'Bendahara', 'POKJA', 'Auditor', 'PA', 'KPA'] as $st)
                        <option value="{{ $st }}" {{ $inaprocAccount->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">User ID <span class="text-red-500">*</span></label>
                <input type="text" name="user_id" value="{{ old('user_id', $inaprocAccount->user_id) }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">NIK <span class="text-red-500">*</span></label>
                <input type="number" name="nik" value="{{ old('nik', $inaprocAccount->nik) }}" oninput="if(this.value.length > 16) this.value = this.value.slice(0, 16);" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">NIP <span class="text-red-500">*</span></label>
                <input type="number" name="nip" value="{{ old('nip', $inaprocAccount->nip) }}" oninput="if(this.value.length > 18) this.value = this.value.slice(0, 18);" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded">
                <div>
                    <label class="block text-gray-700 font-bold mb-1 text-sm">No. Surat Permohonan</label>
                    <input type="text" name="no_surat_permohonan" value="{{ old('no_surat_permohonan', $inaprocAccount->no_surat_permohonan) }}" class="w-full border rounded px-3 py-2 bg-white" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-1 text-sm">Perihal</label>
                    <select name="perihal_permohonan" class="w-full border rounded px-3 py-2 bg-white font-bold" required>
                        <option value="Penerbitan Akun" {{ $inaprocAccount->perihal_permohonan == 'Penerbitan Akun' ? 'selected' : '' }}>Penerbitan Akun</option>
                        <option value="Update Akun" {{ $inaprocAccount->perihal_permohonan == 'Update Akun' ? 'selected' : '' }}>Update Akun</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-1 text-sm">Nomor SK</label>
                    <input type="text" name="no_sk" value="{{ old('no_sk', $inaprocAccount->no_sk) }}" class="w-full border rounded px-3 py-2 bg-white" required>
                </div>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Pangkat/Gol <span class="text-red-500">*</span></label>
                <input type="text" name="pangkat_gol" value="{{ old('pangkat_gol', $inaprocAccount->pangkat_gol) }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Jabatan <span class="text-red-500">*</span></label>
                <input type="text" name="jabatan" value="{{ old('jabatan', $inaprocAccount->jabatan) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">No WhatsApp <span class="text-red-500">*</span></label>
                <input type="number" name="no_hp" value="{{ old('no_hp', $inaprocAccount->no_hp) }}" oninput="if(this.value.length > 12) this.value = this.value.slice(0, 12);" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Jenis Data <span class="text-red-500">*</span></label>
                <div class="mt-2 space-x-6 text-sm font-semibold">
                    <label class="inline-flex items-center">
                        <input type="radio" name="jenis_data" value="Katalog v.6" {{ $inaprocAccount->jenis_data == 'Katalog v.6' ? 'checked' : '' }} required>
                        <span class="ml-2">Katalog v.6</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="jenis_data" value="SPSE" {{ $inaprocAccount->jenis_data == 'SPSE' ? 'checked' : '' }} required>
                        <span class="ml-2">SPSE</span>
                    </label>
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-1">Sumber Data <span class="text-red-500">*</span></label>
                <div class="mt-2 space-x-6 text-sm font-semibold">
                    <label class="inline-flex items-center">
                        <input type="radio" name="sumber" value="Fisik" {{ $inaprocAccount->sumber == 'Fisik' ? 'checked' : '' }} required>
                        <span class="ml-2 text-orange-600">Fisik</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="sumber" value="Digital" {{ $inaprocAccount->sumber == 'Digital' ? 'checked' : '' }} required>
                        <span class="ml-2 text-green-600">Digital</span>
                    </label>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-1">Alamat <span class="text-red-500">*</span></label>
                <textarea name="alamat" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('alamat', $inaprocAccount->alamat) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('inaproc-accounts.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600 shadow-lg font-bold">Update Data</button>
        </div>
    </form>
</div>
@endsection