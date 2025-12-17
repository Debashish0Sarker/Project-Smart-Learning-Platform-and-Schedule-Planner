<div class="relative" x-data="{ open: false, unreadCount: {{ auth()->user()->unreadNotifications->count() }} }">
    <button @click="open = !open" 
            class="relative p-2 text-gray-600 hover:text-gray-900">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>
        
        <span x-show="unreadCount > 0" 
              x-text="unreadCount"
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
        </span>
    </button>

    <div x-show="open" 
         @click.away="open = false"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-700">Notifications</h3>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
            @if(auth()->user()->notifications->count() === 0)
                <div class="p-4 text-center text-gray-500">
                    No notifications
                </div>
            @else
                @foreach(auth()->user()->notifications->take(5) as $notification)
                    @php
                        $data = $notification->data;
                    @endphp
                    <a href="{{ $data['url'] ?? '#' }}" 
                       class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}"
                       onclick="markAsRead('{{ $notification->id }}')">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 text-lg">
                                {{ $data['icon'] ?? '🔔' }}
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $data['title'] ?? 'Notification' }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $data['message'] ?? '' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if(!$notification->read_at)
                                <span class="h-2 w-2 bg-blue-500 rounded-full mt-2"></span>
                            @endif
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
        
        <div class="p-4 border-t">
            <a href="{{ auth()->user()->isTeacher() ? route('teacher.notifications.index') : route('student.notifications.index') }}" 
               class="block text-center text-sm text-blue-600 hover:text-blue-800">
                View All Notifications
            </a>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/student/notifications/${notificationId}/read`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    });
}
</script>