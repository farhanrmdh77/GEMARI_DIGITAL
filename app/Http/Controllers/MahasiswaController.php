<?php

namespace App\Http\Controllers;

use App\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    // 1. Tampilkan Halaman Daftar & Fitur Pencarian
    public function index(Request $request)
    {
        $query = Mahasiswa::latest();

        // Logika Filter/Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
        }

        $mahasiswa = $query->get();
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    // 2. Tampilkan Form Tambah
    public function create()
    {
        return view('mahasiswa.create');
    }

    // 3. Proses Simpan Data Baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'nullable|unique:mahasiswas,nim',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'asal_kampus' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'unit_penempatan' => 'nullable|string|max:255',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'pembimbing' => 'nullable|string|max:255',
            'rekam_jejak' => 'nullable|string',
            'catatan_khusus' => 'nullable|string',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('pas_foto')) {
            $file = $request->file('pas_foto');
            $path = $file->store('pas_foto', 'public');
            $validatedData['pas_foto'] = $path;
        }

        Mahasiswa::create($validatedData);

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil ditambahkan!');
    }

    // 4. Tampilkan Detail Data
    public function show($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    // 5. Tampilkan Form Edit Data
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    // 6. Proses Update Data & Kelola Foto
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'nullable|unique:mahasiswas,nim,' . $mahasiswa->id, 
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'asal_kampus' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'unit_penempatan' => 'nullable|string|max:255',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'pembimbing' => 'nullable|string|max:255',
            'rekam_jejak' => 'nullable|string',
            'catatan_khusus' => 'nullable|string',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Logika Centang Hapus Foto
        if ($request->has('hapus_foto') && $request->hapus_foto == '1') {
            if ($mahasiswa->pas_foto) {
                Storage::disk('public')->delete('pas_foto/' . basename($mahasiswa->pas_foto));
            }
            $validatedData['pas_foto'] = null; // Kosongkan data foto di database
        } 
        // Logika Jika Ada Upload Foto Baru
        elseif ($request->hasFile('pas_foto')) {
            if ($mahasiswa->pas_foto) {
                Storage::disk('public')->delete('pas_foto/' . basename($mahasiswa->pas_foto));
            }
            $file = $request->file('pas_foto');
            $path = $file->store('pas_foto', 'public');
            $validatedData['pas_foto'] = $path;
        }

        $mahasiswa->update($validatedData);

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diperbarui!');
    }

    // 7. Proses Hapus Data
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        // Hapus foto dari server jika ada
        if ($mahasiswa->pas_foto) {
            Storage::disk('public')->delete('pas_foto/' . basename($mahasiswa->pas_foto));
        }
        
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus!');
    }

    public function sertifikat($id)
    {
        $data = Mahasiswa::findOrFail($id);
        
        // Jika tanggal selesai kosong, gunakan tanggal hari ini untuk di sertifikat
        $tanggal_cetak = $data->tgl_selesai 
            ? \Carbon\Carbon::parse($data->tgl_selesai)->translatedFormat('d F Y') 
            : \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y');

        return view('sertifikat.index', compact('data', 'tanggal_cetak'));
    }

    public function idCard($id)
    {
        $data = \App\Mahasiswa::findOrFail($id);
        return view('cetak.id-card', compact('data'));
    }

    public function biodata($id)
    {
        $data = \App\Mahasiswa::findOrFail($id);
        return view('cetak.biodata', compact('data'));
    }
}