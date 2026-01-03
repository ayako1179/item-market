<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $order_id)
    {
        $order = Order::with(['buyer', 'seller'])->findOrFail($order_id);
        $user = Auth::user();

        // バリデーション
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // 取引完了チェック
        if ($order->status !== 'completed') {
            abort(403, '取引が完了していません');
        }

        // レビュアー判定（購入者 or 出品者）
        if ($user->id === $order->buyer_id) {
            $reviewerId = $order->buyer_id;
            $reviewedId = $order->seller_id;
        } elseif ($user->id === $order->seller_id) {
            $reviewerId = $order->seller_id;
            $reviewedId = $order->buyer_id;
        } else {
            abort(403, 'この取引の関係者ではありません');
        }

        // 二重評価防止
        $alreadyReviewed = Review::where('order_id', $order->id)
            ->where('reviewer_id', $reviewerId)
            ->exists();

        if ($alreadyReviewed) {
            abort(403, 'すでに評価済みです');
        }

        // 保存
        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $reviewerId,
            'reviewed_id' => $reviewedId,
            'rating' => $request->rating,
            'comment' => $request->comment ?? null,
        ]);

        // 商品一覧へ遷移
        return redirect()->route('home');
    }
}
