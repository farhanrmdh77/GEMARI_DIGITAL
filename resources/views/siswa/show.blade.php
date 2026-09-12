@extends('layouts.app')

@section('title', 'Detail Data Siswa')

@section('content')
@php
    $today = \Carbon\Carbon::today()->toDateString();
    
    // Logika Status
    if (empty($siswa->tgl_selesai)) {
        $status = 'Selesai';
        $badgeColor = 'bg-slate-100 text-slate-600 border-slate-200';
        $dotColor = 'bg-slate-400';
    } elseif ($siswa->tgl_mulai > $today) {
        $status = 'Akan Masuk';
        $badgeColor = 'bg-amber-50 text-amber-600 border-amber-200';
        $dotColor = 'bg-amber-500';
    } elseif ($siswa->tgl_mulai <= $today && $siswa->tgl_selesai >= $today) {
        $status = 'Aktif';
        $badgeColor = 'bg-teal-50 text-teal-600 border-teal-200';
        $dotColor = 'bg-teal-500';
    } else {
        $status = 'Selesai';
        $badgeColor = 'bg-slate-100 text-slate-600 border-slate-200';
        $dotColor = 'bg-slate-400';
    }
@endphp

<!-- Header Aksi -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <a href="{{ route('siswa.index') }}" class="inline-flex items-center text-sm font-medium font-inter text-slate-500 hover:text-slate-800 transition-colors">
        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar
    </a>
    
    <div class="flex items-center gap-3">
        <!-- GROUP CETAK DOKUMEN -->
        <div class="flex items-center bg-white border border-slate-100 rounded-lg p-1 shadow-sm hover:shadow-md transition-shadow duration-300">
            
            <a href="{{ route('siswa.id_card', $siswa->id) }}" target="_blank" class="inline-flex items-center justify-center text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 font-medium font-inter rounded-md px-3 py-1.5 text-xs transition-colors" title="Cetak ID Card Magang">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                ID Card
            </a>
            
            <div class="w-px h-4 bg-slate-200 mx-1"></div>
            
            <a href="{{ route('siswa.biodata', $siswa->id) }}" target="_blank" class="inline-flex items-center justify-center text-slate-600 hover:bg-blue-50 hover:text-blue-600 font-medium font-inter rounded-md px-3 py-1.5 text-xs transition-colors" title="Cetak Formulir Biodata">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Biodata
            </a>
            
            @if(empty($siswa->tgl_selesai) || $siswa->tgl_selesai < \Carbon\Carbon::today()->toDateString())
                <div class="w-px h-4 bg-slate-200 mx-1"></div>
                <a href="{{ route('siswa.sertifikat', $siswa->id) }}" target="_blank" class="inline-flex items-center justify-center text-slate-600 hover:bg-amber-50 hover:text-amber-600 font-medium font-inter rounded-md px-3 py-1.5 text-xs transition-colors" title="Cetak Sertifikat Selesai Magang">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Sertifikat
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Layout Profil -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- Kolom Kiri: Foto & Ringkasan -->
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 p-6 flex flex-col items-center text-center">
            <div class="w-32 h-40 bg-[#eaf1ff] border border-slate-200 rounded-xl mb-4 relative overflow-hidden flex items-center justify-center shadow-inner">
                @if($siswa->pas_foto)
                    <img src="{{ asset('storage/' . $siswa->pas_foto) }}" alt="Foto" class="w-full h-full object-cover">
                @else
                    <span class="text-sm font-medium font-inter text-slate-400">3 x 4</span>
                @endif
            </div>
            
            <h2 class="text-xl font-bold font-geist text-slate-800 leading-tight mb-1">{{ $siswa->nama }}</h2>
            <p class="text-sm font-inter text-slate-500 mb-4">{{ $siswa->nis }}</p>
            
            <div class="inline-flex items-center px-2.5 py-1 rounded-full border text-[11px] font-bold font-inter tracking-wide uppercase {{ $badgeColor }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }} mr-1.5"></span>
                {{ $status }}
            </div>
        </div>

        <!-- Kartu Kontak Pribadi -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
            <h3 class="text-sm font-bold font-geist text-slate-800 mb-4 border-b border-slate-100 pb-2">Kontak & Alamat</h3>
            <ul class="space-y-4 font-inter text-sm">
                <li class="flex items-start gap-3 text-slate-600">
                    <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="break-all">{{ $siswa->email ?? 'Tidak ada email' }}</span>
                </li>
                <li class="flex items-start gap-3 text-slate-600">
                    <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span>{{ $siswa->no_hp ?? 'Tidak ada No. HP' }}</span>
                </li>
                <li class="flex items-start gap-3 text-slate-600">
                    <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>{{ $siswa->alamat ?? 'Alamat belum diatur' }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Kolom Kanan: Detail Informasi -->
    <div class="md:col-span-2 space-y-6">
        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 p-6 sm:p-8">
            <h3 class="text-lg font-bold font-geist text-primary mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Detail Program Magang
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8 font-inter">
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Asal Sekolah</p>
                    <p class="text-sm font-medium text-slate-800">{{ $siswa->asal_sekolah }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Jurusan / Keahlian</p>
                    <p class="text-sm font-medium text-slate-800">{{ $siswa->jurusan }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Unit Penempatan / Divisi</p>
                    <p class="text-sm font-medium text-slate-800">{{ $siswa->unit_penempatan }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Pembimbing Lapangan</p>
                    <p class="text-sm font-medium text-slate-800">{{ $siswa->pembimbing ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Periode Pelaksanaan</p>
                    <p class="text-sm font-medium text-slate-800">
                        {{ $siswa->tgl_mulai ? \Carbon\Carbon::parse($siswa->tgl_mulai)->format('d M Y') : 'Belum diatur' }} 
                        <span class="text-slate-400 font-normal mx-1">s/d</span> 
                        {{ $siswa->tgl_selesai ? \Carbon\Carbon::parse($siswa->tgl_selesai)->format('d M Y') : 'Belum diatur' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 p-6 sm:p-8">
            <h3 class="text-lg font-bold font-geist text-primary mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Biodata Pribadi
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8 font-inter">
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</p>
                    <p class="text-sm font-medium text-slate-800">
                        {{ $siswa->tempat_lahir ?? '-' }}, 
                        {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</p>
                    <p class="text-sm font-medium text-slate-800">
                        {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 p-6 sm:p-8">
            <h3 class="text-lg font-bold font-geist text-primary mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Catatan & Rekam Jejak
            </h3>
            
            <div class="space-y-6 font-inter">
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Rekam Jejak & Pencapaian</p>
                    <div class="bg-slate-50 border border-slate-100 rounded-lg p-4 text-sm text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $siswa->rekam_jejak ?: 'Tidak ada rekam jejak yang dicatat.' }}</div>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Catatan Khusus</p>
                    <p class="text-sm font-medium text-amber-600">{{ $siswa->catatan_khusus ?: '-' }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= FOOTER AKSI BAWAH ================= -->
    <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
        <a href="{{ route('siswa.edit', $siswa->id) }}" class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium font-geist rounded-lg px-5 py-2.5 text-sm transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            Edit Data
        </a>
        
        <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="form-delete" data-nama="{{ $siswa->nama }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center justify-center bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 font-medium font-geist rounded-lg px-5 py-2.5 text-sm transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus Data
            </button>
        </form>
    </div>
@endsection