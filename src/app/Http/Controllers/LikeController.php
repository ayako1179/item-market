<?php

namespace App\Http\Controllers;

class LikeController extends Controller
{
    public function store($id)
    {
        auth()->user()->like($id);

        return redirect()->route('items.show', $id);
    }

    public function destroy($id)
    {
        auth()->user()->unlike($id);

        return redirect()->route('items.show', $id);
    }
}
