<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_ユーザー情報が正しく取得できる()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);
        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image' => 'profile_images/test.png',
            'postal_code' => '111-1111',
            'address' => '東京都新宿区テスト町1-1',
            'building' => 'テストビル101',
        ]);

        $item1 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品A',
            'is_sold' => false,
        ]);

        $item2 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品B',
            'is_sold' => true,
        ]);

        $seller = User::factory()->create();
        $boughtItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入商品X',
            'is_sold' => true,
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $boughtItem->id,
            'payment_method' => 'コンビニ払い',
            'postal_code' => '111-1111',
            'address' => '東京都新宿区テスト町1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('profile_images/test.png');

        $response->assertSee('出品商品A');
        $response->assertSee('出品商品B');

        $responseBuy = $this->actingAs($user)->get('/mypage?page=buy');
        $responseBuy->assertStatus(200);
        $responseBuy->assertSee('購入商品X');
    }
}
