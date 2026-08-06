<?php

namespace App\Contracts\Services;

use App\DTOs\BorrowingDTO;
use App\Models\Borrowing;

interface BorrowingServiceInterface
{
    public function createBorrowing(BorrowingDTO $dto): Borrowing;
    public function returnBook(int $borrowingId, \DateTimeInterface $returnDate): array;
}