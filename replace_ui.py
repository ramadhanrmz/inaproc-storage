import re

file_path = r'c:\laragon\www\inaproc-storage\resources\views\inaproc\create.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    html = f.read()

# Replace main container
html = html.replace('<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md mb-10">', 
                    '<div class="max-w-4xl mx-auto bg-white p-8 md:p-10 rounded-2xl shadow-xl outline outline-1 outline-gray-100 mb-10">')

# Replace Header (add logo and center)
header_orig = '<h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Form Tambah Akun Inaproc</h2>'
header_new = '''<div class="flex flex-col items-center justify-center mb-10 pb-6 border-b border-gray-100">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Coat_of_arms_of_West_Nusa_Tenggara.svg/500px-Coat_of_arms_of_West_Nusa_Tenggara.svg.png" class="h-16 w-auto mb-4" alt="Logo NTB">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Form Tambah Akun Inaproc</h2>
        <p class="text-sm font-bold text-gray-500 mt-1">LPSE Provinsi Nusa Tenggara Barat</p>
    </div>'''
html = html.replace(header_orig, header_new)

# Upgrade Inputs generic class
html = html.replace('w-full border rounded px-3 py-2 ', 'w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white text-sm transition-all ')

# Upgrade Labels
html = re.sub(r'<label class="block text-gray-700 font-bold mb-1(?: text-sm)?">', '<label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-2">', html)

# Upgrade Buttons
html = html.replace('bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition', 'bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-50 font-bold transition-all')
html = html.replace('bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow-lg font-bold', 'w-full md:w-auto bg-blue-600 text-white px-8 py-3 rounded-xl hover:bg-blue-700 shadow-lg font-black transition-all')

# Fix grid gap
html = html.replace('grid grid-cols-1 md:grid-cols-2 gap-4', 'grid grid-cols-1 md:grid-cols-2 gap-6')

# Check multiple input for WhatsApp block changes
# Wait, WhatsApp is different.
html = html.replace('w-full border border-gray-300 rounded-none rounded-r px-3 py-2 outline-none focus:ring-0', 'w-full border border-gray-200 bg-gray-50 focus:bg-white transition-all rounded-none rounded-r-xl px-4 py-3 text-sm outline-none focus:ring-0')
html = html.replace('rounded-l', 'rounded-l-xl')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(html)
print('Done class replacements')
