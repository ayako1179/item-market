<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_いいねした商品だけが表示される()
    {
        $condition = Condition::factory()->create();

        $user = User::factory()->create();
        $likedItem = Item::factory()->create(['condition_id' => $condition->id]);
        $otherItem = Item::factory()->create(['condition_id' => $condition->id]);

        $user->likedItems()->attach($likedItem->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee($likedItem->name);
        $response->assertDontSee($otherItem->name);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_購入済み商品は_Sold_と表示される()
    {
        $condition = Condition::factory()->create();

        $user = User::factory()->create();
        $soldItem = Item::factory()->create([
            'condition_id' => $condition->id,
            'is_sold' => true,
        ]);

        $user->likedItems()->attach($soldItem->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee($soldItem->name);
        $response->assertSee('Sold');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_未認証の場合は何も表示されない()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('マイリスト');

        $response->assertDontSee('Sold');
        $response->assertDontSee('<div class="product-card">');
    }
}
