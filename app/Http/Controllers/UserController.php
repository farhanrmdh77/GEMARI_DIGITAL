<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user.
     */
    public function index()
    {
        // Hanya Superadmin dan Admin Humas yang boleh mengakses halaman ini
        if (!in_array(Auth::user()->role, ['Superadmin', 'Admin Humas'])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke Manajemen Akun.');
        }

        // Ambil semua user
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }



    /**
     * Simpan user baru ke database.
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['Superadmin', 'Admin Humas'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $validRoles = Auth::user()->role === 'Superadmin' ? 'Admin Humas,Mahasiswa,Siswa' : 'Mahasiswa,Siswa';
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:' . $validRoles,
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Akun berhasil ditambahkan.');
    }



    /**
     * Update data user.
     */
    public function update(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['Superadmin', 'Admin Humas'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id);

        if (Auth::user()->role === 'Admin Humas' && in_array($user->role, ['Superadmin', 'Admin Humas'])) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat mengedit akun admin.');
        }
        if (Auth::user()->role === 'Superadmin' && $user->role === 'Superadmin') {
            return redirect()->route('users.index')->with('error', 'Superadmin tidak dapat diedit dari sini.');
        }

        $validRoles = Auth::user()->role === 'Superadmin' ? 'Admin Humas,Mahasiswa,Siswa' : 'Mahasiswa,Siswa';

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:' . $validRoles,
        ];

        // Jika password diisi, maka validasi password
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data akun berhasil diperbarui.');
    }

    /**
     * Hapus user.
     */
    public function destroy($id)
    {
        if (!in_array(Auth::user()->role, ['Superadmin', 'Admin Humas'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id);

        if (Auth::user()->role === 'Admin Humas' && in_array($user->role, ['Superadmin', 'Admin Humas'])) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun admin.');
        }
        if (Auth::user()->role === 'Superadmin' && $user->role === 'Superadmin') {
            return redirect()->route('users.index')->with('error', 'Superadmin tidak dapat dihapus.');
        }

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Upload dokumen (Surat Permohonan & Laporan) oleh User.
     */
    public function uploadDokumen(Request $request)
    {
        $user = Auth::user();

        // Validasi file (Bisa pdf, docx, dll. Sesuaikan dengan kebutuhan. Di sini kita contohkan pdf,doc,docx, Max 5MB)
        $request->validate([
            'surat_permohonan' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'laporan_magang'   => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($user->role === 'Siswa') {
            $data = \App\Siswa::where('email', $user->email)->first();
        } elseif ($user->role === 'Mahasiswa') {
            $data = \App\Mahasiswa::where('email', $user->email)->first();
        } else {
            return redirect()->back()->with('error', 'Role tidak valid untuk upload dokumen.');
        }

        if (!$data) {
            return redirect()->back()->with('error', 'Data biodata Anda belum tersedia. Silakan hubungi Admin Humas.');
        }

        // Upload Surat Permohonan
        if ($request->hasFile('surat_permohonan')) {
            // Hapus yang lama jika ada
            if ($data->surat_permohonan) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('dokumen/' . basename($data->surat_permohonan));
            }
            $file = $request->file('surat_permohonan');
            $path = $file->store('dokumen', 'public');
            $data->surat_permohonan = $path;
        }

        // Upload Laporan Magang
        if ($request->hasFile('laporan_magang')) {
            // Hapus yang lama jika ada
            if ($data->laporan_magang) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('dokumen/' . basename($data->laporan_magang));
            }
            $file = $request->file('laporan_magang');
            $path = $file->store('dokumen', 'public');
            $data->laporan_magang = $path;
        }

        $data->save();

        return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
    }
}
