<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Books management
    Route::resource('books', BookController::class);

    // Members management
    Route::resource('members', MemberController::class);

    // Borrowings management
    Route::resource('borrowings', BorrowingController::class);
    Route::get('borrowings/{borrowing}/return-form', [BorrowingController::class, 'returnForm'])->name('borrowings.returnForm');
    Route::put('borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');
});
