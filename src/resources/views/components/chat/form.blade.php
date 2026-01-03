<form
  action="{{ route('messages.store', ['chat_id' => $chat->id]) }}"
  method="POST"
  enctype="multipart/form-data"
  class="chat-form">
  @csrf

  <!-- バリデーションエラー表示 -->
  @if ($errors->any())
  <div class="chat-form__errors">
    @foreach ($errors->all() as $error)
    <p class="chat-form__error">{{ $error }}</p>
    @endforeach
  </div>
  @endif

  <!-- メッセージ入力 -->
  <div class="chat-form__row">
    <textarea
      name="body"
      class="chat-form__textarea"
      placeholder="取引メッセージを記入してください"
      rows="1">{{ old('body') }}</textarea>

    <!-- プレビュー表示 -->
    <div id="image-preview" class="chat-form_preview" style="display:none;">
      <img src="" id="preview-image" alt="画像プレビュー">
    </div>

    <!-- 画像添付 -->
    <label for="chat-image-input" class="chat-form__image-button">
      <input
        type="file"
        id="chat-image-input"
        name="image"
        accept="image/png,image/jpeg"
        hidden>
      画像を追加
    </label>

    <!-- 送信ボタン -->
    <button type="submit" class="chat-form__send-button">
      <img src="{{ asset('images/inputbutton.png') }}" alt="送信">
    </button>
  </div>
</form>

<script>
  document.addEventListener('input', function(e) {
    if (e.target.classList.contains('chat-form__textarea')) {
      e.target.style.height = 'auto';
      e.target.style.height = e.target.scrollHeight + 'px';
    }
  });

  const imageInput = document.getElementById('chat-image-input');
  const previewWrapper = document.getElementById('image-preview');
  const previewImage = document.getElementById('preview-image');

  if (imageInput) {
    imageInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function(event) {
        previewImage.src = event.target.result;
        previewWrapper.style.display = 'block';
      };
      reader.readAsDataURL(file);
    });
  }

  const chatId = "{{ $chat->id }}";
  const storageKey = `chat_draft_${chatId}`;
  const textarea = document.querySelector('.chat-form__textarea');

  document.addEventListener('DOMContentLoaded', () => {
    const savedDraft = localStorage.getItem(storageKey);
    if (saveDraft && textarea && textarea.value === '') {
      textarea.value = savedDraft;
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    }
  });

  if (textarea) {
    textarea.addEventListener('input', () => {
      localStorage.setItem(storageKey, textarea.value);
    });
  }

  const form = document.querySelector('.chat-form');
  if (form) {
    form.addEventListener('submit', () => {
      localStorage.removeItem(storageKey);
    });
  }
</script>