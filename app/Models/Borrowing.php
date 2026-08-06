<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Borrowing extends Model
{
    protected $table = 'borrowings';
    protected $primaryKey = 'id_pinjam';
    public $timestamps = true;

    protected $fillable = [
        'id_anggota', 'kode_transaksi', 'tgl_pinjam', 
        'tenggat_kembali', 'status_pinjam', 'tgl_kembali_actual', 'denda'
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tenggat_kembali' => 'date',
        'tgl_kembali_actual' => 'date',
        'denda' => 'integer',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_anggota', 'id_anggota');
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(
            Book::class,
            'borrowing_details',
            'id_pinjam',
            'id_buku'
        )->withPivot('jumlah_buku')->withTimestamps();
    }

    public function details()
    {
        return $this->hasMany(BorrowingDetail::class, 'id_pinjam', 'id_pinjam');
    }
}