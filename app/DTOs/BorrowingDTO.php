<?php

namespace App\DTOs;

class BorrowingDTO
{
    public function __construct(
        public readonly int $memberId,
        public readonly array $items, // [['book_id' => 1, 'quantity' => 2], ...]
        public readonly ?\DateTimeInterface $borrowDate = null,
        public readonly ?\DateTimeInterface $dueDate = null
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            memberId: $validated['id_anggota'],
            items: $validated['items'],
            borrowDate: isset($validated['tgl_pinjam']) 
                ? new \DateTime($validated['tgl_pinjam']) 
                : now(),
            dueDate: isset($validated['tenggat_kembali']) 
                ? new \DateTime($validated['tenggat_kembali']) 
                : now()->addDays(7)
        );
    }
}