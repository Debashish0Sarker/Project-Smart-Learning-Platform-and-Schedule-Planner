{{-- resources/views/news/partials/article-card.blade.php --}}
<div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <!-- Article Image -->
    <div class="h-48 overflow-hidden">
        <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" 
             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
    </div>
    
    <!-- Article Content -->
    <div class="p-6">
        <!-- Source & Date -->
        <div class="flex justify-between items-start mb-3">
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                {{ Str::limit($article['source'], 20) }}
            </span>
            <span class="text-gray-500 text-sm">{{ $article['published_at'] }}</span>
        </div>
        
        <!-- Title -->
        <h3 class="font-bold text-gray-900 text-lg mb-3 line-clamp-2">
            {{ $article['title'] }}
        </h3>
        
        <!-- Description -->
        <p class="text-gray-600 mb-4 line-clamp-3">
            {{ $article['description'] }}
        </p>
        
        <!-- Author & Read More -->
        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
            <span class="text-gray-500 text-sm">
                @if($article['author'] !== 'Unknown')
                    <i class="fas fa-user-edit mr-1"></i> {{ Str::limit($article['author'], 20) }}
                @endif
            </span>
            <a href="{{ $article['url'] }}" target="_blank" 
               class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                Read More
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>