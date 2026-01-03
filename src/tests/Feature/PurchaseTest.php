<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use DatabaseTransactions;

    public function test_購入ボタンを押下すると購入が完了する()
    {
        $user = User::factory()->create();

        $condition = Condition::factory()->create();
        $item = Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'コンビニ払い',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'コンビニ払い',
        ]);
    }

    public function test_購入済み商品は一覧画面で_sold_と表示される()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        Order::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'コンビニ払い',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        $item->update(['is_sold' => true]);

        $item = $item->fresh();

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);

        $response = $this->actingAs($buyer)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold', false);
    }

    public function test_購入済み商品がプロフィール購入一覧に表示される()
    {
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '987-6543',
            'address' => '東京都品川区2-2-2',
            'building' => 'サンプルハウス202',
            'profile_image' => 'default.png',
        ]);

        $condition = Condition::factory()->create();
        $item = Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'コンビニ払い',
            'postal_code' => '111-1111',
            'address' => '東京都新宿区1-1-1',
            'building' => '新宿ハイツ101',
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }
}
