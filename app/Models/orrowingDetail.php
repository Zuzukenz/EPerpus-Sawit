<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingDetail extends Model
{
    protected $table = 'borrowing_details';
    protected $primaryKey = 'id_detail';
    public $timestamps = true;

    protected $fillable = ['id_pinjam', 'id_buku', 'jumlah_buku'];

    protected $casts = [
        'jumlah_buku' => 'integer',
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'id_pinjam', 'id_pinjam');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'id_buku', 'id_buku');
    }
}