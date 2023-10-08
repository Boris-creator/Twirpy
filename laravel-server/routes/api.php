<?php

use App\Http\Controllers\Api;
use App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great! - Yes I will!
|
*/
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'authenticate'])->name('login');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::namespace('App\Http\Controllers\Api')->group(function () {
    Route::apiResource('books', 'BooksController');
    Route::get('books/{id}/download', [Api\BooksController::class, 'download'])->middleware(
        Middleware\EnsureBookIsAccessible::class
    );

    Route::post('books/{id}/buy', [Api\BargainController::class, 'buy'])->middleware(
        Middleware\EnsureBookCanBeBought::class
    );

    Route::apiResource('publishers', Api\PublisherController::class);
    Route::apiResource('comments', Api\CommentController::class);
    Route::apiResource('wishes', Api\WishController::class);
    Route::prefix('offers')->group(function () {
        Route::post('/new', [Api\WishOfferController::class, 'offer']);
    });
})->middleware('auth:sanctum');
