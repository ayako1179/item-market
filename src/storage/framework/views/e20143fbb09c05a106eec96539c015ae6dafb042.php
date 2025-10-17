<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/purchase.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('orders.store', $item->id)); ?>" method="post">
  <?php echo csrf_field(); ?>
  <div class="order">
    <div class="order-left">
      <div class="order__item">
        <div class="order__item-image">
          <img src="<?php echo e(asset('storage/' .$item->image_path)); ?>" alt="<?php echo e($item->name); ?>" class="item-image">
        </div>
        <div class="order__item-title">
          <h1 class="item-name"><?php echo e($item->name); ?></h1>
          <p class="price">
            <span class="tax">¥</span><?php echo e(number_format($item->price)); ?>

          </p>
        </div>
      </div>
    
      <div class="order__payment">
        <label for="payment_method" class="item-title">支払い方法</label>
        <div class="custom-select" id="paymentSelect">
          <div class="custom-select__trigger">
            <span id="selected-text">選択してください</span>
            <img src="<?php echo e(asset('/images/arrow.png')); ?>" alt="▼" class="arrow-icon">
          </div>
          <div class="custom-options">
            <span class="custom-option" data-value="コンビニ払い">
              <img src="<?php echo e(asset('images/mark.png')); ?>" alt="" class="check-icon">コンビニ払い
            </span>
            <span class="custom-option" data-value="カード支払い">
              <img src="<?php echo e(asset('images/mark.png')); ?>" alt="" class="check-icon">カード支払い
            </span>
          </div>
        </div>
        <input type="hidden" name="payment_method" id="payment_value">
        <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <p class="error"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="order__address">
        <div class="address-group">
          <label for="address" class="item-title">配送先</label>
          <a href="<?php echo e(route('orders.address', ['item_id' => $item->id])); ?>">変更する</a>
        </div>
        <div class="item-address">
          <p>〒 <?php echo e($address['postal_code']); ?></p>
          <p><?php echo e($address['address']); ?>

            <span><?php echo e($address['building'] ?? ''); ?></span>
          </p>
        </div>
      </div>

      <input type="hidden" name="postal_code" value="<?php echo e($address['postal_code']); ?>">
      <input type="hidden" name="address" value="<?php echo e($address['address']); ?>">
      <input type="hidden" name="building" value="<?php echo e($address['building'] ?? ''); ?>">

      <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="error"><?php echo e($message); ?></p>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="order-right">
      <div class="order-confirm">
        <div class="confirm-box">
          <p class="confirm-title">商品代金</p>
        </div>

        <div class="confirm-box">
          <p class="confirm-value">
            <span class="confirm-tax">¥</span>
            <span class="price"><?php echo e(number_format($item->price)); ?></span>
          </p>
        </div>

        <div class="confirm-box">
          <p class="confirm-title">支払い方法</p>
        </div>

        <div class="confirm-box">
          <p class="confirm-value" id="selected-method">未選択</p>
        </div>
      </div>

      <div class="order-action">
        <button type="submit" class="btn-order">購入する</button>
      </div>
    </div>
  </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('paymentSelect');
    const trigger = select.querySelector('.custom-select__trigger');
    const options = select.querySelectorAll('.custom-option');
    const selectedText = document.getElementById('selected-text');
    const hiddenInput = document.getElementById('payment_value');
    const display = document.getElementById('selected-method');

    trigger.addEventListener('click', () => {
      select.classList.toggle('open');
    });

    options.forEach(option => {
      option.addEventListener('click', () => {
        options.forEach(o => o.classList.remove('selected'));
        option.classList.add('selected');

        selectedText.textContent = option.dataset.value;
        hiddenInput.value = option.dataset.value;
        display.textContent = option.dataset.value;

        select.classList.remove('open');
      });
    });

    document.addEventListener('click', (e) => {
      if (!select.contains(e.target)) {
        select.classList.remove('open');
      }
    });
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/orders/purchase.blade.php ENDPATH**/ ?>