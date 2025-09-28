@extends('layouts.app')

@section('content')
  <div class="container">
    <h1 class="stripe-message">決済が完了しました</h1>
    <a href="{{ route('profile.mypage') }}">マイページへ</a>
  </div>
@endsection
