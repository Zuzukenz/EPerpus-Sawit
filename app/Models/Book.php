<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $primaryKey = 'id_buku';
    public $timestamps = true;

    protected $fillable = [
        'id_kategori', 'judul_buku', 'penulis', 
        'penerbit', 'tahun_terbit', 'stok_buku'
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'stok_buku' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id_kategori');
    }

    public function borrowings(): BelongsToMany
    {
        return $this->belongsToMany(
            Borrowing::class,
            'borrowing_details',
            'id_buku',
            'id_pinjam'
        )->withPivot('jumlah_buku')->withTimestamps();
    }
}