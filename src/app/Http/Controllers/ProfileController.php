<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user()->load('profile');
        $page = $request->query('page', 'sell');

        if ($page === 'sell') {
            $products = Item::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        } else {
            $products = Order::where('user_id', $user->id)
                ->with(['item' => function ($query) {
                    $query->orderByDesc('created_at');
                }])
                ->whereHas('item')
                ->get();
        }

        return view('profile.mypage', compact('user', 'products', 'page'));
    }

    public function edit()
    {
        $user = Auth::user()->load('profile');
        $profile = $user->profile ?? new Profile();

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $profile->profile_image = $path;
        } elseif (!$profile->profile_image) {
            $defaultPath = 'images/default_profile.png';
            $newFileName = uniqid('default_') . '.png';
            Storage::disk('public')->put(
                "profile_images/{$newFileName}",
                file_get_contents(public_path($defaultPath))
            );
            $profile->profile_image = $newFileName;
        }

        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building = $request->building;

        $user->name = $request->name;
        $user->save();
        $profile->save();

        Auth::setUser($user->load('profile'));

        return redirect()->route('profile.edit');
    }
}
