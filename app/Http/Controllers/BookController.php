<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of books
     */
    public function index()
    {
        $books = Book::with('category')->paginate(10);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book
     */
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created book in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Display the specified book
     */
    public function show(Book $book)
    {
        $book->load('category');
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book in database
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Remove the specified book from database
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }
}
