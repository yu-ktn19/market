<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DisplayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/register', [UserController::class, 'create']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::get('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', '認証メールを再送信しました');
})->middleware(['auth'])->name('verification.send');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage/profile', [UserController::class, 'edit']);
    Route::post('/mypage/profile', [UserController::class, 'update']);
});

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [UserController::class, 'mypage']);
    Route::post('/item/{item_id}/like', [LikeController::class, 'store']);
    Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy']);
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);
    Route::get('/sell', [DisplayController::class, 'create']);
    Route::post('/sell', [DisplayController::class, 'store']);
});