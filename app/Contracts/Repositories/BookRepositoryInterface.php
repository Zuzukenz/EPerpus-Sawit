<?php

namespace App\Contracts\Repositories;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function findById(int $id): ?Book;
    public function findByIdWithLock(int $id): ?Book;
    public function decrementStock(int $bookId, int $quantity): bool;
    public function incrementStock(int $bookId, int $quantity): bool;
    public function isStockAvailable(int $bookId, int $quantity): bool;
}