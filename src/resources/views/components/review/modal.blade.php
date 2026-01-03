<div class="review-modal-overlay">
  <div class="review-modal">
    <p class="review-modal__title">
      取引が完了しました。
    </p>

    <p class="review-modal__subtitle">
      今回の取引相手はどうでしたか？
    </p>

    <form
      method="POST"
      action="{{ route('reviews.store', ['order_id' => $order->id]) }}"
      class="review-modal__form">
      @csrf

      <!-- ☆評価 -->
      <div class="review-stars">
        @for ($i = 5; $i>= 1; $i--)
        <input
          type="radio"
          id="star{{ $i }}"
          name="rating"
          value="{{ $i }}">
        <label for="star{{ $i }}">★</label>
        @endfor
      </div>

      <button type="submit" class="review-modal__submit">
        送信する
      </button>
    </form>
  </div>
</div>