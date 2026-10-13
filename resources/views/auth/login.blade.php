<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - GEMARI DIGITAL BPK Prov. Jambi</title>
    
    <!-- Import Font Geist & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/geist@1.0.3/dist/fonts/geist-sans/style.css" rel="stylesheet">
    <!-- Load FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tailwind CSS -->
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
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased bg-white text-slate-800 overflow-hidden">

    <div class="flex min-h-screen h-screen">
        
        <!-- ================= BAGIAN KIRI (BANNER BRANDING) ================= -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-[#0f5132] to-[#022c22] flex-col items-center justify-center p-12 text-center overflow-hidden">
            <!-- Aksen Pola Dekoratif / Gedung BPK -->
            <img src="{{ asset('images/gedung-bpk.jpg') }}" alt="Gedung BPK" class="absolute inset-0 w-full h-full object-cover opacity-10 mix-blend-overlay">
            
            <div class="relative z-10 flex flex-col items-center">
                
                <!-- Logo with Glow & Float Animation -->
                <div class="relative flex justify-center items-center mb-8 animate-float">
                    <!-- Glow Effect -->
                    <div class="absolute w-28 h-28 bg-teal-400/60 rounded-full blur-2xl animate-pulse"></div>
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo BPK" class="w-32 h-32 object-contain relative z-10 drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]">
                </div>

                <h3 class="text-white/70 font-inter text-xs sm:text-sm font-semibold uppercase tracking-[0.3em] mb-4">
                    BPK Perwakilan Provinsi Jambi
                </h3>
                
                <!-- Gradient Text GEMARI DIGITAL -->
                <h1 class="text-5xl sm:text-7xl font-black font-geist tracking-wide mb-6 text-transparent bg-clip-text bg-gradient-to-b from-white to-teal-400 drop-shadow-sm pb-1">
                    GEMARI DIGITAL
                </h1>
                
                <p class="text-white/80 font-inter text-base sm:text-lg max-w-md mx-auto leading-relaxed">
                    Manajemen PKL & Magang Digital
                </p>
            </div>
        </div>

        <!-- ================= BAGIAN KANAN (FORM LOGIN) ================= -->
        <div class="w-full lg:w-1/2 flex flex-col relative overflow-y-auto bg-gradient-to-br from-[#0f5132] to-[#022c22] lg:bg-none lg:bg-white">
            
            <!-- Aksen Pola Dekoratif Khusus Mobile -->
            <img src="{{ asset('images/gedung-bpk.jpg') }}" alt="Gedung BPK" class="lg:hidden absolute inset-0 w-full h-full object-cover opacity-10 mix-blend-overlay">
            
            <div class="flex-1 flex flex-col justify-center px-4 sm:px-8 md:px-16 lg:px-24 py-8 lg:py-12 max-w-xl mx-auto w-full relative z-10">
                
                <!-- Tampilan Logo & Judul Khusus Mobile -->
                <div class="flex lg:hidden flex-col items-center justify-center mb-6 sm:mb-8 text-center mt-4">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo BPK" class="w-20 h-20 sm:w-24 sm:h-24 object-contain mb-3 sm:mb-4 drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]">
                    <h1 class="text-2xl sm:text-3xl font-black font-geist text-white tracking-wide drop-shadow-sm">GEMARI DIGITAL</h1>
                    <p class="text-[10px] sm:text-[11px] font-inter text-white/70 uppercase tracking-widest mt-1">BPK Perwakilan Prov. Jambi</p>
                </div>

                <!-- CARD FORM -->
                <div class="bg-white rounded-[1.5rem] shadow-2xl p-6 sm:p-10 lg:p-0 lg:shadow-none lg:bg-transparent">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold font-geist text-slate-900 mb-2">Selamat Datang!</h2>
                    <p class="text-sm sm:text-base text-slate-500 font-inter mb-8 sm:mb-10">Silakan masuk menggunakan kredensial Anda.</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-[11px] font-bold font-inter text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full bg-[#f8fafc] border border-transparent focus:border-primary/20 rounded-lg pl-11 pr-4 py-3.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" placeholder="contoh@email.com">
                        </div>
                        @error('email')
                            <span class="text-xs text-red-500 mt-1.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kata Sandi -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-[11px] font-bold font-inter text-slate-500 uppercase tracking-wider">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-primary hover:text-primary-hover transition-colors font-inter">Lupa Sandi?</a>
                            @endif
                        </div>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" class="w-full bg-[#f8fafc] border border-transparent focus:border-primary/20 rounded-lg pl-11 pr-11 py-3.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors focus:outline-none">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-xs text-red-500 mt-1.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Ingat Saya -->
                    <div class="flex items-center pt-2">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-primary bg-slate-200 border-none rounded focus:ring-primary focus:ring-2 cursor-pointer">
                        <label for="remember_me" class="ml-3 text-sm font-semibold text-slate-600 font-inter cursor-pointer">Biarkan saya tetap masuk</label>
                    </div>

                    <!-- Tombol Masuk -->
                    <div class="pt-4">
                        <button type="submit" class="w-full flex items-center justify-center gap-3 bg-primary hover:bg-primary-hover text-white font-bold font-geist rounded-lg px-4 py-3.5 text-sm transition-all shadow-md hover:shadow-lg focus:ring-2 focus:ring-primary/40 focus:ring-offset-2">
                            Masuk ke Sistem <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        </button>
                    </div>
                </form>
                </div> <!-- End of Card Form -->
            </div>
            
            <!-- Footer Form Area -->
            <div class="w-full text-[11px] text-white/50 lg:text-slate-400 text-center font-inter py-6 relative z-10 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2">
                <span>&copy; {{ date('Y') }} BPK Perwakilan Provinsi Jambi.</span>
                <span class="hidden sm:inline opacity-40">&bull;</span>
                <button type="button" onclick="showDevInfo()" class="hover:underline hover:text-white lg:hover:text-teal-600 transition-colors cursor-pointer opacity-70 hover:opacity-100 focus:outline-none inline-flex items-center gap-1" title="Informasi Pembuat Aplikasi">
                    Developed by <strong>Farhan</strong>
                </button>
            </div>
        </div>

    </div>
    
    <!-- Global Toast Notification -->
    @include('components.toast')

    <!-- SweetAlert2 & Script Developer Info -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
</body>
</html>


