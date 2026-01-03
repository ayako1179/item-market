<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chat_id',
        'user_id',
        'body',
        'attachment_path',
    ];

    // 所属するチャット
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    // 投稿者
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 既読情報
    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function isReadBy(User $user): bool
    {
        return $this->reads()
            ->where('user_id', $user->id)
            ->exists();
    }
}
