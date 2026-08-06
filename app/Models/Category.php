<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id_kategori';
    public $timestamps = true;

    protected $fillable = ['nama_kategori', 'slug_kategori'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'id_kategori', 'id_kategori');
    }
}