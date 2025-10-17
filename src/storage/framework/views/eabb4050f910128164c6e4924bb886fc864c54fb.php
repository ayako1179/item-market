<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/items-index.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="products">
  <div class="products__tabs">
    <div class="products__tabs-inner">
      <a href="<?php echo e(route('home', ['tab' => 'recommend', 'keyword' => request('keyword')])); ?>" class="products__tab <?php echo e(($tab ?? 'recommend') === 'recommend' ? 'products__tab--active' : ''); ?>">おすすめ</a>

      <a href="<?php echo e(route('home', ['tab' => 'mylist', 'keyword' => request('keyword')])); ?>" class="products__tab <?php echo e(($tab ?? '') === 'mylist' ? 'products__tab--active' : ''); ?>">マイリスト</a>
    </div>
  </div>

  <div class="products__grid">
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/index.blade.php ENDPATH**/ ?>