@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Notifications</h1>

    <div class="mb-4 flex justify-end space-x-2">
        <form action="{{ route('student.notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Mark all as read</button>
        </form>

        <form action="{{ route('student.notifications.clear-all') }}" method="POST" onsubmit="return confirm('Clear all notifications?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Clear all</button>
        </form>
    </div>

    @if($notifications->count() === 0)
        <div class="p-6 bg-white rounded shadow">No notifications.</div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notification)
                @php $data = $notification->data; @endphp
                <div class="p-4 bg-white rounded shadow flex justify-between items-start {{ $notification->read_at ? '' : 'border-l-4 border-blue-500' }}">
                    <div>
                        <a href="{{ $data['url'] ?? '#' }}" onclick="event.preventDefault(); document.getElementById('read-form-{{ $notification->id }}').submit();" class="text-lg font-semibold text-gray-800">{{ $data['title'] ?? 'Notification' }}</a>
                        <p class="text-sm text-gray-600">{{ $data['message'] ?? '' }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="flex flex-col items-end space-y-2">
                        @if(!$notification->read_at)
                            <form id="read-form-{{ $notification->id }}" action="{{ route('student.notifications.mark-as-read', $notification->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="px-3 py-1 bg-green-600 text-white rounded text-sm">Mark as read</button>
                            </form>
                        @endif

                        <form action="{{ route('student.notifications.delete', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
