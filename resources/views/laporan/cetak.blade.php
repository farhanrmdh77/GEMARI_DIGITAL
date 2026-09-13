<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Magang - BPK Jambi</title>
    <!-- Tailwind CSS untuk styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gaya khusus saat dokumen dicetak ke PDF */
        @media print {
            @page { size: A4 landscape; margin: 15mm; }
            body { -webkit-print-color-adjust: exact; background-color: white !important; }
            .no-print { display: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body class="bg-white text-slate-900 font-sans antialiased p-8 md:p-12 max-w-[1200px] mx-auto">

    <!-- Tombol Bantuan (Tidak akan ikut tercetak) -->
    <div class="no-print flex justify-end mb-8">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    <!-- Kop Surat Resmi -->
    <div class="flex items-center border-b-[4px] border-slate-900 pb-5 mb-6">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo BPK" class="h-[25mm] mr-6">
        <div class="text-center flex-1 pr-[25mm]">
            <h1 class="text-2xl font-bold uppercase tracking-widest text-slate-900 mb-1">Badan Pemeriksa Keuangan</h1>
            <h2 class="text-xl font-bold uppercase tracking-wide text-slate-800 mb-1">Perwakilan Provinsi Jambi</h2>
            <p class="text-sm">Jl. Pangeran Hidayat Km. 6,5 No.65, Kelurahan Sukakarya, Kecamatan Kotabaru, Kota Jambi, Jambi 36129</p>
        </div>
    </div>

    <!-- Judul Dokumen -->
    <div class="text-center mb-8">
        <h3 class="text-lg font-bold uppercase underline mb-2">Laporan Rekapitulasi Peserta Magang / PKL</h3>
        @if($tgl_mulai || $tgl_akhir)
            <p class="text-sm text-slate-600 mt-1">
                Periode: {{ $tgl_mulai ? \Carbon\Carbon::parse($tgl_mulai)->format('d/m/Y') : 'Awal' }} s/d {{ $tgl_akhir ? \Carbon\Carbon::parse($tgl_akhir)->format('d/m/Y') : 'Akhir' }}
            </p>
        @endif
    </div>

    <!-- Tabel Data -->
    @if($data->count() > 0)
        <table class="w-full text-left text-sm border-collapse border border-slate-300">
            <thead class="bg-slate-100 text-slate-800 font-bold uppercase text-[11px] tracking-wider">
                <tr>
                    <th class="border border-slate-300 px-4 py-3 w-10 text-center">No</th>
                    <th class="border border-slate-300 px-4 py-3">Nama Lengkap</th>
                    <th class="border border-slate-300 px-4 py-3">NIS/NIM</th>
                    <th class="border border-slate-300 px-4 py-3">{{ $kategori == 'mahasiswa' ? 'Asal Kampus' : ($kategori == 'siswa' ? 'Asal Sekolah' : 'Asal Sekolah / Kampus') }}</th>
                    <th class="border border-slate-300 px-4 py-3">Unit Penempatan</th>
                    <th class="border border-slate-300 px-4 py-3 text-center">Tgl Mulai</th>
                    <th class="border border-slate-300 px-4 py-3 text-center">Tgl Selesai</th>
                </tr>
            </thead>
            <tbody class="text-slate-700">
                @foreach($data as $index => $item)
                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50' }}">
                        <td class="border border-slate-300 px-4 py-2.5 text-center">{{ $index + 1 }}</td>
                        <td class="border border-slate-300 px-4 py-2.5 font-semibold text-slate-900">{{ $item->nama ?: '-' }}</td>
                        <td class="border border-slate-300 px-4 py-2.5">{{ $item->identitas ?: '-' }}</td>
                        <td class="border border-slate-300 px-4 py-2.5">{{ $item->instansi ?: '-' }}</td>
                        <td class="border border-slate-300 px-4 py-2.5">{{ $item->unit_penempatan ?: '-' }}</td>
                        <td class="border border-slate-300 px-4 py-2.5 text-center">{{ $item->tgl_mulai ? \Carbon\Carbon::parse($item->tgl_mulai)->format('d/m/Y') : '-' }}</td>
                        <td class="border border-slate-300 px-4 py-2.5 text-center">{{ $item->tgl_selesai ? \Carbon\Carbon::parse($item->tgl_selesai)->format('d/m/Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Tanda Tangan (Opsional, umum di laporan BPK) -->
        <!-- Tanda Tangan Dinamis -->
        <div class="mt-16 flex justify-end">
            <div class="text-center">
                <p class="text-sm mb-1">Jambi, {{ \Carbon\Carbon::today()->locale('id')->isoFormat('D MMMM Y') }}</p>
                <p class="text-sm font-bold mb-16">{{ $setting->jabatan_pejabat ?? 'Kepala Subbagian Humas' }}</p>
                
                <p class="text-sm font-bold underline uppercase">{{ $setting->nama_pejabat ?? '.......................................' }}</p>
                <p class="text-sm">NIP. {{ $setting->nip_pejabat ?? '.......................................' }}</p>
            </div>
        </div>
    @else
        <div class="text-center py-10 border border-slate-300 bg-slate-50">
            <p class="text-slate-500 italic">Tidak ada data yang sesuai dengan kriteria laporan.</p>
        </div>
    @endif

    <!-- Script Autoprint (Akan langsung memunculkan dialog PDF/Print saat halaman dimuat) -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
