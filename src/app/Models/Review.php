<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reviewer_id',
        'reviewed_id',
        'rating',
        'comment',
    ];

    // 紐づく取引
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // 評価したユーザー
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // 評価されたユーザー
    public function reviewed()
    {
        return $this->belongsTo(User::class, 'reviewed_id');
    }
}
