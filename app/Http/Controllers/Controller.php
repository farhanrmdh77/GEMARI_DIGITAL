<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Memeriksa ketersediaan kuota (maksimal 20 orang per hari)
     * 
     * @param string $tgl_mulai
     * @param string $tgl_selesai
     * @param int|null $ignore_id ID peserta yang sedang diedit (agar tidak dihitung ganda)
     * @param string|null $ignore_type 'siswa' atau 'mahasiswa'
     * @return array ['status' => boolean, 'message' => string]
     */
    protected function checkQuota($tgl_mulai, $tgl_selesai, $ignore_id = null, $ignore_type = null)
    {
        if (!$tgl_mulai || !$tgl_selesai) {
            return ['status' => true];
        }

        $start = \Carbon\Carbon::parse($tgl_mulai);
        $end = \Carbon\Carbon::parse($tgl_selesai);

        // Ambil semua siswa yang jadwalnya tumpang tindih dengan rentang tanggal yang diajukan
        $siswaQuery = \App\Siswa::whereNotNull('tgl_mulai')
            ->whereNotNull('tgl_selesai')
            ->where('tgl_mulai', '<=', $tgl_selesai)
            ->where('tgl_selesai', '>=', $tgl_mulai);
            
        if ($ignore_type === 'siswa' && $ignore_id) {
            $siswaQuery->where('id', '!=', $ignore_id);
        }
        $siswas = $siswaQuery->get();

        // Ambil semua mahasiswa yang jadwalnya tumpang tindih
        $mahasiswaQuery = \App\Mahasiswa::whereNotNull('tgl_mulai')
            ->whereNotNull('tgl_selesai')
            ->where('tgl_mulai', '<=', $tgl_selesai)
            ->where('tgl_selesai', '>=', $tgl_mulai);
            
        if ($ignore_type === 'mahasiswa' && $ignore_id) {
            $mahasiswaQuery->where('id', '!=', $ignore_id);
        }
        $mahasiswas = $mahasiswaQuery->get();

        // Pengecekan kuota per hari dalam rentang tanggal
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $count = 0;
            $currentDateStr = $date->toDateString();
            
            foreach ($siswas as $s) {
                if ($s->tgl_mulai <= $currentDateStr && $s->tgl_selesai >= $currentDateStr) {
                    $count++;
                }
            }
            foreach ($mahasiswas as $m) {
                if ($m->tgl_mulai <= $currentDateStr && $m->tgl_selesai >= $currentDateStr) {
                    $count++;
                }
            }

            // Jika pada hari tersebut sudah ada 20 orang (dan kita akan menambah 1, berarti bakal jadi 21)
            // Jadi jika $count >= 20, tolak pendaftaran/perubahan
            if ($count >= 20) {
                return [
                    'status' => false,
                    'message' => 'Kuota penuh! Pada tanggal ' . $date->translatedFormat('d F Y') . ' sudah mencapai batas maksimal 20 peserta aktif.'
                ];
            }
        }

        return ['status' => true];
    }
}
