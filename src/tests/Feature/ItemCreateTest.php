<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品出品画面で入力した情報が正しく保存される()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $category = Category::factory()->create(['category' => 'アクセサリー']);
        $condition = Condition::factory()->create(['condition' => '新品']);

        $file = UploadedFile::fake()->create('watch.jpeg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('items.store'), [
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'name' => 'テスト腕時計',
            'brand_name' => 'Rolax',
            'description' => '高級感のある腕時計です。',
            'price' => 15000,
            'image' => $file,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'name' => 'テスト腕時計',
            'price' => 15000,
        ]);
    }
}
