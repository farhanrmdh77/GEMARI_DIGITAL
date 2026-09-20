@extends('layouts.app')

@section('title', 'Manajemen Data Siswa')

@section('content')
<!-- Header Utama -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold font-geist text-slate-800">Manajemen Data Siswa</h2>
        <p class="text-sm text-slate-500 font-inter mt-1">Kelola data siswa sekolah menengah (SMA/SMK/Sederajat) yang terdaftar di instansi.</p>
    </div>
    <!-- Search Input -->
    <div class="relative w-full md:w-72 shrink-0">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <input type="text" id="searchInput" class="block w-full pl-9 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter shadow-sm" placeholder="Cari nama atau NIS...">
    </div>
</div>

<!-- Toolbar: Real-time Filter -->
<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 p-4 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
    <div class="flex flex-col md:flex-row flex-wrap items-center gap-3 w-full lg:flex-1">
        <!-- Filter Status -->
        <select id="statusFilter" class="w-full md:w-auto bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:border-primary outline-none cursor-pointer font-inter">
            <option value="all">Semua Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Akan Masuk">Akan Masuk</option>
            <option value="Selesai">Selesai</option>
        </select>

        <!-- Filter Gender -->
        <select id="genderFilter" class="w-full md:w-auto bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:border-primary outline-none cursor-pointer font-inter">
            <option value="all">Semua Gender</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>

        <!-- Filter Sekolah -->
        <select id="instansiFilter" class="w-full md:w-auto bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:border-primary outline-none cursor-pointer font-inter">
            <option value="all">Semua Sekolah</option>
            @foreach($instansis as $instansi)
                <option value="{{ strtolower($instansi->nama_instansi) }}">{{ $instansi->nama_instansi }}</option>
            @endforeach
        </select>

        <!-- Filter Jenis Magang -->
        <select id="jenisMagangFilter" class="w-full md:w-auto bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:border-primary outline-none cursor-pointer font-inter">
            <option value="all">Semua Jenis Magang</option>
            <option value="reguler">Magang Reguler</option>
            <option value="berbayar">Magang Berbayar</option>
        </select>
    </div>

    <!-- Tambah Button -->
    <!-- Tambah Button -->
    @if(Auth::check() && in_array(Auth::user()->role, ['Superadmin', 'Admin Humas']))
    <a href="{{ route('siswa.create') }}" class="inline-flex items-center justify-center bg-primary hover:bg-primary-hover text-white font-medium font-geist rounded-lg px-5 py-2 text-sm transition-colors shadow-sm focus:ring-2 focus:ring-primary/40 whitespace-nowrap">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
        Tambah Siswa
    </a>
    @endif
</div>

