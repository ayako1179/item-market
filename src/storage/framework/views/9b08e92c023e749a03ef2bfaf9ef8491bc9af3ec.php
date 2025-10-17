<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/sell.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="sell">
  <h1 class="sell-title">商品の出品</h1>
  <form action="<?php echo e(route('items.store')); ?>" method="post" enctype="multipart/form-data" novalidate>
    <?php echo csrf_field(); ?>
    <div class="sell__image-area">
      <label for="image" class="sell__label">商品画像</label>
      <div class="image-preview" id="imagePreview">
        <img id="previewImage" src="#" alt="プレビュー画像" style="display:none;">
        <input type="file" name="image" id="image" class="sell__image-input">
        <button type="button" class="sell__image-btn" id="selectImageBtn">画像を選択する</button>
      </div>
      <?php $__errorArgs = ['image'];
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

    <div class="sell__detail-area">
      <h2 class="sell__subtitle">商品の詳細</h2>
      <div class="form-group form-group--category">
        <label for="category" class="sell__label">カテゴリー</label>
        <?php $__errorArgs = ['categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <p class="error error--category"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="category-tags">
          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="category-tag">
              <input type="checkbox" name="categories[]" value="<?php echo e($category->id); ?>"
              <?php if(is_array(old('categories')) && in_array($category->id, old('categories'))): ?> checked <?php endif; ?>>
              <span class="category-label"><?php echo e($category->category); ?></span>
            </label>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

      <div class="sell__condition">
        <label for="condition" class="sell__label">商品の状態</label>

        <div class="custom-select" id="conditionSelect">
          <div class="custom-select__trigger" id="conditionTrigger">
            <span id="selectedCondition">選択してください</span>
            <img src="<?php echo e(asset('images/arrow.png')); ?>" alt="▼" class="arrow-icon">
          </div>

          <div class="custom-options" id="conditionOptions">
            <?php $__currentLoopData = $conditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="custom-option" data-value="<?php echo e($condition->id); ?>">
                <img src="<?php echo e(asset('images/mark.png')); ?>" alt="✓" class="check-icon">
                <span><?php echo e($condition->condition); ?></span>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
        
        <input type="hidden" name="condition_id" id="conditionInput" value="<?php echo e(old('condition_id')); ?>">

        <?php $__errorArgs = ['condition_id'];
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
    </div>

    <div class="sell__form-area">
      <h2 class="sell__subtitle">商品名と説明</h2>
      <div class="form-group">
        <label for="name" class="sell__label">商品名</label>
        <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>">
        <?php $__errorArgs = ['name'];
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

      <div class="form-group">
        <label for="brand_name" class="sell__label">ブランド名</label>
        <input type="text" name="brand_name" id="brand_name" value="<?php echo e(old('brand_name')); ?>">
      </div>
      
      <div class="form-group">
        <label for="description" class="sell__label">商品の説明</label>
        <textarea name="description" id="description" class="form-textarea" rows="5"><?php echo e(old('description')); ?></textarea>
        <?php $__errorArgs = ['description'];
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

      <div class="form-group">
        <label for="price" class="sell__label">販売価格</label>
        <div class="price-input">
          <span class="price-prefix">￥</span>
          <input type="text" name="price" id="price" inputmode="numeric" value="<?php echo e(old('price')); ?>">
        </div>
        <?php $__errorArgs = ['price'];
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
    </div>

    <button type="submit" class="sell-btn">出品する</button>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('image');
  const button = document.getElementById('selectImageBtn');
  const preview = document.getElementById('previewImage');

  if (input && button && preview) {
    button.addEventListener('click', function() {
      input.click();
    });

    input.addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
          button.style.display = 'none';
        };
        reader.readAsDataURL(file);
      }
    });
  }

  const select = document.getElementById('conditionSelect');
  const trigger = document.getElementById('conditionTrigger');
  const options = document.getElementById('conditionOptions');
  const hiddenInput = document.getElementById('conditionInput');
  const selectedText = document.getElementById('selectedCondition');

  if (select && trigger && options && hiddenInput && selectedText) {
    trigger.addEventListener('click', () => {
      select.classList.toggle('open');
    });

    document.querySelectorAll('.custom-option').forEach(option => {
      option.addEventListener('click', () => {
        const value = option.dataset.value;
        const label = option.querySelector('span').textContent;

        hiddenInput.value = value;
        selectedText.textContent = label;

        document.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
        option.classList.add('selected');

        select.classList.remove('open');
      });
    });

    document.addEventListener('click', e => {
      if (!select.contains(e.target)) {
        select.classList.remove('open');
      }
    });
  }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/sell.blade.php ENDPATH**/ ?>