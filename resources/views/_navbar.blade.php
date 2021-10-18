<div id="sidebar">
    <ul class="sidebar-menu-list">
        <li>
            <a href="/room"><i class="fa fa-commenting" aria-hidden="true"></i>ルーム一覧</a>

            <ul class="sub-list sidebar-room-list">
                @empty($rooms['public'])
                @else
                <li>
                    <p class="sub-item">公開ルーム</p>
                    <ul>
                        @foreach($rooms['public'] as $index => $room)
                        <li class="sub-sub-item">
                            <a href="/room/{{ $room['id'] }}">{{ $room['room_name'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endempty

                @empty($rooms['private'])
                @else
                <li>
                    <p class="sub-item">プライベートルーム</p>
                    <ul>
                        @foreach($rooms['private'] as $index => $room)
                        <li class="sub-sub-item">
                            <a href="/room/{{ $room['id'] }}">{{ $room['room_name'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endempty

                <li>
                    <p class="sub-item">特別ルーム</p>
                    <ul>
                        <li class="sub-sub-item">
                            <a href="/room/ai-chat" class="ai-chat-room">AIチャットルーム</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <a href="/account-setting"><i class="fa fa-wrench" aria-hidden="true"></i>個人設定</a>
        </li>
    </ul>
</div>
