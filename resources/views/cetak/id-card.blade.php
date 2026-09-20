<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $data->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Montserrat:wght@700&display=swap');
        
        @page { size: A4 portrait; margin: 0; }
        
        body {
            margin: 0; padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            background-color: #f1f5f9;
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh; font-family: 'Inter', sans-serif;
        }

        /* Ukuran standar ATM/KTP/SIM (54mm x 85.6mm) */
        .id-card-container {
            width: 54mm; height: 85.6mm;
            background-color: white;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            position: relative; overflow: hidden;
            border-radius: 8px; border: 1px solid #e2e8f0;
        }

        @media print {
            body { background-color: white; }
            .no-print { display: none !important; }
            .id-card-container { box-shadow: none; border: 1px dashed #cbd5e1; }
        }
    </style>
</head>
<body>

    <div class="fixed top-8 right-8 no-print z-50">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg shadow-lg font-bold flex items-center gap-2 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak ID Card
        </button>
    </div>

    <!-- Kanvas ID Card -->
    <div class="id-card-container flex flex-col items-center">
        
        <!-- Bagian Atas (Header Biru BPK) -->
        <div class="w-full bg-[#034c7a] h-[30mm] flex flex-col items-center pt-[3mm] relative rounded-b-[16px] shadow-sm z-10">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo BPK" class="h-[14mm] max-w-[45mm] object-contain drop-shadow-md mb-1">
            <!-- Teks BPK RI diubah menjadi BPK PERWAKILAN PROVINSI JAMBI -->
            <h2 class="text-white text-[7px] font-bold tracking-wider text-center leading-tight">BPK PERWAKILAN<br>PROVINSI JAMBI</h2>
        </div>

        <!-- Foto Profil -->
        <!-- FOTO DITAIKKAN: Margin negatif disesuaikan proporsional -->
        <div class="w-[22mm] h-[28mm] bg-slate-100 rounded-md border-[2px] border-white shadow-md relative -mt-[5mm] z-20 overflow-hidden flex items-center justify-center">
            @if($data->pas_foto)
                <img src="{{ asset('storage/' . $data->pas_foto) }}" alt="Foto" class="w-full h-full object-cover">
            @else
                <svg class="w-8 h-8 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            @endif
        </div>

        <!-- Detail Peserta -->
        <div class="w-full px-2 mt-2 text-center flex-1">
            <p class="text-[9.5px] font-bold text-amber-600 tracking-widest uppercase mb-0.5 font-['Montserrat']">PESERTA MAGANG</p>
            
            <h1 class="text-[11px] font-bold text-slate-800 leading-tight mb-0.5">{{ $data->nama }}</h1>
            <p class="text-[8px] text-slate-500 font-semibold mb-1.5">{{ $data instanceof \App\Mahasiswa ? 'NIM. ' : 'NIS. ' }}{{ $data->nis ?? $data->nim ?? '-' }}</p>

            <div class="w-6 h-[1.5px] bg-amber-500 mx-auto mb-1.5"></div>

            <p class="text-[7.5px] font-bold text-slate-700 leading-tight uppercase">{{ $data->asal_sekolah ?? $data->asal_kampus }}</p>
            <p class="text-[6.5px] text-slate-500 mt-0.5">{{ $data->jurusan ?? $data->prodi }}</p>
        </div>

        <!-- Bagian Bawah -->
        <div class="w-full bg-[#034c7a] h-[6mm] flex items-center justify-center mt-auto text-white shadow-[0_-2px_4px_rgba(0,0,0,0.1)]">
            <p class="text-[6.5px] font-bold tracking-widest">{{ \Carbon\Carbon::parse($data->tgl_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($data->tgl_selesai)->format('d M Y') }}</p>
        </div>
    </div>
</body>
</html>
