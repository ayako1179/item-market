<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use DatabaseTransactions;

    public function test_商品詳細ページに必要な情報が表示される()
    {
        $user = User::factory()->create();

        $user->profile()->create([
            'profile_image' => 'default.png',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $condition = Condition::factory()->create(['condition' => '新品']);
        $categories = Category::factory()->count(2)->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '限定スニーカー',
            'brand_name' => 'NIKE',
            'price' => 12000,
            'description' => '限定モデルのスニーカーです。',
            'condition_id' => $condition->id,
            'image_path' => 'storage/products/sample.jpeg',
        ]);

        $item->categories()->attach($categories->pluck('id'));

        DB::table('likes')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'すごく欲しいです！',
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee($item->name);
        $response->assertSee($item->brand_name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->description);
        $response->assertSee($condition->condition);
        $response->assertSee('すごく欲しいです！');
        $response->assertSee($user->name);
        $response->assertSee($item->image_path);

        $response->assertSee((string) $item->likedBy()->count());
        $response->assertSee((string) $item->comments()->count());
    }

    public function test_複数カテゴリが表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create(['condition' => '中古']);
        $categories = Category::factory()->count(3)->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'マウンテンパーカー',
            'condition_id' => $condition->id,
        ]);
        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->category);
        }
    }
}
