<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(): JsonResponse
    {
        try {
            $books = Book::with('category')->get();
            return response()->json([
                'status' => true,
                'message' => 'Data buku berhasil diambil',
                'data' => BookResource::collection($books),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data buku',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $book = Book::create([
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'author' => $validated['author'],
                'publisher' => $validated['publisher'],
                'year' => $validated['published_year'],
                'quantity' => $validated['stock'],
            ]);

            $book->load('category');

            return response()->json([
                'status' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => new BookResource($book),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menambahkan buku',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified book.
     */
    public function show($id): JsonResponse
    {
        try {
            $book = Book::with('category')->find($id);

            if (!$book) {
                return response()->json([
                    'status' => false,
                    'message' => 'Buku tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Detail buku berhasil diambil',
                'data' => new BookResource($book),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail buku',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Update the specified book in storage.
     */
    public function update(UpdateBookRequest $request, $id): JsonResponse
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return response()->json([
                    'status' => false,
                    'message' => 'Buku tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            $validated = $request->validated();

            $book->update([
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'author' => $validated['author'],
                'publisher' => $validated['publisher'],
                'year' => $validated['published_year'],
                'quantity' => $validated['stock'],
            ]);

            $book->load('category');

            return response()->json([
                'status' => true,
                'message' => 'Buku berhasil diperbarui',
                'data' => new BookResource($book),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui buku',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return response()->json([
                    'status' => false,
                    'message' => 'Buku tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            $book->delete();

            return response()->json([
                'status' => true,
                'message' => 'Buku berhasil dihapus',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus buku',
                'data' => null,
            ], 500);
        }
    }
}
