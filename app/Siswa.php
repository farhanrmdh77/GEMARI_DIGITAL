<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    // Mengizinkan semua kolom untuk diisi (Mass Assignment)
    protected $guarded = []; 
}