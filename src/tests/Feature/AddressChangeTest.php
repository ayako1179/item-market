<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_送付先住所変更画面で登録した住所が商品購入画面に反映される()
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '東京都新宿区旧住所',
            'building' => '旧ビル101',
        ]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            'postal_code' => '222-2222',
            'address' => '東京都渋谷区新住所',
            'building' => '新ビル202',
        ]);

        $response->assertRedirect("/purchase/{$item->id}");

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");

        $response->assertSee('222-2222');
        $response->assertSee('東京都渋谷区新住所');
        $response->assertSee('新ビル202');
    }

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '東京都新宿区旧住所',
            'building' => '旧ビル101',
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            'postal_code' => '222-2222',
            'address' => '東京都渋谷区新住所',
            'building' => '新ビル202',
        ]);

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'コンビニ払い',
            'address' => '東京都渋谷区新住所',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postal_code' => '222-2222',
            'address' => '東京都渋谷区新住所',
            'building' => '新ビル202',
        ]);
    }
}
