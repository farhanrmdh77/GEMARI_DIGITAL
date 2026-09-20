@extends('layouts.app')

@section('title', 'Portal ' . $user->role)

@section('content')
<div class="bg-white min-h-[calc(100vh-8rem)] rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 p-8 lg:p-10 font-inter max-w-5xl mx-auto mb-10">
    
    <div class="mb-8 pb-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold font-geist text-slate-800">Selamat Datang, {{ $user->name }}</h2>
            <p class="text-sm text-slate-500 font-inter mt-1">Ini adalah halaman portal Anda sebagai {{ $user->role }}.</p>
        </div>
    </div>

    @if(!$data)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-5 mb-8">
        <div class="flex">
            <svg class="h-6 w-6 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <h3 class="text-sm font-bold text-yellow-800">Data Biodata Belum Tersedia</h3>
                <p class="text-sm text-yellow-700 mt-1">Data Anda belum diinput oleh Admin Humas ke dalam sistem atau email akun ini tidak sesuai dengan email biodata. Silakan hubungi Admin Humas.</p>
            </div>
        </div>
    </div>
    @else
    
    <!-- Card Detail Biodata -->
    <div class="border border-slate-200 rounded-xl p-6 mb-8 bg-white shadow-sm">
        <h3 class="text-lg font-bold font-geist text-slate-800 mb-4 border-b border-slate-200 pb-2 flex items-center justify-between">
            Detail Biodata
            @if($data->pas_foto)
            <img src="{{ asset('storage/' . $data->pas_foto) }}" alt="Foto Profil" class="w-16 h-16 rounded-full object-cover border-2 border-slate-200 shadow-sm">
            @else
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center border-2 border-slate-200 text-slate-500 font-bold text-xl shadow-sm">
                {{ substr($data->nama, 0, 1) }}
            </div>
            @endif
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase">{{ $user->role == 'Siswa' ? 'NIS' : 'NIM' }}</p>
                <p class="text-sm font-medium text-slate-800">{{ $user->role == 'Siswa' ? $data->nis : $data->nim }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase">Nama Lengkap</p>
                <p class="text-sm font-medium text-slate-800">{{ $data->nama }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase">Jenis Kelamin</p>
                <p class="text-sm font-medium text-slate-800">{{ $data->jenis_kelamin == 'L' ? 'Laki-laki' : ($data->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase">Tempat, Tanggal Lahir</p>
                <p class="text-sm font-medium text-slate-800">{{ $data->tempat_lahir }}, {{ $data->tanggal_lahir ? \Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase">Email</p>
                <p class="text-sm font-medium text-slate-800">{{ $data->email }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase">No. Telepon / HP</p>
                <p class="text-sm font-medium text-slate-800">{{ $data->no_hp ?: '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase">{{ $user->role == 'Siswa' ? 'Jurusan' : 'Program Studi' }}</p>
                <p class="text-sm font-medium text-slate-800">{{ $user->role == 'Siswa' ? $data->jurusan : $data->prodi }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase">Instansi / Asal</p>
                <p class="text-sm font-medium text-slate-800">{{ $user->role == 'Siswa' ? $data->asal_sekolah : $data->asal_kampus }}</p>
            </div>
            <div class="md:col-span-2 lg:col-span-1">
                <p class="text-[11px] font-semibold text-slate-500 uppercase">Alamat Lengkap</p>
                <p class="text-sm font-medium text-slate-800">{{ $data->alamat ?: '-' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Card Info Magang -->
        <div class="border border-slate-200 rounded-xl p-6 bg-slate-50">
            <h3 class="text-lg font-bold font-geist text-slate-800 mb-4 border-b border-slate-200 pb-2">Informasi Magang</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-[11px] font-semibold text-slate-500 uppercase">Status</p>
                    @php
                        $todayTs = strtotime(\Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d'));
                        $mulaiTs = strtotime($data->tgl_mulai);
                        $selesaiTs = strtotime($data->tgl_selesai);
                        
                        if (empty($data->tgl_mulai) || empty($data->tgl_selesai)) {
                            $status = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Tanggal Belum Diatur</span>';
                            $isSelesai = false;
                        } elseif ($mulaiTs > $todayTs) {
                            $status = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Akan Masuk</span>';
                            $isSelesai = false;
                        } elseif ($mulaiTs <= $todayTs && $selesaiTs >= $todayTs) {
                            $status = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Sedang Aktif</span>';
                            $isSelesai = false;
                        } else {
                            $status = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Selesai Magang</span>';
                            $isSelesai = true;
                        }
                    @endphp
                    <div class="mt-1">{!! $status !!}</div>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-500 uppercase">Unit Penempatan</p>
                    <p class="text-sm font-medium text-slate-800">{{ $data->unit_penempatan ?: 'Belum ditentukan' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-500 uppercase">Periode Magang</p>
                    <p class="text-sm font-medium text-slate-800">
                        {{ $data->tgl_mulai ? \Carbon\Carbon::parse($data->tgl_mulai)->format('d/m/Y') : '-' }} 
                        s/d 
                        {{ $data->tgl_selesai ? \Carbon\Carbon::parse($data->tgl_selesai)->format('d/m/Y') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-500 uppercase">Pembimbing Lapangan</p>
                    <p class="text-sm font-medium text-slate-800">{{ $data->pembimbing ?: '-' }}</p>
                </div>
            </div>

            @if($isSelesai)
            <div class="mt-6 pt-4 border-t border-slate-200">
                <a href="{{ route(strtolower($user->role) . '.sertifikat', $data->id) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Sertifikat
                </a>
            </div>
            @endif
        </div>

        <!-- Card Upload Dokumen -->
        <div class="border border-slate-200 rounded-xl p-6">
            <h3 class="text-lg font-bold font-geist text-slate-800 mb-4 border-b border-slate-200 pb-2">Upload Dokumen</h3>
            
            <form action="{{ route('user.upload_dokumen') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    
                    <!-- Surat Permohonan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Surat Permohonan Magang</label>
                        @if($data->surat_permohonan)
                            <div class="mb-2 flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded text-sm text-green-700">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Sudah Diupload
                                </span>
                                <a href="{{ route('dokumen.view', ['path' => $data->surat_permohonan]) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Dokumen</a>
                            </div>
                        @else
                            <div class="mb-2 text-xs text-yellow-600 bg-yellow-50 p-2 rounded border border-yellow-200">Belum ada dokumen yang diupload.</div>
                        @endif
                        <input type="file" name="surat_permohonan" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer border border-slate-200 rounded-lg" accept=".pdf,.doc,.docx">
                        <p class="mt-1 text-xs text-slate-400">PDF, DOC, DOCX up to 5MB.</p>
                    </div>

                    <!-- Laporan Magang -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Laporan Magang</label>
                        @if($data->laporan_magang)
                            <div class="mb-2 flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded text-sm text-green-700">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Sudah Diupload
                                </span>
                                <a href="{{ route('dokumen.view', ['path' => $data->laporan_magang]) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Dokumen</a>
                            </div>
                        @else
                            <div class="mb-2 text-xs text-yellow-600 bg-yellow-50 p-2 rounded border border-yellow-200">Belum ada dokumen yang diupload.</div>
                        @endif
                        <input type="file" name="laporan_magang" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer border border-slate-200 rounded-lg" accept=".pdf,.doc,.docx">
                        <p class="mt-1 text-xs text-slate-400">PDF, DOC, DOCX up to 5MB.</p>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-800 transition-colors">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Upload Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>

    @endif

</div>
@endsection
