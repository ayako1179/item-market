<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    public function store($id)
    {
        DB::table('likes')->updateOrInsert(
            [
                'user_id' => Auth::id(),
                'item_id' => $id,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
            );

            return redirect()->route('items.show', $id);
    }

    public function destroy($id)
    {
        DB::table('likes')
            ->where('user_id', Auth::id())
            ->where('item_id', $id)
            ->delete();

        return redirect()->route('items.show', $id);
    }
}
