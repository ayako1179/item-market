<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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

    // ここからリレーション

    // プロフィール（1対1）
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // 出品した商品（1対多）
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // 注文（1対多）
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // コメント（1対多）
    public function comments()
    {
        return $this->hasMany(comment::class);
    }

    // いいねした商品（多対多）
    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes', 'user_id', 'item_id')->withTimestamps();
    }
}
