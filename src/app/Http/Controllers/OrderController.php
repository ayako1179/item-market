<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class OrderController extends Controller
{
    //購入画面の表示
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        // $user = Auth::user()->load('profile');

        // セッションに保存されていればそれを使う、なければプロフィール
        $address = session('purchase_address', [
            'postal_code' => $user->profile->postal_code,
            'address' => $user->profile->address,
            'building' => $user->profile->building,
        ]);

        return view('orders.purchase', compact('item','user', 'address'));
    }

    // 購入処理
    public function store(PurchaseRequest $request, $item_id)
    {
        // バリデーション済みの支払い方法
        $validated = $request->validated();

        $user = Auth::user();

        // セッションから住所を取得（なければプロフィール）
        $address = session('purchase_address', [
            'postal_code' => $user->profile->postal_code,
            'address' => $user->profile->address,
            'building' => $user->profile->building,
        ]);

        // 商品を取得
        $item = Item::findOrFail($item_id);

        // Stripe を初期化
        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentType = $validated['payment_method'];

        // Checkout セッションを作成(支払方法に応じて)
        if ($paymentType === 'カード支払い') {
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
                'success_url' => route('orders.success', ['item_id' => $item->id]),
                'cancel_url' => route('orders.cancel'),
            ]);
        } elseif ($paymentType === 'コンビニ払い') {

            $order = Order::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => $validated['payment_method'],
                'postal_code' => $address['postal_code'],
                'address' => $address['address'],
                'building' => $address['building'],
            ]);

            $item->is_sold = true;
            $item->save();

            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['konbini'],
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
                'success_url' => route('orders.success', ['item_id' => $item->id]),
                'cancel_url' => route('orders.cancel'),
            ]);
        }

        // 購入時に使う情報をセッションに保存
        session([
            'pending_order' => [
                'item_id' => $item_id,
                'payment_method' => $validated['payment_method'],
                'postal_code' => $address['postal_code'],
                'address' => $address['address'],
                'building' => $address['building'],
            ]
        ]);

        // Stripe の決済ページにリダイレクト
        return redirect($checkoutSession->url);
    }

    public function success(Request $request, $item_id)
    {
        $user = Auth::user();
        $pending = session('pending_order');

        if ($pending) {
            // 注文を保存
            $order = Order::create([
                'user_id' => $user->id,
                'item_id' => $pending['item_id'],
                'payment_method' => $pending['payment_method'],
                'postal_code' => $pending['postal_code'],
                'address' => $pending['address'],
                'building' => $pending['building'],
            ]);

            // 商品を Sold 状態に
            $item = Item::findOrFail($pending['item_id']);
            $item->is_sold = true;
            $item->save();

            // セッションの住所を削除（次の購入に残さないように）
            session()->forget(['pending_order', 'purchase_address']);
        }

        return view('orders.success');
    }

    public function cancel()
    {
        // セッションの注文情報を削除
        session()->forget('pending_order');

        return view('orders.cancel');
    }

    // 住所変更画面
    public function edit($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $address = Session::get('address', [
            'postal_code' => '',
            'address' => '',
            'building' => ''
        ]);

        return view('orders.address', compact('item', 'address'));
    }

    // 住所変更処理（セッション保存）
    public function update(AddressRequest $request, $item_id)
    {
        session([
            'purchase_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);
        return redirect()->route('orders.purchase', $item_id);
    }
}
