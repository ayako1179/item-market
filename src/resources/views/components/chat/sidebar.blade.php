<h1 class="chat-sidebar__title">
  その他の取引
</h1>

<ul class="chat-sidebar__list">
  @foreach ($chats as $sidebarChat)
  <li class="chat-sidebar__item">
    <a
      href="{{ route('chats.show', $sidebarChat->id) }}"
      class="chat-sidebar__link">

      {{ $sidebarChat->order->item->name }}

    </a>
  </li>
  @endforeach
</ul>