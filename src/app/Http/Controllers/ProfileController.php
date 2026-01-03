<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user()->load('profile');
        $page = $request->query('page', 'sell');

        $tradingOrders = Order::where('status', 'in_progress')
            ->where(function ($query) use ($user) {
                $query
                    ->where('user_id', $user->id)
                    ->orWhereHas('item', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            })
            ->with(['chat.messages.reads'])
            ->get();

        $tradingUnreadTotal = 0;

        foreach ($tradingOrders as $order) {
            if ($order->chat) {
                $order->chat->unread_count =
                    $order->chat->unreadMessagesCountFor($user);

                $tradingUnreadTotal += $order->chat->unread_count;
            }
        }

        // 出品した商品
        if ($page === 'sell') {
            $products = Item::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();

            return view('profile.mypage', compact('user', 'products', 'page', 'tradingUnreadTotal'));
        }

        // 購入した商品
        if ($page === 'buy') {
            $products = Order::where('user_id', $user->id)
                ->with('item')
                ->orderByDesc('created_at')
                ->get();

            return view('profile.mypage', compact('user', 'products', 'page', 'tradingUnreadTotal'));
        }

        // 取引中の商品
        if ($page === 'trading') {
            $orders = $tradingOrders->sortByDesc(function ($order) {
                return $order->chat->last_message_at ?? $order->updated_at;
            });

            return view(
                'profile.mypage',
                compact('user', 'orders', 'page', 'tradingUnreadTotal')
            );
        }

        // 想定外のpageが来た場合の保険
        return view('profile.mypage', compact('user', 'page', 'tradingUnreadTotal'));
    }

    public function edit()
    {
        $user = Auth::user()->load('profile');
        $profile = $user->profile ?? new Profile([
            'user_id' => $user->id,
            'profile_image' => 'images/default_profile.png',
        ]);

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        if ($request->hasFile('profile_image')) {
            $profile->profile_image = $request->file('profile_image')
                ->store('profile_images', 'public');
        } else {
            $profile->profile_image = 'profile_images/default.png';
        }

        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building = $request->building;

        $user->name = $request->name;
        $user->save();
        $profile->save();

        Auth::setUser($user->load('profile'));

        return redirect()->route('profile.mypage');
    }
}
