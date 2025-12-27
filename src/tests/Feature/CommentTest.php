<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $data = ['comment' => 'とても欲しい商品です！'];

        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'とても欲しい商品です！',
        ]);

        $this->assertEquals(1, Comment::count());
    }

    public function test_ログイン前のユーザーはコメントを送信できない()
    {
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $data = ['comment' => '未ログインでは送信できないはず'];

        $response = $this->post("/item/{$item->id}/comment", $data);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', ['comment' => '未ログインでは送信できないはず']);
    }

    public function test_コメントが入力されていない場合_バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $data = ['comment' => ''];

        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", $data);

        $response->assertSessionHasErrors(['comment']);
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_コメントが255字以上の場合_バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $data = ['comment' => str_repeat('あ', 256)];

        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", $data);

        $response->assertSessionHasErrors(['comment']);
        $this->assertDatabaseCount('comments', 0);
    }
}
