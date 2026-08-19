<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        // Get statistics
        $totalBooks = Book::count();
        $totalMembers = Member::count();
        $activeBorrowings = Borrowing::where('status', 'borrowed')->count();
        $totalBorrowings = Borrowing::count();

        // Get low stock books
        $lowStockBooks = Book::where('stock', '<', 5)->get();

        // Get overdue borrowings
        $overdueBorrowings = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now())
            ->with('member')
            ->get();

        // Get recent borrowings
        $recentBorrowings = Borrowing::with(['member', 'books'])
            ->latest('created_at')
            ->limit(5)
            ->get();

        // Get total fine collected
        $totalFine = 0;

        return view('dashboard', compact(
            'totalBooks',
            'totalMembers',
            'activeBorrowings',
            'totalBorrowings',
            'lowStockBooks',
            'overdueBorrowings',
            'recentBorrowings',
            'totalFine'
        ));
    }
}
