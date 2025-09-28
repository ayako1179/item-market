@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
@endsection

@section('content')
<div class="sell">
  <h1 class="sell-title">商品の出品</h1>
  <form action="{{ route('home') }}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- 商品画像アップロード -->
    <div class="sell__image-area">
      <label for="image" class="sell__label">商品画像</label>
      <input type="file" name="image" id="image" class="sell__image-input">
      <span class="sell__image-btn">画像を選択する</span>
    </div>
  </form>
</div>
@endsection