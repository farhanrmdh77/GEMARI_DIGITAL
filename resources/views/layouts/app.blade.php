<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GEMARI DIGITAL') - BPK Provinsi Jambi</title>
    
    <!-- Import Font Geist & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/geist@1.0.3/dist/fonts/geist-sans/style.css" rel="stylesheet">
    
    <!-- Tom Select (Searchable Dropdown) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        geist: ['Geist', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0d9488', // Teal 600
                        'primary-hover': '#0f766e', // Teal 700
                        'sidebar-top': '#0f5132', // Hijau Emerald Terang
                        'sidebar-bottom': '#022c22', // Hijau Emerald Sangat Gelap (Hampir Hitam)
                    },
                    boxShadow: {
                        'level-1': '0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05)',
                        'level-2': '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js untuk fungsionalitas Dropdown & Mobile Menu -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html.swal2-shown, body.swal2-shown {
            height: 100% !important;
            overflow: hidden !important;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-hidden flex h-screen" x-data="{ sidebarOpen: false }">

    <!-- ================= SIDEBAR (GRADIENT EMERALD TEGAS) ================= -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-[280px] bg-gradient-to-b from-sidebar-top to-sidebar-bottom transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-xl h-screen max-h-screen shrink-0">
        
        <!-- Header Sidebar: Logo & Title -->
        <div class="px-6 py-8 flex items-center gap-4 border-b border-white/5 shrink-0">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo GEMARI DIGITAL" class="w-14 h-14 object-contain">
            <div class="flex flex-col">
                <span class="font-geist font-bold text-[19px] text-white tracking-wide leading-tight">GEMARI DIGITAL</span>
                <span class="font-inter text-[9.5px] font-medium text-white/70 leading-tight mt-0.5">Manajemen PKL & Magang<br>Digital BPK Jambi</span>
            </div>
        </div>

        <!-- Menu Navigasi Utama -->
        <nav class="flex-1 overflow-y-auto px-4 mt-6 min-h-0">
            <!-- Label Navigasi Dipertebal -->
            <p class="px-3 text-[11px] font-bold font-geist text-white/50 uppercase tracking-widest mb-3">Navigasi Utama</p>
            
            <ul class="space-y-2">
                <li>
                    <a href="/home" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->is('home') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        @if(Auth::check() && in_array(Auth::user()->role, ['Siswa', 'Mahasiswa']))
                            Portal Magang
                        @else
                            Dashboard
                        @endif
                    </a>
                </li>
                @if(Auth::check() && in_array(Auth::user()->role, ['Superadmin', 'Admin Humas', 'Siswa']))
                <li>
                    <a href="{{ route('siswa.index') }}" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->routeIs('siswa.*') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Data Siswa
                    </a>
                </li>
                @endif
                
                @if(Auth::check() && in_array(Auth::user()->role, ['Superadmin', 'Admin Humas', 'Mahasiswa']))
                <li>
                    <a href="{{ route('mahasiswa.index') }}" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->routeIs('mahasiswa.*') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Data Mahasiswa
                    </a>
                </li>
                @endif
                
                @if(Auth::check() && in_array(Auth::user()->role, ['Superadmin', 'Admin Humas']))
                <li>
                    <a href="{{ route('laporan.index') }}" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->routeIs('laporan.*') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan
                    </a>
                </li>
                @endif
                @if(Auth::check() && in_array(Auth::user()->role, ['Superadmin', 'Admin Humas']))
                <li>
                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Manajemen Akun
                    </a>
                </li>
                @endif
            </ul>
        </nav>

        <!-- Bottom Area: Pengaturan & Watermark Developer -->
        <div class="px-4 py-4 mt-auto border-t border-white/10 shrink-0">
            @if(Auth::check() && in_array(Auth::user()->role, ['Superadmin', 'Admin Humas']))
            <p class="px-3 text-[11px] font-bold font-geist text-white/50 uppercase tracking-widest mb-2">Sistem</p>
            <a href="{{ route('pengaturan.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->routeIs('pengaturan.*') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pengaturan
            </a>
            @endif
            <div class="mt-3 px-3 flex items-center justify-between text-[10px] text-white/30 font-inter">
                <span>GEMARI DIGITAL v2.0</span>
                <button type="button" onclick="showDevInfo()" class="hover:text-white/80 transition-colors cursor-pointer focus:outline-none" title="Informasi Pembuat Aplikasi">Dev by Farhan</button>
            </div>
        </div>
    </aside>

    <!-- Overlay Mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" style="display: none;"></div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">
        
        <!-- Top Header -->
    <header class="h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 bg-white/70 backdrop-blur-md border-b border-white/20 z-30 shadow-sm sticky top-0">
        <div class="flex items-center">
            <!-- Hamburger Menu Mobile -->
            <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-800 focus:outline-none lg:hidden mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            
            <!-- Page Title -->
            <h1 class="text-lg font-semibold font-geist text-slate-800 hidden sm:block">@yield('title', 'Dashboard')</h1>
        </div>

        <!-- Profile Info & Dropdown -->
        <div class="flex items-center gap-4">
            <div class="text-sm font-medium font-geist text-slate-600 hidden sm:block">
                {{ Auth::user()->name ?? 'Administrator' }}
            </div>
            
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center focus:outline-none">
                    <!-- Avatar Profil dengan Pengecekan Foto -->
                    <div class="w-9 h-9 rounded-full overflow-hidden border border-slate-200 bg-teal-50 flex items-center justify-center text-primary font-bold font-geist shadow-sm uppercase shrink-0">
                        @if(Auth::check() && Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profil" class="w-full h-full object-cover">
                        @else
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        @endif
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-level-2 border border-slate-200 py-2 z-50" style="display: none;">
                    @if(Auth::check() && in_array(Auth::user()->role, ['Superadmin', 'Admin Humas']))
                    <a href="{{ route('pengaturan.index') }}" class="block px-4 py-2 text-sm font-medium font-geist text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">Pengaturan Akun</a>
                    <div class="border-t border-slate-100 my-1"></div>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="w-full text-left px-4 py-2 text-sm font-medium font-geist text-slate-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                            Keluar Aplikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Global Toast Notification -->
    @include('components.toast')

        <!-- Main Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 flex flex-col justify-between">
            <div class="max-w-[1440px] mx-auto w-full mb-8">
                @yield('content')
            </div>

            <!-- Footer Aplikasi Tersembunyi (Discreet Footer) -->
            <footer class="mt-auto pt-6 border-t border-slate-200/80 max-w-[1440px] mx-auto w-full flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400 font-inter">
                <div class="flex items-center gap-2">
                    <span>&copy; {{ date('Y') }} <span class="font-semibold text-slate-600">GEMARI DIGITAL</span></span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-slate-500">BPK Perwakilan Provinsi Jambi</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <!-- Subtle Developer Badge / Modal Trigger -->
                    <button type="button" onclick="showDevInfo()" class="group inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 hover:bg-teal-50 border border-slate-200 hover:border-teal-200 text-slate-400 hover:text-teal-700 transition-all duration-200 cursor-pointer focus:outline-none" title="Klik untuk informasi pembuat aplikasi">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300 group-hover:bg-teal-500 transition-colors"></span>
                        <span class="text-[11px] font-medium tracking-tight">Developed by <span class="font-semibold text-slate-600 group-hover:text-teal-700 transition-colors">Farhan</span></span>
                    </button>
                </div>
            </footer>
        </main>
    </div>

    <!-- Script Global SweetAlert2 -->
    <script>
        // Konfirmasi Keluar (Logout)
        function confirmLogout() {
            Swal.fire({
                title: 'Keluar Aplikasi?',
                text: "Anda harus login kembali untuk mengakses sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d9488',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                heightAuto: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }

        // Konfirmasi Hapus Data (Global)
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.form-delete');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const nama = this.getAttribute('data-nama') || 'data ini';
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: `Apakah Anda yakin ingin menghapus ${nama}? Data yang dihapus tidak dapat dikembalikan.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        heightAuto: false,
                        customClass: {
                            confirmButton: 'font-geist',
                            cancelButton: 'font-geist',
                            title: 'font-geist font-bold',
                            popup: 'rounded-2xl shadow-xl border border-slate-100'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    })
                });
            });
        });

        // Inisialisasi TomSelect untuk elemen select dengan class .searchable-select
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.searchable-select').forEach(function(el) {
                new TomSelect(el, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            });
        });

        // Modal Informasi Pengembang (Developer Credits)
        function showDevInfo() {
            Swal.fire({
                title: '<div class="flex items-center justify-center gap-2 text-slate-800 font-geist font-bold text-lg"><span>Informasi Pengembang</span></div>',
                html: `
                    <div class="text-left font-inter text-sm pt-2">
                        <!-- Developer Card -->
                        <div class="p-4 bg-gradient-to-r from-teal-50 to-emerald-50 border border-teal-100 rounded-2xl flex items-center gap-3.5 mb-4 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-teal-600 to-emerald-500 text-white flex items-center justify-center font-geist font-bold text-xl shadow-md shrink-0">
                                F
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 font-geist text-base leading-tight">Farhan</h4>
                                <p class="text-xs text-teal-700 font-medium mt-0.5">Developer & System Creator</p>
                            </div>
                        </div>

                        <!-- Info Meta -->
                        <div class="space-y-2 text-xs text-slate-600 bg-slate-50 p-3.5 rounded-xl border border-slate-100 mb-3">
                            <div class="flex justify-between items-center py-1 border-b border-slate-200/50">
                                <span class="text-slate-400">Aplikasi</span>
                                <span class="font-bold text-slate-700">GEMARI DIGITAL v2.0</span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-slate-200/50">
                                <span class="text-slate-400">Instansi</span>
                                <span class="font-semibold text-slate-700">BPK Perwakilan Provinsi Jambi</span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-slate-200/50">
                                <span class="text-slate-400">Unit / Penempatan</span>
                                <span class="font-semibold text-slate-700">Subbagian SDM</span>
                            </div>
                            <div class="flex justify-between items-center py-1">
                                <span class="text-slate-400">Tahun</span>
                                <span class="font-semibold text-slate-700">2026</span>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="text-center p-3 rounded-lg bg-teal-500/5 border border-teal-500/10">
                            <p class="text-[11.5px] text-slate-500 leading-relaxed italic">
                                "Sistem ini dirancang dan dikembangkan untuk mempermudah instansi dalam mengelola administrasi, verifikasi berkas, dan operasional peserta magang & PKL secara digital terpadu."
                            </p>
                        </div>
                    </div>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#0d9488',
                heightAuto: false,
                scrollbarPadding: false,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-100',
                    confirmButton: 'px-6 py-2 rounded-xl text-sm font-semibold font-geist'
                }
            });
        }
        console.log("%c GEMARI DIGITAL %c Developed by Farhan - BPK Perwakilan Prov. Jambi ", "background:#0d9488;color:#fff;padding:3px 6px;border-radius:4px 0 0 4px;font-weight:bold;", "background:#0f172a;color:#94a3b8;padding:3px 6px;border-radius:0 4px 4px 0;font-weight:medium;");
    </script>

    @stack('scripts')
</body>
</html>


