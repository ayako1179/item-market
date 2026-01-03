<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'buyer_id',
        'seller_id',
        'last_message_at',
    ];

    // 取引（1対1）
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // 購入者
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // 出品者
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // チャットメッセージ
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest('created_at');
    }

    public function isParticipant(User $user): bool
    {
        return $this->buyer_id === $user->id
            || $this->seller_id === $user->id;
    }

    public function unreadMessagesCountFor(User $user): int
    {
        return $this->messages()
            ->where('user_id', '!=', $user->id)
            ->whereDoesntHave('reads', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();
    }

    public function markAsReadBy(User $user): void
    {
        $this->messages()
            ->where('user_id', '!=', $user->id)
            ->whereDoesntHave('reads', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get()
            ->each(function ($message) use ($user) {
                $message->reads()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['read_at' => now()]
                );
            });
    }
}
