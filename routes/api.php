<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts', [PostController::class, 'index'])->name('post.index');
Route::get('/comments', [CommentController::class, 'index'])->name('comment.index');
Route::post('/comments', [CommentController::class, 'store'])->name('comment.store');
Route::get('/comments/{id}', [CommentController::class, 'show'])->name('comment.show');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
