<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Nantinya kita akan mengirim data statistik (total siswa, mahasiswa, dll) ke view ini
        return view('home'); 
    }
}