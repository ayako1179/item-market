<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');
Route::get('/purchase/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('items.comment.store');
    Route::post('/item/like/{item_id}', [LikeController::class, 'store'])->name('items.like.store');
    Route::post('/item/unlike/{item_id}', [LikeController::class, 'destroy'])->name('items.like.destroy');
    Route::get('/purchase/{item_id}', [OrderController::class, 'create'])->name('orders.purchase');
    Route::post('/purchase/{item_id}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/purchase/address/{item_id}', [OrderController::class, 'edit'])->name('orders.address');
    Route::post('/purchase/address/{item_id}', [OrderController::class, 'update'])->name('orders.address.update');
    Route::get('/purchase/stripe/success/{item_id}', [OrderController::class, 'stripeSuccess'])->name('orders.stripeSuccess');
    Route::get('/purchase/stripe/cancel/{item_id}', [OrderController::class, 'stripeCancel'])->name('orders.stripeCancel');
    Route::get('/sell', [ItemController::class, 'create'])->name('items.sell');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
    Route::get('/mypage', [ProfileController::class, 'index'])->name('profile.mypage');

    // 取引チャット画面
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');

    // 取引完了（購入者のみ）
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('trading.complete');

    // メッセージ操作（画面遷移なし）
    Route::post('/chats/{chat_id}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::put('/messages/{message_id}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message_id}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // 評価機能（モーダル送信）
    Route::post('/orders/{order_id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
