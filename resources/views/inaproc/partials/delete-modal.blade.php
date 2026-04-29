{{-- MODAL HAPUS AKUN --}}
<div id="delete-modal-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center transition-opacity duration-300 hidden">
    <div id="delete-modal" class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 transform scale-95 opacity-0 transition-all duration-300 flex flex-col text-center p-8">
        
        {{-- Icon Warning --}}
        <div class="mx-auto w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="text-xl font-black text-gray-800 mb-2">Konfirmasi Hapus</h3>
        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus user <strong id="delete-user-name" class="text-red-600"></strong>?</p>
        
        <form id="delete-form" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex justify-center gap-3 w-full">
                <button type="button" onclick="closeDeleteModal()" class="w-full bg-white border border-gray-300 text-gray-700 px-6 py-2.5 rounded-xl hover:bg-gray-50 font-bold transition-all shadow-sm">
                    Tidak
                </button>
                <button type="submit" id="delete-submit-btn" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-lg shadow-red-100 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openDeleteModal(id, nama) {
    const overlay = document.getElementById('delete-modal-overlay');
    const modal = document.getElementById('delete-modal');
    const form = document.getElementById('delete-form');
    const nameLabel = document.getElementById('delete-user-name');

    // Update nama
    nameLabel.textContent = "'" + nama + "'";
    
    // Update action url
    form.action = `/accounts/${id}`;

    // Show overlay
    overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        modal.classList.remove('scale-95', 'opacity-0');
        modal.classList.add('scale-100', 'opacity-100');
    }, 50);
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    const overlay = document.getElementById('delete-modal-overlay');
    
    modal.classList.remove('scale-100', 'opacity-100');
    modal.classList.add('scale-95', 'opacity-0');
    overlay.classList.add('opacity-0');
    document.body.style.overflow = '';
    
    setTimeout(() => {
        overlay.classList.add('hidden');
        overlay.classList.remove('opacity-0');
    }, 300);
}

// Close modal on overlay click
document.getElementById('delete-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('delete-modal-overlay').classList.contains('hidden')) {
        closeDeleteModal();
    }
});

// Add loading state when submitting
document.getElementById('delete-form').addEventListener('submit', function() {
    const btn = document.getElementById('delete-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div> Menghapus...';
});
</script>
