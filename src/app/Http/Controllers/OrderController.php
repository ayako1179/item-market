<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class OrderController extends Controller
{
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $address = session('purchase_address', [
            'postal_code' => $user->profile->postal_code,
            'address' => $user->profile->address,
            'building' => $user->profile->building,
        ]);

        return view('orders.purchase', compact('item', 'user', 'address'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $address = session('purchase_address', [
            'postal_code' => $user->profile->postal_code,
            'address' => $user->profile->address,
            'building' => $user->profile->building,
        ]);

        $paymentType = $validated['payment_method'];

        if ($paymentType === 'コンビニ払い') {
            $order = Order::create([
                'user_id' => $user->id,
                'item_id' => $item_id,
                'payment_method' => $paymentType,
                'postal_code' => $address['postal_code'],
                'address' => $address['address'],
                'building' => $address['building'],
                'status' => 'in_progress',
            ]);

            Chat::create([
                'order_id' => $order->id,
                'buyer_id' => $user->id,
                'seller_id' => $item->user_id,
                'last_message_at' => now(),
            ]);

            $item->is_sold = true;
            $item->save();

            session()->forget(['purchase_address']);

            return redirect()->route('home');
        }

        if ($paymentType === 'カード支払い') {
            Stripe::setApiKey(config('services.stripe.secret'));

            session([
                'pending_order' => [
                    'user_id' => $user->id,
                    'item_id' => $item_id,
                    'payment_method' => $paymentType,
                    'postal_code' => $address['postal_code'],
                    'address' => $address['address'],
                    'building' => $address['building'],
                ],
            ]);

            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('orders.stripeSuccess', ['item_id' => $item_id]),
                'cancel_url' => route('orders.stripeCancel', ['item_id' => $item_id]),
            ]);

            return redirect($checkoutSession->url);
        }
    }

    public function stripeSuccess($item_id)
    {
        $pending = session('pending_order');

        if ($pending) {
            $order = Order::create([
                'user_id' => $pending['user_id'],
                'item_id' => $pending['item_id'],
                'payment_method' => $pending['payment_method'],
                'postal_code' => $pending['postal_code'],
                'address' => $pending['address'],
                'building' => $pending['building'],
                'status' => 'in_progress',
            ]);

            $item = Item::findOrFail($pending['item_id']);

            Chat::create([
                'order_id' => $order->id,
                'buyer_id' => $pending['user_id'],
                'seller_id' => $item->user_id,
                'last_message_at' => now(),
            ]);

            $item->is_sold = true;
            $item->save();

            session()->forget(['pending_order', 'purchase_address']);
        }

        return redirect()->route('home');
    }

    public function stripeCancel($item_id)
    {
        session()->forget(['pending_order']);

        return redirect()->route('orders.purchase', ['item_id' => $item_id]);
    }

    public function edit($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $address = Session::get('address', [
            'postal_code' => '',
            'address' => '',
            'building' => '',
        ]);

        return view('orders.address', compact('item', 'address'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        session([
            'purchase_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ],
        ]);

        return redirect()->route('orders.purchase', $item_id);
    }

    public function complete(Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->update([
            'status' => 'completed',
        ]);

        return redirect()
            ->route('profile.mypage', ['page' => 'buy']);
    }
}
