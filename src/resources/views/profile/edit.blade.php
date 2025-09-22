@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css')}}">
@endsection

@section('content')
<div class="edit">
  @if (session('success'))
    <div class="session-message">
      {{ session('success') }}
    </div>
  @endif

  <h1 class="edit__title">プロフィール設定</h1>

  <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="edit__form">
    @csrf

    <!-- プロフィール画像 -->
    <div class="edit__group profile-image">
      <div class="profile-preview">
        @php
          $file = $profile->profile_image ?: 'default.png';
        @endphp
        <img id="preview" src="{{ Storage::url('profile_images/'.$file) }}" alt="プロフィール画像">
      </div>
      <div class="edit__image-action">
        <input type="file" name="profile_image" id="profile_image" accept=".jpeg,.png" class="edit__input">
        <label for="profile_image" class="edit__button">画像を選択する</label>
      </div>
      @error('profile_image')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <!-- ユーザー名 -->
    <div class="edit__group">
      <label for="name" class="edit__label">ユーザー名</label>
      <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
      @error('name')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <!-- 郵便番号 -->
    <div class="edit__group">
      <label for="postal_code" class="edit__label">郵便番号</label>
      <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $profile->postal_code) }}">
      @error('postal_code')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <!-- 住所 -->
    <div class="edit__group">
      <label for="address" class="edit__label">住所</label>
      <input type="text" name="address" id="address" value="{{ old('address', $profile->address) }}">
      @error('address')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <div class="edit__group">
      <label for="building" class="edit__label">建物名</label>
      <input type="text" name="building" id="building" value="{{ old('building', $profile->building) }}">
    </div>

    <div class="edit__actions">
      <button type="submit" class="btn-primary">更新する</button>
    </div>
  </form>
</div>

<!-- プレビュー用スクリプト -->
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
@endsection