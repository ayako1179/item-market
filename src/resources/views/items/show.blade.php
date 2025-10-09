@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css')}}">
@endsection

@section('content')
<div class="show">
  @if (session('success'))
    <div class="session-message">
      {{ session('success') }}
    </div>
  @endif
  <!-- 左：商品画像 -->
  <div class="show__image-area">
    <img src="{{ asset('storage/' .$item->image_path) }}" alt="{{ $item->name }}" class="item-image">
    @if($item->is_sold)
      <div class="show__image-sold">
        <span class="sold-text">Sold</span>
      </div>
    @endif
  </div>

  <!-- 右：商品情報 -->
  <div class="show__detail">

    <!-- 商品名・ブランド名・価格・アイコン -->
    <div class="show__detail-title">
      <h1 class="item-name">{{ $item->name }}</h1>
      <p class="brand">{{ $item->brand_name }}</p>
      <p class="price">
        <span class="tax">¥</span>{{ number_format($item->price) }}
        <span class="tax">(税込)</span>
      </p>
      <div class="show__actions">
        <!-- いいねボタン -->
        @if(!$hasLiked)
          <!-- 未いいね → 登録 -->
          <form action="{{ route('items.like.store', $item->id) }}" method="POST" class="like-action">
            @csrf
            <button type="submit" class="action">
              <img src="{{ asset('images/like.png') }}" class="like-icon not-liked" alt="いいね">
              <span>{{ $item->likedBy->count() }}</span>
            </button>
          </form>
        @else
          <!-- いいね済み → 削除 -->
          <form action="{{ route('items.like.destroy', $item->id) }}" method="POST" class="like-action">
            @csrf
            <button type="submit" class="action">
              <img src="{{ asset('images/like.png') }}" class="like-icon liked" alt="いいね解除">
              <span>{{ $item->likedBy->count() }}</span>
            </button>
          </form>
        @endif
        <div class="action">
          <img src="{{ asset('images/comment.png') }}" alt="コメント">
          <span>{{ $item->comments->count() }}</span>
        </div>
      </div>

      @if($item->is_sold)
        <button class="purchase-btn" disabled>Sold Out</button>
      @else
        <a href="{{ route('orders.purchase', $item->id) }}" class="purchase-btn">購入手続きへ</a>
      @endif
    </div>

    <!-- 商品説明 -->
    <div class="show__description">
      <h2>商品説明</h2>
      <p>{!! nl2br(e($item->description)) !!}</p>
    </div>

    <!-- 商品情報 -->
    <div class="show__info">
      <h2>商品の情報</h2>

      <div class="show__info-categories">
        <span class="label">カテゴリー</span>
        <div class="category-tags">
          @foreach($item->categories as $category)
            <span class="category-tag">{{ $category->category }}</span>
          @endforeach
        </div>
      </div>

      <div class="show__info-condition">
        <span class="label">商品の状態</span>
        <span class="condition">{{ $item->condition->condition }}</span>
      </div>
    </div>

    <!-- コメント一覧 -->
    <div class="show__comments">
      <h3 class="comments-title">コメント ({{ $item->comments->count() }})</h3>

      <div class="comments-list">
        @foreach($item->comments as $comment)
          <div class="comment-item">
            <div class="comment-body">
              <div class="comment-avatar">
                <img src="{{ asset('storage/profile_images/' . $comment->user->profile->profile_image) }}" alt="{{ $comment->user->name }}">
              </div>              
              <p class="comment-user">{{ $comment->user->name }}</p>
            </div>
            <p class="comment-text">{{ $comment->comment }}</p>            
          </div>
        @endforeach
      </div>
    </div>

    <!-- コメント投稿フォーム -->
    <div class="show__comment-form">
      <h2>商品へのコメント</h2>
      <form action="{{ route('items.comment.store', $item->id) }}" method="POST">
        @csrf
        <textarea name="comment" class="comment-textarea" id="comment">{{ old('comment') }}</textarea>
        @error('comment')
          <p class="error">{{ $message }}</p>
        @enderror
        <button type="submit" class="comment-btn">コメントを送信する</button>
      </form>
    </div>
  </div>
</div>
@endsection