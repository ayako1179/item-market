<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // ?tab=xxx を取得。デフォルトは"recommend"
        $tab = $request->query('tab', 'recommend');

        // 検索キーワード（商品名で部分一致）
        $keyword = $request->query('keyword');

        if ($tab === 'mylist') {
            // マイリストはログイン必須
            if (!auth()->check()) {
                // 未ログインなら空コレクション
                $items = collect();
            } else {
                // 出品者リレーションも読み込む
                $query = auth()->user()->likedItems();

                if ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                }

                $items = $query->get();
            }

        } else {
            // おすすめ商品の取得（全商品一覧）
            $query = Item::query();
            // 出品者リレーションを事前に読み込む

            // 自分が出品した商品は除外
            if(auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

            // 検索条件
            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }

            $items = $query->latest()->get();
        }

        return view('items.index', compact('items', 'tab', 'keyword'));
    }

    public function show($id)
    {
        $item = Item::with(['categories', 'condition', 'comments.user', 'likedBy'])->findOrFail($id);

        $item->load('likedBy');

        // ログイン中ユーザーが「いいね」しているか判定
        $hasLiked = $item->likedBy->contains('id', Auth::id());

        return view('items.show', compact('item', 'hasLiked'));
    }

    public function create()
    {
        return view('items.sell');
    }
}
