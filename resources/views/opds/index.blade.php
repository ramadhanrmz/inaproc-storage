@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('inaproc-accounts.index') }}" title="Kembali ke Beranda" class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-gray-50 text-gray-400 border border-gray-100 hover:bg-gray-600 hover:text-white transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-black text-blue-800 uppercase tracking-tight">Master Data OPD</h1>
                <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">LPSE Provinsi Nusa Tenggara Barat</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- Tombol Bulk Delete (Hidden by default) --}}
            <button type="button" id="bulkDeleteBtn" onclick="openBulkDeleteModal()" class="hidden items-center justify-center bg-rose-100 text-rose-600 border border-rose-200 px-4 h-11 rounded-xl shadow-sm hover:bg-rose-600 hover:text-white transition-all duration-300 font-bold text-xs">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus Terpilih (<span id="selectedCount">0</span>)
            </button>

            {{-- Tombol Tambah OPD --}}
            <button type="button" onclick="openCreateModal()" title="Tambah OPD Baru" class="inline-flex items-center justify-center bg-blue-600 text-white w-11 h-11 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:scale-110 transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form id="bulkDeleteForm" action="{{ route('opds.bulk-delete') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-black tracking-widest border-b border-gray-100">
                            <th class="p-4 text-center w-12">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </th>
                            <th class="p-4 text-center w-16">No</th>
                            <th class="p-4">Nama OPD / Satuan Kerja</th>
                            <th class="p-4 text-center w-40">Jumlah Akun</th>
                            <th class="p-4 text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($opds as $index => $item)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="p-4 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="opd-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="p-4 text-center text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                            <td class="p-4">
                                <span class="font-bold text-gray-700 text-sm">{{ $item->nama }}</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-black">
                                    {{ $item->accounts_count ?? $item->accounts()->count() }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-center space-x-2">
                                    <button type="button" onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->nama) }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white transition-all">
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
        </form>
    </div>
</div>

{{-- MODAL TAMBAH OPD --}}
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateModal()"></div>
        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
            <h3 class="text-lg font-black text-gray-800 uppercase mb-4">Tambah OPD Baru</h3>
            <form action="{{ route('opds.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama OPD</label>
                    <input type="text" name="nama" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT OPD --}}
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
            <h3 class="text-lg font-black text-gray-800 uppercase mb-4">Edit OPD</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama OPD</label>
                    <input type="text" name="nama" id="editNama" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition-colors">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL HAPUS OPD --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="inline-block w-full max-w-md p-8 my-8 overflow-hidden text-center align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-black text-gray-800 mb-2">Hapus OPD?</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus OPD <span id="deleteOpdName" class="font-black text-gray-800"></span>? Tindakan ini tidak dapat dibatalkan.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex flex-col gap-2">
                    <button type="submit" class="w-full px-4 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">Ya, Hapus OPD</button>
                    <button type="button" onclick="closeDeleteModal()" class="w-full px-4 py-3 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL BULK DELETE --}}
<div id="bulkDeleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeBulkDeleteModal()"></div>
        <div class="inline-block w-full max-w-md p-8 my-8 overflow-hidden text-center align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-xl font-black text-gray-800 mb-2">Hapus OPD Terpilih?</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus <span id="bulkDeleteCount" class="font-black text-gray-800"></span> OPD yang dipilih? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex flex-col gap-2">
                <button type="button" onclick="submitBulkDelete()" class="w-full px-4 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">Ya, Hapus Semua Terpilih</button>
                <button type="button" onclick="closeBulkDeleteModal()" class="w-full px-4 py-3 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.opd-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.getElementById('selectedCount');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkDeleteButton();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkDeleteButton);
    });

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.opd-checkbox:checked').length;
        selectedCountSpan.innerText = checkedCount;
        if (checkedCount > 0) {
            bulkDeleteBtn.classList.remove('hidden');
            bulkDeleteBtn.classList.add('flex');
        } else {
            bulkDeleteBtn.classList.add('hidden');
            bulkDeleteBtn.classList.remove('flex');
        }
    }

    function openBulkDeleteModal() {
        const checkedCount = document.querySelectorAll('.opd-checkbox:checked').length;
        document.getElementById('bulkDeleteCount').innerText = checkedCount;
        document.getElementById('bulkDeleteModal').classList.remove('hidden');
    }

    function closeBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').classList.add('hidden');
    }

    function submitBulkDelete() {
        document.getElementById('bulkDeleteForm').submit();
    }

    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(id, nama) {
        document.getElementById('editForm').action = `/opds/${id}`;
        document.getElementById('editNama').value = nama;
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('deleteForm').action = `/opds/${id}`;
        document.getElementById('deleteOpdName').innerText = nama;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
