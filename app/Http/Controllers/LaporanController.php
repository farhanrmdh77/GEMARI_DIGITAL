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
            if ($tgl_mulai && $tgl_akhir) {
                $query->where('tgl_mulai', '<=', $tgl_akhir)
                      ->where('tgl_selesai', '>=', $tgl_mulai);
            } elseif ($tgl_mulai) {
                $query->where('tgl_selesai', '>=', $tgl_mulai);
            } elseif ($tgl_akhir) {
                $query->where('tgl_mulai', '<=', $tgl_akhir);
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

    public function calendarData(Request $request)
    {
        $startStr = $request->input('start'); // dari FullCalendar (ISO8601)
        $endStr = $request->input('end');     // dari FullCalendar (ISO8601)
        
        $kategori = $request->input('kategori', 'semua');
        $status = $request->input('status', 'semua');
        $tgl_mulai = $request->input('tgl_mulai');
        $tgl_akhir = $request->input('tgl_akhir');
        
        if (!$startStr || !$endStr) {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } else {
            $start = Carbon::parse($startStr);
            $end = Carbon::parse($endStr);
        }

        $today = Carbon::today()->toDateString();

        // Fungsi bantuan filter untuk calendar
        $applyFilters = function($query) use ($status, $today, $tgl_mulai, $tgl_akhir, $start, $end) {
            // Filter rentang waktu yang terlihat di kalender
            $query->whereNotNull('tgl_mulai')
                  ->whereNotNull('tgl_selesai')
                  ->where('tgl_mulai', '<=', $end->toDateString())
                  ->where('tgl_selesai', '>=', $start->toDateString());

            // Filter Status
            if ($status == 'aktif') {
                $query->where('tgl_mulai', '<=', $today)
                      ->where('tgl_selesai', '>=', $today);
            } elseif ($status == 'selesai') {
                $query->where('tgl_selesai', '<', $today);
            }

            // Filter Tanggal Manual
            if ($tgl_mulai && $tgl_akhir) {
                $query->where('tgl_mulai', '<=', $tgl_akhir)
                      ->where('tgl_selesai', '>=', $tgl_mulai);
            } elseif ($tgl_mulai) {
                $query->where('tgl_selesai', '>=', $tgl_mulai);
            } elseif ($tgl_akhir) {
                $query->where('tgl_mulai', '<=', $tgl_akhir);
            }
            return $query;
        };

        $siswas = collect();
        if ($kategori == 'semua' || $kategori == 'siswa') {
            $siswas = $applyFilters(Siswa::query())
                ->get()->map(function($item) {
                    $item->kategori = 'Siswa';
                    $item->institusi = $item->asal_sekolah;
                    return $item;
                });
        }

        $mahasiswas = collect();
        if ($kategori == 'semua' || $kategori == 'mahasiswa') {
            $mahasiswas = $applyFilters(Mahasiswa::query())
                ->get()->map(function($item) {
                    $item->kategori = 'Mahasiswa';
                    $item->institusi = $item->asal_kampus;
                    return $item;
                });
        }

        $allData = $siswas->concat($mahasiswas);
        $events = [];
        
        $today = Carbon::today()->toDateString();
        
        // Kelompokkan data berdasarkan tanggal mulai dan kategori
        $groupedData = $allData->groupBy(function($item) {
            return $item->tgl_mulai . '_' . $item->kategori;
        });

        foreach ($groupedData as $key => $pesertaHariIni) {
            $parts = explode('_', $key);
            $dateStr = $parts[0];
            $kategoriGroup = $parts[1]; // 'Siswa' atau 'Mahasiswa'

            $count = count($pesertaHariIni);
            $hasAktif = false;
            $hasPending = false;
            
            $pesertaFormatted = [];

            foreach ($pesertaHariIni as $p) {
                // Logika Warna Status berdasarkan hari ini (today)
                if ($today < $p->tgl_mulai) {
                    $status = 'Pending';
                    $hasPending = true;
                } elseif ($today >= $p->tgl_mulai && $today <= $p->tgl_selesai) {
                    $status = 'Aktif';
                    $hasAktif = true;
                } else {
                    $status = 'Selesai';
                }

                $pesertaFormatted[] = [
                    'nama' => $p->nama,
                    'kategori' => $p->kategori,
                    'institusi' => $p->institusi,
                    'unit' => $p->unit_penempatan,
                    'periode' => Carbon::parse($p->tgl_mulai)->format('d M Y') . ' - ' . Carbon::parse($p->tgl_selesai)->format('d M Y'),
                    'status' => $status
                ];
            }

            // Warna titik penanda prioritas:
            // Mahasiswa = Biru/Cyan
            // Siswa = Ungu/Pink
            // Tapi untuk tetap bedakan status aktif/selesai:
            if ($kategoriGroup == 'Mahasiswa') {
                if ($hasAktif) {
                    $color = '#3b82f6'; // Biru aktif
                } elseif ($hasPending) {
                    $color = '#93c5fd'; // Biru muda pending
                } else {
                    $color = '#94a3b8'; // Abu-abu selesai
                }
            } else {
                if ($hasAktif) {
                    $color = '#8b5cf6'; // Ungu aktif
                } elseif ($hasPending) {
                    $color = '#c4b5fd'; // Ungu muda pending
                } else {
                    $color = '#94a3b8'; // Abu-abu selesai
                }
            }

            $events[] = [
                'id' => 'mulai_' . $key,
                'title' => $count . ' ' . $kategoriGroup,
                'start' => $dateStr,
                'color' => $color,
                'extendedProps' => [
                    'peserta' => $pesertaFormatted,
                    'tanggal_format' => Carbon::parse($dateStr)->translatedFormat('d F Y'),
                    'count' => $count,
                    'kategori' => $kategoriGroup
                ]
            ];
        }

        return response()->json($events);
    }
}