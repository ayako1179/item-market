<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRead extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'user_id',
        'read_at',
    ];

    // 対象メッセージ
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    // 既読ユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
