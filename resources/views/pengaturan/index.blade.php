@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<!-- Container Utama -->
<div class="max-w-4xl mx-auto font-inter pb-12">
    
    <!-- ================= HEADER & ALERT ================= -->
    <div class="mb-10">
        <h2 class="text-2xl md:text-[26px] font-bold font-geist text-slate-800">Pengaturan Sistem</h2>
        <p class="text-sm text-slate-500 font-inter mt-1.5">Kelola profil, keamanan akun, dan konfigurasi cetak dokumen instansi.</p>
    </div>



    <!-- Alert Error Validasi -->
    @if($errors->any())
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity.duration.500ms class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <ul class="text-sm font-medium list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ================= KARTU 1: PROFIL AKUN ================= -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-teal-50 text-primary flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <h3 class="text-[17px] font-bold font-geist text-slate-800">Profil Pengguna</h3>
        </div>
        
        <!-- Form Update Profil -->
        <form action="{{ route('pengaturan.profil') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-8">
                <!-- Foto Profil -->
                <div class="flex items-center gap-6 mb-8">
                    <!-- Preview Avatar -->
                    <div class="relative w-20 h-20 rounded-full bg-teal-100 border-2 border-white shadow-sm flex items-center justify-center text-primary font-bold font-geist text-2xl uppercase overflow-hidden shrink-0">
                        @if($user->foto_admin)
                            <img id="preview-avatar" src="{{ asset('storage/' . $user->foto_admin) }}" class="w-full h-full object-cover">
                        @else
                            <span id="preview-initial">{{ substr($user->name, 0, 1) }}</span>
                            <img id="preview-avatar" src="#" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    
                    <div>
                        <label class="inline-flex items-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium font-inter px-4 py-2 rounded-lg cursor-pointer transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Ubah Foto Profil
                            <input type="file" name="foto_admin" class="hidden" accept="image/png, image/jpeg" onchange="previewImage(this)">
                        </label>
                        <p class="text-[11px] text-slate-400 mt-2 font-inter">Format JPG atau PNG. Maksimal 2MB.</p>
                    </div>
                </div>

                <!-- Input Profil -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Peran / Jabatan</label>
                        <input type="text" value="{{ $user->role }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 outline-none font-inter cursor-not-allowed" readonly title="Hubungi Super Admin untuk mengubah Peran">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Email Utama</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" required>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center bg-primary hover:bg-primary-hover text-white font-medium font-geist rounded-lg px-5 py-2 text-sm transition-colors shadow-sm focus:ring-2 focus:ring-primary/40 focus:ring-offset-2">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- ================= KARTU 2: KEAMANAN AKUN ================= -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h3 class="text-[17px] font-bold font-geist text-slate-800">Keamanan Akun</h3>
        </div>
        
        <!-- Form Update Password -->
        <form action="{{ route('pengaturan.password') }}" method="POST">
            @csrf
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" required>
                    </div>
                    <div class="border-t border-slate-100 md:col-span-2 my-2"></div>
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                        <input type="password" name="new_password" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" placeholder="Minimal 8 karakter" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" placeholder="Ketik ulang kata sandi baru" required>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center bg-slate-800 hover:bg-slate-900 text-white font-medium font-geist rounded-lg px-5 py-2 text-sm transition-colors shadow-sm focus:ring-2 focus:ring-slate-800/40 focus:ring-offset-2">
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>

    @if(Auth::user()->role === 'Superadmin')
    <!-- ================= KARTU 3: KONFIGURASI INSTANSI ================= -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-12">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h3 class="text-[17px] font-bold font-geist text-slate-800">Konfigurasi Instansi</h3>
                </div>
            </div>
        </div>
        
        <!-- Form Update Konfigurasi -->
        <form action="{{ route('pengaturan.instansi') }}" method="POST">
            @csrf
            <div class="p-8">
                <p class="text-sm text-slate-500 mb-6 font-inter leading-relaxed">
                    Pengaturan ini akan digunakan secara otomatis pada seluruh format dokumen yang dicetak oleh sistem.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Nama Instansi Utama</label>
                        <input type="text" name="nama_instansi" value="{{ old('nama_instansi', $setting->nama_instansi ?? 'BPK RI Perwakilan Provinsi Jambi') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" required>
                    </div>
                    
                    <!-- BAGIAN 1: LAPORAN -->
                    <div class="md:col-span-2 mt-4 pt-4 border-t border-slate-100">
                        <h4 class="text-sm font-bold font-geist text-slate-800 mb-4 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Pihak Penandatangan Laporan (Subbag Humas)</h4>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Jabatan Penandatangan Laporan</label>
                        <input type="text" name="jabatan_pejabat" value="{{ old('jabatan_pejabat', $setting->jabatan_pejabat) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary transition-all font-inter" placeholder="Contoh: Kepala Subbagian Humas" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Nama Pejabat Humas</label>
                        <input type="text" name="nama_pejabat" value="{{ old('nama_pejabat', $setting->nama_pejabat) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary transition-all font-inter" placeholder="Contoh: Budi Santoso, S.E." required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">NIP Pejabat Humas</label>
                        <input type="text" name="nip_pejabat" value="{{ old('nip_pejabat', $setting->nip_pejabat) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary transition-all font-inter" required>
                    </div>

                    <!-- BAGIAN 2: SERTIFIKAT -->
                    <div class="md:col-span-2 mt-4 pt-4 border-t border-slate-100">
                        <h4 class="text-sm font-bold font-geist text-slate-800 mb-4 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pihak Penandatangan Sertifikat</h4>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Jabatan Penandatangan Sertifikat</label>
                        <input type="text" name="jabatan_pejabat_sertifikat" value="{{ old('jabatan_pejabat_sertifikat', $setting->jabatan_pejabat_sertifikat) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary transition-all font-inter" placeholder="Contoh: Kepala Sekretariat Perwakilan" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Nama Pejabat Penandatangan</label>
                        <input type="text" name="nama_pejabat_sertifikat" value="{{ old('nama_pejabat_sertifikat', $setting->nama_pejabat_sertifikat) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary transition-all font-inter" placeholder="Contoh: Dr. Ir. Ahmad Yani" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">NIP Pejabat Penandatangan</label>
                        <input type="text" name="nip_pejabat_sertifikat" value="{{ old('nip_pejabat_sertifikat', $setting->nip_pejabat_sertifikat) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary transition-all font-inter" required>
                    </div>
                </div>
            </div>
            
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center bg-primary hover:bg-primary-hover text-white font-medium font-geist rounded-lg px-5 py-2 text-sm transition-colors shadow-sm focus:ring-2 focus:ring-primary/40 focus:ring-offset-2">
                    Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>

    <!-- ================= KARTU 4: MASTER DATA INSTANSI ================= -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-12">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h3 class="text-[17px] font-bold font-geist text-slate-800">Master Data Asal Sekolah / Kampus</h3>
                </div>
            </div>
        </div>
        
        <div class="p-8" x-data="instansiData()">
            <p class="text-sm text-slate-500 mb-6 font-inter leading-relaxed">
                Tambahkan daftar asal sekolah atau kampus agar pada saat pendaftaran pengguna bisa memilih dari dropdown untuk menghindari duplikasi data.
            </p>

            <form action="{{ route('instansi.store') }}" method="POST" class="mb-8 bg-slate-50 p-6 rounded-xl border border-slate-200">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div class="md:col-span-1">
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Jenis</label>
                        <select name="jenis" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" required>
                            <option value="Sekolah">Sekolah</option>
                            <option value="Kampus">Kampus</option>
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Nama Instansi</label>
                        <input type="text" name="nama_instansi" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary transition-all font-inter" placeholder="Contoh: SMKN 1 Jambi" required>
                    </div>
                    <div class="md:col-span-1">
                        <button type="submit" class="w-full inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-medium font-geist rounded-lg px-5 py-2.5 text-sm transition-colors shadow-sm focus:ring-2 focus:ring-emerald-600/40 focus:ring-offset-2">
                            Tambah Instansi
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabel Instansi dgn AlpineJS -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                <!-- Dropdown Show Entries -->
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-500">Tampilkan</span>
                    <select x-model="perPage" @change="changePage(1)" class="bg-white border border-slate-200 rounded-md text-sm px-2 py-1 outline-none focus:border-primary">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-slate-500">entri</span>
                </div>
                
                <!-- Filter Jenis dan Pencarian -->
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <select x-model="filterJenis" @change="changePage(1)" class="bg-white border border-slate-200 rounded-md text-sm px-3 py-1.5 outline-none focus:border-primary">
                        <option value="">Semua Jenis</option>
                        <option value="Sekolah">Sekolah</option>
                        <option value="Kampus">Kampus</option>
                    </select>
                    
                    <div class="relative w-full sm:w-64">
                        <input type="text" x-model="search" @input="changePage(1)" placeholder="Cari nama instansi..." class="w-full bg-white border border-slate-200 rounded-md text-sm pl-8 pr-3 py-1.5 outline-none focus:border-primary">
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto bg-white border border-slate-200 rounded-xl shadow-sm">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider font-geist w-16">ID</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider font-geist">Nama Instansi</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider font-geist w-32">Jenis</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider font-geist text-right w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="inst in paginatedData" :key="inst.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-4 text-sm text-slate-600 font-inter" x-text="inst.id"></td>
                                <td class="px-5 py-4 text-sm text-slate-800 font-medium font-inter" x-text="inst.nama_instansi"></td>
                                <td class="px-5 py-4 text-sm font-inter">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-medium" 
                                          :class="inst.jenis === 'Sekolah' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700'" 
                                          x-text="inst.jenis"></span>
                                </td>
                                <td class="px-5 py-4 text-sm text-right">
                                    <button type="button" @click="deleteInstansi(inst.id, inst.nama_instansi)" class="text-red-500 hover:text-red-700 font-medium text-xs transition-colors p-1.5 hover:bg-red-50 rounded-md">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="paginatedData.length === 0" style="display: none;">
                            <td colspan="4" class="px-5 py-8 text-center text-slate-500 text-sm font-inter">Tidak ada data ditemukan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination -->
            <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-slate-500 font-inter">
                    Menampilkan <span x-text="startRecord" class="font-medium text-slate-700"></span> sampai <span x-text="endRecord" class="font-medium text-slate-700"></span> dari <span x-text="filteredData.length" class="font-medium text-slate-700"></span> entri
                </div>
                <div class="flex items-center gap-1" x-show="totalPages > 1" style="display: none;">
                    <button @click="prevPage()" :disabled="currentPage === 1" class="px-3 py-1.5 text-sm font-medium rounded-md border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Sebelumnya
                    </button>
                    
                    <button @click="nextPage()" :disabled="currentPage === totalPages" class="px-3 py-1.5 text-sm font-medium rounded-md border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Selanjutnya
                    </button>
                </div>
            </div>

            <!-- Form Hidden untuk Hapus -->
            <form id="delete-instansi-form" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
    @endif

    <!-- ================= KARTU 5: ZONA BAHAYA (LOGOUT) ================= -->
    <div class="bg-red-50/50 rounded-2xl border border-red-100 shadow-sm p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h3 class="text-[17px] font-bold font-geist text-red-800 mb-1">Keluar dari Aplikasi</h3>
            <p class="text-sm text-red-600/80 font-inter">Akhiri sesi Anda saat ini. Pastikan Anda telah menyimpan semua perubahan data.</p>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" id="logout-form-pengaturan">
            @csrf
            <button type="button" onclick="confirmLogout()" class="inline-flex items-center justify-center bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 font-medium font-geist rounded-lg px-6 py-3 text-sm transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar Aplikasi
            </button>
        </form>
    </div>

