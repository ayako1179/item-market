<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes', 'user_id', 'item_id')->withTimestamps();
    }

    public function like($itemId)
    {
        $this->likedItems()->syncWithoutDetaching([$itemId]);
    }

    public function unlike($itemId)
    {
        $this->likedItems()->detach($itemId);
    }

    // チャット関連
    // 購入者として関わるチャット
    public function buyerChats()
    {
        return $this->hasMany(Chat::class, 'buyer_id');
    }

    // 出品者として関わるチャット
    public function sellerChats()
    {
        return $this->hasMany(Chat::class, 'seller_id');
    }

    // メッセージ関連
    // 投稿したメッセージ
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // メッセージ既読情報
    public function messageReads()
    {
        return $this->hasMany(MessageRead::class);
    }

    // 評価関連
    // 自分がした評価
    public function givenReviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    // 自分が受けた評価
    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewed_id');
    }
}
