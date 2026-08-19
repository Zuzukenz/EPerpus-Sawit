<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    /**
     * Display a listing of borrowings
     */
    public function index()
    {
        $borrowings = Borrowing::with(['member', 'books'])->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new borrowing
     */
    public function create()
    {
        $members = Member::all();
        $books = Book::where('quantity', '>', 0)->get();
        return view('borrowings.create', compact('members', 'books'));
    }

    /**
     * Store a newly created borrowing
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'books' => 'required|array|min:1',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after:borrow_date',
        ]);

        $borrow_date = Carbon::parse($validated['borrow_date']);
        $return_date = Carbon::parse($validated['return_date']);

        // Create borrowing record
        $borrowing = Borrowing::create([
            'member_id' => $validated['member_id'],
            'borrow_date' => $borrow_date,
            'return_date' => $return_date,
            'actual_return_date' => null,
            'status' => 'borrowed',
        ]);

        // Attach books and update quantity
        foreach ($validated['books'] as $bookData) {
            $book = Book::find($bookData['book_id']);
            $quantity = $bookData['quantity'];

            // Check if sufficient quantity available
            if ($book->quantity < $quantity) {
                $borrowing->delete();
                return back()->withErrors(['books' => 'Stok buku tidak cukup!']);
            }

            // Attach to borrowing
            $borrowing->books()->attach($book->id, ['quantity' => $quantity]);

            // Reduce book quantity
            $book->decrement('quantity', $quantity);
        }

        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil dicatat!');
    }

    /**
     * Display the specified borrowing
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['member', 'books']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for returning books
     */
    public function returnForm(Borrowing $borrowing)
    {
        if ($borrowing->status === 'returned') {
            return back()->withErrors(['error' => 'Peminjaman ini sudah dikembalikan!']);
        }
        return view('borrowings.return', compact('borrowing'));
    }

    /**
     * Handle book return
     */
    public function return(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'actual_return_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($borrowing->status === 'returned') {
            return back()->withErrors(['error' => 'Peminjaman ini sudah dikembalikan!']);
        }

        $actual_return_date = Carbon::parse($validated['actual_return_date']);
        $return_date = Carbon::parse($borrowing->return_date);

        // Calculate fine if returned late (Rp 5000 per hari)
        $fine = 0;
        if ($actual_return_date->greaterThan($return_date)) {
            $late_days = $actual_return_date->diffInDays($return_date);
            $fine = $late_days * 5000;
        }

        // Update borrowing
        $borrowing->update([
            'actual_return_date' => $actual_return_date,
            'status' => 'returned',
            'fine' => $fine,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Return books to stock
        foreach ($borrowing->books as $book) {
            $quantity = $book->pivot->quantity;
            $book->increment('quantity', $quantity);
        }

        return redirect()->route('borrowings.index')
            ->with('success', 'Pengembalian berhasil dicatat!' . ($fine > 0 ? ' Denda: Rp ' . number_format($fine, 0, ',', '.') : ''));
    }

    /**
     * Remove the specified borrowing
     */
    public function destroy(Borrowing $borrowing)
    {
        if ($borrowing->status === 'borrowed') {
            return back()->withErrors(['error' => 'Tidak dapat menghapus peminjaman yang belum dikembalikan!']);
        }

        $borrowing->delete();

        return redirect()->route('borrowings.index')
            ->with('success', 'Data peminjaman berhasil dihapus!');
    }
}