</div>

<!-- Script Pratinjau Avatar -->
<script>
    function previewImage(input) {
        var file = input.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewImg = document.getElementById('preview-avatar');
                var previewInit = document.getElementById('preview-initial');
                
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                if (previewInit) previewInit.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>

<!-- Script AlpineJS untuk DataTable Instansi -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('instansiData', () => ({
            allData: @json($instansis),
            perPage: 10,
            currentPage: 1,
            search: '',
            filterJenis: '',
            
            get filteredData() {
                return this.allData.filter(item => {
                    const matchJenis = this.filterJenis === '' || item.jenis === this.filterJenis;
                    const matchSearch = item.nama_instansi.toLowerCase().includes(this.search.toLowerCase());
                    return matchJenis && matchSearch;
                });
            },
            
            get paginatedData() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + parseInt(this.perPage);
                return this.filteredData.slice(start, end);
            },
            
            get totalPages() {
                return Math.max(1, Math.ceil(this.filteredData.length / this.perPage));
            },
            
            get startRecord() {
                return this.filteredData.length === 0 ? 0 : ((this.currentPage - 1) * parseInt(this.perPage)) + 1;
            },
            
            get endRecord() {
                const end = this.currentPage * parseInt(this.perPage);
                return end > this.filteredData.length ? this.filteredData.length : end;
            },
            
            changePage(page) {
                this.currentPage = page;
            },
            
            prevPage() {
                if (this.currentPage > 1) this.currentPage--;
            },
            
            nextPage() {
                if (this.currentPage < this.totalPages) this.currentPage++;
            },
            
            deleteInstansi(id, nama) {
                Swal.fire({
                    title: 'Hapus Data?',
                    text: `Apakah Anda yakin ingin menghapus instansi ${nama}? Data pengguna yang terkait mungkin terdampak.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    heightAuto: false,
                    customClass: {
                        confirmButton: 'font-geist',
                        cancelButton: 'font-geist',
                        title: 'font-geist font-bold',
                        popup: 'rounded-2xl shadow-xl border border-slate-100'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-instansi-form');
                        form.action = `/instansi/${id}`;
                        form.submit();
                    }
                });
            }
        }));
    });
</script>
@endsection