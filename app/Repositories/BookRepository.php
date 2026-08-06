<?php

namespace App\Repositories;

use App\Contracts\Repositories\BookRepositoryInterface;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BookRepository implements BookRepositoryInterface
{
    public function findById(int $id): ?Book
    {
        return Book::find($id);
    }

    public function findByIdWithLock(int $id): ?Book
    {
        return Book::where('id_buku', $id)->lockForUpdate()->first();
    }

    public function decrementStock(int $bookId, int $quantity): bool
    {
        return Book::where('id_buku', $bookId)
            ->where('stok_buku', '>=', $quantity)
            ->decrement('stok_buku', $quantity);
    }

    public function incrementStock(int $bookId, int $quantity): bool
    {
        return Book::where('id_buku', $bookId)
            ->increment('stok_buku', $quantity);
    }

    public function isStockAvailable(int $bookId, int $quantity): bool
    {
        $book = Book::where('id_buku', $bookId)->first(['stok_buku']);
        return $book && $book->stok_buku >= $quantity;
    }
}