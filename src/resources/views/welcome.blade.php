<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            @php
                try {
                    $logo = \App\Models\LibrarySetting::get('site_logo');
                } catch (\Exception $e) {
                    $logo = null;
                }
            @endphp
            
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo Satker" class="h-10 object-contain">
            @else
                <div class="h-10 w-10 bg-indigo-600 rounded flex items-center justify-center text-white font-bold text-xl">
                    📚
                </div>
            @endif
            
            <span class="font-bold text-xl text-gray-900">Perpustakaan Digital</span>
        </div>
        <div>
            @auth
                <a href="{{ url('/admin') }}" class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-md font-medium hover:bg-indigo-100 transition">
                    Dashboard Admin
                </a>
            @else
                <a href="{{ url('/admin/login') }}" class="text-gray-600 hover:text-gray-900 font-medium mr-4">Log in</a>
                <a href="{{ url('/admin/login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-medium hover:bg-indigo-700 transition">
                    Mulai
                </a>
            @endauth
        </div>
    </nav>

    <main class="flex-grow flex flex-col items-center justify-center max-w-4xl mx-auto p-6 text-center">
        @if($logo)
            <img src="{{ asset('storage/' . $logo) }}" alt="Logo Satker Besar" class="h-32 object-contain mb-8">
        @endif
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">Selamat Datang di Perpustakaan</h1>
        <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-2xl">
            Jelajahi ribuan koleksi buku digital maupun fisik kami. Pinjam, baca, dan kembalikan dengan mudah melalui sistem yang terintegrasi.
        </p>
        <div class="flex gap-4">
            <a href="{{ url('/admin') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:bg-indigo-700 hover:shadow-xl transition-all">
                Masuk ke Sistem
            </a>
        </div>
    </main>

    <footer class="bg-white border-t py-6 text-center text-gray-500 text-sm">
        <div class="mb-2">
            &copy; {{ date('Y') }} Perpustakaan Digital. All rights reserved.
        </div>
        <div class="text-xs text-gray-400 opacity-80 mt-2">
            Developed by <span class="font-semibold text-gray-500 tracking-wider">zhayyn</span> &bull; 081317361689
        </div>
    </footer>
</body>
</html>
