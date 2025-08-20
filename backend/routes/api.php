<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanItemController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/get-user', [AuthController::class, 'getUser']);

    // Authors
    Route::apiResource('authors', AuthorController::class);
    Route::get('/authors/{id}/books', [AuthorController::class, 'books']);

    // Books
    Route::apiResource('books', BookController::class);

    // Members
    Route::apiResource('members', MemberController::class);
    Route::get('/members/{id}/loans', [MemberController::class, 'loans']);

    // Loans
    Route::apiResource('loans', LoanController::class);
    Route::patch('/loans/{id}/complete', [LoanController::class, 'complete']);

    // Loan Items
    Route::apiResource('loan-items', LoanItemController::class);
    Route::patch('/loan-items/{id}/return', [LoanItemController::class, 'return']);
});


