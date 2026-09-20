<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Setting; // Pastikan model Setting dipanggil di sini
use App\Instansi;

class PengaturanController extends Controller
{
    // 1. Tampilkan Halaman Pengaturan
    public function index()
    {
        $user = Auth::user(); // Ambil data admin yang sedang login
        // Ambil data setting pertama, jika belum ada di database, buat instance/objek kosong
        $setting = Setting::first() ?? new Setting(); 
        $instansis = Instansi::all();
        
        return view('pengaturan.index', compact('user', 'setting', 'instansis'));
    }

    // 2. Proses Perbarui Profil & Foto
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            // Validasi email agar tidak bentrok, kecuali dengan emailnya sendiri
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'foto_admin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Jika ada upload foto profil baru
        if ($request->hasFile('foto_admin')) {
            // Hapus foto lama jika ada
            if ($user->foto_admin) {
                Storage::disk('public')->delete($user->foto_admin);
            }
            // Simpan foto baru ke folder 'admin_fotos'
            $user->foto_admin = $request->file('foto_admin')->store('admin_fotos', 'public');
        }

        $user->save();

        return back()->with('success', 'Profil dan foto berhasil diperbarui!');
    }

    // 3. Proses Perbarui Kata Sandi
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek apakah password lama yang diketik sama dengan yang ada di database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok!']);
        }

        // Ganti dengan password baru yang sudah di-enkripsi (Hash)
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui dengan aman!');
    }

   // 4. Proses Perbarui Konfigurasi Instansi (Header & Penandatangan)
    public function updateInstansi(Request $request)
    {
        $data = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            
            // Validasi Penandatangan Laporan
            'jabatan_pejabat' => 'required|string|max:255',
            'nama_pejabat' => 'required|string|max:255',
            'nip_pejabat' => 'required|string|max:255',
            
            // Validasi Penandatangan Sertifikat
            'jabatan_pejabat_sertifikat' => 'required|string|max:255',
            'nama_pejabat_sertifikat' => 'required|string|max:255',
            'nip_pejabat_sertifikat' => 'required|string|max:255',
        ]);

        $setting = Setting::first();

        if ($setting) {
            $setting->update($data);
        } else {
            Setting::create($data);
        }

        return back()->with('success', 'Konfigurasi instansi berhasil disimpan!');
    }
}