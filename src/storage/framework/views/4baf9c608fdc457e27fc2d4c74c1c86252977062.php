<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/mypage.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="mypage">
  <div class="mypage__profile">
    <img src="<?php echo e(asset('storage/' . $user->profile->profile_image)); ?>" alt="プロフィール写真" class="mypage__profile-image">
    <p class="mypage__username"><?php echo e($user->name); ?></p>
    <div class="btn">
      <a href="<?php echo e(route('profile.edit')); ?>">プロフィールを編集</a>
    </div>
  </div>

  <div class="mypage__tabs">
    <div class="mypage__tabs-inner">
      <a href="<?php echo e(route('profile.mypage', ['page' => 'sell'])); ?>" class="mypage__tab <?php echo e($page === 'sell' ? 'active' : ''); ?>">
        出品した商品
      </a>

      <a href="<?php echo e(route('profile.mypage', ['page' => 'buy'])); ?>" class="mypage__tab <?php echo e($page === 'buy' ? 'active' : ''); ?>">
        購入した商品
      </a>

      <a href="<?php echo e(route('profile.mypage', ['page' => 'trading'])); ?>" class="mypage__tab <?php echo e($page === 'trading' ? 'active' : ''); ?>">
        取引中の商品

        <?php if($tradingUnreadTotal > 0): ?>
          <span class="tab-notification">
            <?php echo e($tradingUnreadTotal); ?>

          </span>
        <?php endif; ?>
      </a>
    </div>
  </div>

  <div class="products__grid">
    <?php if($page === 'sell'): ?>
    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="product-card">
      <a href="<?php echo e(route('items.show', $item->id)); ?>">
        <div class="product-card__image">
          <img src="<?php echo e(asset('storage/' .$item->image_path)); ?>" alt="<?php echo e($item->name); ?>">
          <?php if($item->is_sold): ?>
          <div class="product-card__sold">
            <span class="sold-text">Sold</span>
          </div>
          <?php endif; ?>
        </div>
        <p class="product-card__name"><?php echo e($item->name); ?></p>
      </a>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <p class="product-text">出品商品はありません</p>
    <?php endif; ?>

    <?php elseif($page === 'buy'): ?>
    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="product-card">
      <a href="<?php echo e(route('items.show', $order->item->id)); ?>">
        <div class="product-card__image">
          <img src="<?php echo e(asset('storage/' . $order->item->image_path)); ?>" alt="<?php echo e($order->item->name); ?>">
          <?php if($order->item->is_sold): ?>
          <div class="product-card__sold">
            <span class="sold-text">Sold</span>
          </div>
          <?php endif; ?>
        </div>
        <p class="product-card__name"><?php echo e($order->item->name); ?></p>
      </a>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <p class="product-text">購入商品はありません</p>
    <?php endif; ?>
    <?php endif; ?>

    <?php if($page === 'trading'): ?>
    <?php echo $__env->make('profile.partials.trading', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
  </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/profile/mypage.blade.php ENDPATH**/ ?>