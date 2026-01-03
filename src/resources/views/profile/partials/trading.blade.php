@forelse ($orders as $order)
@php
$item = $order->item;
$unreadCount = $order->chat->unread_count ?? 0;
@endphp

<div class="product-card">
  <a href="{{ route('chats.show', $order->chat->id) }}">
    <div class="product-card__image">
      <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">

      <!-- 商品ごとの未読通知 -->
      @if ($unreadCount > 0)
        <div class="unread-badge">
          {{ $unreadCount }}
        </div>
      @endif
    </div>

    <p class="product-card__name">{{ $item->name }}</p>
  </a>
</div>

@empty
<p class="product-text">取引中の商品はありません</p>
@endforelse
