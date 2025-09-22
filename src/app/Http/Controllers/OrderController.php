<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //購入画面の表示
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('orders.purchase', compact('item','user'));
    }
}
