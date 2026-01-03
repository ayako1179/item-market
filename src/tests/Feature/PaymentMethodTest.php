<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase
;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_支払い方法選択が反映される()
    {
        $buyer = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $buyer->id,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区テスト町1-1',
            'building' => 'テストビル101',
        ]);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->actingAs($buyer)->get("/purchase/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('支払い方法');

        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}", [
            'payment_method' => 'コンビニ払い',
            'address' => '東京都新宿区テスト町1-1',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'コンビニ払い',
        ]);
    }
}
