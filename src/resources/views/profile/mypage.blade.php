@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css')}}">
@endsection

@section('content')
<div class="mypage">
  <div class="mypage__profile">
    <img src="{{ asset('storage/' . $user->profile->profile_image) }}" alt="プロフィール写真" class="mypage__profile-image">
    <p class="mypage__username">{{ $user->name }}</p>
    <div class="btn">
      <a href="{{ route('profile.edit') }}">プロフィールを編集</a>
    </div>
  </div>

  <div class="mypage__tabs">
    <div class="mypage__tabs-inner">
      <a href="{{ route('profile.mypage', ['page' => 'sell']) }}" class="mypage__tab {{ $page === 'sell' ? 'active' : '' }}">
        出品した商品
      </a>

      <a href="{{ route('profile.mypage', ['page' => 'buy']) }}" class="mypage__tab {{ $page === 'buy' ? 'active' : '' }}">
        購入した商品
      </a>

      <a href="{{ route('profile.mypage', ['page' => 'trading']) }}" class="mypage__tab {{ $page === 'trading' ? 'active' : '' }}">
        取引中の商品

        @if ($tradingUnreadTotal > 0)
          <span class="tab-notification">
            {{ $tradingUnreadTotal }}
          </span>
        @endif
      </a>
    </div>
  </div>

  <div class="products__grid">
    @if($page === 'sell')
    @forelse($products as $item)
    <div class="product-card">
      <a href="{{ route('items.show', $item->id) }}">
        <div class="product-card__image">
          <img src="{{ asset('storage/' .$item->image_path) }}" alt="{{ $item->name }}">
          @if($item->is_sold)
          <div class="product-card__sold">
            <span class="sold-text">Sold</span>
          </div>
          @endif
        </div>
        <p class="product-card__name">{{ $item->name }}</p>
      </a>
    </div>
    @empty
    <p class="product-text">出品商品はありません</p>
    @endforelse

    @elseif($page === 'buy')
    @forelse($products as $order)
    <div class="product-card">
      <a href="{{ route('items.show', $order->item->id) }}">
        <div class="product-card__image">
          <img src="{{ asset('storage/' . $order->item->image_path) }}" alt="{{ $order->item->name }}">
          @if($order->item->is_sold)
          <div class="product-card__sold">
            <span class="sold-text">Sold</span>
          </div>
          @endif
        </div>
        <p class="product-card__name">{{ $order->item->name }}</p>
      </a>
    </div>
    @empty
    <p class="product-text">購入商品はありません</p>
    @endforelse
    @endif

    @if ($page === 'trading')
    @include('profile.partials.trading')
    @endif
  </div>

</div>
@endsection