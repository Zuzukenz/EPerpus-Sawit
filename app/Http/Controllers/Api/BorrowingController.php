<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\BorrowingServiceInterface;
use App\DTOs\BorrowingDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnBorrowingRequest;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Resources\BorrowingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BorrowingController extends Controller
{
    public function __construct(
        private BorrowingServiceInterface $borrowingService
    ) {}

    /**
     * GET /api/v1/borrowings
     * Daftar transaksi peminjaman dengan paginasi
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $borrowings = $this->borrowingService->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'message' => 'Daftar peminjaman berhasil diambil.',
                'data' => BorrowingResource::collection($borrowings),
                'meta' => [
                    'current_page' => $borrowings->currentPage(),
                    'last_page' => $borrowings->lastPage(),
                    'per_page' => $borrowings->perPage(),
                    'total' => $borrowings->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching borrowings: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peminjaman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/v1/borrowings
     * Membuat transaksi peminjaman baru
     */
    public function store(StoreBorrowingRequest $request): JsonResponse
    {
        try {
            $dto = BorrowingDTO::fromRequest($request->validated());
            $borrowing = $this->borrowingService->createBorrowing($dto);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi peminjaman berhasil dibuat.',
                'data' => new BorrowingResource($borrowing)
            ], 201);

        } catch (\App\Exceptions\MemberNotActiveException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);

        } catch (\App\Exceptions\InsufficientStockException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error creating borrowing: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal server.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * GET /api/v1/borrowings/{id}
     * Detail transaksi peminjaman
     */
    public function show(int $id): JsonResponse
    {
        try {
            $borrowing = $this->borrowingService->findById($id);
            
            if (!$borrowing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi peminjaman tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail peminjaman berhasil diambil.',
                'data' => new BorrowingResource($borrowing)
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching borrowing detail: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail peminjaman.',
            ], 500);
        }
    }

    /**
     * PUT /api/v1/borrowings/{id}/return
     * Proses pengembalian buku
     */
    public function returnBook(ReturnBorrowingRequest $request, int $id): JsonResponse
    {
        try {
            $returnDate = new \DateTime($request->validated('tgl_kembali'));
            $result = $this->borrowingService->returnBook($id, $returnDate);

            return response()->json([
                'success' => true,
                'message' => $result['keterangan'],
                'data' => [
                    'transaksi' => new BorrowingResource($result['borrowing']),
                    'denda' => $result['denda'],
                ]
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (\Exception $e) {
            Log::error('Error returning book: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pengembalian.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}