@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')
<form action="{{ route('orders.store', $item->id) }}" method="post">
  @csrf
  <div class="order">
    <div class="order-left">
      <div class="order__item">
        <!-- 商品画像 -->
        <div class="order__item-image">
          <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="item-image">
        </div>
        <!-- 商品名・価格 -->
        <div class="order__item-title">
          <h1 class="item-name">{{ $item->name }}</h1>
          <p class="price">
            <span class="tax">¥</span>{{ number_format($item->price) }}
          </p>
        </div>
      </div>
    
      <!-- 支払い方法 -->
      <div class="order__payment">
        <label for="payment_method" class="item-title">支払い方法</label>
        <div class="custom-select" id="paymentSelect">
          <div class="custom-select__trigger">
            <span id="selected-text">選択してください</span>
            <img src="{{ asset('/images/arrow.png') }}" alt="▼" class="arrow-icon">
          </div>
          <div class="custom-options">
            <span class="custom-option" data-value="コンビニ払い">
              <img src="{{ asset('images/mark.png') }}" alt="" class="check-icon">コンビニ払い
            </span>
            <span class="custom-option" data-value="カード支払い">
              <img src="{{ asset('images/mark.png') }}" alt="" class="check-icon">カード支払い
            </span>
          </div>
        </div>
        <input type="hidden" name="payment_method" id="payment_value">
        @error('payment_method')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>

      <!-- 配送先住所 -->
      <div class="order__address">
        <div class="address-group">
          <label for="address" class="item-title">配送先</label>
          <a href="{{ route('orders.address', ['item_id' => $item->id]) }}">変更する</a>
        </div>
        <div class="item-address">
          <p>〒 {{ $address['postal_code'] }}</p>
          <p>{{ $address['address'] }}
            <span>{{ $address['building'] ?? '' }}</span>
          </p>
        </div>
      </div>

      <!-- hidden フィールド（必須項目を送信） -->
      <input type="hidden" name="postal_code" value="{{ $address['postal_code'] }}">
      <input type="hidden" name="address" value="{{ $address['address'] }}">
      <input type="hidden" name="building" value="{{ $address['building'] ?? '' }}">

      <!-- エラーメッセージの表示 -->
      @error('address')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <!-- 右：確認エリア -->
    <div class="order-right">
      <div class="order-confirm">
        <!-- 左上 -->
        <div class="confirm-box">
          <p class="confirm-title">商品代金</p>
        </div>

        <!-- 右上 -->
        <div class="confirm-box">
          <p class="confirm-value">
            <span class="confirm-tax">¥</span>
            <span class="price">{{ number_format($item->price) }}</span>
          </p>
        </div>

        <!-- 左下 -->
        <div class="confirm-box">
          <p class="confirm-title">支払い方法</p>
        </div>

        <!-- 右下 -->
        <div class="confirm-box">
          <p class="confirm-value" id="selected-method">未選択</p>
        </div>
      </div>

      <div class="order-action">
        <button type="submit" class="btn-order">購入する</button>
      </div>
    </div>
  </div>
</form>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('paymentSelect');
    const trigger = select.querySelector('.custom-select__trigger');
    const options = select.querySelectorAll('.custom-option');
    const selectedText = document.getElementById('selected-text');
    const hiddenInput = document.getElementById('payment_value');
    const display = document.getElementById('selected-method');

    trigger.addEventListener('click', () => {
      select.classList.toggle('open');
    });

    options.forEach(option => {
      option.addEventListener('click', () => {
        options.forEach(o => o.classList.remove('selected'));
        option.classList.add('selected');

        selectedText.textContent = option.dataset.value;
        hiddenInput.value = option.dataset.value;
        display.textContent = option.dataset.value;

        select.classList.remove('open');
      });
    });

    document.addEventListener('click', (e) => {
      if (!select.contains(e.target)) {
        select.classList.remove('open');
      }
    });
  });
</script>
@endsection