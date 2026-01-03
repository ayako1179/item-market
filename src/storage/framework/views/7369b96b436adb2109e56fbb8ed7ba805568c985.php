<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="auth auth--login">
  <h1 class="auth__title">ログイン</h1>

  <form action="<?php echo e(route('login')); ?>" method="POST" class="auth__form">
    <?php echo csrf_field(); ?>

    <div class="auth__group">
      <label for="email" class="auth__label">メールアドレス</label>
      <input type="text" id="email" name="email" value="<?php echo e(old('email')); ?>">
      
      <?php $__errorArgs = ['email'];
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

    <div class="auth__group">
      <label for="password" class="auth__label">パスワード</label>
      <input type="password" id="password" name="password">
      
      <?php $__errorArgs = ['password'];
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

    <button type="submit" class="btn--auth">ログインする</button>
  </form>

  <div class="auth__link">
    <a href="<?php echo e(route('register')); ?>">会員登録はこちら</a>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/auth/login.blade.php ENDPATH**/ ?>