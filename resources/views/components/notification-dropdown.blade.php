@auth
    @php
        $unreadNotifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = auth()->user()
            ->unreadNotifications()
            ->count();
    @endphp

    <div class="notification-dropdown">
        <a href="{{ route('notifications.index') }}">
            Notifikasi

            @if ($unreadCount > 0)
                <span>{{ $unreadCount }}</span>
            @endif
        </a>

        @if ($unreadNotifications->isNotEmpty())
            <div>
                @foreach ($unreadNotifications as $notification)
                    <div>
                        <strong>
                            {{ $notification->data['title'] ?? 'Notifikasi' }}
                        </strong>

                        <p>
                            {{ $notification->data['message'] ?? '' }}
                        </p>

                        <form
                            method="POST"
                            action="{{ route('notifications.read', $notification) }}"
                        >
                            @csrf

                            <button type="submit">
                                Tandai sudah dibaca
                            </button>
                        </form>
                    </div>
                @endforeach

                <a href="{{ route('notifications.index') }}">
                    Lihat semua notifikasi
                </a>
            </div>
        @else
            <div>
                Belum ada notifikasi baru.
            </div>
        @endif
    </div>
@endauth