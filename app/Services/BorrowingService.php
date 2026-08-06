<?php

namespace App\Services;

use App\Contracts\Repositories\BookRepositoryInterface;
use App\Contracts\Repositories\BorrowingRepositoryInterface;
use App\Contracts\Repositories\MemberRepositoryInterface;
use App\Contracts\Services\BorrowingServiceInterface;
use App\DTOs\BorrowingDTO;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\MemberNotActiveException;
use App\Models\Borrowing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BorrowingService implements BorrowingServiceInterface
{
    public function __construct(
        private BorrowingRepositoryInterface $borrowingRepo,
        private BookRepositoryInterface $bookRepo,
        private MemberRepositoryInterface $memberRepo
    ) {}

    public function createBorrowing(BorrowingDTO $dto): Borrowing
    {
        return DB::transaction(function () use ($dto) {
            // 1. Validasi Member Aktif
            if (!$this->memberRepo->isActive($dto->memberId)) {
                throw new MemberNotActiveException();
            }

            // 2. Validasi Stok & Kunci (Lock) setiap buku
            $bookData = [];
            foreach ($dto->items as $item) {
                $book = $this->bookRepo->findByIdWithLock($item['id_buku']);
                
                if (!$book) {
                    throw new \InvalidArgumentException("Buku dengan ID {$item['id_buku']} tidak ditemukan.");
                }

                if ($book->stok_buku < $item['jumlah_buku']) {
                    throw new InsufficientStockException(
                        $book->judul_buku,
                        $item['jumlah_buku'],
                        $book->stok_buku
                    );
                }

                $bookData[] = [
                    'id_buku' => $item['id_buku'],
                    'jumlah_buku' => $item['jumlah_buku'],
                    'judul_buku' => $book->judul_buku
                ];
            }

            // 3. Generate Kode Transaksi Unik
            $kodeTransaksi = $this->generateTransactionCode();

            // 4. Buat Nota Peminjaman
            $borrowing = $this->borrowingRepo->create([
                'id_anggota' => $dto->memberId,
                'kode_transaksi' => $kodeTransaksi,
                'tgl_pinjam' => $dto->borrowDate,
                'tenggat_kembali' => $dto->dueDate,
                'status_pinjam' => 'borrowed',
                'denda' => 0,
            ]);

            // 5. Simpan Detail Buku (Pivot)
            $this->borrowingRepo->attachBooks($borrowing->id_pinjam, $bookData);

            // 6. Kurangi Stok Buku
            foreach ($bookData as $book) {
                $this->bookRepo->decrementStock($book['id_buku'], $book['jumlah_buku']);
            }

            return $borrowing->load(['member', 'books']);
        });
    }

    public function returnBook(int $borrowingId, \DateTimeInterface $returnDate): array
    {
        return DB::transaction(function () use ($borrowingId, $returnDate) {
            $borrowing = $this->borrowingRepo->findByIdWithRelations($borrowingId);

            if (!$borrowing) {
                throw new \InvalidArgumentException('Transaksi peminjaman tidak ditemukan.');
            }

            if ($borrowing->status_pinjam === 'returned') {
                throw new \InvalidArgumentException('Buku sudah dikembalikan sebelumnya.');
            }

            if ($borrowing->status_pinjam !== 'borrowed') {
                throw new \InvalidArgumentException('Status transaksi tidak valid untuk pengembalian.');
            }

            // Hitung Denda
            $denda = $this->calculateFine($borrowing->tenggat_kembali, $returnDate);

            // Update status & tanggal kembali
            $borrowing->update([
                'status_pinjam' => 'returned',
                'tgl_kembali_actual' => $returnDate,
                'denda' => $denda,
            ]);

            // Kembalikan stok buku
            foreach ($borrowing->details as $detail) {
                $this->bookRepo->incrementStock($detail->id_buku, $detail->jumlah_buku);
            }

            return [
                'borrowing' => $borrowing->fresh(['member', 'books']),
                'denda' => $denda,
                'keterangan' => $denda > 0 
                    ? 'Pengembalian terlambat, denda dikenakan.' 
                    : 'Pengembalian tepat waktu.',
            ];
        });
    }

    private function calculateFine(\DateTimeInterface $dueDate, \DateTimeInterface $returnDate): int
    {
        if ($returnDate <= $dueDate) {
            return 0;
        }

        $diff = $dueDate->diff($returnDate);
        $daysLate = $diff->days;
        
        // Tarif denda: Rp 2.000 per hari keterlambatan
        $ratePerDay = config('eperpus.denda_per_hari', 2000);
        
        return $daysLate * $ratePerDay;
    }

    private function generateTransactionCode(): string
    {
        $prefix = 'TRX-SAWIT';
        $date = now()->format('Ymd');
        $random = Str::upper(Str::random(4));
        
        return "{$prefix}-{$date}-{$random}";
    }
}