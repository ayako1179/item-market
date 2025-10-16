@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items-index.css')}}">
@endsection

@section('content')
<div class="products">
  <div class="products__tabs">
    <div class="products__tabs-inner">
      <a href="{{ route('home', ['tab' => 'recommend', 'keyword' => request('keyword')]) }}" class="products__tab {{ ($tab ?? 'recommend') === 'recommend' ? 'products__tab--active' : '' }}">おすすめ</a>

      <a href="{{ route('home', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}" class="products__tab {{ ($tab ?? '') === 'mylist' ? 'products__tab--active' : '' }}">マイリスト</a>
    </div>
  </div>

  <div class="products__grid">
    @foreach($items as $item)
      <div class="product-card">
        <a href="{{ route('items.show', $item->id) }}">
          <div class="product-card__image">
            <img src="{{ asset('storage/' .$item->image_path) }}" alt="{{ $item->name}}">
            @if($item->is_sold)
              <div class="product-card__sold">
                <span class="sold-text">Sold</span>
              </div>
            @endif
          </div>
          <p class="product-card__name">{{ $item->name }}</p>
        </a>
      </div>
    @endforeach
  </div>
</div>
@endsection
