<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/show.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="show">
  <div class="show__image-area">
    <img src="<?php echo e(asset('storage/' .$item->image_path)); ?>" alt="<?php echo e($item->name); ?>" class="item-image">
    <?php if($item->is_sold): ?>
    <div class="show__image-sold">
      <span class="sold-text">Sold</span>
    </div>
    <?php endif; ?>
  </div>

  <div class="show__detail">
    <div class="show__detail-title">
      <h1 class="item-name"><?php echo e($item->name); ?></h1>
      <p class="brand"><?php echo e($item->brand_name); ?></p>
      <p class="price">
        <span class="tax">¥</span><?php echo e(number_format($item->price)); ?>

        <span class="tax">(税込)</span>
      </p>
      <div class="show__actions">
        <?php if(!$hasLiked): ?>
        <form action="<?php echo e(route('items.like.store', $item->id)); ?>" method="POST" class="like-action">
          <?php echo csrf_field(); ?>
          <button type="submit" class="action">
            <img src="<?php echo e(asset('images/like.png')); ?>" class="like-icon not-liked" alt="いいね">
            <span><?php echo e($item->likedBy->count()); ?></span>
          </button>
        </form>
        <?php else: ?>
        <form action="<?php echo e(route('items.like.destroy', $item->id)); ?>" method="POST" class="like-action">
          <?php echo csrf_field(); ?>
          <button type="submit" class="action">
            <img src="<?php echo e(asset('images/like.png')); ?>" class="like-icon liked" alt="いいね解除">
            <span><?php echo e($item->likedBy->count()); ?></span>
          </button>
        </form>
        <?php endif; ?>
        <div class="action">
          <img src="<?php echo e(asset('images/comment.png')); ?>" alt="コメント">
          <span><?php echo e($item->comments->count()); ?></span>
        </div>
      </div>

      <?php if($item->is_sold): ?>
      <button class="purchase-btn" disabled>Sold Out</button>
      <?php elseif(Auth::check() && $item->user_id === Auth::id()): ?>
      <button class="purchase-btn disabled" disabled>購入手続きへ</button>
      <?php else: ?>
      <a href="<?php echo e(route('orders.purchase', $item->id)); ?>" class="purchase-btn">購入手続きへ</a>
      <?php endif; ?>
    </div>

    <div class="show__description">
      <h2>商品説明</h2>
      <p><?php echo nl2br(e($item->description)); ?></p>
    </div>

    <div class="show__info">
      <h2>商品の情報</h2>

      <div class="show__info-categories">
        <span class="label">カテゴリー</span>
        <div class="category-tags">
          <?php $__currentLoopData = $item->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <span class="category-tag"><?php echo e($category->category); ?></span>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

      <div class="show__info-condition">
        <span class="label">商品の状態</span>
        <span class="condition"><?php echo e($item->condition->condition); ?></span>
      </div>
    </div>

    <div class="show__comments">
      <h3 class="comments-title">コメント (<?php echo e($item->comments->count()); ?>)</h3>

      <div class="comments-list">
        <?php $__currentLoopData = $item->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="comment-item">
          <div class="comment-body">
            <div class="comment-avatar">
              <img src="<?php echo e(asset('storage/' . ($comment->user->profile->profile_image))); ?>" alt="<?php echo e($comment->user->name); ?>">
            </div>
            <p class="comment-user"><?php echo e($comment->user->name); ?></p>
          </div>
          <p class="comment-text"><?php echo e($comment->comment); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    <div class="show__comment-form">
      <h2>商品へのコメント</h2>
      <form action="<?php echo e(route('items.comment.store', $item->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <textarea name="comment" class="comment-textarea" id="comment"><?php echo e(old('comment')); ?></textarea>
        <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="error"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <button type="submit" class="comment-btn">コメントを送信する</button>
      </form>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/show.blade.php ENDPATH**/ ?>