<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal saat domain diakses
Route::get('/', function () {
    // Sebagai aplikasi admin, lebih praktis jika diarahkan langsung ke halaman login
    return redirect()->route('login');
});

// ==========================================================
// RUTE GUEST (Hanya bisa diakses jika belum login)
// ==========================================================
Route::middleware('guest')->group(function () {
    // Menampilkan halaman dan proses Login
    Route::get('/login', 'AuthController@showLogin')->name('login');
    Route::post('/login', 'AuthController@login');
    
    // Rute register dimatikan (dipindah ke User Management)

    // Password Reset OTP Routes
    Route::get('password/reset', 'AuthController@showEmailForm')->name('password.request');
    Route::post('password/email', 'AuthController@sendOtp')->name('password.email')->middleware('throttle:5,1');
    Route::get('password/otp', 'AuthController@showOtpForm')->name('password.otp');
    Route::post('password/reset', 'AuthController@resetWithOtp')->name('password.update');
});


// ==========================================================
// RUTE TERPROTEKSI (Hanya bisa diakses jika SUDAH login)
// ==========================================================
Route::middleware('auth')->group(function () {
    
    // Proses Logout
    Route::post('/logout', 'AuthController@logout')->name('logout');
    
    // Dashboard Utama
    Route::get('/home', 'HomeController@index')->name('home');
    
    // --- MANAJEMEN DATA SISWA (Khusus Admin) ---
    Route::middleware('role:Superadmin,Admin Humas')->group(function () {
        Route::get('/siswa', 'SiswaController@index')->name('siswa.index');
        Route::get('/siswa/tambah', 'SiswaController@create')->name('siswa.create'); 
        Route::post('/siswa', 'SiswaController@store')->name('siswa.store'); 
        Route::get('/siswa/{id}/edit', 'SiswaController@edit')->name('siswa.edit'); 
        Route::put('/siswa/{id}', 'SiswaController@update')->name('siswa.update'); 
        Route::delete('/siswa/{id}', 'SiswaController@destroy')->name('siswa.destroy'); 
        Route::get('/siswa/{id}', 'SiswaController@show')->name('siswa.show');
        
        // --- MANAJEMEN DATA MAHASISWA ---
        Route::get('/mahasiswa', 'MahasiswaController@index')->name('mahasiswa.index');
        Route::get('/mahasiswa/tambah', 'MahasiswaController@create')->name('mahasiswa.create'); 
        Route::post('/mahasiswa', 'MahasiswaController@store')->name('mahasiswa.store'); 
        Route::get('/mahasiswa/{id}/edit', 'MahasiswaController@edit')->name('mahasiswa.edit'); 
        Route::put('/mahasiswa/{id}', 'MahasiswaController@update')->name('mahasiswa.update'); 
        Route::delete('/mahasiswa/{id}', 'MahasiswaController@destroy')->name('mahasiswa.destroy'); 
        Route::get('/mahasiswa/{id}', 'MahasiswaController@show')->name('mahasiswa.show');

        // --- MANAJEMEN AKUN (USER) ---
        Route::resource('users', 'UserController');

        // --- MANAJEMEN LAPORAN ---
        Route::get('/laporan', 'LaporanController@index')->name('laporan.index');
        Route::get('/laporan/kalender-data', 'LaporanController@calendarData')->name('laporan.calendar_data');

        // --- PENGATURAN PROFIL & SANDI ---
        Route::get('/pengaturan', 'PengaturanController@index')->name('pengaturan.index');
        Route::post('/pengaturan/profil', 'PengaturanController@updateProfile')->name('pengaturan.profil');
        Route::post('/pengaturan/password', 'PengaturanController@updatePassword')->name('pengaturan.password');
    });

    // Route Cetak Sertifikat, ID Card, dan Biodata (Bisa diakses oleh admin dan peserta ybs)
    Route::get('/siswa/{id}/sertifikat', 'SiswaController@sertifikat')->name('siswa.sertifikat');
    Route::get('/siswa/{id}/id-card', 'SiswaController@idCard')->name('siswa.id_card');
    Route::get('/siswa/{id}/biodata', 'SiswaController@biodata')->name('siswa.biodata');
    Route::get('/mahasiswa/{id}/sertifikat', 'MahasiswaController@sertifikat')->name('mahasiswa.sertifikat');
    Route::get('/mahasiswa/{id}/id-card', 'MahasiswaController@idCard')->name('mahasiswa.id_card');
    Route::get('/mahasiswa/{id}/biodata', 'MahasiswaController@biodata')->name('mahasiswa.biodata');

    // --- KHUSUS SUPERADMIN ---
    Route::middleware('role:Superadmin')->group(function () {
        // --- PENGATURAN SISTEM & INSTANSI ---
        Route::post('/pengaturan/instansi', 'PengaturanController@updateInstansi')->name('pengaturan.instansi');

        // --- MANAJEMEN MASTER DATA INSTANSI ---
        Route::post('/instansi', 'InstansiController@store')->name('instansi.store');
        Route::delete('/instansi/{id}', 'InstansiController@destroy')->name('instansi.destroy');
    });

    // --- UPLOAD DOKUMEN OLEH USER ---
    Route::middleware('role:Siswa,Mahasiswa')->group(function () {
        Route::post('/user/upload-dokumen', 'UserController@uploadDokumen')->name('user.upload_dokumen');
    });
    
    // --- VIEW DOKUMEN SECARA AMAN ---
    Route::get('/view-dokumen/{path}', 'HomeController@viewDokumen')
        ->where('path', '.*')
        ->name('dokumen.view');
});