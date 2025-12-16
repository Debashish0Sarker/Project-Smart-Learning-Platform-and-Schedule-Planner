<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Student Dashboard</h1>
                    <p class="mb-4">Welcome, {{ auth()->user()->name }}!</p>

                    <form action="/logout" method="POST" class="mb-6">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Logout
                        </button>
                    </form>

                    <h2 class="text-xl font-semibold mb-4">Available Quizzes</h2>

                    @if(isset($upcomingQuizzes) && count($upcomingQuizzes) > 0)
                        <div class="space-y-2">
                            @foreach($upcomingQuizzes as $quiz)
                                <a href="{{ route('student.quizzes.show', $quiz->id) }}" 
                                   class="block px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-md transition duration-150 ease-in-out">
                                    Start {{ $quiz->title }}
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No quizzes available at the moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>