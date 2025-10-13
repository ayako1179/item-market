<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $validated = $request->validated();

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('items.show', $item_id)->with('success', 'コメントを投稿しました');
    }
}
