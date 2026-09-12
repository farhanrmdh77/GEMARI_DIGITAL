@extends('layouts.app')

@section('title', 'Tambah Data Mahasiswa')

@section('content')
<!-- Container Utama -->
<div class="bg-white min-h-[calc(100vh-8rem)] rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 p-8 lg:p-10 font-inter max-w-5xl mx-auto mb-10">
    
    <div class="mb-8 pb-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold font-geist text-slate-800">Tambah Data Mahasiswa</h2>
            <p class="text-sm text-slate-500 font-inter mt-1">Masukkan informasi mahasiswa magang baru dengan lengkap dan benar.</p>
        </div>
    </div>

    <form action="{{ route('mahasiswa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-12">
            
            <!-- ================= SEKSI 1: DATA DIRI ================= -->
            <section>
                <h3 class="text-[17px] font-bold font-geist text-primary mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Diri
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all" placeholder="Opsional (Bisa dikosongkan)">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Tempat & Tanggal Lahir</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-1/2 bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all" placeholder="Tempat">
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-1/2 bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all" placeholder="contoh@email.com">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">No HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all" placeholder="08xxxxxxxxxx">
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all cursor-pointer">
                            <option value="" disabled selected>Pilih jenis kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all resize-none" placeholder="Masukkan alamat domisili saat ini">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </section>

            <!-- ================= SEKSI 2: PENDIDIKAN & MAGANG ================= -->
            <section>
                <h3 class="text-[17px] font-bold font-geist text-primary mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Pendidikan & Magang
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Asal Kampus <span class="text-red-500">*</span></label>
                        <input type="text" name="asal_kampus" value="{{ old('asal_kampus') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all" placeholder="Nama Universitas / Perguruan Tinggi" required>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Program Studi <span class="text-red-500">*</span></label>
                        <input type="text" name="prodi" value="{{ old('prodi') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all" placeholder="Contoh: Sistem Informasi" required>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Unit Penempatan / Divisi Magang</label>
                        <select name="unit_penempatan" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all cursor-pointer">
                            <option value="" disabled selected>Pilih Unit Penempatan</option>
                            <option value="Subbagian SDM" {{ old('unit_penempatan') == 'Subbagian SDM' ? 'selected' : '' }}>Subbagian SDM</option>
                            <option value="Subbagian Umum dan TI" {{ old('unit_penempatan') == 'Subbagian Umum dan TI' ? 'selected' : '' }}>Subbagian Umum dan TI</option>
                            <option value="Subbagian Keuangan" {{ old('unit_penempatan') == 'Subbagian Keuangan' ? 'selected' : '' }}>Subbagian Keuangan</option>
                            <option value="Subbagian Hukum" {{ old('unit_penempatan') == 'Subbagian Hukum' ? 'selected' : '' }}>Subbagian Hukum</option>
                            <option value="Subbagian Humas dan TU Kalan" {{ old('unit_penempatan') == 'Subbagian Humas dan TU Kalan' ? 'selected' : '' }}>Subbagian Humas dan TU Kalan</option>
                            <option value="Pemeriksa (Auditor)" {{ old('unit_penempatan') == 'Pemeriksa (Auditor)' ? 'selected' : '' }}>Pemeriksa (Auditor)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Periode Magang</label>
                        <div class="flex items-center gap-2">
                            <input type="date" name="tgl_mulai" value="{{ old('tgl_mulai') }}" class="flex-1 bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all">
                            <span class="text-sm font-medium text-slate-400">s/d</span>
                            <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}" class="flex-1 bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Pembimbing Lapangan / Mentor</label>
                        <input type="text" name="pembimbing" value="{{ old('pembimbing') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all" placeholder="Masukkan nama pembimbing lapangan">
                    </div>
                </div>
            </section>

            <!-- ================= SEKSI 3: PAS FOTO & INFO TAMBAHAN ================= -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="md:col-span-1">
                    <h3 class="text-[17px] font-bold font-geist text-primary mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Pas Foto
                    </h3>
                    <p class="text-xs text-slate-500 mb-4">Format JPG atau PNG, max 2MB.</p>
                    
                    <div class="flex flex-col gap-3">
                        <div class="flex gap-4 items-center">
                            <!-- Kotak Preview -->
                            <div class="w-20 h-24 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0 relative flex items-center justify-center">
                                <img id="preview-img" src="#" class="w-full h-full object-cover hidden">
                                <span id="preview-placeholder" class="text-[10px] font-medium text-slate-400">3 x 4</span>
                            </div>

                            <label class="flex-1 border-2 border-dashed border-slate-300 rounded-xl p-4 flex flex-col items-center justify-center text-center hover:bg-slate-50 hover:border-primary transition-all cursor-pointer group bg-white h-24">
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-primary mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <div class="text-xs font-medium font-geist text-primary">Pilih Foto</div>
                                <input type="file" name="pas_foto" class="hidden" accept="image/png, image/jpeg" onchange="previewFile(this)">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <h3 class="text-[17px] font-bold font-geist text-primary mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Informasi Tambahan
                    </h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Rekam Jejak & Pencapaian Utama</label>
                            <textarea name="rekam_jejak" rows="3" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all resize-none" placeholder="Tuliskan pengalaman organisasi, lomba, atau project yang relevan">{{ old('rekam_jejak') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold font-geist text-slate-500 uppercase tracking-wider mb-2">Catatan Khusus (Opsional)</label>
                            <input type="text" name="catatan_khusus" value="{{ old('catatan_khusus') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/40 outline-none transition-all" placeholder="Kondisi kesehatan, kebutuhan khusus, dll">
                        </div>
                    </div>
                </div>
            </div>

        </div> 
        
        <!-- ================= FOOTER BUTTONS ================= -->
        <div class="mt-12 flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
            <a href="{{ route('mahasiswa.index') }}" class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium font-geist rounded-lg px-5 py-2.5 text-sm transition-colors shadow-sm">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center justify-center bg-primary hover:bg-primary-hover text-white font-medium font-geist rounded-lg px-6 py-2.5 text-sm transition-colors focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                Simpan Data Mahasiswa
            </button>
        </div>
    </form>
</div>

<!-- Script Pratinjau Foto -->
<script>
    function previewFile(input) {
        var file = input.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-img').classList.remove('hidden');
                var placeholder = document.getElementById('preview-placeholder');
                if(placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection