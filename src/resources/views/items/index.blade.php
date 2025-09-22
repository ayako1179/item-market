@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items-index.css')}}">
@endsection

@section('content')
<div class="products">
  <!-- タブ部分 -->
  <div class="products__tabs">
    <div class="products__tabs-inner">
      <a href="{{ route('home', ['tab' => 'recommend', 'keyword' => request('keyword')]) }}" class="products__tab {{ ($tab ?? 'recommend') === 'recommend' ? 'products__tab--active' : '' }}">おすすめ</a>

      <a href="{{ route('home', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}" class="products__tab {{ ($tab ?? '') === 'mylist' ? 'products__tab--active' : '' }}">マイリスト</a>
    </div>
  </div>

  <!-- 商品一覧 -->
  <div class="products__grid">
    @foreach($items as $item)
      <div class="product-card">
        <a href="{{ route('items.show', $item->id) }}">
          <div class="product-card__image">
            <img src="{{ $item->image_url }}" alt="{{ $item->name}}">
            @if($item->order)
              <span class="product-card__sold">Sold</span>
            @endif
          </div>
          <p class="product-card__name">{{ $item->name }}</p>
        </a>
      </div>
    @endforeach
  </div>
</div>
@endsection