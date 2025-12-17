{{-- resources/views/teacher/notifications/index.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Teacher Notifications')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Teacher Notifications</h1>
        
        {{-- Actions --}}
        <div class="flex gap-4 mb-6">
            {{-- Mark All as Read Form --}}
            <form action="{{ route('teacher.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Mark All as Read
                </button>
            </form>
            
            {{-- Clear All Form --}}
            <form action="{{ route('teacher.notifications.clear-all') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Clear All
                </button>
            </form>
        </div>
        
        {{-- Notifications List --}}
        <div class="bg-white rounded-lg shadow">
            @if($notifications->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    <p class="text-lg">No notifications yet</p>
                </div>
            @else
                <div class="divide-y">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                        @endphp
                        
                        <div class="p-4 hover:bg-gray-50 {{ !$notification->read_at ? 'bg-blue-50' : '' }}">
                            <div class="flex items-start gap-3">
                                {{-- Icon --}}
                                <div class="text-2xl mt-1">
                                    {{ $data['icon'] ?? '🔔' }}
                                </div>
                                
                                {{-- Content --}}
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-lg">
                                                {{ $data['title'] ?? 'Notification' }}
                                            </h3>
                                            <p class="text-gray-600 mt-1">
                                                {{ $data['message'] ?? '' }}
                                            </p>
                                        </div>
                                        
                                        {{-- Time and Status --}}
                                        <div class="text-right">
                                            <span class="text-sm text-gray-500">
                                                {{ $notification->created_at->format('M d, Y h:i A') }}
                                            </span>
                                            @if(!$notification->read_at)
                                                <span class="ml-2 inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Type and Actions --}}
                                    <div class="flex justify-between items-center mt-3">
                                        <span class="text-sm text-gray-500">
                                            {{ ucfirst(str_replace('_', ' ', $data['type'] ?? 'general')) }}
                                        </span>
                                        
                                        <div class="flex gap-2">
                                            {{-- Mark as Read Form --}}
                                            @if(!$notification->read_at)
                                                <form action="{{ route('teacher.notifications.mark-as-read', $notification) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                                                        Mark as Read
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            {{-- Delete Form --}}
                                            <form action="{{ route('teacher.notifications.delete', $notification) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                            
                                            {{-- View Button if URL exists --}}
                                            @if(isset($data['url']))
                                                <a href="{{ $data['url'] }}" class="text-green-600 hover:text-green-800 text-sm">
                                                    View
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="p-4 border-t">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection