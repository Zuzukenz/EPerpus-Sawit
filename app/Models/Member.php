<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $primaryKey = 'id_anggota';
    public $timestamps = true;

    protected $fillable = [
        'nisn_siswa', 'nama_siswa', 'email_siswa', 
        'alamat', 'no_hp', 'status'
    ];

    protected $casts = [
        'status' => 'boolean', // aktif = true
    ];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class, 'id_anggota', 'id_anggota');
    }
}