<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SI-LOKO') - BPK Provinsi Jambi</title>
    
    <!-- Import Font Geist & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/geist@1.0.3/dist/fonts/geist-sans/style.css" rel="stylesheet">

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
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-hidden flex h-screen" x-data="{ sidebarOpen: false }">

    <!-- ================= SIDEBAR (GRADIENT EMERALD TEGAS) ================= -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-[280px] bg-gradient-to-b from-sidebar-top to-sidebar-bottom transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-xl">
        
        <!-- Header Sidebar: Logo & Title -->
        <div class="px-6 py-8 flex items-center gap-4 border-b border-white/5">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo SI-LOKO" class="w-14 h-14 object-contain">
            <div class="flex flex-col">
                <span class="font-geist font-bold text-[19px] text-white tracking-wide leading-tight">SI-LOKO</span>
                <span class="font-inter text-[9.5px] font-medium text-white/70 leading-tight mt-0.5">Sistem Informasi Log<br>Operasional & Kegiatan Organisasi</span>
            </div>
        </div>

        <!-- Menu Navigasi Utama -->
        <nav class="flex-1 overflow-y-auto px-4 mt-6">
            <!-- Label Navigasi Dipertebal -->
            <p class="px-3 text-[11px] font-bold font-geist text-white/50 uppercase tracking-widest mb-3">Navigasi Utama</p>
            
            <ul class="space-y-2">
                <li>
                    <a href="/home" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->is('home') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.index') }}" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->routeIs('siswa.*') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Data Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.index') }}" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->routeIs('mahasiswa.*') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Data Mahasiswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan.index') }}" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->routeIs('laporan.*') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Bottom Area: Pengaturan (Dipisah Sempurna di Bawah) -->
        <div class="px-4 py-6 mt-auto border-t border-white/10">
            <p class="px-3 text-[11px] font-bold font-geist text-white/50 uppercase tracking-widest mb-3">Sistem</p>
            <a href="{{ route('pengaturan.index') }}" class="flex items-center px-4 py-3.5 rounded-xl text-sm font-semibold font-geist transition-all duration-300 {{ request()->routeIs('pengaturan.*') ? 'bg-gradient-to-r from-teal-500/90 to-teal-400/80 backdrop-blur-md text-white shadow-lg border border-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pengaturan
            </a>
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
                        @if(Auth::check() && Auth::user()->foto_admin)
                            <img src="{{ asset('storage/' . Auth::user()->foto_admin) }}" alt="Profil" class="w-full h-full object-cover">
                        @else
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        @endif
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-level-2 border border-slate-200 py-2 z-50" style="display: none;">
                    <a href="{{ route('pengaturan.index') }}" class="block px-4 py-2 text-sm font-medium font-geist text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">Pengaturan Akun</a>
                    <div class="border-t border-slate-100 my-1"></div>
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
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-[1440px] mx-auto">
                @yield('content')
            </div>
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
                cancelButtonText: 'Batal'
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
    </script>
</body>
</html>
