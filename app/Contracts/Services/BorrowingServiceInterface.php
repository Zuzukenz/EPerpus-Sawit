<?php

namespace App\Contracts\Repositories;

use App\Models\Borrowing;
use Illuminate\Pagination\LengthAwarePaginator;

interface BorrowingRepositoryInterface
{
    public function create(array $data): Borrowing;
    public function findById(int $id): ?Borrowing;
    public function findByIdWithRelations(int $id): ?Borrowing;
    public function updateStatus(int $id, string $status): bool;
    public function attachBooks(int $borrowingId, array $books): void;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
}