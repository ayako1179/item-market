<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

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

// 未認証でも閲覧可能
Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::get('/purchase/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

// 認証必須
Route::middleware('auth')->group(function () {

    // コメント
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('items.comment.store');

    // いいね
    Route::post('/item/like/{item_id}', [LikeController::class, 'store'])->name('items.like.store');
    Route::post('/item/unlike/{item_id}', [LikeController::class, 'destroy'])->name('items.like.destroy');

    // 購入関連
    Route::get('/purchase/{item_id}', [OrderController::class, 'create'])->name('orders.purchase');
    Route::post('/purchase/{item_id}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/purchase/address/{item_id}', [OrderController::class, 'edit'])->name('orders.address');
    Route::post('/purchase/address/{item_id}', [OrderController::class, 'update'])->name('orders.address.update');

    // stripeの処理
    Route::get('/purchase/success/{item_id}', [OrderController::class, 'success'])->name('orders.success');

    // 出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.sell');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // プロフィール関連
    Route::get('/mypage', [ProfileController::class, 'index'])->name('profile.mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Route::get('/', function () {
//     return view('welcome');
// });
