@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Notifikasi</h1>

        @if (session('success'))
            <div>
                {{ session('success') }}
            </div>
        @endif

        @forelse ($notifications as $notification)
            <div>
                <strong>
                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                </strong>

                <p>
                    {{ $notification->data['message'] ?? '' }}
                </p>

                @if (is_null($notification->read_at))
                    <form
                        method="POST"
                        action="{{ route('notifications.read', $notification) }}"
                    >
                        @csrf

                        <button type="submit">
                            Tandai sudah dibaca
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p>Belum ada notifikasi.</p>
        @endforelse

        {{ $notifications->links() }}

        @if ($notifications->count())
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf

                <button type="submit">
                    Tandai semua sudah dibaca
                </button>
            </form>
        @endif
    </div>
@endsection