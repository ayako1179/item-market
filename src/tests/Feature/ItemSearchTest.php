<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_商品名で部分一致検索ができる()
    {
        $condition = Condition::factory()->create();

        $matchItem = Item::factory()->create([
            'name' => 'おしゃれな腕時計',
            'condition_id' => $condition->id,
        ]);

        $nonMatchItem = Item::factory()->create([
            'name' => 'スポーツシューズ',
            'condition_id' => $condition->id,
        ]);

        $response = $this->get('/?keyword=腕');

        $response->assertStatus(200);
        $response->assertSee('おしゃれな腕時計');
        $response->assertDontSee('スポーツシューズ');
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();

        $likedItem = Item::factory()->create([
            'name' => '限定スニーカー',
            'condition_id' => $condition->id,
        ]);

        $user->likedItems()->attach($likedItem->id);

        $response = $this->actingAs($user)->get('/?keyword=スニーカー');
        $response->assertSee('限定スニーカー');

        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=スニーカー');

        $response->assertStatus(200);
        $response->assertSee('限定スニーカー');

        $response->assertSee('スニーカー', false);
    }
}
