<?php

namespace App\Http\Controllers;

use App\Siswa;
use App\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'Siswa') {
            $data = Siswa::where('email', $user->email)->first();
            return view('user.home', compact('data', 'user'));
        } elseif ($user->role === 'Mahasiswa') {
            $data = Mahasiswa::where('email', $user->email)->first();
            return view('user.home', compact('data', 'user'));
        }

        // 1. Ubah Hari Ini menjadi ANGKA MURNI (Timestamp)
        $todayTs = strtotime(Carbon::now('Asia/Jakarta')->format('Y-m-d'));

        // 2. Tarik semua data dari database
        $siswas = Siswa::all();
        $mahasiswas = Mahasiswa::all();
        $semua_peserta = $siswas->concat($mahasiswas);
        
        $total_terdaftar = $semua_peserta->count();

        // 3. Siapkan keranjang hitungan dari 0
        $sedang_aktif = 0;
        $akan_masuk = 0;
        $selesai_magang = 0;
        
        $total_laki = 0;
        $total_perempuan = 0;

        // ====================================================================
        // 4. LOGIKA PERHITUNGAN
        // ====================================================================
        foreach ($semua_peserta as $p) {
            
            // ---> KUNCI PERUBAHAN: HITUNG GENDER UNTUK SEMUA ORANG <---
            // Sekarang gender dihitung di luar cek status, jadi semua pasti terhitung
            $jk = strtoupper(trim($p->jenis_kelamin));
            if (substr($jk, 0, 1) == 'L') {
                $total_laki++;
            } elseif (substr($jk, 0, 1) == 'P') {
                $total_perempuan++;
            }

            // Lanjut ke pengecekan status tanggal
            $mulaiRaw = $p->tgl_mulai;
            $selesaiRaw = $p->tgl_selesai;
            
            if (empty($mulaiRaw) || empty($selesaiRaw) || $mulaiRaw == '0000-00-00' || $selesaiRaw == '0000-00-00') {
                $selesai_magang++;
                continue; 
            }

            try {
                $mulaiTs = strtotime(Carbon::parse($mulaiRaw)->format('Y-m-d'));
                $selesaiTs = strtotime(Carbon::parse($selesaiRaw)->format('Y-m-d'));
            } catch (\Exception $e) {
                $selesai_magang++;
                continue;
            }

            if ($mulaiTs > $todayTs) {
                $akan_masuk++;
            } elseif ($mulaiTs <= $todayTs && $selesaiTs >= $todayTs) {
                $sedang_aktif++;
            } else {
                $selesai_magang++;
            }
        }

        // ==============================================
        // 5. Data Chart Instansi Top 5
        // ==============================================
        $instansi_siswa = Siswa::select('asal_sekolah as instansi', DB::raw('count(*) as total'))->groupBy('asal_sekolah')->get();
        $instansi_mhs = Mahasiswa::select('asal_kampus as instansi', DB::raw('count(*) as total'))->groupBy('asal_kampus')->get();
        
        $top_instansi = $instansi_siswa->concat($instansi_mhs)
            ->groupBy('instansi')
            ->map(function ($row) { return $row->sum('total'); })
            ->sortDesc()
            ->take(5);

        $instansi_labels = $top_instansi->keys()->toArray() ?: ['Belum ada data'];
        $instansi_data = $top_instansi->values()->toArray() ?: [0];

        return view('home', compact(
            'total_terdaftar', 'sedang_aktif', 'akan_masuk', 'selesai_magang',
            'total_laki', 'total_perempuan',
            'instansi_labels', 'instansi_data'
        ));
    }

    public function viewDokumen($path)
    {
        // Hilangkan slash di awal/akhir jika ada
        $path = trim($path, '/');
        
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return response()->file($fullPath);
    }
}