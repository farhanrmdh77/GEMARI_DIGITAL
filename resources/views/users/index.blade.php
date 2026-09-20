@extends('layouts.app')

@section('title', 'Manajemen Akun')

@section('content')
<div x-data="userManagement()">
    <!-- Header Utama -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-geist text-slate-800">Manajemen Akun</h2>
            <p class="text-sm text-slate-500 font-inter mt-1">Kelola data pengguna sistem (Admin, Siswa, Mahasiswa).</p>
        </div>
        
        <button @click="openCreateModal()" class="inline-flex items-center justify-center bg-primary hover:bg-primary-hover text-white font-medium font-geist rounded-lg px-5 py-2.5 text-sm transition-colors shadow-sm focus:ring-2 focus:ring-primary/40 whitespace-nowrap shrink-0">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            Tambah Akun
        </button>
    </div>

    <!-- Daftar User -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold font-geist text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Email</th>
                        <th class="px-5 py-4 whitespace-nowrap">Role</th>
                        <th class="px-5 py-4 whitespace-nowrap">Dibuat Pada</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-inter text-sm">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($u->profile_photo)
                                    <img src="{{ asset('storage/' . $u->profile_photo) }}" alt="Foto {{ $u->name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 shadow-sm shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-teal-50 text-primary font-bold flex items-center justify-center uppercase shrink-0">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="font-semibold text-slate-800">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-slate-600">{{ $u->email }}</td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($u->role === 'Superadmin')
                                <span class="inline-block bg-purple-100 text-purple-700 px-2.5 py-0.5 rounded-full text-xs font-semibold whitespace-nowrap">Superadmin</span>
                            @elseif($u->role === 'Admin Humas')
                                <span class="inline-block bg-indigo-100 text-indigo-700 px-2.5 py-0.5 rounded-full text-xs font-semibold whitespace-nowrap">Admin Humas</span>
                            @elseif($u->role === 'Mahasiswa')
                                <span class="inline-block bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full text-xs font-semibold whitespace-nowrap">Mahasiswa</span>
                            @else
                                <span class="inline-block bg-amber-100 text-amber-700 px-2.5 py-0.5 rounded-full text-xs font-semibold whitespace-nowrap">Siswa</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-500 whitespace-nowrap">{{ $u->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                @php
                                    $canEditDelete = false;
                                    if (Auth::user()->role === 'Superadmin') {
                                        if ($u->role !== 'Superadmin') {
                                            $canEditDelete = true;
                                        }
                                    } elseif (Auth::user()->role === 'Admin Humas') {
                                        if (!in_array($u->role, ['Superadmin', 'Admin Humas'])) {
                                            $canEditDelete = true;
                                        }
                                    }
                                @endphp
                                @if(!$canEditDelete)
                                    <span class="text-xs text-slate-400 font-inter italic px-2 py-1 bg-slate-50 rounded whitespace-nowrap">Akses Terkunci</span>
                                @else
                                    <button type="button" @click="openEditModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}', '{{ $u->role }}')" class="p-2 text-slate-400 hover:text-amber-500 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline-block form-delete" data-nama="{{ $u->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-inter">
                            Tidak ada data akun.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH AKUN -->
    <div x-cloak x-show="showCreate" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Background overlay -->
            <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showCreate = false"></div>

            <!-- Modal panel -->
            <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-slate-100">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                        <h3 class="text-lg leading-6 font-bold font-geist text-slate-900" id="modal-title">
                            Tambah Akun Pengguna
                        </h3>
                        <button @click="showCreate = false" type="button" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Nama -->
                        <div>
                            <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" 
                                placeholder="Masukkan nama lengkap...">
                            @error('name') @if(!old('_method')) <span class="text-xs text-red-500 mt-1 block font-inter">{{ $message }}</span> @endif @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" 
                                placeholder="contoh@email.com">
                            @error('email') @if(!old('_method')) <span class="text-xs text-red-500 mt-1 block font-inter">{{ $message }}</span> @endif @enderror
                        </div>
                        
                        <!-- Role -->
                        <div>
                            <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Role Akses</label>
                            <select name="role" required class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter">
                                @if(Auth::user()->role === 'Superadmin')
                                <option value="Admin Humas" {{ old('role') == 'Admin Humas' ? 'selected' : '' }}>Admin Humas</option>
                                @endif
                                <option value="Mahasiswa" {{ old('role') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="Siswa" {{ old('role') == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                            @error('role') @if(!old('_method')) <span class="text-xs text-red-500 mt-1 block font-inter">{{ $message }}</span> @endif @enderror
                        </div>

                        <!-- Password -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Kata Sandi</label>
                                <input type="password" name="password" required autocomplete="new-password"
                                    class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" 
                                    placeholder="Min 8 karakter">
                                @error('password') @if(!old('_method')) <span class="text-xs text-red-500 mt-1 block font-inter">{{ $message }}</span> @endif @enderror
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Konfirmasi Sandi</label>
                                <input type="password" name="password_confirmation" required autocomplete="new-password"
                                    class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" 
                                    placeholder="Ulangi sandi">
                            </div>
                        </div>

                        <div class="pt-5 mt-2 flex justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="showCreate = false" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold font-geist rounded-lg px-5 py-2.5 text-sm transition-all">
                                Batal
                            </button>
                            <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-bold font-geist rounded-lg px-5 py-2.5 text-sm transition-all shadow-md">
                                Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT AKUN -->
    <div x-cloak x-show="showEdit" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Background overlay -->
            <div x-show="showEdit" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showEdit = false"></div>

            <!-- Modal panel -->
            <div x-show="showEdit" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-slate-100">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                        <h3 class="text-lg leading-6 font-bold font-geist text-slate-900" id="modal-title">
                            Edit Akun Pengguna
                        </h3>
                        <button @click="showEdit = false" type="button" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <form :action="'/users/' + editForm.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" :value="editForm.id">
                        
                        <!-- Nama -->
                        <div>
                            <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" x-model="editForm.name" required
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter">
                            @error('name') @if(old('_method') == 'PUT') <span class="text-xs text-red-500 mt-1 block font-inter">{{ $message }}</span> @endif @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Alamat Email</label>
                            <input type="email" name="email" x-model="editForm.email" required
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter">
                            @error('email') @if(old('_method') == 'PUT') <span class="text-xs text-red-500 mt-1 block font-inter">{{ $message }}</span> @endif @enderror
                        </div>
                        
                        <!-- Role -->
                        <div>
                            <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Role Akses</label>
                            <select x-model="editForm.role" name="role" required class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter">
                                @if(Auth::user()->role === 'Superadmin')
                                <option value="Admin Humas">Admin Humas</option>
                                @endif
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Siswa">Siswa</option>
                            </select>
                            @error('role') @if(old('_method') == 'PUT') <span class="text-xs text-red-500 mt-1 block font-inter">{{ $message }}</span> @endif @enderror
                        </div>

                        <!-- Warning Password -->
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-3 rounded-r-lg mt-4 mb-2">
                            <p class="text-[12px] text-amber-700 font-inter">
                                Biarkan field <strong>Kata Sandi</strong> kosong jika tidak ingin mengubah kata sandi.
                            </p>
                        </div>

                        <!-- Password -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Kata Sandi Baru</label>
                                <input type="password" name="password" autocomplete="new-password"
                                    class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" 
                                    placeholder="Opsional">
                                @error('password') @if(old('_method') == 'PUT') <span class="text-xs text-red-500 mt-1 block font-inter">{{ $message }}</span> @endif @enderror
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold font-geist text-slate-500 uppercase tracking-widest mb-1.5">Konfirmasi Sandi</label>
                                <input type="password" name="password_confirmation" autocomplete="new-password"
                                    class="w-full bg-slate-50 border border-slate-200 focus:border-primary rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/40 outline-none transition-all font-inter" 
                                    placeholder="Ulangi sandi">
                            </div>
                        </div>

                        <div class="pt-5 mt-2 flex justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="showEdit = false" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold font-geist rounded-lg px-5 py-2.5 text-sm transition-all">
                                Batal
                            </button>
                            <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-bold font-geist rounded-lg px-5 py-2.5 text-sm transition-all shadow-md">
                                Perbarui Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('userManagement', () => ({
        showCreate: {{ ($errors->any() && !old('_method')) ? 'true' : 'false' }},
        showEdit: {{ ($errors->any() && old('_method') == 'PUT') ? 'true' : 'false' }},
        editForm: {
            id: {!! json_encode(old('user_id', '')) !!},
            name: {!! json_encode(old('name', '')) !!},
            email: {!! json_encode(old('email', '')) !!},
            role: {!! json_encode(old('role', 'Admin Humas')) !!}
        },
        
        openCreateModal() {
            this.showCreate = true;
        },
        
        openEditModal(id, name, email, role) {
            this.editForm.id = id;
            this.editForm.name = name;
            this.editForm.email = email;
            this.editForm.role = role;
            this.showEdit = true;
        }
    }))
})
</script>
@endsection
