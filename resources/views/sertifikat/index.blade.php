<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - {{ $data->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            background-color: #f3f4f6;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        @media print {
            @page { size: A4 landscape; margin: 0 !important; }
            
            body { 
                background-color: white; 
                margin: 0 !important; 
                padding: 0 !important;
            }
            
            .no-print { display: none !important; }
            
            .cert-container { 
                box-shadow: none !important; 
                width: 297mm !important; 
                height: 210mm !important; 
                margin: 0 !important;
            }
        }
        
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Poppins', sans-serif; }

        .cert-container {
            width: 297mm;
            height: 210mm;
            background-color: white;
            background-image: url("{{ asset('assets/img/bg-sertifikat.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

    @php
        $pengaturan = \Illuminate\Support\Facades\DB::table('settings')->first();
        
        $tanggal_masuk = $data->tgl_mulai ? \Carbon\Carbon::parse($data->tgl_mulai)->locale('id')->isoFormat('D MMMM Y') : null;
        $tanggal_keluar = $data->tgl_selesai ? \Carbon\Carbon::parse($data->tgl_selesai)->locale('id')->isoFormat('D MMMM Y') : \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y');
    @endphp

    <!-- Tombol Cetak -->
    <div class="fixed bottom-8 right-8 no-print z-50">
        <button onclick="window.print()" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-full shadow-xl font-bold flex items-center gap-2 transition-transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Sertifikat
        </button>
    </div>

    <!-- Kanvas Sertifikat -->
    <!-- PERBAIKAN: pt (padding-top) dikurangi sedikit dari 35mm ke 28mm untuk menarik konten atas naik, memberi spasi lega di tengah -->
    <div class="cert-container relative overflow-hidden flex flex-col items-center pt-[28mm] pb-[35mm] px-[30mm] box-border">
        
        <!-- Header -->
        <div class="text-center flex flex-col items-center w-full">
            <!-- PERBAIKAN: Logo diperbesar dari h-16 menjadi h-20 (lebih besar sekitar 25%) -->
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo BPK RI" class="h-20 w-auto mb-2 object-contain drop-shadow-sm">
            <h2 class="font-sans font-bold text-gray-800 text-lg tracking-[0.2em] uppercase mb-0.5">Badan Pemeriksa Keuangan</h2>
            <h3 class="font-sans font-medium text-gray-500 text-xs tracking-widest uppercase mb-5">Perwakilan Provinsi Jambi</h3>
        </div>

        <!-- Body Teks -->
        <div class="text-center w-full flex flex-col items-center">
            <h1 class="font-serif font-bold text-[3.5rem] leading-none text-[#d48c29] tracking-[0.05em] uppercase mb-1 drop-shadow-sm">Sertifikat</h1>
            <p class="font-sans text-gray-400 tracking-[0.3em] text-xs uppercase mb-5 font-semibold">Praktek Kerja Lapangan</p>

            <p class="font-sans text-gray-600 text-sm mb-2 italic">Diberikan dengan penuh penghargaan kepada:</p>
            
            <h2 class="font-serif font-bold text-4xl text-gray-900 border-b-2 border-gray-300 inline-block pb-1.5 px-10 mb-4">
                {{ $data->nama }}
            </h2>
            
            <!-- PERBAIKAN: Menambahkan mb-8 (margin-bottom) agar paragraf ini dengan sendirinya memberikan jarak/spasi kosong ke area tanggal di bawahnya -->
            @if(!empty($data->tgl_mulai) && !empty($data->tgl_selesai))
                <p class="font-sans text-gray-700 text-sm leading-relaxed max-w-2xl px-4 mb-8">
                    Atas partisipasi dan dedikasinya dalam menyelesaikan program <br>
                    <strong class="font-semibold text-gray-900 text-base tracking-wide">Praktek Kerja Lapangan (PKL) / Magang Akademik</strong> <br>
                    di lingkungan BPK Perwakilan Provinsi Jambi terhitung sejak tanggal <br>
                    <span class="font-semibold text-[#d48c29]">{{ $tanggal_masuk }}</span> sampai dengan <span class="font-semibold text-[#d48c29]">{{ $tanggal_keluar }}</span>.
                </p>
            @else
                <p class="font-sans text-gray-700 text-sm leading-relaxed max-w-2xl px-4 mb-8">
                    Atas partisipasi dan dedikasinya dalam menyelesaikan program <br>
                    <strong class="font-semibold text-gray-900 text-base tracking-wide">Praktek Kerja Lapangan (PKL) / Magang Akademik</strong> <br>
                    di lingkungan BPK Perwakilan Provinsi Jambi dengan baik dan penuh tanggung jawab.
                </p>
            @endif
        </div>

        <!-- Area Tanda Tangan -->
        <div class="mt-auto w-full flex justify-center">
            <div class="text-center w-full">
                <p class="font-sans text-gray-700 text-sm mb-1">Jambi, {{ $tanggal_keluar }}</p>
                
                <p class="font-sans text-gray-800 text-sm font-semibold">
                    {{ trim($pengaturan->jabatan_pejabat_sertifikat ?? '') }}
                </p>
                
                <div class="h-[60px]"></div>
                
                <p class="font-sans font-bold text-gray-900 inline-block underline underline-offset-4 decoration-1 text-base">
                    {{ trim($pengaturan->nama_pejabat_sertifikat ?? '') }}
                </p>
                
                <p class="font-sans text-gray-900 text-sm mt-0.5 font-semibold">
                    {{ !empty($pengaturan->nip_pejabat_sertifikat) ? 'NIP. ' . trim($pengaturan->nip_pejabat_sertifikat) : '' }}
                </p>
            </div>
        </div>

    </div>

</body>
</html>
