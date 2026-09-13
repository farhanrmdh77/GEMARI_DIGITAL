<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata - {{ $data->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 15mm; }
        body { background-color: #f1f5f9; font-family: 'Times New Roman', Times, serif; }
        .page-container { width: 210mm; min-height: 297mm; background-color: white; margin: 0 auto; padding: 20mm; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        @media print { body { background-color: white; } .page-container { box-shadow: none; margin: 0; padding: 0; width: 100%; } .no-print { display: none !important; } }
        table tr td { padding: 8px 4px; vertical-align: top; }
        .kolom-label { width: 35%; font-weight: bold; }
        .kolom-titik { width: 3%; text-align: center; }
    </style>
</head>
<body class="py-10">

    <div class="fixed top-8 right-8 no-print z-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-lg font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Biodata
        </button>
    </div>

    <div class="page-container relative">
        <!-- KOP Surat -->
        <div class="flex items-center border-b-[3px] border-black pb-4 mb-8">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo BPK" class="h-[25mm] mr-6">
            <div class="text-center flex-1 pr-[25mm]">
                <h2 class="text-[18px] font-bold tracking-widest uppercase">Badan Pemeriksa Keuangan</h2>
                <h3 class="text-[16px] font-bold tracking-widest uppercase mb-1">Perwakilan Provinsi Jambi</h3>
                <p class="text-[12px]">Jl. Pangeran Hidayat Km. 6,5 No.65, Kelurahan Sukakarya, Kecamatan Kotabaru, Kota Jambi, Jambi 36129</p>
            </div>
        </div>

        <h1 class="text-center text-[18px] font-bold uppercase underline mb-8">Formulir Biodata Peserta Magang</h1>

        <div class="flex justify-between items-start gap-8">
            <!-- Tabel Data -->
            <div class="flex-1 text-[14px]">
                <table class="w-full">
                    <tr><td class="kolom-label">Nama Lengkap</td><td class="kolom-titik">:</td><td>{{ $data->nama }}</td></tr>
                    <tr><td class="kolom-label">{{ $data instanceof \App\Mahasiswa ? 'NIM' : 'NIS' }}</td><td class="kolom-titik">:</td><td>{{ $data->nis ?? $data->nim ?? '-' }}</td></tr>
                    <tr><td class="kolom-label">Tempat, Tanggal Lahir</td><td class="kolom-titik">:</td><td>{{ $data->tempat_lahir ?? '-' }}, {{ $data->tanggal_lahir ? \Carbon\Carbon::parse($data->tanggal_lahir)->locale('id')->translatedFormat('d F Y') : '-' }}</td></tr>
                    <tr><td class="kolom-label">Jenis Kelamin</td><td class="kolom-titik">:</td><td>{{ $data->jenis_kelamin == 'L' ? 'Laki-laki' : ($data->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td></tr>

                    <tr><td class="kolom-label">Alamat Lengkap</td><td class="kolom-titik">:</td><td class="leading-relaxed">{{ $data->alamat ?? '-' }}</td></tr>
                    <tr><td class="kolom-label">Nomor HP / WhatsApp</td><td class="kolom-titik">:</td><td>{{ $data->no_hp ?? '-' }}</td></tr>
                    <tr><td class="kolom-label">Email</td><td class="kolom-titik">:</td><td>{{ $data->email ?? '-' }}</td></tr>
                    
                    <tr><td colspan="3"><div class="h-4"></div></td></tr>
                    
                    <tr><td class="kolom-label">Asal Instansi</td><td class="kolom-titik">:</td><td class="font-bold">{{ $data->asal_sekolah ?? $data->asal_kampus ?? '-' }}</td></tr>
                    <tr><td class="kolom-label">Jurusan / Program Studi</td><td class="kolom-titik">:</td><td>{{ $data->jurusan ?? $data->prodi ?? '-' }}</td></tr>
                    <tr><td class="kolom-label">Unit Penempatan (Divisi)</td><td class="kolom-titik">:</td><td class="font-bold">{{ $data->unit_penempatan ?: '-' }}</td></tr>
                    <tr><td class="kolom-label">Periode Pelaksanaan</td><td class="kolom-titik">:</td>
                        <td>
                            {{ $data->tgl_mulai ? \Carbon\Carbon::parse($data->tgl_mulai)->locale('id')->translatedFormat('d F Y') : '-' }} 
                            s.d 
                            {{ $data->tgl_selesai ? \Carbon\Carbon::parse($data->tgl_selesai)->locale('id')->translatedFormat('d F Y') : '-' }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Pas Foto Area -->
            <div class="w-[30mm] h-[40mm] border-2 border-slate-300 flex items-center justify-center text-[10px] text-slate-400 bg-slate-50 overflow-hidden shrink-0">
                @if($data->pas_foto)
                    <img src="{{ asset('storage/' . $data->pas_foto) }}" alt="Foto" class="w-full h-full object-cover">
                @else
                    3 x 4
                @endif
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="mt-[20mm] flex justify-end text-[14px]">
            <div class="text-center">
                <p class="mb-20">Jambi, {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y') }}<br>Peserta Magang,</p>
                <p class="font-bold underline">{{ $data->nama }}</p>
                <p>{{ $data instanceof \App\Mahasiswa ? 'NIM.' : 'NIS.' }} {{ $data->nis ?? $data->nim ?? '-' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
