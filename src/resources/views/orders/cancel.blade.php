@extends('layouts.app')

@section('content')
  <div class="container">
    <h1 class="stripe-message">決済がキャンセルされました</h1>
    <a href="{{ route('home') }}">商品一覧へ戻る</a>
  </div>
@endsection
