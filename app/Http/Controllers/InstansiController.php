<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Instansi;

class InstansiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'jenis' => 'required|in:Sekolah,Kampus',
        ]);

        Instansi::create([
            'nama_instansi' => $request->nama_instansi,
            'jenis' => $request->jenis,
        ]);

        return back()->with('success', 'Instansi berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $instansi = Instansi::findOrFail($id);
        $instansi->delete();

        return back()->with('success', 'Instansi berhasil dihapus!');
    }
}
