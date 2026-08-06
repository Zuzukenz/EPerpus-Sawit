<?php

use App\Http\Controllers\Api\BorrowingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    
    // Public routes (tambahkan middleware auth jika perlu)
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show']);
    
    // Protected routes
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    Route::put('/borrowings/{id}/return', [BorrowingController::class, 'returnBook']);
    
});