<?php

namespace App\Http\Controllers;

use App\Models\Chat;
// use App\Models\MessageRead;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show(Chat $chat)
    {
        $user = Auth::user();

        // チャットに参加しているかチェック
        if (!$chat->isParticipant($user)) {
            abort(403);
        }
        // if (
        //     $chat->buyer_id !== $user->id &&
        //     $chat->seller_id !== $user->id
        // ) {
        //     abort(403);
        // }

        // 相手ユーザーを判定
        $partner = $chat->buyer_id === $user->id
            ? $chat->seller
            : $chat->buyer;

        // メッセージ一覧取得
        $messages = $chat->messages()
            ->with(['user', 'reads'])
            ->orderBy('created_at')
            ->get();

        // 既読処理（自分宛て & 未読のみ）
        $chat->markAsReadBy($user);
        // $unreadMessageIds = $chat->messages()
        //     ->where('user_id', '!=', $user->id)
        //     ->whereDoesntHave('reads', function ($query) use ($user) {
        //         $query->where('user_id', $user->id);
        //     })
        //     ->pluck('id');
        // foreach ($unreadMessageIds as $messageId) {
        //     MessageRead::create([
        //         'message_id' => $messageId,
        //         'user_id' => $user->id,
        //         'read_at' => now(),
        //     ]);
        // }

        // サイドバー用チャット一覧
        $chats = Chat::where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                ->orWhere('seller_id', $user->id);
        })
            ->where('id', '!=', $chat->id)
            ->with(['latestMessage', 'buyer', 'seller'])
            ->orderByDesc('last_message_at')
            ->get()
            ->map(function ($sidebarChat) use ($user) {
                $sidebarChat->unread_count =
                    $sidebarChat->unreadMessagesCountFor($user);
                return $sidebarChat;
            });

        // viewへ
        return view('chats.show', compact(
            'chat',
            'partner',
            'messages',
            'chats'
        ));
    }
}
