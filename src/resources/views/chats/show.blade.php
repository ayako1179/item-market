@extends('layouts.chat')

@section('content')
<div class="chat-container">
  <!-- サイドバー -->
  <aside class="chat-sidebar">
    @include('components.chat.sidebar')
  </aside>

  <!-- メイン -->
  <section class="chat-main">

    <!-- 取引相手・商品情報 -->
    <div class="chat-header-area">
      @include('components.chat.header')
    </div>

    <!-- メッセージ一覧 -->
    <div class="chat-messages">
      @include('components.chat.message', ['messages' => $messages])
    </div>

    <!-- フォーム -->
    <div class="chat-form">
      @include('components.chat.form')
    </div>
  </section>
</div>
@endsection