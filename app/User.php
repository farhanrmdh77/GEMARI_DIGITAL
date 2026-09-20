<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'role',         
        'foto_admin',   
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Dapatkan foto profil pengguna berdasarkan role.
     */
    public function getProfilePhotoAttribute()
    {
        if (in_array($this->role, ['Superadmin', 'Admin Humas'])) {
            return $this->foto_admin;
        } elseif ($this->role === 'Mahasiswa') {
            $mahasiswa = \App\Mahasiswa::where('email', $this->email)->first();
            return $mahasiswa ? $mahasiswa->pas_foto : null;
        } elseif ($this->role === 'Siswa') {
            $siswa = \App\Siswa::where('email', $this->email)->first();
            return $siswa ? $siswa->pas_foto : null;
        }
        
        return null;
    }
}