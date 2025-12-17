{{-- resources/views/student/practice-quiz/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Practice Quiz: {{ $category }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('student.practice-quiz.submit') }}" method="POST" id="quizForm">
                @csrf
                
                @foreach($questions as $index => $question)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-start mb-4">
                                <span class="bg-gray-100 text-gray-800 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                    {{ $index + 1 }}
                                </span>
                                <h3 class="text-lg font-medium text-gray-900">{{ $question['question'] }}</h3>
                            </div>
                            
                            <div class="ml-11">
                                @php
                                    $isMultiple = isset($question['multiple_correct_answers']) && 
                                                 $question['multiple_correct_answers'] === 'true';
                                @endphp
                                
                                @foreach(['a', 'b', 'c', 'd', 'e', 'f'] as $option)
                                    @if(!empty($question['answers']['answer_' . $option]))
                                        <div class="mb-3">
                                            <label class="inline-flex items-center cursor-pointer">
                                                @if($isMultiple)
                                                    <input type="checkbox" 
                                                           name="answers[{{ $index }}][]" 
                                                           value="{{ $question['answers']['answer_' . $option] }}"
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                @else
                                                    <input type="radio" 
                                                           name="answers[{{ $index }}]" 
                                                           value="{{ $question['answers']['answer_' . $option] }}"
                                                           class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                @endif
                                                <span class="ml-2 text-gray-700">{{ $question['answers']['answer_' . $option] }}</span>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-4 shadow-lg">
                    <div class="max-w-7xl mx-auto flex justify-between items-center">
                        <span class="text-gray-600">{{ count($questions) }} questions</span>
                        <button type="submit" 
                                class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2"
                                style="color: #1f2937 !important; background-color: #86efac !important; border: 2px solid #16a34a;">
                            <span class="flex items-center" style="color: inherit;">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #166534;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span style="font-weight: 700; color: #166534;">Submit Quiz</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            const totalQuestions = {{ count($questions) }};
            let unanswered = 0;
            
            for(let i = 0; i < totalQuestions; i++) {
                const checkboxes = document.querySelectorAll(`input[name="answers[${i}][]"]`);
                const radios = document.querySelectorAll(`input[name="answers[${i}]"]`);
                
                let answered = false;
                if(checkboxes.length > 0) {
                    answered = Array.from(checkboxes).some(cb => cb.checked);
                } else if(radios.length > 0) {
                    answered = Array.from(radios).some(radio => radio.checked);
                }
                
                if(!answered) unanswered++;
            }
            
            if(unanswered > 0) {
                e.preventDefault();
                if(confirm(`You have ${unanswered} unanswered question(s). Submit anyway?`)) {
                    this.submit();
                }
            }
        });
    </script>
</x-app-layout>