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
</head>
<body class="bg-[#f3f4f6] font-sans antialiased">
    {{-- Container lebar agar tabel tidak gepeng --}}
    <div class="w-full p-4 md:p-8">
        @yield('content')
        {{-- Jaga-jaga agar tidak error undefined variable --}}
        {{ $slot ?? '' }}
    </div>
    @stack('scripts')
</body>
</html>