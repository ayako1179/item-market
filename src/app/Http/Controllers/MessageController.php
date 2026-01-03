<?php

namespace App\Http\Controllers;

use App\Http\Requests\Messages\StoreMessageRequest;
use App\Http\Requests\Messages\UpdateMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    // メッセージ送信
    public function store(StoreMessageRequest $request, $chat_id)
    {
        $user = Auth::user();
        $chat = Chat::findOrFail($chat_id);

        // チャット参加者チェック
        if (
            $chat->buyer_id !== $user->id &&
            $chat->seller_id !== $user->id
        ) {
            abort(403);
        }

        // 画像保存（あれば）
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('chat_messages', 'public');
        }

        // メッセージ作成
        Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'body' => $request->body,
            'attachment_path' => $imagePath,
        ]);

        // チャットの最終更新日時を更新（並び替え用）
        $chat->update([
            'last_message_at' => now(),
        ]);

        return redirect()->route('chats.show', $chat->id);
    }

    // メッセージ編集
    public function update(UpdateMessageRequest $request, $message_id)
    {
        $message = Message::findOrFail($message_id);
        $user = Auth::user();

        // 自分のメッセージのみ編集可
        if ($message->user_id !== $user->id) {
            abort(403);
        }

        $message->update([
            'body' => $request->body,
        ]);

        return redirect()->route('chats.show', $message->chat_id);
    }

    // メッセージ削除（論理削除）
    public function destroy($message_id)
    {
        $message = Message::findOrFail($message_id);
        $user = Auth::user();

        // 自分のメッセージのみ削除可
        if ($message->user_id !== $user->id) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('chats.show', $message->chat_id);
    }
}