<!-- ================= GRID CARD VIEW ================= -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8" id="cardContainer">
    
    @forelse($siswa as $s)
        @php
            $today = \Carbon\Carbon::today()->toDateString();
            
            // Logika Status Baru
            if (empty($s->tgl_selesai)) {
                $status = 'Selesai';
                $statusDot = 'bg-slate-400';
            } elseif ($s->tgl_mulai > $today) {
                $status = 'Akan Masuk';
                $statusDot = 'bg-amber-500';
            } elseif ($s->tgl_mulai <= $today && $s->tgl_selesai >= $today) {
                $status = 'Aktif';
                $statusDot = 'bg-teal-500';
            } else {
                $status = 'Selesai';
                $statusDot = 'bg-slate-400';
            }
        @endphp

        <!-- CARD SISWA (Ditambahkan class 'data-card' dan 'data-*' attributes) -->
        <div class="data-card bg-white rounded-[16px] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 flex flex-col group" 
             data-nama="{{ strtolower($s->nama) }}" 
             data-identitas="{{ strtolower($s->nis) }}" 
             data-status="{{ $status }}" 
             data-gender="{{ $s->jenis_kelamin }}"
             data-instansi="{{ strtolower($s->asal_sekolah) }}"
             data-jenismagang="{{ strtolower($s->jenis_magang ?? 'reguler') }}">
            
            <!-- Body Card -->
            <div class="p-6 flex gap-6">
                <!-- Pas Foto & Status -->
                <div class="shrink-0 flex flex-col items-center gap-3">
                    <div class="w-[96px] h-[128px] bg-[#eaf1ff] border border-slate-200 rounded-lg flex flex-col items-center justify-center text-center relative overflow-hidden">
                        @if($s->pas_foto)
                            <img src="{{ asset('storage/' . $s->pas_foto) }}" alt="Foto {{ $s->nama }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xs font-medium font-inter text-slate-400 p-2">Tanpa Foto</span>
                        @endif
                        <div class="absolute bottom-2 right-2 bg-[#0b1c30] text-white text-[10px] font-bold px-1.5 py-0.5 rounded">3x4</div>
                    </div>
                    <!-- Status Badge -->
                    <div class="bg-white border border-slate-200 rounded-full px-3 py-1 shadow-sm flex items-center">
                        <span class="w-2 h-2 rounded-full {{ $statusDot }} mr-1.5"></span>
                        <span class="text-[11px] font-bold font-inter text-slate-700">{{ $status }}</span>
                    </div>
                </div>

                <!-- Informasi -->
                <div class="flex-1 min-w-0 py-1">
                    <h3 class="text-lg font-bold font-geist text-primary truncate leading-tight mb-1">{{ $s->nama }}</h3>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="text-xs font-inter text-slate-500">NIS: <span class="text-slate-800 font-medium">{{ $s->nis }}</span></div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ ($s->jenis_magang ?? 'Reguler') == 'Berbayar' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">{{ $s->jenis_magang ?? 'Reguler' }}</span>
                    </div>
                    <div class="text-sm font-bold font-inter text-slate-800 mb-2 truncate">{{ $s->asal_sekolah }}</div>
                    
                    <div class="text-xs font-inter text-slate-500 leading-tight mb-0.5">
                        {{ $s->tempat_lahir ?? '-' }}, {{ $s->tanggal_lahir ? \Carbon\Carbon::parse($s->tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') : '-' }}
                    </div>
                    <div class="text-xs font-inter text-slate-500 leading-tight mb-0.5">
                        {{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                    </div>
                    <div class="text-xs font-semibold font-inter text-slate-700 leading-tight mt-1">{{ $s->no_hp ?? '-' }}</div>

                    <div class="mt-4">
                        <div class="text-[10px] font-bold font-geist text-slate-400 uppercase tracking-widest mb-1">Periode Magang:</div>
                        <div class="text-xs font-inter text-slate-600 font-medium">
                            {{ $s->tgl_mulai ? \Carbon\Carbon::parse($s->tgl_mulai)->locale('id')->isoFormat('D MMMM Y') : '-' }} 
                            <span class="font-normal text-slate-400 mx-1">s/d</span> 
                            {{ $s->tgl_selesai ? \Carbon\Carbon::parse($s->tgl_selesai)->locale('id')->isoFormat('D MMMM Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Card (Aksi) -->
            <div class="relative mt-auto px-6 py-3.5 border-t border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-b-[16px]">
                <div class="w-20"></div> <!-- Spacer untuk menyeimbangkan flex space-between -->
                <a href="{{ route('siswa.show', $s->id) }}" class="absolute left-1/2 -translate-x-1/2 inline-flex items-center text-sm font-semibold font-geist text-primary hover:text-primary-hover transition-colors whitespace-nowrap">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Lihat Dokumen
                </a>
                <div class="flex items-center gap-2">
                    @if(Auth::check() && in_array(Auth::user()->role, ['Superadmin', 'Admin Humas']))
                    <a href="{{ route('siswa.edit', $s->id) }}" class="p-2 text-slate-400 hover:text-amber-500 transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <form action="{{ url('/siswa/' . $s->id) }}" method="POST" class="inline-block form-delete" data-nama="{{ $s->nama }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
    @endforelse

    <!-- State Saat Data Kosong / Tidak Ditemukan -->
    <div id="emptyState" class="col-span-1 lg:col-span-2 bg-white rounded-[16px] shadow-sm border border-slate-200 p-12 flex flex-col items-center justify-center text-center {{ $siswa->count() > 0 ? 'hidden' : 'flex' }}">
        <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <p class="font-medium font-inter text-sm text-slate-500">Tidak ada data yang sesuai dengan pencarian/filter.</p>
    </div>

</div>

<!-- Script Real-time Filter -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const genderFilter = document.getElementById('genderFilter');
        const instansiFilter = document.getElementById('instansiFilter');
        const jenisMagangFilter = document.getElementById('jenisMagangFilter');
        const cards = document.querySelectorAll('.data-card');
        const emptyState = document.getElementById('emptyState');

        function filterData() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            const genderValue = genderFilter.value;
            const instansiValue = instansiFilter.value.toLowerCase();
            const jenisMagangValue = jenisMagangFilter ? jenisMagangFilter.value.toLowerCase() : 'all';
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.getAttribute('data-nama');
                const identitas = card.getAttribute('data-identitas');
                const status = card.getAttribute('data-status');
                const gender = card.getAttribute('data-gender');
                const instansi = card.getAttribute('data-instansi');
                const jenisMagang = card.getAttribute('data-jenismagang');

                const matchesSearch = name.includes(searchTerm) || identitas.includes(searchTerm);
                const matchesStatus = statusValue === 'all' || status === statusValue;
                const matchesGender = genderValue === 'all' || gender === genderValue;
                const matchesInstansi = instansiValue === 'all' || instansi === instansiValue;
                const matchesJenisMagang = jenisMagangValue === 'all' || jenisMagang === jenisMagangValue;

                if (matchesSearch && matchesStatus && matchesGender && matchesInstansi && matchesJenisMagang) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Tampilkan pesan kosong jika tidak ada yang cocok
            if (emptyState) {
                emptyState.classList.toggle('hidden', visibleCount > 0);
                emptyState.classList.toggle('flex', visibleCount === 0);
            }
        }

        // Jalankan fungsi filter setiap kali pengguna mengetik atau mengubah opsi
        searchInput.addEventListener('input', filterData);
        statusFilter.addEventListener('change', filterData);
        genderFilter.addEventListener('change', filterData);
        instansiFilter.addEventListener('change', filterData);
        if(jenisMagangFilter) jenisMagangFilter.addEventListener('change', filterData);
    });
</script>
@endsection