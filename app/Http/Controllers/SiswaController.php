<?php

namespace App\Http\Controllers;

use App\Siswa;
use App\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    // 1. Tampilkan Halaman Daftar & Fitur Pencarian
    public function index(Request $request)
    {
        $query = Siswa::orderBy('nama', 'asc');

        // Logika Filter/Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
        }

        $siswa = $query->get();
        $instansis = Instansi::where('jenis', 'Sekolah')->get();
        return view('siswa.index', compact('siswa', 'instansis'));
    }

    // 2. Tampilkan Form Tambah
    public function create()
    {
        $instansis = Instansi::where('jenis', 'Sekolah')->get();
        return view('siswa.create', compact('instansis'));
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
            'jenis_magang' => 'required|in:Reguler,Berbayar',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'pembimbing' => 'nullable|string|max:255',
            'rekam_jejak' => 'nullable|string',
            'catatan_khusus' => 'nullable|string',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'surat_permohonan' => 'nullable|file|mimes:pdf|max:5120',
            'laporan_magang' => 'nullable|file|mimes:pdf|max:10240',
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
        $instansis = Instansi::where('jenis', 'Sekolah')->get();
        return view('siswa.edit', compact('siswa', 'instansis'));
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
            'jenis_magang' => 'required|in:Reguler,Berbayar',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'pembimbing' => 'nullable|string|max:255',
            'rekam_jejak' => 'nullable|string',
            'catatan_khusus' => 'nullable|string',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'surat_permohonan' => 'nullable|file|mimes:pdf|max:5120',
            'laporan_magang' => 'nullable|file|mimes:pdf|max:10240',
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

        // Logika Hapus/Upload Surat Permohonan
        if ($request->has('hapus_surat') && $request->hapus_surat == '1') {
            if ($siswa->surat_permohonan) {
                Storage::disk('public')->delete($siswa->surat_permohonan);
            }
            $validatedData['surat_permohonan'] = null;
        } elseif ($request->hasFile('surat_permohonan')) {
            if ($siswa->surat_permohonan) {
                Storage::disk('public')->delete($siswa->surat_permohonan);
            }
            $validatedData['surat_permohonan'] = $request->file('surat_permohonan')->store('surat_permohonan', 'public');
        }

        // Logika Hapus/Upload Laporan Magang
        if ($request->has('hapus_laporan') && $request->hapus_laporan == '1') {
            if ($siswa->laporan_magang) {
                Storage::disk('public')->delete($siswa->laporan_magang);
            }
            $validatedData['laporan_magang'] = null;
        } elseif ($request->hasFile('laporan_magang')) {
            if ($siswa->laporan_magang) {
                Storage::disk('public')->delete($siswa->laporan_magang);
            }
            $validatedData['laporan_magang'] = $request->file('laporan_magang')->store('laporan_magang', 'public');
        }

        // Cek Kuota
        $quotaCheck = $this->checkQuota($request->tgl_mulai, $request->tgl_selesai, $siswa->id, 'siswa');
        if (!$quotaCheck['status']) {
            return back()->withInput()->with('error', $quotaCheck['message']);
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
        
        $user = auth()->user();
        if (!in_array($user->role, ['Superadmin', 'Admin Humas'])) {
            if ($user->role !== 'Siswa' || $user->email !== $data->email) {
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
        $data = \App\Siswa::findOrFail($id);
        
        $user = auth()->user();
        if (!in_array($user->role, ['Superadmin', 'Admin Humas'])) {
            if ($user->role !== 'Siswa' || $user->email !== $data->email) {
                abort(403, 'Anda tidak memiliki akses.');
            }
        }

        return view('cetak.id-card', compact('data'));
    }

    public function biodata($id)
    {
        $data = \App\Siswa::findOrFail($id);
        
        $user = auth()->user();
        if (!in_array($user->role, ['Superadmin', 'Admin Humas'])) {
            if ($user->role !== 'Siswa' || $user->email !== $data->email) {
                abort(403, 'Anda tidak memiliki akses.');
            }
        }

        return view('cetak.biodata', compact('data'));
    }
}