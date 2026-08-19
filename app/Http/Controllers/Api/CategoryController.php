<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::all();
            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil diambil',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kategori',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = Category::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menambahkan kategori',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified category.
     */
    public function show($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kategori tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Detail kategori berhasil diambil',
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail kategori',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kategori tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui kategori',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kategori tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil dihapus',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus kategori',
                'data' => null,
            ], 500);
        }
    }
}
