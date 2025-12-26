<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $keyword = $request->query('keyword');

        if ($tab === 'mylist') {
            if (!auth()->check()) {
                $items = collect();
            } else {
                $query = auth()->user()
                    ->likedItems()
                    ->where('items.user_id', '!=', auth()->id());

                if ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                }

                $items = $query->get();
            }
        } else {
            $query = Item::query();

            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }

            $items = $query->latest()->get();
        }

        return view('items.index', compact('items', 'tab', 'keyword'));
    }

    public function show($id)
    {
        $item = Item::with([
            'categories',
            'condition',
            'comments.user',
            'likedBy'
        ])->findOrFail($id);

        $hasLiked = $item->likedBy->contains('id', Auth::id());

        return view('items.show', compact('item', 'hasLiked'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.sell', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user_id = Auth::id();
        $imagePath = $request->file('image')->store('products', 'public');

        $item = new Item();
        $item->user_id = $user_id;
        $item->name = $request->name;
        $item->brand_name = $request->brand_name;
        $item->price = $request->price;
        $item->description = $request->description;
        $item->image_path = $imagePath;
        $item->condition_id = $request->condition_id;
        $item->save();

        $item->categories()->sync($request->categories);

        return redirect()->route('profile.mypage');
    }
}
