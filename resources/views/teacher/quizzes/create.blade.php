@extends('layouts.teacher')

@section('title', 'Create Quiz')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Quiz</h1>
    
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
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
                           placeholder="Enter quiz title" required value="{{ old('title') }}">
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
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->code }} - {{ $course->title }}
                            </option>
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
                        <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="medium" {{ old('difficulty', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe what this quiz covers...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Questions Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6" id="questionsSection">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-700">Questions</h2>
                <button type="button" id="addQuestionBtn" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    + Add Question
                </button>
            </div>
            
            <!-- Questions Container -->
            <div id="questionsContainer">
                <!-- First question will be added by JavaScript -->
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" id="submitBtn"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-md font-semibold">
                Create Quiz
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionCount = 0;
    const questionsContainer = document.getElementById('questionsContainer');
    const addQuestionBtn = document.getElementById('addQuestionBtn');
    const topicTags = @json($topicTags);
    
    // Add first question
    addQuestion();
    
    // Add question button click handler
    addQuestionBtn.addEventListener('click', addQuestion);
    
    function addQuestion() {
        questionCount++;
        const questionIndex = questionCount - 1;
        
        const questionHtml = `
            <div class="border border-gray-200 rounded-lg p-6 mb-4 bg-gray-50 question-item" data-index="${questionIndex}">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-700">
                        Question ${questionCount}
                    </h3>
                    <button type="button" class="remove-question text-red-500 hover:text-red-700" 
                            ${questionCount === 1 ? 'style="display:none;"' : ''}>
                        Remove
                    </button>
                </div>
                
                <div class="space-y-4">
                    <!-- Question Text -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Question Text *
                        </label>
                        <textarea name="questions[${questionIndex}][question_text]"
                                  class="question-text w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  rows="3" required></textarea>
                    </div>
                    
                    <!-- Question Type and Topic Tag -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Question Type *
                            </label>
                            <select name="questions[${questionIndex}][question_type]"
                                    class="question-type w-full px-4 py-2 border border-gray-300 rounded-md" required>
                                <option value="mcq">Multiple Choice</option>
                                <option value="true_false">True/False</option>
                                <option value="short_answer">Short Answer</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Topic Tag *
                            </label>
                            <select name="questions[${questionIndex}][topic_tag]"
                                    class="topic-tag w-full px-4 py-2 border border-gray-300 rounded-md" required>
                                <option value="">Select a topic</option>
                                ${topicTags.map(tag => `<option value="${tag}">${tag}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                    
                    <!-- Points -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Points *
                        </label>
                        <input type="number" name="questions[${questionIndex}][points]"
                               class="points w-32 px-4 py-2 border border-gray-300 rounded-md" 
                               min="1" max="10" value="1" required>
                    </div>
                    
                    <!-- Options Container (for MCQ) -->
                    <div class="options-container" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Options * (Check correct answers)
                        </label>
                        <div class="options-list space-y-2">
                            <div class="option-item flex items-center space-x-2">
                                <input type="checkbox" 
                                       name="questions[${questionIndex}][correct_answers][]"
                                       value="A"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <input type="text" 
                                       name="questions[${questionIndex}][options][A]"
                                       class="flex-1 px-3 py-1 border border-gray-300 rounded" 
                                       placeholder="Option A" required>
                            </div>
                            <div class="option-item flex items-center space-x-2">
                                <input type="checkbox" 
                                       name="questions[${questionIndex}][correct_answers][]"
                                       value="B"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <input type="text" 
                                       name="questions[${questionIndex}][options][B]"
                                       class="flex-1 px-3 py-1 border border-gray-300 rounded" 
                                       placeholder="Option B" required>
                            </div>
                        </div>
                        <button type="button" class="add-option mt-2 text-blue-500 hover:text-blue-700 text-sm">
                            + Add Option
                        </button>
                    </div>
                    
                    <!-- True/False Answer -->
                    <div class="true-false-container" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Correct Answer *
                        </label>
                        <select name="questions[${questionIndex}][correct_answers][]"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                            <option value="true">True</option>
                            <option value="false">False</option>
                        </select>
                    </div>
                    
                    <!-- Short Answer -->
                    <div class="short-answer-container" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Correct Answer *
                        </label>
                        <textarea name="questions[${questionIndex}][correct_answers][]"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md" 
                                  rows="2" required></textarea>
                    </div>
                    
                    <!-- Explanation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Explanation (Optional)
                        </label>
                        <textarea name="questions[${questionIndex}][explanation]"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md" 
                                  rows="2"></textarea>
                    </div>
                </div>
            </div>
        `;
        
        questionsContainer.insertAdjacentHTML('beforeend', questionHtml);
        
        // Setup event listeners for this question
        const questionElement = questionsContainer.lastElementChild;
        setupQuestionEvents(questionElement, questionIndex);
        
        // Show MCQ options by default for first question
        if (questionCount === 1) {
            updateQuestionType(questionElement);
        }
    }
    
    function setupQuestionEvents(questionElement, questionIndex) {
        // Question type change handler
        const typeSelect = questionElement.querySelector('.question-type');
        typeSelect.addEventListener('change', () => updateQuestionType(questionElement));
        
        // Remove question button
        const removeBtn = questionElement.querySelector('.remove-question');
        removeBtn.addEventListener('click', function() {
            questionElement.remove();
            questionCount--;
            renumberQuestions();
        });
        
        // Add option button
        const addOptionBtn = questionElement.querySelector('.add-option');
        if (addOptionBtn) {
            addOptionBtn.addEventListener('click', function() {
                addOption(questionElement);
            });
        }
    }
    
    function updateQuestionType(questionElement) {
        const typeSelect = questionElement.querySelector('.question-type');
        const questionType = typeSelect.value;
        
        // Hide all containers
        questionElement.querySelector('.options-container').style.display = 'none';
        questionElement.querySelector('.true-false-container').style.display = 'none';
        questionElement.querySelector('.short-answer-container').style.display = 'none';
        
        // Show appropriate container
        if (questionType === 'mcq') {
            questionElement.querySelector('.options-container').style.display = 'block';
        } else if (questionType === 'true_false') {
            questionElement.querySelector('.true-false-container').style.display = 'block';
        } else if (questionType === 'short_answer') {
            questionElement.querySelector('.short-answer-container').style.display = 'block';
        }
    }
    
    function addOption(questionElement) {
        const optionsList = questionElement.querySelector('.options-list');
        const optionCount = optionsList.children.length;
        const nextLetter = String.fromCharCode(65 + optionCount); // A, B, C, etc.
        
        const optionHtml = `
            <div class="option-item flex items-center space-x-2">
                <input type="checkbox" 
                       name="questions[${questionElement.dataset.index}][correct_answers][]"
                       value="${nextLetter}"
                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <input type="text" 
                       name="questions[${questionElement.dataset.index}][options][${nextLetter}]"
                       class="flex-1 px-3 py-1 border border-gray-300 rounded" 
                       placeholder="Option ${nextLetter}" required>
                <button type="button" class="remove-option text-red-500 hover:text-red-700 px-2">
                    ×
                </button>
            </div>
        `;
        
        optionsList.insertAdjacentHTML('beforeend', optionHtml);
        
        // Add remove event to the new option
        const newOption = optionsList.lastElementChild;
        const removeBtn = newOption.querySelector('.remove-option');
        removeBtn.addEventListener('click', function() {
            if (optionsList.children.length > 2) {
                newOption.remove();
            }
        });
    }
    
    function renumberQuestions() {
        const questions = document.querySelectorAll('.question-item');
        questions.forEach((question, index) => {
            question.querySelector('h3').textContent = `Question ${index + 1}`;
            question.dataset.index = index;
            
            // Update all input names with new index
            updateInputNames(question, index);
            
            // Show/hide remove button
            const removeBtn = question.querySelector('.remove-question');
            removeBtn.style.display = questions.length > 1 ? '' : 'none';
        });
        questionCount = questions.length;
    }
    
    function updateInputNames(questionElement, newIndex) {
        // Update all input names in the question
        const inputs = questionElement.querySelectorAll('[name^="questions["]');
        inputs.forEach(input => {
            const oldName = input.name;
            const newName = oldName.replace(/questions\[\d+\]/, `questions[${newIndex}]`);
            input.name = newName;
        });
    }
    
    // Form validation
    document.getElementById('quizForm').addEventListener('submit', function(e) {
        let isValid = true;
        const errorMessages = [];
        
        // Check basic fields
        if (!document.getElementById('title').value.trim()) {
            errorMessages.push('Quiz title is required');
            isValid = false;
        }
        
        if (!document.getElementById('course_id').value) {
            errorMessages.push('Please select a course');
            isValid = false;
        }
        
        // Check questions
        const questions = document.querySelectorAll('.question-item');
        if (questions.length === 0) {
            errorMessages.push('At least one question is required');
            isValid = false;
        }
        
        questions.forEach((question, index) => {
            // Check question text
            const questionText = question.querySelector('.question-text');
            if (!questionText.value.trim()) {
                errorMessages.push(`Question ${index + 1}: Question text is required`);
                isValid = false;
            }
            
            // Check topic tag
            const topicTag = question.querySelector('.topic-tag');
            if (!topicTag.value) {
                errorMessages.push(`Question ${index + 1}: Please select a topic tag`);
                isValid = false;
            }
            
            // Check based on question type
            const questionType = question.querySelector('.question-type').value;
            
            if (questionType === 'mcq') {
                // Check MCQ options
                const optionInputs = question.querySelectorAll('.option-item input[type="text"]');
                let filledOptions = 0;
                optionInputs.forEach(opt => {
                    if (opt.value.trim()) filledOptions++;
                });
                
                if (filledOptions < 2) {
                    errorMessages.push(`Question ${index + 1}: At least 2 options are required for MCQ`);
                    isValid = false;
                }
                
                // Check at least one correct answer is selected
                const correctCheckboxes = question.querySelectorAll('.option-item input[type="checkbox"]:checked');
                if (correctCheckboxes.length === 0) {
                    errorMessages.push(`Question ${index + 1}: Select at least one correct answer for MCQ`);
                    isValid = false;
                }
            }
            
            if (questionType === 'true_false') {
                // True/False always has a value, so no validation needed
            }
            
            if (questionType === 'short_answer') {
                const answerTextarea = question.querySelector('.short-answer-container textarea');
                if (!answerTextarea.value.trim()) {
                    errorMessages.push(`Question ${index + 1}: Correct answer is required for Short Answer`);
                    isValid = false;
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fix the following errors:\n\n' + errorMessages.join('\n'));
        }
    });
});
</script>
@endsection