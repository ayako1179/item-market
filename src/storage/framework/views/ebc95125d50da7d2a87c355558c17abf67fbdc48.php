<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/edit.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="edit">
  <h1 class="edit__title">プロフィール設定</h1>

  <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data" class="edit__form">
    <?php echo csrf_field(); ?>

    <div class="edit__group profile-image">
      <div class="profile-preview">
        <?php
        if (!empty($profile->profile_image) && str_contains($profile->profile_image, 'profile_images/')) {
        $imagePath = asset('storage/' . $profile->profile_image);
        } else {
        $imagePath = asset('images/default_profile.png');
        }
        ?>

        <img id="preview" src="<?php echo e($imagePath); ?>" alt="プロフィール画像">
      </div>
      <div class="edit__image-action">
        <input type="file" name="profile_image" id="profile_image" accept=".jpeg,.png" class="edit__input">
        <label for="profile_image" class="edit__button">画像を選択する</label>
      </div>
      <?php $__errorArgs = ['profile_image'];
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

    <div class="edit__group">
      <label for="name" class="edit__label">ユーザー名</label>
      <input type="text" name="name" id="name" value="<?php echo e(old('name', $user->name)); ?>">
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

    <div class="edit__group">
      <label for="postal_code" class="edit__label">郵便番号</label>
      <input type="text" name="postal_code" id="postal_code" value="<?php echo e(old('postal_code', $profile->postal_code)); ?>">
      <?php $__errorArgs = ['postal_code'];
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

    <div class="edit__group">
      <label for="address" class="edit__label">住所</label>
      <input type="text" name="address" id="address" value="<?php echo e(old('address', $profile->address)); ?>">
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

    <div class="edit__group">
      <label for="building" class="edit__label">建物名</label>
      <input type="text" name="building" id="building" value="<?php echo e(old('building', $profile->building)); ?>">
    </div>

    <div class="edit__actions">
      <button type="submit" class="btn-primary">更新する</button>
    </div>
  </form>
</div>

<script>
  document.getElementById('profile_image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('preview').src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/profile/edit.blade.php ENDPATH**/ ?>