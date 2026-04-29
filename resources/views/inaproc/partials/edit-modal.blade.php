{{-- MODAL EDIT AKUN --}}
<div id="edit-modal-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center transition-opacity duration-300 hidden">
    <div id="edit-modal" class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 transform scale-95 opacity-0 transition-all duration-300 max-h-[90vh] flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-800">Edit Data Akun</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">LPSE Provinsi NTB</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-red-100 text-gray-400 hover:text-red-500 flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Loading Spinner --}}
        <div id="edit-modal-loading" class="flex items-center justify-center py-20 hidden">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-amber-500"></div>
        </div>

        {{-- Form Body (scrollable) --}}
        <form id="edit-form" class="overflow-y-auto flex-1 hidden" onsubmit="return submitEditForm(event)">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="edit-nama" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Perangkat Daerah (OPD) <span class="text-red-500">*</span></label>
                        <input list="edit_list_opd" name="opd" id="edit-opd" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                        <datalist id="edit_list_opd">
                            <option value="Badan Kesatuan Bangsa dan Politik Dalam Negeri">
                            <option value="Badan Kepegawaian Daerah">
                            <option value="Badan Keuangan dan Aset Daerah">
                            <option value="Badan Penanggulangan Bencana Daerah">
                            <option value="Badan Pendapatan Daerah">
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
                            <option value="Dinas Kelautan dan Perikanan">
                            <option value="Dinas Kesehatan">
                            <option value="Rumah Sakit Umum Daerah Provinsi NTB">
                            <option value="Dinas Komunikasi, Informatika dan Statistik">
                            <option value="Dinas Koperasi Usaha Kecil dan Menengah">
                            <option value="Dinas Lingkungan Hidup dan Kehutanan">
                            <option value="Dinas Pariwisata dan Ekonomi Kreatif">
                            <option value="Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu">
                            <option value="Dinas Pekerjaan Umum, Penataan Ruang, Perumahan dan Kawasan Permukiman">
                            <option value="Dinas Pendidikan, Pemuda dan Olahraga">
                            <option value="Dinas Pemberdayaan Masyarakat, Pemerintahan Desa, Kependudukan dan Catatan Sipil">
                            <option value="Dinas Perhubungan">
                            <option value="Dinas Perindustrian dan Perdagangan">
                            <option value="Dinas Perpustakaan dan Kearsipan">
                            <option value="Dinas Pertanian dan Ketahanan Pangan">
                            <option value="Dinas Peternakan dan Kesehatan Hewan">
                            <option value="Dinas Sosial, Pemberdayaan Perempuan dan Perlindungan Anak">
                            <option value="Dinas Tenaga Kerja dan Transmigrasi">
                            <option value="Inspektorat">
                            <option value="Satuan Polisi Pamong Praja">
                            <option value="Sekretariat Dewan Perwakilan Rakyat Daerah">
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="edit-status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                            <option value="PPK">PPK</option>
                            <option value="PP">PP</option>
                            <option value="Bendahara">Bendahara</option>
                            <option value="POKJA">POKJA</option>
                            <option value="Auditor">Auditor</option>
                            <option value="PA">PA</option>
                            <option value="KPA">KPA</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">User ID <span class="text-red-500">*</span></label>
                        <input type="text" name="user_id" id="edit-user_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">NIK <span class="text-red-500">*</span></label>
                        <input type="number" name="nik" id="edit-nik" oninput="if(this.value.length > 16) this.value = this.value.slice(0, 16);" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">NIP <span class="text-red-500">*</span></label>
                        <input type="number" name="nip" id="edit-nip" oninput="if(this.value.length > 18) this.value = this.value.slice(0, 18);" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                    </div>
                </div>

                {{-- Surat Section --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">No. Surat Permohonan <span class="text-red-500">*</span></label>
                        <input type="text" name="no_surat_permohonan" id="edit-no_surat_permohonan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Perihal <span class="text-red-500">*</span></label>
                        <select name="perihal_permohonan" id="edit-perihal_permohonan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400 font-bold" required>
                            <option value="Penerbitan Akun">Penerbitan Akun</option>
                            <option value="Update Akun">Update Akun</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Nomor SK <span class="text-red-500">*</span></label>
                        <input type="text" name="no_sk" id="edit-no_sk" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Pangkat/Gol <span class="text-red-500">*</span></label>
                        <input type="text" name="pangkat_gol" id="edit-pangkat_gol" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="jabatan" id="edit-jabatan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">No WhatsApp <span class="text-red-500">*</span></label>
                        <div class="flex items-stretch focus-within:ring-2 focus-within:ring-amber-400 rounded-xl transition-all">
                            <span class="flex items-center justify-center px-4 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-gray-600 font-black text-sm">+62</span>
                            <input type="number" name="no_hp" id="edit-no_hp" oninput="if(this.value.length > 12) this.value = this.value.slice(0, 12);" class="w-full border border-gray-200 bg-gray-50 focus:bg-white transition-all rounded-none rounded-r-xl px-4 py-2.5 text-sm outline-none focus:ring-0" required placeholder="87865xxxxxx">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Tanggal Pengaktifan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_daftar" id="edit-tanggal_daftar" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required>
                    </div>
                </div>

                {{-- Radio Groups --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-2">Sumber Data <span class="text-red-500">*</span></label>
                        <div class="flex space-x-6">
                            <label class="inline-flex items-center"><input type="radio" name="sumber" value="Fisik" class="form-radio text-amber-500" required><span class="ml-2 text-sm">Fisik</span></label>
                            <label class="inline-flex items-center"><input type="radio" name="sumber" value="Digital" class="form-radio text-amber-500" required><span class="ml-2 text-sm">Digital</span></label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-2">Jenis Data <span class="text-red-500">*</span></label>
                        <div class="flex space-x-6">
                            <label class="inline-flex items-center"><input type="radio" name="jenis_data" value="Katalog v.6" class="form-radio text-amber-500" required><span class="ml-2 text-sm">Katalog v.6</span></label>
                            <label class="inline-flex items-center"><input type="radio" name="jenis_data" value="SPSE" class="form-radio text-amber-500" required><span class="ml-2 text-sm">SPSE</span></label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" id="edit-alamat" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white text-sm transition-all focus:ring-2 focus:ring-amber-400" required></textarea>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 shrink-0 bg-gray-50/50 rounded-b-2xl">
                <button type="button" onclick="closeEditModal()" class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded-xl hover:bg-gray-50 font-bold text-sm transition-all">Batal</button>
                <button type="submit" id="edit-submit-btn" class="bg-amber-500 text-white px-6 py-2.5 rounded-xl hover:bg-amber-600 shadow-lg font-black text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update Data
                </button>
            </div>

            {{-- Error container --}}
            <div id="edit-modal-errors" class="hidden px-6 pb-4">
                <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                    <p class="text-sm font-bold text-red-600 mb-2">Gagal menyimpan:</p>
                    <ul id="edit-modal-error-list" class="space-y-1 text-sm text-red-500"></ul>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let editAccountId = null;

function openEditModal(id) {
    editAccountId = id;
    const overlay = document.getElementById('edit-modal-overlay');
    const modal = document.getElementById('edit-modal');
    const loading = document.getElementById('edit-modal-loading');
    const form = document.getElementById('edit-form');
    const errBox = document.getElementById('edit-modal-errors');

    // Show overlay + loading
    overlay.classList.remove('hidden');
    loading.classList.remove('hidden');
    form.classList.add('hidden');
    errBox.classList.add('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        modal.classList.remove('scale-95', 'opacity-0');
        modal.classList.add('scale-100', 'opacity-100');
    }, 50);

    // Fetch account data
    fetch(`{{ url('accounts') }}/${id}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        // Populate fields
        document.getElementById('edit-nama').value = data.nama || '';
        document.getElementById('edit-opd').value = data.opd || '';
        document.getElementById('edit-status').value = data.status || '';
        document.getElementById('edit-user_id').value = data.user_id || '';
        document.getElementById('edit-nik').value = data.nik || '';
        document.getElementById('edit-nip').value = data.nip || '';
        document.getElementById('edit-no_surat_permohonan').value = data.no_surat_permohonan || '';
        document.getElementById('edit-perihal_permohonan').value = data.perihal_permohonan || '';
        document.getElementById('edit-no_sk').value = data.no_sk || '';
        document.getElementById('edit-pangkat_gol').value = data.pangkat_gol || '';
        document.getElementById('edit-jabatan').value = data.jabatan || '';
        document.getElementById('edit-alamat').value = data.alamat || '';
        document.getElementById('edit-tanggal_daftar').value = data.tanggal_daftar || '';

        // Clean phone: remove 62 prefix for display
        let hp = (data.no_hp || '').replace(/[^0-9]/g, '');
        if (hp.startsWith('62')) hp = hp.substring(2);
        document.getElementById('edit-no_hp').value = hp;

        // Set radio buttons
        document.querySelectorAll('input[name="sumber"]').forEach(r => r.checked = (r.value === data.sumber));
        document.querySelectorAll('input[name="jenis_data"]').forEach(r => r.checked = (r.value === data.jenis_data));

        loading.classList.add('hidden');
        form.classList.remove('hidden');
    })
    .catch(() => {
        loading.classList.add('hidden');
        alert('Gagal memuat data. Silakan coba lagi.');
        closeEditModal();
    });
}

function closeEditModal() {
    const modal = document.getElementById('edit-modal');
    const overlay = document.getElementById('edit-modal-overlay');
    modal.classList.remove('scale-100', 'opacity-100');
    modal.classList.add('scale-95', 'opacity-0');
    overlay.classList.add('opacity-0');
    document.body.style.overflow = '';
    setTimeout(() => {
        overlay.classList.add('hidden');
        overlay.classList.remove('opacity-0');
    }, 300);
}

function submitEditForm(e) {
    e.preventDefault();
    const form = document.getElementById('edit-form');
    const btn = document.getElementById('edit-submit-btn');
    const errBox = document.getElementById('edit-modal-errors');
    const errList = document.getElementById('edit-modal-error-list');

    btn.disabled = true;
    btn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div> Menyimpan...';
    errBox.classList.add('hidden');

    const formData = new FormData(form);

    fetch(`{{ url('accounts') }}/${editAccountId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(async r => {
        const data = await r.json();
        if (!r.ok) throw data;
        return data;
    })
    .then(data => {
        closeEditModal();
        // Reload page with success message
        window.location.reload();
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Update Data';

        if (err.errors) {
            errList.innerHTML = '';
            Object.values(err.errors).flat().forEach(msg => {
                const li = document.createElement('li');
                li.textContent = '• ' + msg;
                errList.appendChild(li);
            });
            errBox.classList.remove('hidden');
            // Scroll to error
            errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            alert(err.message || 'Terjadi kesalahan. Silakan coba lagi.');
        }
    });

    return false;
}

// Close modal on overlay click
document.getElementById('edit-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('edit-modal-overlay').classList.contains('hidden')) {
        closeEditModal();
    }
});
</script>
