<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route for displaying a list of books
Route::get('/books', [BookController::class, 'index'])->name('books.index');

// Route for showing a form to create a new book
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');

// Route for storing a newly created book
Route::post('/books', [BookController::class, 'store'])->name('books.store');

// Route for displaying a specific book
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Route for showing a form to edit a specific book
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');

// Route for updating a specific book
Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');

// Route for deleting a specific book
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');