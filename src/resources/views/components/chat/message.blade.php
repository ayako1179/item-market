<div class="chat-messages-inner">
  @forelse ($messages as $message)
  <div class="chat-message {{ $message->user_id === auth()->id() ? 'is-own' : 'is-other' }}">

    <!-- 上段：アイコン ＋ 名前 -->
    <div class="chat-message__header">
      <div class="chat-message__avatar"></div>
      <p class="chat-message__user">{{ $message->user->name }}</p>
    </div>

    <!-- 下段：本文 -->
    <div class="chat-message__body">
      <!-- <div class="chat-message__bubble">
        {{ $message->body }}
      </div> -->

      @if ($message->user_id === auth()->id() && request('edit') == $message->id)
      <!-- <div class="chat-message__actions"> -->
      <!-- 編集中 -->
      <form
        action="{{ route('messages.update', $message->id) }}"
        method="POST"
        class="chat-message__edit-form">
        @csrf
        @method('PUT')

        <textarea
          name="body"
          class="chat-message__edit-textarea"
          rows="3">{{ old('body', $message->body) }}</textarea>

        <div class="chat-message__actions">
          <button type="submit">保存</button>
          <a href="{{ route('chats.show', $chat->id) }}">キャンセル</a>
        </div>
      </form>
      @else
      <div class="chat-message__bubble">
        @if ($message->attachment_path)
        <div class="chat-message__image">
          <img
            src="{{ asset('storage/' . $message->attachment_path) }}"
            alt="添付画像">
        </div>
        @endif
        @if ($message->body)
        <p class="chat-message__text">
          {!! nl2br(e($message->body)) !!}
        </p>
        @endif
      </div>

      @if ($message->user_id === auth()->id())
      <div class="chat-message__actions">
        <a href="{{ route('chats.show', [$chat->id, 'edit' => $message->id]) }}">
          編集
        </a>

        <!-- 削除 -->
        <form
          action="{{ route('messages.destroy', $message->id) }}"
          method="POST"
          onsubmit="return confirm('このメッセージを削除しますか？');">
          @csrf
          @method('DELETE')
          <button type="submit">削除</button>
        </form>
      </div>
      @endif
      <!-- </div> -->
      @endif
    </div>
  </div>
  @empty
  <p class="chat-message__empty">
    まだメッセージはありません
  </p>
  @endforelse
</div>