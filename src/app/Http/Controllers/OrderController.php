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
        $paymentType = $validated['payment_method'];

        // コンビニ払い → DB保存して商品一覧へ
        if ($paymentType === 'コンビニ払い') {
            // Stripe に遷移しない
            Order::create([
                'user_id' => $user->id,
                'item_id' => $item_id,
                'payment_method' => $paymentType,
                'postal_code' => $address['postal_code'],
                'address' => $address['address'],
                'building' => $address['building'],
            ]);

            $item->is_sold = true;
            $item->save();

            session()->forget(['purchase_address']);
            return redirect()->route('home');
        }       

        // カード支払い → Stripeへ遷移
        if ($paymentType === 'カード支払い') {
            // Stripe を初期化
            Stripe::setApiKey(config('services.stripe.secret'));

            // 情報をセッションに一時保存（決済成功後に使用）
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
                // 成功時は商品一覧へ遷移（success画面なし）
                'success_url' => route('orders.stripeSuccess', ['item_id' => $item_id]),
                // キャンセル時は購入画面へ戻る
                'cancel_url' => route('orders.stripeCancel', ['item_id' => $item_id]),
            ]);  

            return redirect($checkoutSession->url);
        }
    }

    // Stripe成功 → DB保存＆Sold反映 → 一覧へ
    public function stripeSuccess($item_id)
    {
        $pending = session('pending_order');

        if($pending) {
            Order::create([
                'user_id' => $pending['user_id'],
                'item_id' => $pending['item_id'],
                'payment_method' => $pending['payment_method'],
                'postal_code' => $pending['postal_code'],
                'address' => $pending['address'],
                'building' => $pending['building'],
            ]);

            $item = Item::findOrFail($pending['item_id']);
            $item->is_sold = true;
            $item->save();

            session()->forget(['pending_order', 'purchase_address']);
        }

        return redirect()->route('home');
    }

    // Stripeキャンセル → DB保存なし、購入画面に戻る
    public function stripeCancel($item_id)
    {
        session()->forget(['pending_order']);
        return redirect()->route('orders.purchase', ['item_id' => $item_id]);
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
