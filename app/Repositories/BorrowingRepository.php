<?php

namespace App\Repositories;

use App\Contracts\Repositories\BorrowingRepositoryInterface;
use App\Models\Borrowing;
use Illuminate\Pagination\LengthAwarePaginator;

class BorrowingRepository implements BorrowingRepositoryInterface
{
    public function create(array $data): Borrowing
    {
        return Borrowing::create($data);
    }

    public function findById(int $id): ?Borrowing
    {
        return Borrowing::find($id);
    }

    public function findByIdWithRelations(int $id): ?Borrowing
    {
        return Borrowing::with(['member', 'books', 'details'])->find($id);
    }

    public function updateStatus(int $id, string $status): bool
    {
        return Borrowing::where('id_pinjam', $id)->update(['status_pinjam' => $status]);
    }

    public function attachBooks(int $borrowingId, array $books): void
    {
        $borrowing = Borrowing::find($borrowingId);
        if ($borrowing) {
            foreach ($books as $book) {
                $borrowing->books()->attach($book['id_buku'], [
                    'jumlah_buku' => $book['jumlah_buku']
                ]);
            }
        }
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Borrowing::with(['member', 'books'])->latest('tgl_pinjam')->paginate($perPage);
    }
}