<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        // プロフィール画像の処理
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $profile->profile_image = basename($path);
        } elseif (!$profile->profile_image) {
            $defaultPath = 'images/default_profile.png';
            $newFileName = uniqid('default_') . '.png';
            \Storage::disk('public')->put("profile_images/{$newFileName}", file_get_contents(public_path($defaultPath)));
            $profile->profile_image = $newFileName;
        }

        // 他のプロフィール情報を更新
        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building = $request->building;

        // ユーザー名は users テーブルに保存
        $user->name = $request->name;
        $user->save();

        // プロフィールを保存
        $profile->save();

        return redirect()->route('profile.edit')->with('success', 'プロフィールを更新しました');
    }
}
