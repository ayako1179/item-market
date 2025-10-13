<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_支払い方法選択が反映される()
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区テスト町1-1',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('支払い方法');

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'コンビニ払い',
            'address' => '東京都新宿区テスト町1-1'
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'コンビニ払い',
        ]);
    }
}
