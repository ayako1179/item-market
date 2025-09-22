<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category')->withTimestamps();
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes', 'item_id', 'user_id')->withTimestamps();
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function getImageUrlAttribute()
    {
        return asset($this->image_path);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
}
