@php
if(!isset($motivationalTip)) {
    $motivationalTip = \App\Http\Controllers\Student\MotivationalTipController::getTip();
}
@endphp

<div class="mt-8">
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4 border border-blue-200">
        <div class="flex items-start">
            <div class="mr-3">
                <span class="text-2xl">💡</span>
            </div>
            <div>
                <h4 class="font-bold text-gray-800 mb-1">Daily Motivation</h4>
                <p class="text-gray-700 italic">"{{ $motivationalTip }}"</p>
            </div>
        </div>
    </div>
</div>