{{-- [file name]: index.blade.php --}}
{{-- Location: resources/views/teacher/calendar/index.blade.php --}}

@extends('layouts.teacher')

@section('title', 'Google Calendar Integration')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Google Calendar</h1>
                <p class="text-gray-600 mt-2">Sync your schedule with Google Calendar</p>
            </div>
            
            <div class="flex gap-3">
                @if($isConnected)
                    <form action="{{ route('google-calendar.disconnect') }}" method="GET">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                            Disconnect
                        </button>
                    </form>
                @else
                    <a href="{{ route('google-calendar.connect') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        Connect Google Calendar
                    </a>
                @endif
            </div>
        </div>

        {{-- Connection Status --}}
        @if($isConnected)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-800 font-medium">Connected to Google Calendar</span>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-yellow-800">Connect to Google Calendar to sync your schedule</span>
                </div>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Create Event & Quick Actions --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Create Event Form --}}
                @if($isConnected)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold mb-4">Create New Event</h2>
                        <form action="{{ route('google-calendar.events.create') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                                    <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Start *</label>
                                        <input type="datetime-local" name="start" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">End *</label>
                                        <input type="datetime-local" name="end" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                    <input type="text" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg">
                                    Add to Google Calendar
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Quick Add</h2>
                    <div class="space-y-3">
                        @foreach($courses as $course)
                            <form action="{{ route('google-calendar.events.course', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left p-3 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">{{ Str::limit($course->title, 25) }}</span>
                                        <span class="text-blue-600 text-sm">Add Schedule</span>
                                    </div>
                                </button>
                            </form>
                        @endforeach
                        
                        @foreach($quizzes as $quiz)
                            <form action="{{ route('google-calendar.events.quiz', $quiz) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left p-3 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">{{ Str::limit($quiz->title, 25) }}</span>
                                        <span class="text-green-600 text-sm">Add to Calendar</span>
                                    </div>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column: Calendar Events --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold">Google Calendar Events</h2>
                        <p class="text-gray-600 text-sm mt-1">Your upcoming events from Google Calendar</p>
                    </div>
                    
                    <div class="p-6">
                        @if($isConnected)
                            @if(count($events) > 0)
                                <div class="space-y-4">
                                    @foreach($events as $event)
                                        <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg">
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-lg">{{ $event['title'] }}</h3>
                                                <p class="text-gray-600 text-sm mt-1">
                                                    {{ \Carbon\Carbon::parse($event['start'])->format('D, M d, Y \a\t h:i A') }}
                                                    @if($event['location'])
                                                        • {{ $event['location'] }}
                                                    @endif
                                                </p>
                                                @if($event['description'])
                                                    <p class="text-gray-700 mt-2 text-sm">{{ Str::limit($event['description'], 100) }}</p>
                                                @endif
                                            </div>
                                            <div class="ml-4 flex gap-2">
                                                <a href="{{ $event['htmlLink'] }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                                    View
                                                </a>
                                                <form action="{{ route('google-calendar.events.delete', $event['id']) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                                            onclick="return confirm('Delete this event from Google Calendar?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <p class="text-gray-500">No upcoming events in your Google Calendar</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <p class="text-gray-500">Connect to Google Calendar to view your events</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('event_url'))
<div id="event-success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <div class="text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">Event Added to Google Calendar!</h3>
            <p class="text-gray-600 mb-4">The event has been added to your Google Calendar.</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ session('event_url') }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    View in Calendar
                </a>
                <button onclick="document.getElementById('event-success-modal').remove()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('event-success-modal');
        setTimeout(() => {
            if (modal) modal.remove();
        }, 10000);
    });
</script>
@endif
@endsection