@extends('layouts.app')

@section('title', 'Laporan Sistem')

@section('content')
<!-- Header & Deskripsi -->
<div class="mb-8">
    <h2 class="text-2xl font-bold font-geist text-slate-800">Cetak Laporan Rekapitulasi</h2>
    <p class="text-sm text-slate-500 font-inter mt-1.5">Saring data berdasarkan kriteria tertentu untuk mengekspor dokumen laporan.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Kolom Kiri: Form Filter -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-[16px] shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 p-6 sticky top-6">
            <h3 class="text-[17px] font-bold font-geist text-slate-800 mb-6 pb-4 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Kriteria Laporan
            </h3>
            
            <form action="{{ route('laporan.index') }}" method="GET" class="space-y-5">
                
                <!-- Filter Kategori -->
                <div>
                    <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Kategori Data</label>
                    <select name="kategori" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 font-inter focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none cursor-pointer transition-all">
                        <option value="semua" {{ request('kategori') == 'semua' ? 'selected' : '' }}>Semua (Siswa & Mahasiswa)</option>
                        <option value="siswa" {{ request('kategori') == 'siswa' ? 'selected' : '' }}>Hanya Data Siswa</option>
                        <option value="mahasiswa" {{ request('kategori') == 'mahasiswa' ? 'selected' : '' }}>Hanya Data Mahasiswa</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Status Peserta</label>
                    <select name="status" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 font-inter focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none cursor-pointer transition-all">
                        <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Sedang Aktif</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai Magang</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Mulai Tanggal</label>
                        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 font-inter focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all">
                    </div>
                    <!-- Tanggal Akhir -->
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 font-inter focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all">
                    </div>
                </div>

                <div class="pt-6 mt-2 border-t border-slate-100 space-y-3">
                    <!-- Tombol Tampilkan Preview -->
                    <button type="submit" name="action" value="preview" class="w-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-medium font-geist rounded-lg px-4 py-2.5 text-sm transition-colors shadow-sm">
                        Tampilkan Pratinjau
                    </button>
                    
                    <!-- Tombol Cetak/Export (formtarget="_blank" untuk tab baru) -->
                    <button type="submit" name="action" value="cetak" formtarget="_blank" class="w-full flex items-center justify-center bg-primary hover:bg-primary-hover text-white font-medium font-geist rounded-lg px-4 py-2.5 text-sm transition-colors focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Unduh Laporan (PDF)
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Preview Box -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-[16px] shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 h-full min-h-[400px] flex flex-col overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white z-10">
                <h3 class="text-[17px] font-bold font-geist text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Pratinjau Data
                </h3>
                @if(request()->has('action') && request('action') == 'preview')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-teal-50 text-teal-600 font-inter uppercase">
                        Ditemukan {{ $data->count() }} Data
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500 font-inter uppercase">
                        Menunggu Kriteria
                    </span>
                @endif
            </div>
            
            @if(request()->has('action') && request('action') == 'preview')
                @if($data->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap font-inter">
                            <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="px-5 py-3">Nama & Identitas</th>
                                    <th class="px-5 py-3">Kategori</th>
                                    <th class="px-5 py-3">Instansi / Unit</th>
                                    <th class="px-5 py-3">Periode</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                                @foreach($data as $d)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-3">
                                        <div class="font-bold text-slate-800">{{ $d->nama }}</div>
                                        <div class="text-xs text-slate-500">{{ $d->identitas }}</div>
                                    </td>
                                    <td class="px-5 py-3 font-medium text-slate-600">{{ $d->kategori_peserta }}</td>
                                    <td class="px-5 py-3">
                                        <div class="truncate max-w-[150px]" title="{{ $d->instansi }}">{{ $d->instansi }}</div>
                                        <div class="text-xs text-slate-500 truncate max-w-[150px]">{{ $d->unit_penempatan }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-xs">
                                        {{ $d->tgl_mulai ? \Carbon\Carbon::parse($d->tgl_mulai)->format('d/m/Y') : '-' }}<br>
                                        s/d {{ $d->tgl_selesai ? \Carbon\Carbon::parse($d->tgl_selesai)->format('d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-slate-50/50">
                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p class="font-medium text-slate-500 text-sm">Tidak ada data yang sesuai dengan kriteria.</p>
                    </div>
                @endif
            @else
                <!-- Empty State Illustration -->
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-slate-50/50">
                    <div class="w-16 h-16 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-center text-slate-300 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold font-geist text-slate-800">Belum Ada Pratinjau</h4>
                    <p class="text-sm text-slate-500 font-inter mt-1.5 max-w-sm">Silakan pilih kriteria di panel sebelah kiri dan klik "Tampilkan Pratinjau" untuk melihat ringkasan data sebelum mengunduh laporan.</p>
                </div>
            @endif
            
        </div>
    </div>

</div>
@endsection