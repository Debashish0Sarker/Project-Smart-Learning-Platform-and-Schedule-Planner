@extends('layouts.teacher')

@section('title', 'Create Quiz')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Quiz</h1>
    
    <form id="quizForm" action="{{ route('teacher.quizzes.store') }}" method="POST">
        @csrf
        
        <!-- Quiz Basic Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Quiz Information</h2>
            
            <div class="space-y-4">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Quiz Title *
                    </label>
                    <input type="text" id="title" name="title" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter quiz title">
                </div>
                
                <!-- Course Selection -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Course *
                    </label>
                    <select id="course_id" name="course_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Choose a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->code }} - {{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Difficulty -->
                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                        Difficulty Level *
                    </label>
                    <select id="difficulty" name="difficulty" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe what this quiz covers..."></textarea>
                </div>
            </div>
        </div>
        
        <!-- Questions Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6" x-data="quizForm()">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-700">Questions</h2>
                <button type="button" @click="addQuestion()" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    + Add Question
                </button>
            </div>
            
            <!-- Questions Container -->
            <div id="questionsContainer">
                <template x-for="(question, qIndex) in questions" :key="qIndex">
                    <div class="border border-gray-200 rounded-lg p-6 mb-4 bg-gray-50">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-medium text-gray-700">
                                Question <span x-text="qIndex + 1"></span>
                            </h3>
                            <button type="button" @click="removeQuestion(qIndex)" 
                                    class="text-red-500 hover:text-red-700"
                                    x-show="questions.length > 1">
                                Remove
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Question Text -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Question Text *
                                </label>
                                <textarea x-model="question.question_text" 
                                          :name="'questions[' + qIndex + '][question_text]'"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                          rows="3" required></textarea>
                            </div>
                            
                            <!-- Question Type and Topic Tag -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Question Type *
                                    </label>
                                    <select x-model="question.question_type" 
                                            :name="'questions[' + qIndex + '][question_type]'"
                                            @change="updateQuestionType(qIndex)"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                                        <option value="mcq">Multiple Choice</option>
                                        <option value="true_false">True/False</option>
                                        <option value="short_answer">Short Answer</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Topic Tag *
                                    </label>
                                    <select :name="'questions[' + qIndex + '][topic_tag]'"
                                            x-model="question.topic_tag"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                                        <option value="">Select a topic</option>
                                        @foreach($topicTags as $tag)
                                            <option value="{{ $tag }}">{{ $tag }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Points -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Points *
                                </label>
                                <input type="number" x-model="question.points" 
                                       :name="'questions[' + qIndex + '][points]'"
                                       class="w-32 px-4 py-2 border border-gray-300 rounded-md" 
                                       min="1" max="10" required>
                            </div>
                            
                            <!-- Options Container (for MCQ) -->
                            <div x-show="question.question_type === 'mcq'">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Options * (Check correct answers)
                                </label>
                                <div class="space-y-2">
                                    <template x-for="(option, oIndex) in question.options" :key="oIndex">
                                        <div class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                   :name="'questions[' + qIndex + '][correct_answers][]'"
                                                   :value="String.fromCharCode(65 + oIndex)"
                                                   x-model="question.correct_answers"
                                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            <input type="text" 
                                                   :name="'questions[' + qIndex + '][options][' + String.fromCharCode(65 + oIndex) + ']'"
                                                   x-model="question.options[oIndex]"
                                                   class="flex-1 px-3 py-1 border border-gray-300 rounded" 
                                                   :placeholder="'Option ' + String.fromCharCode(65 + oIndex)"
                                                   required>
                                            <button type="button" @click="removeOption(qIndex, oIndex)" 
                                                    class="text-red-500 hover:text-red-700 px-2"
                                                    x-show="question.options.length > 2">
                                                ×
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="addOption(qIndex)" 
                                        class="mt-2 text-blue-500 hover:text-blue-700 text-sm">
                                    + Add Option
                                </button>
                            </div>
                            
                            <!-- True/False Answer -->
                            <div x-show="question.question_type === 'true_false'">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Correct Answer *
                                </label>
                                <select :name="'questions[' + qIndex + '][correct_answers][]'"
                                        x-model="question.correct_answers[0]"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            </div>
                            
                            <!-- Short Answer -->
                            <div x-show="question.question_type === 'short_answer'">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Correct Answer *
                                </label>
                                <textarea :name="'questions[' + qIndex + '][correct_answers][]'"
                                          x-model="question.correct_answers[0]"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-md" 
                                          rows="2" required></textarea>
                            </div>
                            
                            <!-- Explanation -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Explanation (Optional)
                                </label>
                                <textarea :name="'questions[' + qIndex + '][explanation]'"
                                          x-model="question.explanation"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-md" 
                                          rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-md font-semibold">
                Create Quiz
            </button>
        </div>
    </form>
</div>

<script>
function quizForm() {
    return {
        questions: [{
            question_text: '',
            question_type: 'mcq',
            topic_tag: '',
            points: 1,
            options: ['', ''],
            correct_answers: [],
            explanation: ''
        }],
        
        addQuestion() {
            this.questions.push({
                question_text: '',
                question_type: 'mcq',
                topic_tag: '',
                points: 1,
                options: ['', ''],
                correct_answers: [],
                explanation: ''
            });
        },
        
        removeQuestion(index) {
            if (this.questions.length > 1) {
                this.questions.splice(index, 1);
            }
        },
        
        addOption(qIndex) {
            this.questions[qIndex].options.push('');
        },
        
        removeOption(qIndex, oIndex) {
            if (this.questions[qIndex].options.length > 2) {
                this.questions[qIndex].options.splice(oIndex, 1);
            }
        },
        
        updateQuestionType(qIndex) {
            const question = this.questions[qIndex];
            
            if (question.question_type === 'mcq') {
                question.options = ['', ''];
                question.correct_answers = [];
            } else if (question.question_type === 'true_false') {
                question.correct_answers = ['true'];
            } else {
                question.correct_answers = [''];
            }
        }
    }
}
</script>
@endsection