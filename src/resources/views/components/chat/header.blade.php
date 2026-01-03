<header class="chat-header">
  <div class="chat-header__inner">
    <div class="chat-header__left">
      <div class="chat-header__avatar"></div>

      <h2 class="chat-header__title">
        「{{ $partner->name }}」さんとの取引画面
      </h2>
    </div>

    @if(auth()->id() === $chat->buyer_id)
    <form action="{{ route('trading.complete', $chat->order_id) }}" method="POST" class="chat-header__complete">
      @csrf
      <button type="submit" class="chat-header__complete-btn">
        取引を完了する
      </button>
    </form>
    @endif
  </div>

  <div class="chat-header__product">
    <div class="chat-header__product-image">
      <img src="{{ asset('storage/' . $chat->order->item->image_path) }}" alt="商品画像">
    </div>

    <div class="chat-header__product-info">
      <p class="chat-header__product-name">{{ $chat->order->item->name }}</p>
      <p class="chat-header__product-price">
        ¥ {{ number_format($chat->order->item->price) }}
      </p>
    </div>
  </div>
</header>