@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
@endsection

@section('content')
<div class="sell">
  <h1 class="sell-title">商品の出品</h1>
  <form action="{{ route('items.store') }}" method="post" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="sell__image-area">
      <label for="image" class="sell__label">商品画像</label>
      <div class="image-preview" id="imagePreview">
        <img id="previewImage" src="#" alt="プレビュー画像" style="display:none;">
        <input type="file" name="image" id="image" class="sell__image-input">
        <button type="button" class="sell__image-btn" id="selectImageBtn">画像を選択する</button>
      </div>
      @error('image')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <div class="sell__detail-area">
      <h2 class="sell__subtitle">商品の詳細</h2>
      <div class="form-group form-group--category">
        <label for="category" class="sell__label">カテゴリー</label>
        @error('categories')
          <p class="error error--category">{{ $message }}</p>
        @enderror
        <div class="category-tags">
          @foreach($categories as $category)
            <label class="category-tag">
              <input type="checkbox" name="categories[]" value="{{ $category->id }}"
              @if(is_array(old('categories')) && in_array($category->id, old('categories'))) checked @endif>
              <span class="category-label">{{ $category->category }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <div class="sell__condition">
        <label for="condition" class="sell__label">商品の状態</label>

        <div class="custom-select" id="conditionSelect">
          <div class="custom-select__trigger" id="conditionTrigger">
            <span id="selectedCondition">選択してください</span>
            <img src="{{ asset('images/arrow.png') }}" alt="▼" class="arrow-icon">
          </div>

          <div class="custom-options" id="conditionOptions">
            @foreach($conditions as $condition)
              <div class="custom-option" data-value="{{ $condition->id }}">
                <img src="{{ asset('images/mark.png') }}" alt="✓" class="check-icon">
                <span>{{ $condition->condition }}</span>
              </div>
            @endforeach
          </div>
        </div>
        
        <input type="hidden" name="condition_id" id="conditionInput" value="{{ old('condition_id') }}">

        @error('condition_id')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="sell__form-area">
      <h2 class="sell__subtitle">商品名と説明</h2>
      <div class="form-group">
        <label for="name" class="sell__label">商品名</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}">
        @error('name')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="brand_name" class="sell__label">ブランド名</label>
        <input type="text" name="brand_name" id="brand_name" value="{{ old('brand_name') }}">
      </div>
      
      <div class="form-group">
        <label for="description" class="sell__label">商品の説明</label>
        <textarea name="description" id="description" class="form-textarea" rows="5">{{ old('description') }}</textarea>
        @error('description')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="price" class="sell__label">販売価格</label>
        <div class="price-input">
          <span class="price-prefix">￥</span>
          <input type="text" name="price" id="price" inputmode="numeric" value="{{ old('price') }}">
        </div>
        @error('price')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <button type="submit" class="sell-btn">出品する</button>
  </form>
</div>
@endsection

@section('scripts')
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
@endsection
