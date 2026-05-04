<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inaproc Storage - Prov. NTB</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[#f3f4f6] font-sans antialiased">
    {{-- Container lebar agar tabel tidak gepeng --}}
    <div class="max-w-7xl mx-auto w-full p-4 md:p-8">
        @yield('content')
        {{-- Jaga-jaga agar tidak error undefined variable --}}
        {{ $slot ?? '' }}
    </div>
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('login_success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Login Berhasil!',
                    text: '{{ session('login_success') }}',
                    showConfirmButton: false,
                    timer: 2500,
                    customClass: {
                        popup: 'rounded-2xl shadow-xl border border-gray-100'
                    }
                });
            @endif
        });
    </script>
</body>
</html>