{{-- FLOATING BULK ACTION BAR --}}
<div id="bulk-action-bar" class="fixed bottom-0 left-0 right-0 z-[50] transform translate-y-full transition-transform duration-300 ease-in-out">
    <div class="max-w-4xl mx-auto px-4 pb-6">
        <div class="bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-2xl shadow-2xl shadow-red-200 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-xl p-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <span class="font-black text-sm"><span id="bulk-count">0</span> data dipilih</span>
                    <p class="text-[10px] text-white/70 font-medium">Klik tombol hapus untuk menghapus data terpilih</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="clearAllSelection()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                    Batal Pilih
                </button>
                <button type="button" onclick="openBulkDeleteModal()" class="bg-white text-red-600 hover:bg-red-50 px-5 py-2 rounded-xl text-xs font-black transition-all shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Terpilih
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI BULK DELETE --}}
<div id="bulk-delete-modal-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center transition-opacity duration-300 hidden">
    <div id="bulk-delete-modal" class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 transform scale-95 opacity-0 transition-all duration-300 flex flex-col text-center p-8">
        
        {{-- Icon Warning --}}
        <div class="mx-auto w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="text-xl font-black text-gray-800 mb-2">Hapus Banyak Data?</h3>
        <p class="text-sm text-gray-500 mb-1">Anda akan menghapus</p>
        <p class="text-3xl font-black text-red-600 mb-1" id="bulk-delete-count">0</p>
        <p class="text-sm text-gray-500 mb-6">data akun yang dipilih. Tindakan ini <strong class="text-red-600">tidak dapat dibatalkan</strong>.</p>
        
        <form id="bulk-delete-form" method="POST" action="{{ route('inaproc.bulk-delete') }}">
            @csrf
            <div id="bulk-delete-ids-container"></div>
            <div class="flex justify-center gap-3 w-full">
                <button type="button" onclick="closeBulkDeleteModal()" class="w-full bg-white border border-gray-300 text-gray-700 px-6 py-2.5 rounded-xl hover:bg-gray-50 font-bold transition-all shadow-sm">
                    Batal
                </button>
                <button type="submit" id="bulk-delete-submit-btn" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-lg shadow-red-100 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Ya, Hapus Semua
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const bulkActionBar = document.getElementById('bulk-action-bar');
    const bulkCountLabel = document.getElementById('bulk-count');

    // Update UI berdasarkan jumlah checkbox yang dicentang
    function updateBulkUI() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const allCheckboxes = document.querySelectorAll('.row-checkbox');
        const count = checked.length;

        bulkCountLabel.textContent = count;

        if (count > 0) {
            bulkActionBar.classList.remove('translate-y-full');
            bulkActionBar.classList.add('translate-y-0');
        } else {
            bulkActionBar.classList.remove('translate-y-0');
            bulkActionBar.classList.add('translate-y-full');
        }

        // Update "select all" state
        if (allCheckboxes.length > 0 && count === allCheckboxes.length) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (count > 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }

        // Highlight rows yang dicentang
        allCheckboxes.forEach(cb => {
            const row = cb.closest('tr');
            if (cb.checked) {
                row.classList.add('bg-blue-50/60');
                row.classList.remove('hover:bg-blue-50/30');
            } else {
                row.classList.remove('bg-blue-50/60');
                row.classList.add('hover:bg-blue-50/30');
            }
        });
    }

    // Event: Select All
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkUI();
        });
    }

    // Event: Individual checkbox
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('row-checkbox')) {
            updateBulkUI();
        }
    });

    // Batal Pilih
    window.clearAllSelection = function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        if (selectAllCheckbox) selectAllCheckbox.checked = false;
        updateBulkUI();
    };

    // Buka Modal Bulk Delete
    window.openBulkDeleteModal = function() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;

        const overlay = document.getElementById('bulk-delete-modal-overlay');
        const modal = document.getElementById('bulk-delete-modal');
        const countLabel = document.getElementById('bulk-delete-count');
        const container = document.getElementById('bulk-delete-ids-container');

        countLabel.textContent = checked.length;

        // Bersihkan hidden inputs lama & isi dengan ID baru
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            modal.classList.remove('scale-95', 'opacity-0');
            modal.classList.add('scale-100', 'opacity-100');
        }, 50);
    };

    // Tutup Modal Bulk Delete
    window.closeBulkDeleteModal = function() {
        const modal = document.getElementById('bulk-delete-modal');
        const overlay = document.getElementById('bulk-delete-modal-overlay');

        modal.classList.remove('scale-100', 'opacity-100');
        modal.classList.add('scale-95', 'opacity-0');
        overlay.classList.add('opacity-0');
        document.body.style.overflow = '';

        setTimeout(() => {
            overlay.classList.add('hidden');
            overlay.classList.remove('opacity-0');
        }, 300);
    };

    // Tutup modal klik overlay
    document.getElementById('bulk-delete-modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) closeBulkDeleteModal();
    });

    // Tutup modal dengan Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('bulk-delete-modal-overlay').classList.contains('hidden')) {
            closeBulkDeleteModal();
        }
    });

    // Loading state saat submit
    document.getElementById('bulk-delete-form').addEventListener('submit', function() {
        const btn = document.getElementById('bulk-delete-submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div> Menghapus...';
    });
})();
</script>
