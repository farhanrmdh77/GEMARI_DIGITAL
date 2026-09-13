<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SI-LOKO</title>
    
    <!-- Import Font Geist & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex antialiased text-slate-800 bg-white overflow-hidden">

    <!-- BAGIAN KIRI: Area Form -->
    <div class="w-full lg:w-1/2 flex flex-col px-8 py-10 sm:px-16 md:px-24 overflow-y-auto">
        
        <!-- Logo & Header Instansi -->
        <div class="flex items-center gap-3 mb-12 sm:mb-16">
            <div class="w-10 h-10 bg-teal-50 border border-teal-100 rounded-lg flex items-center justify-center text-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <h1 class="font-geist font-bold text-xl text-slate-900 tracking-tight leading-none">SI-LOKO v2</h1>
                <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider mt-0.5">BPK Perwakilan Prov. Jambi</p>
            </div>
        </div>
        
        <!-- Konten Form (Login/Register) -->
        <div class="w-full max-w-md mx-auto flex-1 flex flex-col justify-center">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="mt-auto pt-8 text-sm text-slate-400 font-medium">
            &copy; {{ date('Y') }} Subbagian Humas BPK RI. Hak Cipta Dilindungi.
        </div>
    </div>

    <!-- BAGIAN KANAN: Gambar Gedung BPK -->
    <div class="hidden lg:flex w-1/2 relative bg-slate-900 items-center justify-center">
        <!-- Overlay Warna Teal Gelap agar foto terlihat estetik dan elegan -->
        <div class="absolute inset-0 bg-primary/20 mix-blend-multiply z-10 pointer-events-none"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent z-10 pointer-events-none"></div>
        
        <!-- 
            PASTIKAN ANDA MELETAKKAN FOTO GEDUNG BPK DI FOLDER:
            public/images/kantor-bpk-jambi.jpg 
        -->
        <img src="{{ asset('images/kantor-bpk-jambi.jpg') }}" alt="Gedung BPK Perwakilan Jambi" class="absolute inset-0 w-full h-full object-cover">
        
        <!-- Teks Hiasan di atas Gambar -->
        <div class="relative z-20 p-16 mt-auto w-full text-white">
            <div class="w-16 h-1 bg-primary mb-6 rounded-full"></div>
            <h2 class="font-geist text-4xl font-semibold leading-tight mb-4 tracking-tight">Digitalisasi Tata Kelola<br>Administrasi Operasional.</h2>
            <p class="font-inter text-slate-300 text-lg leading-relaxed max-w-lg">Sistem terintegrasi untuk mendukung efisiensi pendataan dan pemantauan aktivitas kegiatan di lingkungan instansi.</p>
        </div>
    </div>

</body>
</html>
