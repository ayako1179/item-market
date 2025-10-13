<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        Item::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        $items = Item::all();
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_購入済み商品は_Sold_と表示される()
    {
        $soldItem = Item::factory()->create([
            'is_sold' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee($soldItem->name);

        $response->assertSee('Sold');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();

        $myItem = Item::factory()->create(['user_id' => $user->id]);
        $otherItem = Item::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertDontSee($myItem->name);

        $response->assertSee($otherItem->name);
    }
}
