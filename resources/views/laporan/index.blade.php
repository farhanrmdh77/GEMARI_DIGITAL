@extends('layouts.app')

@section('title', 'Laporan Sistem')

@section('content')
<!-- Header & Deskripsi -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold font-geist text-slate-800">Cetak Laporan Rekapitulasi & Kalender Magang</h2>
        <p class="text-sm text-slate-500 font-inter mt-1.5">Pantau kapasitas harian peserta magang dan saring data untuk mengekspor dokumen laporan.</p>
    </div>
</div>

<div class="flex flex-col gap-6">
    
    <!-- Bagian Atas: Form Filter -->
    <div>
        <div class="bg-white rounded-[16px] shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 p-6">
            <h3 class="text-[17px] font-bold font-geist text-slate-800 pb-4 mb-6 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Kriteria Ekspor Laporan
            </h3>
            
            <form action="{{ route('laporan.index') }}" method="GET">
                <!-- Grid Filter 4 Kolom: Lega, Proporsional, dan Mudah Dibaca -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Filter Kategori -->
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Kategori Data</label>
                        <select name="kategori" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 font-inter focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none cursor-pointer transition-all shadow-sm">
                            <option value="semua" {{ request('kategori') == 'semua' ? 'selected' : '' }}>Semua (Siswa & Mahasiswa)</option>
                            <option value="siswa" {{ request('kategori') == 'siswa' ? 'selected' : '' }}>Hanya Data Siswa</option>
                            <option value="mahasiswa" {{ request('kategori') == 'mahasiswa' ? 'selected' : '' }}>Hanya Data Mahasiswa</option>
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Status Peserta</label>
                        <select name="status" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 font-inter focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none cursor-pointer transition-all shadow-sm">
                            <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Sedang Aktif</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai Magang</option>
                        </select>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Mulai Tanggal</label>
                        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 font-inter focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all shadow-sm">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 font-inter focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <!-- Action Bar Bawah: Proporsional, Elegan, dan Nyaman Digunakan -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-5 mt-5 border-t border-slate-100">
                    <div class="text-xs text-slate-400 font-inter flex items-center gap-1.5 self-start sm:self-center">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Sesuaikan kriteria filter di atas untuk menyaring kalender atau mengekspor dokumen laporan.
                    </div>
                    
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <button type="button" onclick="previewCalendar()" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium font-geist rounded-xl px-5 py-2.5 text-sm transition-all focus:ring-2 focus:ring-slate-200 shadow-sm cursor-pointer whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Pratinjau
                        </button>
                        <button type="submit" name="action" value="cetak" formtarget="_blank" class="inline-flex items-center justify-center bg-primary hover:bg-primary-hover text-white font-medium font-geist rounded-xl px-6 py-2.5 text-sm transition-all focus:ring-2 focus:ring-primary/40 shadow-sm cursor-pointer whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Unduh Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bagian Bawah: FullCalendar -->
    <div>
        <div class="bg-white rounded-[16px] shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 p-6 flex flex-col">
            <!-- Calendar Container -->
            <div id="calendar" class="w-full font-inter min-h-[600px]"></div>
        </div>
    </div>
</div>

<!-- Modal Daftar Peserta per Hari -->
<div id="eventModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeEventModal()"></div>
    
    <!-- Modal Box -->
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl relative z-10 mx-4 max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold font-geist text-slate-800" id="modalTitle">Detail Peserta</h3>
                <p class="text-sm font-medium text-primary mt-1" id="modalSubtitle">Tanggal</p>
            </div>
            <button onclick="closeEventModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-base font-bold text-slate-800">Total <span id="modalCount"></span></h4>
                    <p class="text-xs text-slate-500 font-inter">Berdasarkan tanggal mulai magang.</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-left text-sm whitespace-nowrap font-inter">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Instansi / Kampus</th>
                            <th class="px-5 py-3">Unit</th>
                            <th class="px-5 py-3">Periode Magang</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-slate-700" id="modalTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet" />
<style>
    /* Styling Calendar UI to match modern look */
    .fc-theme-standard .fc-scrollgrid { border-color: #f1f5f9; border-radius: 12px; overflow: hidden; }
    .fc-theme-standard th { background: #f8fafc; border-color: #f1f5f9; padding: 12px 0; }
    .fc-theme-standard td { border-color: #f1f5f9; }
    .fc .fc-toolbar-title { font-family: 'Geist', sans-serif; font-weight: 700; color: #1e293b; font-size: 1.25rem; }
    .fc .fc-button-primary {
        background-color: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        font-family: 'Inter', sans-serif;
        text-transform: capitalize;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .fc .fc-button-primary:hover {
        background-color: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active, 
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #eff6ff;
        color: #0ea5e9;
        border-color: #bae6fd;
    }
    .fc .fc-daygrid-day-number { font-family: 'Inter', sans-serif; font-size: 0.875rem; color: #475569; font-weight: 500; padding: 8px; }
    .fc .fc-day-today { background-color: #f0f9ff !important; }
    .fc-event {
        cursor: pointer;
        border: none;
        padding: 4px 6px;
        border-radius: 6px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 0.75rem;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }
    .fc-h-event .fc-event-main {
        color: #fff;
    }
</style>
@endpush

@push('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        window.calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            themeSystem: 'standard',
            dayMaxEvents: true,
            events: {
                url: '{{ route('laporan.calendar_data') }}',
                extraParams: function() {
                    return {
                        kategori: document.querySelector('select[name="kategori"]').value,
                        status: document.querySelector('select[name="status"]').value,
                        tgl_mulai: document.querySelector('input[name="tgl_mulai"]').value,
                        tgl_akhir: document.querySelector('input[name="tgl_akhir"]').value,
                    };
                }
            },
            eventClick: function(info) {
                var props = info.event.extendedProps;
                
                document.getElementById('modalSubtitle').innerText = props.tanggal_format;
                document.getElementById('modalCount').innerText = props.count + ' ' + (props.kategori || 'Peserta');
                
                var tbody = document.getElementById('modalTableBody');
                tbody.innerHTML = '';
                
                props.peserta.forEach(function(p) {
                    var tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50 transition-colors';
                    
                    var statusColor = 'bg-slate-500';
                    if(p.status === 'Aktif') statusColor = 'bg-emerald-500';
                    else if(p.status === 'Pending') statusColor = 'bg-amber-500';
                    else if(p.status === 'Selesai') statusColor = 'bg-slate-400';
                    
                    tr.innerHTML = `
                        <td class="px-5 py-3 font-medium text-slate-800">${p.nama} <span class="ml-2 text-xs text-slate-400 font-normal">(${p.kategori})</span></td>
                        <td class="px-5 py-3 text-slate-600">${p.institusi}</td>
                        <td class="px-5 py-3 text-slate-500">${p.unit || '-'}</td>
                        <td class="px-5 py-3 text-slate-600 font-medium">${p.periode}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold text-white ${statusColor}">${p.status}</span>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                document.getElementById('eventModal').classList.remove('hidden');
            }
        });

        window.calendar.render();
    });

    function closeEventModal() {
        document.getElementById('eventModal').classList.add('hidden');
    }

    function previewCalendar() {
        if (window.calendar) {
            window.calendar.refetchEvents();
        }
    }
</script>
@endpush