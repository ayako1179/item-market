<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>coachtechフリマ</title>
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
  <link rel="stylesheet" href="{{ asset('css/chat.css')}}">
</head>

<body>
  <header class="layout-header">
    <div class="layout-header__inner">
      <a href="{{ route('home') }}">
        <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
      </a>
    </div>
  </header>

  <main class="chat-layout">
    @yield('content')
  </main>

</body>

</html>