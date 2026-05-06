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
                <h1 class="text-xl md:text-2xl font-black text-blue-800 uppercase tracking-tight">Manajemen User Login</h1>
                <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Sistem Keamanan Inaproc Storage</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="openCreateModal()" title="Tambah User Baru" class="inline-flex items-center justify-center bg-blue-600 text-white w-11 h-11 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:scale-110 transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-black tracking-widest border-b border-gray-100">
                        <th class="p-4 text-center w-20">No</th>
                        <th class="p-4">Informasi User</th>
                        <th class="p-4 text-center">Role</th>
                        <th class="p-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4 text-center text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-700 text-sm">{{ $item->name }}</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $item->email }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            @if($item->isAdmin())
                                <span class="inline-block px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-wider border border-indigo-100">Admin</span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider border border-slate-100">User Biasa</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center space-x-2">
                                {{-- Tombol Edit: Hanya Super Admin, atau Admin (jika target bukan admin lain), atau diri sendiri --}}
                                @if(auth()->user()->isSuperAdmin() || !$item->isAdmin() || $item->id === auth()->id())
                                <button type="button" 
                                        onclick="openEditModal('{{ $item->id }}', '{{ addslashes($item->name) }}', '{{ $item->email }}', '{{ $item->role }}')" 
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endif

                                {{-- Tombol Hapus: Hanya Super Admin (untuk siapa saja kecuali dirinya) atau Admin (hanya untuk user biasa) --}}
                                @php
                                    $isSelf = $item->id === auth()->id();
                                    $isTargetAdmin = $item->isAdmin();
                                    $isTargetSuper = $item->isSuperAdmin();
                                    
                                    $showDelete = false;
                                    if (!$isSelf && !$isTargetSuper) {
                                        if (auth()->user()->isSuperAdmin()) {
                                            $showDelete = true;
                                        } elseif (!$isTargetAdmin) {
                                            $showDelete = true;
                                        }
                                    }
                                @endphp

                                @if($showDelete)
                                <button type="button" 
                                        onclick="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->name) }}')" 
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-100 text-rose-600 hover:bg-rose-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH USER --}}
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateModal()"></div>
        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10 border border-gray-100">
            <h3 class="text-lg font-black text-gray-800 uppercase mb-4 border-b pb-2">Tambah User Baru</h3>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Email (Username)</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Role / Hak Akses</label>
                        <select name="role" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm cursor-pointer">
                            <option value="user">User Biasa</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-100 transition-all">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT USER --}}
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10 border border-gray-100">
            <h3 class="text-lg font-black text-gray-800 uppercase mb-4 border-b pb-2">Edit User</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="editName" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Email (Username)</label>
                        <input type="email" name="email" id="editEmail" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 text-amber-600">Password Baru (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="w-full px-4 py-2 bg-amber-50/30 border border-amber-100 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Role / Hak Akses</label>
                        <select name="role" id="editRole" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm cursor-pointer">
                            <option value="user">User Biasa</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-md shadow-amber-100 transition-all">Perbarui User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL HAPUS USER --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="inline-block w-full max-w-md p-8 my-8 overflow-hidden text-center align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-black text-gray-800 mb-2 uppercase">Hapus User?</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus user <span id="deleteUserName" class="font-black text-gray-800"></span>? Tindakan ini tidak dapat dibatalkan.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex flex-col gap-2">
                    <button type="submit" class="w-full px-4 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">Ya, Hapus Permanen</button>
                    <button type="button" onclick="closeDeleteModal()" class="w-full px-4 py-3 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(id, name, email, role) {
        document.getElementById('editForm').action = `/users/${id}`;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRole').value = role;
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteForm').action = `/users/${id}`;
        document.getElementById('deleteUserName').innerText = name;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
