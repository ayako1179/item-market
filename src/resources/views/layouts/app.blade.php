<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>coachtechフリマ</title>
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
  <link rel="stylesheet" href="{{ asset('css/common.css')}}">
  @yield('css')
</head>

<body>
  <div class="app">
    <header class="header">
      <!-- 左：タイトル画像 -->
      <div class="header__logo">
        <a href="{{ route('home') }}">
          <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
        </a>
      </div>

      <!-- ログイン・登録画面以外の時だけ nav を表示 -->
      @if (!in_array(Route::currentRouteName(), ['login', 'register']))
        <!-- 中央：検索フォーム -->
        <form action="{{ route('home') }}" method="GET" class="header__search">
          <input type="hidden" name="tab" value="{{ $tab ?? 'recommend' }}">
          <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}" onfocus="this.placeholder=''" onblur="this.placeholder='なにをお探しですか？'"
          @guest disabled @endguest>
        </form>

        <!-- 右：ナビゲーション -->
        <nav class="header__nav">
          @auth
            <!-- ログアウト -->
            <form action="{{ route('logout') }}" method="POST" class="inline-form">
              @csrf
              <button type="submit" class="logout-link">ログアウト</button>
            </form>

            <!-- マイページ -->
            <a href="{{ route('profile.index') }}">マイページ</a>

            <!-- 出品 -->
            <a href="{{ route('items.sell') }}" class="btn-sell">出品</a>
          @endauth

          @guest
            <!-- 未ログイン時でも見えるけど押すとログイン画面へ -->
            <a href="{{ route('login') }}">ログイン</a>
            <a href="{{ route('login') }}">マイページ</a>
            <a href="{{ route('login') }}" class="btn-sell">出品</a>
          @endguest
        </nav>
      @endif
    </header>

    <div class="content">
      <!-- @if (session('success'))
        <div class="session-message">
          {{ session('success') }}
        </div>
      @endif -->

      @yield('content')
    </div>
  </div>
</body>

</html>