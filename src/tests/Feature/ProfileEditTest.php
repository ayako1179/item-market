<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_プロフィール編集画面で過去設定された値が初期値として表示される()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image' => 'test.png',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区神南1-1-1',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('profile_images/test.png');
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区神南1-1-1');
    }
}
