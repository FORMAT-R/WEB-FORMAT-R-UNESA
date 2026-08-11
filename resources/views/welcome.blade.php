<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Website Resmi FORMAT-R (Forum Mahasiswa Trunojoyo - Rantau) UNESA. Wadah silaturahmi, informasi, dan pengembangan diri mahasiswa rantau di Universitas Negeri Surabaya.">
    <meta name="keywords" content="FORMAT-R, FORMAT-R UNESA, Mahasiswa Rantau UNESA, Forum Mahasiswa Trunojoyo, UNESA, Organisasi Mahasiswa UNESA">
    <meta name="robots" content="index, follow">
    <title>{{ get_setting('cabinetName', 'Kabinet') }} - FORMAT R UNESA</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js & Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #3b82f6, #8b5cf6, #ec4899);
        }
        .blob-1 {
            position: absolute; top: -10%; left: -10%; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(59,130,246,0.3) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%; filter: blur(60px); z-index: -1;
        }
        .blob-2 {
            position: absolute; bottom: -10%; right: -10%; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(139,92,246,0.3) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%; filter: blur(60px); z-index: -1;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-slate-900 dark:text-gray-100 antialiased selection:bg-blue-500 selection:text-white"
      x-data="{ scrolled: false }" 
      @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Background Blobs -->
    <div class="blob-1"></div>
    <div class="blob-2"></div>

    <!-- Navigation -->
    <nav :class="{'glass-nav shadow-sm': scrolled, 'bg-transparent': !scrolled}" class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    @if(get_setting('cabinetLogo'))
                        <img src="{{ Storage::url(get_setting('cabinetLogo')) }}" alt="Logo Kabinet" class="h-10 w-auto object-contain">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            {{ substr(get_setting('cabinetName', 'FORMAT'), 0, 1) }}
                        </div>
                    @endif
                    <span class="font-bold text-xl tracking-tight">{{ get_setting('cabinetName', 'Kabinet Kolaborasi Asa') }}</span>
                </div>
                
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#tentang" class="text-sm font-medium text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 transition-colors">Tentang</a>
                    <a href="#visimisi" class="text-sm font-medium text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 transition-colors">Visi Misi</a>
                    <a href="{{ route('departemen.index') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 transition-colors">Departemen</a>
                    
                    @auth
                        <a href="{{ url('/admin/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5">
                            Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-gray-900 dark:text-white border border-gray-200 dark:border-slate-700 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 shadow-sm transition-all">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-medium mb-8 border border-blue-100 dark:border-blue-800/50">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                </span>
                Selamat Datang di Portal Resmi FORMAT-R
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-tight">
                Bersama Membangun <br class="hidden md:block">
                <span class="gradient-text">{{ get_setting('cabinetName', 'Kolaborasi Asa') }}</span>
            </h1>
            
            <p class="mt-4 text-xl md:text-2xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto mb-10 leading-relaxed">
                Wadah aspirasi dan inovasi mahasiswa untuk menciptakan lingkungan kampus yang lebih baik, inklusif, dan berprestasi.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#tentang" class="px-8 py-4 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-xl shadow-blue-500/30 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                    Kenali Kami
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="{{ route('departemen.index') }}" class="px-8 py-4 rounded-xl bg-white dark:bg-slate-800 text-gray-900 dark:text-white border border-gray-200 dark:border-slate-700 font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 shadow-sm transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                    Lihat Departemen
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section id="tentang" class="py-20 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm border-y border-gray-200/50 dark:border-slate-800/50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1 relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-3xl transform rotate-3 scale-105 -z-10"></div>
                    <div class="glass-card p-8 md:p-10 rounded-3xl">
                        <div class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6">
                            <i data-lucide="info" class="w-8 h-8"></i>
                        </div>
                        <h2 class="text-3xl font-bold mb-4">Tentang FORMAT-R</h2>
                        <div class="prose dark:prose-invert text-gray-600 dark:text-gray-400 text-lg leading-relaxed">
                            {!! nl2br(e(get_setting('aboutFormat', 'FORMAT-R adalah organisasi mahasiswa tingkat program studi yang menaungi seluruh mahasiswa di lingkup jurusan. Kami bergerak di berbagai bidang untuk memaksimalkan potensi setiap individu.'))) !!}
                        </div>
                    </div>
                </div>
                <div class="order-1 md:order-2 space-y-6">
                    <h2 class="text-blue-600 dark:text-blue-400 font-semibold tracking-wider uppercase text-sm">Organisasi Kita</h2>
                    <h3 class="text-4xl md:text-5xl font-bold leading-tight">Ruang Berkembang<br>Bagi Mahasiswa</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">
                        Melalui berbagai program kerja dan inisiatif, kami berdedikasi untuk memfasilitasi minat, bakat, serta aspirasi mahasiswa demi tercapainya lingkungan akademis yang dinamis.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-6 pt-6">
                        <div class="border-l-2 border-blue-500 pl-4">
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">5+</div>
                            <div class="text-sm text-gray-500">Departemen Aktif</div>
                        </div>
                        <div class="border-l-2 border-purple-500 pl-4">
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">100%</div>
                            <div class="text-sm text-gray-500">Kolaborasi & Sinergi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section id="visimisi" class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-blue-600 dark:text-blue-400 font-semibold tracking-wider uppercase text-sm mb-2">Arah Gerak</h2>
                <h3 class="text-4xl md:text-5xl font-bold">Visi & Misi Kabinet</h3>
            </div>
            
            <div class="grid md:grid-cols-12 gap-8">
                <!-- Visi -->
                <div class="md:col-span-5 relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl transform transition-transform group-hover:scale-[1.02] -z-10"></div>
                    <div class="h-full bg-blue-600 text-white p-10 rounded-3xl flex flex-col justify-center relative overflow-hidden">
                        <i data-lucide="eye" class="w-32 h-32 absolute -right-6 -bottom-6 text-white/10 rotate-12"></i>
                        <h4 class="text-2xl font-bold mb-6 flex items-center gap-3">
                            <span class="p-2 bg-white/20 rounded-lg"><i data-lucide="target" class="w-6 h-6"></i></span>
                            Visi
                        </h4>
                        <p class="text-xl leading-relaxed text-blue-50">
                            "{{ get_setting('cabinetVision', 'Menjadikan himpunan sebagai wadah yang progresif, inklusif, dan inovatif bagi seluruh mahasiswa.') }}"
                        </p>
                    </div>
                </div>
                
                <!-- Misi -->
                <div class="md:col-span-7">
                    <div class="glass-card h-full p-10 rounded-3xl relative overflow-hidden">
                        <i data-lucide="list-todo" class="w-32 h-32 absolute -right-6 -bottom-6 text-gray-900/5 dark:text-white/5 rotate-12"></i>
                        <h4 class="text-2xl font-bold mb-8 flex items-center gap-3 text-gray-900 dark:text-white">
                            <span class="p-2 bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 rounded-lg"><i data-lucide="flag" class="w-6 h-6"></i></span>
                            Misi
                        </h4>
                        
                        @php
                            $misiText = get_setting('cabinetMission', "1. Meningkatkan kualitas akademik.\n2. Menjalin relasi yang kuat.\n3. Mewadahi minat dan bakat.");
                            $misiArray = array_filter(explode("\n", $misiText));
                        @endphp
                        
                        <ul class="space-y-4">
                            @foreach($misiArray as $misi)
                                <li class="flex gap-4">
                                    <div class="flex-shrink-0 mt-1 w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed">{{ trim($misi) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-gray-200 dark:border-slate-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-12 mb-12">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        @if(get_setting('cabinetLogo'))
                            <img src="{{ Storage::url(get_setting('cabinetLogo')) }}" alt="Logo" class="h-8 w-auto">
                        @else
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">F</div>
                        @endif
                        <span class="font-bold text-lg">FORMAT-R</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Kabinet {{ get_setting('cabinetName', 'Kolaborasi Asa') }}<br>
                        Bergerak bersama menebar kebermanfaatan.
                    </p>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-6 text-gray-900 dark:text-white">Tautan Cepat</h5>
                    <ul class="space-y-3">
                        <li><a href="#tentang" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 text-sm">Tentang Kami</a></li>
                        <li><a href="#visimisi" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 text-sm">Visi & Misi</a></li>
                        <li><a href="{{ route('departemen.index') }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 text-sm">Departemen</a></li>
                    </ul>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-6 text-gray-900 dark:text-white">Hubungi Kami</h5>
                    <ul class="space-y-3">
                        @if(get_setting('contactEmail'))
                        <li>
                            <a href="mailto:{{ get_setting('contactEmail') }}" class="flex items-center gap-3 text-gray-500 hover:text-blue-600 dark:text-gray-400 text-sm">
                                <i data-lucide="mail" class="w-4 h-4"></i> {{ get_setting('contactEmail') }}
                            </a>
                        </li>
                        @endif
                        @if(get_setting('instagram'))
                        <li>
                            <a href="{{ get_setting('instagram') }}" target="_blank" class="flex items-center gap-3 text-gray-500 hover:text-pink-600 dark:text-gray-400 text-sm">
                                <i data-lucide="instagram" class="w-4 h-4"></i> Instagram
                            </a>
                        </li>
                        @endif
                        @if(get_setting('contactPhone'))
                        <li>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', get_setting('contactPhone')) }}" target="_blank" class="flex items-center gap-3 text-gray-500 hover:text-green-600 dark:text-gray-400 text-sm">
                                <i data-lucide="phone" class="w-4 h-4"></i> WhatsApp
                            </a>
                        </li>
                        @endif
                        @if(get_setting('youtube'))
                        <li>
                            <a href="{{ get_setting('youtube') }}" target="_blank" class="flex items-center gap-3 text-gray-500 hover:text-red-600 dark:text-gray-400 text-sm">
                                <i data-lucide="youtube" class="w-4 h-4"></i> YouTube
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} FORMAT-R UNESA. All rights reserved.</p>
                <p class="text-sm text-gray-400">Created with <i data-lucide="heart" class="w-3 h-3 inline text-red-500 fill-red-500"></i></p>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
