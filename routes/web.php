<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/auth/facebook/redirect', [LoginController::class, 'redirectToProvider'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [LoginController::class, 'handleProviderCallback']);
Route::post('/comment/{mediaId}', [CommentController::class, 'store'])->name('comment.store');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::view('/privacy', 'privacy');
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $profile = session('profile');
        $media = session('media');
        return view('dashboard', compact('profile', 'media'));
    });
});
