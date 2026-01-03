<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>coachtechフリマ</title>
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
  <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>">
  <?php echo $__env->yieldContent('css'); ?>
</head>

<body>
  <div class="app">
    <header class="header">
      <div class="header__logo">
        <a href="<?php echo e(route('home')); ?>">
          <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="coachtech">
        </a>
      </div>

      <?php if(!in_array(Route::currentRouteName(), ['login', 'register'])): ?>
      <form action="<?php echo e(route('home')); ?>" method="GET" class="header__search">
        <input type="hidden" name="tab" value="<?php echo e($tab ?? 'recommend'); ?>">
        <input
          type="text"
          name="keyword"
          placeholder="なにをお探しですか？"
          value="<?php echo e(request('keyword')); ?>"
          onfocus="this.placeholder=''"
          onblur="this.placeholder='なにをお探しですか？'">
      </form>

      <nav class="header__nav">
        <?php if(auth()->guard()->check()): ?>
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline-form">
          <?php echo csrf_field(); ?>
          <button type="submit" class="logout-link">ログアウト</button>
        </form>

        <a href="<?php echo e(route('profile.mypage')); ?>">マイページ</a>

        <a href="<?php echo e(route('items.sell')); ?>" class="btn-sell">出品</a>
        <?php endif; ?>

        <?php if(auth()->guard()->guest()): ?>
        <a href="<?php echo e(route('login')); ?>">ログイン</a>
        <a href="<?php echo e(route('login')); ?>">マイページ</a>
        <a href="<?php echo e(route('login')); ?>" class="btn-sell">出品</a>
        <?php endif; ?>
      </nav>
      <?php endif; ?>
    </header>

    <div class="content">
      <?php echo $__env->yieldContent('content'); ?>
    </div>
  </div>
  <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>