@extends('layouts.app')

@section('title', 'Ringkasan Analitik')

@section('content')
@php
    $now = \Carbon\Carbon::now()->locale('id');
    $day = $now->translatedFormat('d');
    $monthYear = $now->translatedFormat('F Y');
@endphp

<!-- Header & Deskripsi (Welcome Banner) -->
<div class="mb-8 bg-gradient-to-r from-[#0f5132] via-[#0b3a24] to-[#022c22] rounded-[1.5rem] p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl relative overflow-hidden border border-white/5">
    <!-- Glow Decorative Effect -->
    <div class="absolute top-0 right-1/4 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-48 h-48 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 text-center sm:text-left">
        <h2 class="text-2xl sm:text-[28px] font-bold font-geist text-white mb-2 tracking-wide">
            Selamat Datang, <span class="text-teal-300">{{ Auth::user()->name ?? 'Administrator BPK' }}!</span> 👋
        </h2>
        
        <p class="text-sm text-white/80 font-inter mt-1.5 leading-relaxed max-w-lg">
            Berikut adalah ringkasan operasional data peserta magang BPK Prov. Jambi saat ini.
        </p>
    </div>

    <!-- Date Widget -->
    <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 sm:p-5 min-w-[130px] flex flex-col items-center justify-center text-center shadow-[0_8px_30px_rgb(0,0,0,0.12)] transform transition-transform hover:scale-105 hover:bg-white/15">
        <span class="text-[9px] text-white/70 font-bold uppercase tracking-[0.2em] mb-1.5">Tanggal</span>
        <span class="text-4xl font-black font-geist text-white my-1 leading-none drop-shadow-md">{{ $day }}</span>
        <span class="text-[10px] text-white/70 font-bold uppercase tracking-[0.1em] mt-1.5">{{ $monthYear }}</span>
    </div>
</div>

<!-- KPI Cards Grid (4 Column) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Widget 1 -->
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 p-6 flex flex-col justify-between group">
        <div class="flex justify-between items-start">
            <span class="text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider">Total Terdaftar</span>
            <div class="p-2.5 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl text-slate-500 shadow-inner group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-3xl font-black font-geist text-slate-800 tracking-tight">{{ $total_terdaftar ?? 0 }}</h3>
        </div>
    </div>

    <!-- Widget 2 -->
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 p-6 flex flex-col justify-between group">
        <div class="flex justify-between items-start">
            <span class="text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider">Sedang Aktif</span>
            <div class="p-2.5 bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl text-teal-600 shadow-inner group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-3xl font-black font-geist text-slate-800 tracking-tight">{{ $sedang_aktif ?? 0 }}</h3>
        </div>
    </div>

    <!-- Widget 3 -->
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 p-6 flex flex-col justify-between group">
        <div class="flex justify-between items-start">
            <span class="text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider">Akan Masuk</span>
            <div class="p-2.5 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl text-amber-600 shadow-inner group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-3xl font-black font-geist text-slate-800 tracking-tight">{{ $akan_masuk ?? 0 }}</h3>
        </div>
    </div>

    <!-- Widget 4 -->
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 p-6 flex flex-col justify-between group">
        <div class="flex justify-between items-start">
            <span class="text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider">Selesai Magang</span>
            <div class="p-2.5 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl text-indigo-600 shadow-inner group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-3xl font-black font-geist text-slate-800 tracking-tight">{{ $selesai_magang ?? 0 }}</h3>
        </div>
    </div>

</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Donut Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-lg transition-shadow duration-300">
        <div class="mb-6 pb-4 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-[17px] font-bold font-geist text-slate-800">Rasio Gender</h3>
                <p class="text-xs text-slate-500 font-inter mt-1">Persentase peserta aktif</p>
            </div>
            <div class="p-2 bg-blue-50 text-blue-500 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
            </div>
        </div>
        <div class="relative w-full flex-1 flex items-center justify-center min-h-[250px]">
            <canvas id="genderChart"></canvas>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:col-span-2 flex flex-col hover:shadow-lg transition-shadow duration-300">
        <div class="mb-6 pb-4 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-[17px] font-bold font-geist text-slate-800">Top 5 Instansi Pengirim</h3>
                <p class="text-xs text-slate-500 font-inter mt-1">Berdasarkan total kontribusi peserta</p>
            </div>
            <div class="p-2 bg-indigo-50 text-indigo-500 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <div class="relative w-full flex-1 min-h-[250px]">
            <canvas id="instansiChart"></canvas>
        </div>
    </div>

</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        
        // ==========================================
        // 1. Setup Donut Chart (Gender)
        // ==========================================
        const ctxGender = document.getElementById('genderChart').getContext('2d');
        
        let laki = {{ $total_laki ?? 0 }};
        let perempuan = {{ $total_perempuan ?? 0 }};
        
        let genderLabels = ['Laki-laki', 'Perempuan'];
        let genderData = [laki, perempuan];
        let genderColors = ['#0369a1', '#0d9488']; // Deep Blue & Teal

        // Cegah grafik hilang/transparan jika bernilai 0 & 0
        if (laki === 0 && perempuan === 0) {
            genderLabels = ['Belum ada data gender'];
            genderData = [1]; // Beri nilai 1 agar lingkaran penuh (placeholder)
            genderColors = ['#e2e8f0']; // Warna abu-abu (slate-200)
        }

        new Chart(ctxGender, {
            type: 'doughnut',
            data: {
                labels: genderLabels,
                datasets: [{
                    data: genderData, 
                    backgroundColor: genderColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    cutout: '75%',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, color: '#64748b', font: { family: "'Geist', sans-serif", size: 12 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                // Ubah teks tooltip jika data kosong
                                if (laki === 0 && perempuan === 0) return ' Tidak ada peserta aktif dengan data gender';
                                return ' ' + context.label + ': ' + context.raw + ' Orang';
                            }
                        }
                    }
                }
            }
        });

        // ==========================================
        // 2. Setup Bar Chart (Instansi)
        // ==========================================
        const ctxInstansi = document.getElementById('instansiChart').getContext('2d');
        new Chart(ctxInstansi, {
            type: 'bar',
            data: {
                // Menggunakan format json_encode standar (Abaikan jika editor memberi warning)
                labels: {!! json_encode($instansi_labels) !!}, 
                datasets: [{
                    label: 'Jumlah Peserta',
                    data: {!! json_encode($instansi_data) !!}, 
                    backgroundColor: '#0d9488', // Primary Teal
                    borderRadius: 6,
                    borderWidth: 0,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 5, color: '#94a3b8', font: { family: "'Geist', sans-serif" } },
                        grid: { color: '#f1f5f9', drawBorder: false }
                    },
                    x: {
                        ticks: { 
                            color: '#64748b',
                            font: { family: "'Geist', sans-serif", size: 12 },
                            maxRotation: 0,
                            minRotation: 0,
                            callback: function(value) {
                                let label = this.getLabelForValue(value);
                                if (!label) return '';
                                return label.length > 10 ? label.substring(0, 10) + '...' : label;
                            }
                        },
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
    });
</script>
@endsection