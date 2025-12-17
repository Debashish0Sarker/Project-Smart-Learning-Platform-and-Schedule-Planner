<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                📚 Materials: {{ $course->title }}
            </h2>
            <a href="{{ route('student.dashboard') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <style>
        .materials-shell {
            max-width: 1100px;
            margin: auto;
        }

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }

        .material-card {
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            transition: all 0.25s ease;
        }

        .material-card:hover {
            transform: translateY(-4px);
            border-color: #c7d2fe;
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            background: #fafafa;
        }

        .material-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .action-btn {
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.15);
        }
    </style>

    <div class="py-10">
        <div class="materials-shell px-4">

            <!-- Course Info -->
            <div class="card mb-8">
                <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $course->title }}</h3>
                        <p class="text-gray-600 mt-1">
                            {{ $course->code }} • {{ $course->category }}
                        </p>
                        <p class="text-gray-700 mt-3 max-w-2xl">
                            {{ $course->description }}
                        </p>
                    </div>

                    <div>
                        <span class="inline-flex px-4 py-1.5 rounded-full text-sm font-semibold
                            {{ $course->status == 'published'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($course->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Materials Section -->
            <div class="card mb-10">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            Course Materials
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $materials->count() }} materials available
                        </p>
                    </div>

                    @if($materials->isEmpty())
                        <div class="text-center py-16">
                            <div class="text-5xl mb-4">📭</div>
                            <h4 class="text-xl font-semibold text-gray-700 mb-2">
                                No Materials Yet
                            </h4>
                            <p class="text-gray-600">
                                Check back later — materials will appear here.
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($materials as $material)
                                <div class="material-card p-5 flex flex-col">
                                    
                                    <!-- Icon -->
                                    <div class="mb-4">
                                        @if($material->type == 'video')
                                            <div class="material-icon bg-red-100 text-red-700">🎬</div>
                                        @elseif($material->type == 'pdf')
                                            <div class="material-icon bg-blue-100 text-blue-700">📄</div>
                                        @elseif($material->type == 'image')
                                            <div class="material-icon bg-green-100 text-green-700">🖼️</div>
                                        @else
                                            <div class="material-icon bg-gray-100 text-gray-700">📎</div>
                                        @endif
                                    </div>

                                    <!-- Title -->
                                    <h4 class="font-bold text-gray-900 mb-2">
                                        {{ $material->title }}
                                    </h4>

                                    <!-- Description -->
                                    @if($material->description)
                                        <p class="text-gray-600 text-sm mb-4">
                                            {{ Str::limit($material->description, 90) }}
                                        </p>
                                    @endif

                                    <!-- Type Badge -->
                                    <div class="mb-5">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $material->type == 'video' ? 'bg-red-100 text-red-800' :
                                               ($material->type == 'pdf' ? 'bg-blue-100 text-blue-800' :
                                               ($material->type == 'image' ? 'bg-green-100 text-green-800' :
                                               'bg-gray-100 text-gray-800')) }}">
                                            {{ strtoupper($material->type) }}
                                        </span>
                                    </div>

                                    <!-- Action -->
                                    <div class="mt-auto">
                                        @if($material->type == 'video')
                                            <a href="{{ route('student.material.show', $material) }}"
                                               class="block w-full text-center px-4 py-2 bg-red-600 text-white action-btn hover:bg-red-700">
                                                ▶ Watch Video
                                            </a>
                                        @elseif($material->type == 'pdf')
                                            <a href="{{ route('student.material.download', $material) }}"
                                               class="block w-full text-center px-4 py-2 bg-blue-600 text-white action-btn hover:bg-blue-700">
                                                ⬇ Download PDF
                                            </a>
                                        @else
                                            <a href="{{ route('student.material.show', $material) }}"
                                               class="block w-full text-center px-4 py-2 bg-gray-700 text-white action-btn hover:bg-gray-800">
                                                View Material
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Motivational Tip -->
            @include('student.partials.motivational-tip')

        </div>
    </div>

    @section('scripts')
        <script>
            function markAsViewed(materialId) {
                fetch(`/student/materials/${materialId}/mark-viewed`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Marked as viewed!');
                    }
                })
                .catch(err => console.error(err));
            }
        </script>
    @endsection
</x-app-layout>
