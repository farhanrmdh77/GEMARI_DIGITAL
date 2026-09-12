<?php

namespace App\Http\Controllers;

use App\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    // 1. Tampilkan Halaman Daftar & Fitur Pencarian
    public function index(Request $request)
    {
        $query = Siswa::latest();

        // Logika Filter/Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
        }

        $siswa = $query->get();
        return view('siswa.index', compact('siswa'));
    }

    // 2. Tampilkan Form Tambah
    public function create()
    {
        return view('siswa.create');
    }

    // 3. Proses Simpan Data Baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'nullable|unique:siswas,nis',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'asal_sekolah' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
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

        Siswa::create($validatedData);

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil ditambahkan!');
    }

    // 4. Tampilkan Detail Data
    public function show($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswa.show', compact('siswa'));
    }

    // 5. Tampilkan Form Edit Data
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswa.edit', compact('siswa'));
    }

    // 6. Proses Update Data & Kelola Foto
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'nullable|unique:siswas,nis,' . $siswa->id, 
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'asal_sekolah' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
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
            if ($siswa->pas_foto) {
                Storage::disk('public')->delete('pas_foto/' . basename($siswa->pas_foto));
            }
            $validatedData['pas_foto'] = null; // Kosongkan data foto di database
        } 
        // Logika Jika Ada Upload Foto Baru
        elseif ($request->hasFile('pas_foto')) {
            if ($siswa->pas_foto) {
                Storage::disk('public')->delete('pas_foto/' . basename($siswa->pas_foto));
            }
            $file = $request->file('pas_foto');
            $path = $file->store('pas_foto', 'public');
            $validatedData['pas_foto'] = $path;
        }

        $siswa->update($validatedData);

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil diperbarui!');
    }

    // 7. Proses Hapus Data
    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        
        // Hapus foto dari server jika ada
        if ($siswa->pas_foto) {
            Storage::disk('public')->delete('pas_foto/' . basename($siswa->pas_foto));
        }
        
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil dihapus!');
    }

    public function sertifikat($id)
    {
        $data = Siswa::findOrFail($id);
        
        // Jika tanggal selesai kosong, gunakan tanggal hari ini untuk di sertifikat
        $tanggal_cetak = $data->tgl_selesai 
            ? \Carbon\Carbon::parse($data->tgl_selesai)->translatedFormat('d F Y') 
            : \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y');

        return view('sertifikat.index', compact('data', 'tanggal_cetak'));
    }

    public function idCard($id)
    {
        $data = \App\Siswa::findOrFail($id);
        return view('cetak.id-card', compact('data'));
    }

    public function biodata($id)
    {
        $data = \App\Siswa::findOrFail($id);
        return view('cetak.biodata', compact('data'));
    }
}