<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReservationController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profile', [RegisterController::class, 'profile']);
    Route::post('/logout', [RegisterController::class, 'logout']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);



    Route::get('/members', [MemberController::class, 'index']);
    Route::post('/members', [MemberController::class, 'store']);
    Route::get('/members/{id}', [MemberController::class, 'show']);
    Route::put('/members/{id}', [MemberController::class, 'update']);
    Route::delete('/members/{id}', [MemberController::class, 'destroy']);


    // Loans
    Route::post('/loans', [LoanController::class, 'store']);
    Route::get('/loans', [LoanController::class, 'index']);
    Route::put('/loans/{id}/return', [LoanController::class, 'returnBook']);

    // Reservations
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::put('/reservations/{id}/notify', [ReservationController::class, 'notifyMember']);

});
// routes/api.php

use App\Http\Controllers\GenreController;

Route::middleware('auth:sanctum')->group(function () {
    // Genres
    Route::get('/genres', [GenreController::class, 'index']);
    Route::post('/genres', [GenreController::class, 'store']);
    Route::get('/genres/{id}', [GenreController::class, 'show']);
    Route::put('/genres/{id}', [GenreController::class, 'update']);
    Route::delete('/genres/{id}', [GenreController::class, 'destroy']);
});

// routes/api.php

use App\Http\Controllers\ReportController;

Route::middleware('auth:sanctum')->group(function () {
    // Reports
    Route::get('/reports/issued-books', [ReportController::class, 'issuedBooksReport']);
    Route::get('/reports/overdue-books', [ReportController::class, 'overdueBooksReport']);
    Route::get('/reports/fines', [ReportController::class, 'finesReport']);
});



