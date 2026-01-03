<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class EmailVerificationTest extends TestCase
{
  use DatabaseTransactions;

  public function test_会員登録後_認証メールが送信される()
  {
    Notification::fake();

    $this->post('/register', [
      'name' => 'テストユーザー',
      'email' => 'test@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    $this->assertNotNull($user);

    Notification::assertSentTo(
      $user,
      VerifyEmail::class
    );
  }

  public function test_メール認証誘導画面から_メール認証画面に遷移できる()
  {
    $user = User::factory()->create([
      'email_verified_at' => null,
    ]);

    $this->actingAs($user);

    $response = $this->get('/email/verify');

    $response->assertStatus(200);
    $response->assertSee('認証はこちらから');
  }

  public function test_メール認証完了後_プロフィール設定画面に遷移する()
  {
    $user = User::factory()->create([
      'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
      'verification.verify',
      now()->addMinutes(60),
      [
        'id' => $user->id,
        'hash' => sha1($user->email),
      ]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    $response->assertRedirect('/?verified=1');

    $this->assertNotNull($user->fresh()->email_verified_at);
  }
}
