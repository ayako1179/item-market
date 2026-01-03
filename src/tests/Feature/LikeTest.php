<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_いいねアイコンを押下するといいねが登録される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        $this->actingAs($user);

        $this->assertEquals(0, $item->likedBy()->count());

        $response = $this->post("/item/like/{$item->id}");
        $response->assertStatus(302);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->fresh()->likedBy()->count());
    }

    public function test_いいね済みアイコンは色が変化して表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        $item->likedBy()->attach($user->id);

        $response = $this->actingAs($user)->get("/item/{$item->id}");

        $response->assertSee('liked');
    }

    public function test_再度いいねアイコンを押すと解除される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        $item->likedBy()->attach($user->id);
        $this->assertEquals(1, $item->likedBy()->count());

        $response = $this->actingAs($user)->post("/item/unlike/{$item->id}");
        $response->assertStatus(302);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(0, $item->fresh()->likedBy()->count());
    }
}
