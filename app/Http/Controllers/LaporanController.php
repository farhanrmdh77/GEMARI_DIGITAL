<?php

namespace App\Http\Controllers;

use App\Siswa;
use App\Mahasiswa;
use App\Setting; // <-- Jangan lupa tambahkan ini
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil inputan filter (dengan nilai bawaan 'semua')
        $kategori = $request->input('kategori', 'semua');
        $status = $request->input('status', 'semua');
        $tgl_mulai = $request->input('tgl_mulai');
        $tgl_akhir = $request->input('tgl_akhir');
        $action = $request->input('action'); // 'preview' atau 'cetak'

        $data = collect();
        $today = Carbon::today()->toDateString();

        // Fungsi bantuan (closure) agar logika filter tidak ditulis berulang-ulang
        $applyFilters = function($query) use ($status, $today, $tgl_mulai, $tgl_akhir) {
            
            // Filter Status
            if ($status == 'aktif') {
                $query->whereNotNull('tgl_mulai')
                      ->whereNotNull('tgl_selesai')
                      ->where('tgl_mulai', '<=', $today)
                      ->where('tgl_selesai', '>=', $today);
            } elseif ($status == 'selesai') {
                $query->where(function($q) use ($today) {
                    $q->where('tgl_selesai', '<', $today)
                      ->orWhereNull('tgl_selesai')
                      ->orWhereNull('tgl_mulai'); 
                });
            }

            // Filter Tanggal
            if ($tgl_mulai) {
                $query->where('tgl_mulai', '>=', $tgl_mulai);
            }
            if ($tgl_akhir) {
                $query->where('tgl_selesai', '<=', $tgl_akhir);
            }
            return $query;
        };

        // Ambil Data Siswa (Jika dipilih 'semua' atau 'siswa')
        if ($kategori == 'semua' || $kategori == 'siswa') {
            $siswa = $applyFilters(Siswa::query())->get()->map(function($item) {
                // Menyeragamkan nama atribut agar mudah di-looping di satu tabel yang sama
                $item->kategori_peserta = 'Siswa SMK';
                $item->identitas = $item->nis;
                $item->instansi = $item->asal_sekolah;
                return $item;
            });
            $data = $data->concat($siswa);
        }

        // Ambil Data Mahasiswa (Jika dipilih 'semua' atau 'mahasiswa')
        if ($kategori == 'semua' || $kategori == 'mahasiswa') {
            $mahasiswa = $applyFilters(Mahasiswa::query())->get()->map(function($item) {
                $item->kategori_peserta = 'Mahasiswa';
                $item->identitas = $item->nim;
                $item->instansi = $item->asal_kampus;
                return $item;
            });
            $data = $data->concat($mahasiswa);
        }

        // Urutkan berdasarkan tanggal terbaru
        $data = $data->sortByDesc('created_at')->values();

        // Jika tombol cetak ditekan, arahkan ke halaman cetak khusus
        if ($action == 'cetak') {
            // Ambil data pengaturan untuk Kop Surat dan Penandatangan
            $setting = Setting::first(); 
            
            // Tambahkan $setting ke dalam fungsi compact()
            return view('laporan.cetak', compact('data', 'kategori', 'status', 'tgl_mulai', 'tgl_akhir', 'setting'));
        }

        // Jika hanya preview atau muat awal, tampilkan di index
        return view('laporan.index', compact('data'));
    }
}