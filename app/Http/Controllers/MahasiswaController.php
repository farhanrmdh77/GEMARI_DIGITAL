<?php

namespace App\Http\Controllers;

use App\Mahasiswa;
use App\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    // 1. Tampilkan Halaman Daftar & Fitur Pencarian
    public function index(Request $request)
    {
        $query = Mahasiswa::orderBy('nama', 'asc');

        // Logika Filter/Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
        }

        $mahasiswa = $query->get();
        $instansis = Instansi::where('jenis', 'Kampus')->get();
        return view('mahasiswa.index', compact('mahasiswa', 'instansis'));
    }

    // 2. Tampilkan Form Tambah
    public function create()
    {
        $instansis = Instansi::where('jenis', 'Kampus')->get();
        return view('mahasiswa.create', compact('instansis'));
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
            'surat_permohonan' => 'nullable|file|mimes:pdf|max:5120',
            'laporan_magang' => 'nullable|file|mimes:pdf|max:10240',
            'jenis_magang' => 'required|in:Reguler,Berbayar',
        ]);

        if ($request->hasFile('pas_foto')) {
            $file = $request->file('pas_foto');
            $path = $file->store('pas_foto', 'public');
            $validatedData['pas_foto'] = $path;
        }

        if ($request->hasFile('surat_permohonan')) {
            $validatedData['surat_permohonan'] = $request->file('surat_permohonan')->store('surat_permohonan', 'public');
        }

        if ($request->hasFile('laporan_magang')) {
            $validatedData['laporan_magang'] = $request->file('laporan_magang')->store('laporan_magang', 'public');
        }

        // Cek Kuota
        $quotaCheck = $this->checkQuota($request->tgl_mulai, $request->tgl_selesai);
        if (!$quotaCheck['status']) {
            return back()->withInput()->with('error', $quotaCheck['message']);
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
        $instansis = Instansi::where('jenis', 'Kampus')->get();
        return view('mahasiswa.edit', compact('mahasiswa', 'instansis'));
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
            'surat_permohonan' => 'nullable|file|mimes:pdf|max:5120',
            'laporan_magang' => 'nullable|file|mimes:pdf|max:10240',
            'jenis_magang' => 'required|in:Reguler,Berbayar',
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

        // Logika Hapus/Upload Surat Permohonan
        if ($request->has('hapus_surat') && $request->hapus_surat == '1') {
            if ($mahasiswa->surat_permohonan) {
                Storage::disk('public')->delete($mahasiswa->surat_permohonan);
            }
            $validatedData['surat_permohonan'] = null;
        } elseif ($request->hasFile('surat_permohonan')) {
            if ($mahasiswa->surat_permohonan) {
                Storage::disk('public')->delete($mahasiswa->surat_permohonan);
            }
            $validatedData['surat_permohonan'] = $request->file('surat_permohonan')->store('surat_permohonan', 'public');
        }

        // Logika Hapus/Upload Laporan Magang
        if ($request->has('hapus_laporan') && $request->hapus_laporan == '1') {
            if ($mahasiswa->laporan_magang) {
                Storage::disk('public')->delete($mahasiswa->laporan_magang);
            }
            $validatedData['laporan_magang'] = null;
        } elseif ($request->hasFile('laporan_magang')) {
            if ($mahasiswa->laporan_magang) {
                Storage::disk('public')->delete($mahasiswa->laporan_magang);
            }
            $validatedData['laporan_magang'] = $request->file('laporan_magang')->store('laporan_magang', 'public');
        }

        // Cek Kuota
        $quotaCheck = $this->checkQuota($request->tgl_mulai, $request->tgl_selesai, $mahasiswa->id, 'mahasiswa');
        if (!$quotaCheck['status']) {
            return back()->withInput()->with('error', $quotaCheck['message']);
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
        
        $user = auth()->user();
        if (!in_array($user->role, ['Superadmin', 'Admin Humas'])) {
            if ($user->role !== 'Mahasiswa' || $user->email !== $data->email) {
                abort(403, 'Anda tidak memiliki akses.');
            }
        }

        // Jika tanggal selesai kosong, gunakan tanggal hari ini untuk di sertifikat
        $tanggal_cetak = $data->tgl_selesai 
            ? \Carbon\Carbon::parse($data->tgl_selesai)->translatedFormat('d F Y') 
            : \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y');

        return view('sertifikat.index', compact('data', 'tanggal_cetak'));
    }

    public function idCard($id)
    {
        $data = \App\Mahasiswa::findOrFail($id);
        
        $user = auth()->user();
        if (!in_array($user->role, ['Superadmin', 'Admin Humas'])) {
            if ($user->role !== 'Mahasiswa' || $user->email !== $data->email) {
                abort(403, 'Anda tidak memiliki akses.');
            }
        }

        return view('cetak.id-card', compact('data'));
    }

    public function biodata($id)
    {
        $data = \App\Mahasiswa::findOrFail($id);
        
        $user = auth()->user();
        if (!in_array($user->role, ['Superadmin', 'Admin Humas'])) {
            if ($user->role !== 'Mahasiswa' || $user->email !== $data->email) {
                abort(403, 'Anda tidak memiliki akses.');
            }
        }

        return view('cetak.biodata', compact('data'));
    }
}