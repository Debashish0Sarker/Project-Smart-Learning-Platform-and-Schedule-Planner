<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education & Tech News - Smart Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Simple Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-6">
                    <a href="/" class="text-blue-600 font-bold text-lg">
                        🎓 Smart Learning
                    </a>
                    <a href="/dashboard" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <a href="/news" class="text-blue-600 font-medium">News</a>
                </div>
                <div class="flex items-center space-x-4">
                    @if(auth()->check())
                        <span class="text-gray-700">Hello, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">📰 Education & Technology News</h1>
            <p class="text-gray-600 text-lg">Stay updated with the latest in education technology, online learning, and academic innovations.</p>
            
            <!-- API Status Banner -->
            <div id="apiStatus" class="mt-4">
                @if($isRealApi)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>✓ Connected to NewsAPI</strong> - Showing real news articles
                        </p>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Using demo data.</strong> 
                            @if(!$hasApiKey)
                                Get a free API key from <a href="https://newsapi.org" target="_blank" class="underline font-medium">NewsAPI.org</a> 
                                and add it to your .env file as <code>NEWS_API_KEY=your_key_here</code>
                            @else
                                API key found but connection failed. Showing demo data.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-8">
            <form id="newsSearchForm" class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" id="newsSearch" 
                           placeholder="Search education technology news..." 
                           class="w-full px-5 py-4 pr-12 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Category Filter -->
        <div class="mb-8">
            <div class="flex flex-wrap gap-3 justify-center">
                <button onclick="loadNews('technology')" 
                        class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors category-btn active">
                    <i class="fas fa-laptop-code mr-2"></i> Technology
                </button>
                <button onclick="loadNews('education')" 
                        class="px-5 py-2.5 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 transition-colors category-btn">
                    <i class="fas fa-graduation-cap mr-2"></i> Education
                </button>
                <button onclick="loadNews('science')" 
                        class="px-5 py-2.5 rounded-lg bg-purple-600 text-white font-medium hover:bg-purple-700 transition-colors category-btn">
                    <i class="fas fa-flask mr-2"></i> Science
                </button>
                <button onclick="loadNews('business')" 
                        class="px-5 py-2.5 rounded-lg bg-orange-600 text-white font-medium hover:bg-orange-700 transition-colors category-btn">
                    <i class="fas fa-briefcase mr-2"></i> Business
                </button>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="text-center py-12 hidden">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Loading news articles...</p>
        </div>

        <!-- News Grid -->
        <div id="newsContainer" class="fade-in">
            <!-- Content will be loaded here -->
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="hidden text-center py-12">
            <i class="fas fa-newspaper text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-medium text-gray-700 mb-2">No articles found</h3>
            <p class="text-gray-600">Try a different search term or category.</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-12 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="text-center text-gray-500">
                <p>© {{ date('Y') }} Smart Learning Platform. All rights reserved.</p>
                <p class="mt-2 text-sm">Powered by NewsAPI</p>
                @if(!$isRealApi)
                    <p class="mt-1 text-xs text-yellow-600">Currently showing demo data</p>
                @endif
            </div>
        </div>
    </footer>

    <script>
    let currentCategory = 'technology';

    function loadNews(category, search = '') {
        currentCategory = category;
        
        // Update active button
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('active', 'ring-2', 'ring-offset-2', 'ring-blue-500');
        });
        event?.target.classList.add('active', 'ring-2', 'ring-offset-2', 'ring-blue-500');
        
        // Show loading
        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('newsContainer').innerHTML = '';
        document.getElementById('noResults').classList.add('hidden');
        
        // Build URL
        let url = '/news/articles?category=' + category;
        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }
        
        // Fetch news
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('loading').classList.add('hidden');
                
                if (data.success && data.articles && data.articles.length > 0) {
                    renderArticles(data.articles);
                } else {
                    showNoResults();
                }
            })
            .catch(error => {
                console.error('Error loading news:', error);
                document.getElementById('loading').classList.add('hidden');
                showDemoData();
            });
    }

    function renderArticles(articles) {
        const container = document.getElementById('newsContainer');
        container.innerHTML = '';
        
        if (articles.length === 0) {
            showNoResults();
            return;
        }
        
        // Create grid
        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in';
        
        // Add articles
        articles.forEach(article => {
            // Handle missing images
            const imageUrl = article.image || 'https://images.unsplash.com/photo-1589256469067-ea99122bbdc4?auto=format&fit=crop&w=800&q=80';
            
            const articleHTML = `
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="h-48 overflow-hidden bg-gray-100">
                        <img src="${imageUrl}" alt="${article.title}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                             onerror="this.src='https://images.unsplash.com/photo-1589256469067-ea99122bbdc4?auto=format&fit=crop&w=800&q=80'">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                                ${article.source}
                            </span>
                            <span class="text-gray-500 text-sm">${article.published_at}</span>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-3 line-clamp-2">
                            ${article.title}
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            ${article.description}
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">${article.author}</span>
                            <a href="${article.url}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                                Read More <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            `;
            grid.innerHTML += articleHTML;
        });
        
        container.appendChild(grid);
    }

    function showNoResults() {
        document.getElementById('newsContainer').innerHTML = '';
        document.getElementById('noResults').classList.remove('hidden');
    }

    function showDemoData() {
        const demoArticles = [
            {
                title: 'AI in Education: Transforming Learning Experiences',
                description: 'How artificial intelligence is revolutionizing classroom learning and personalized education.',
                url: '#',
                image: 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=800&q=80',
                source: 'EdTech Magazine',
                published_at: 'Today',
                author: 'Sarah Johnson'
            },
            {
                title: 'Latest Trends in Online Learning Platforms',
                description: 'A look at the newest features and technologies shaping digital education.',
                url: '#',
                image: 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80',
                source: 'Education Weekly',
                published_at: 'Yesterday',
                author: 'Michael Chen'
            },
            {
                title: 'Coding Bootcamps vs Traditional Computer Science Degrees',
                description: 'Comparing educational paths for aspiring software developers.',
                url: '#',
                image: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80',
                source: 'Tech Education Review',
                published_at: '2 days ago',
                author: 'David Wilson'
            },
            {
                title: 'Virtual Reality Classrooms: The Future of Education?',
                description: 'Exploring VR technology in immersive learning environments.',
                url: '#',
                image: 'https://images.unsplash.com/photo-1593508512255-86ab42a8e620?auto=format&fit=crop&w=800&q=80',
                source: 'Future Learning',
                published_at: '3 days ago',
                author: 'Emma Rodriguez'
            },
            {
                title: 'Gamification in Education: Making Learning Fun',
                description: 'How game elements improve student engagement and retention.',
                url: '#',
                image: 'https://images.unsplash.com/photo-1551650975-87deedd944c3?auto=format&fit=crop&w=800&q=80',
                source: 'Learning Innovations',
                published_at: '4 days ago',
                author: 'James Miller'
            },
            {
                title: 'The Rise of Microlearning in Corporate Training',
                description: 'Short, focused learning modules are becoming the norm in workplace education.',
                url: '#',
                image: 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=800&q=80',
                source: 'Business Education',
                published_at: '5 days ago',
                author: 'Robert Taylor'
            }
        ];
        
        renderArticles(demoArticles);
        
        // Update status to show we're using demo data
        const statusDiv = document.getElementById('apiStatus');
        if (statusDiv && !statusDiv.querySelector('.bg-yellow-50')) {
            statusDiv.innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Showing demo data.</strong> API connection failed. 
                        Check your API key and try again.
                    </p>
                </div>
            `;
        }
    }

    // Search form handler
    document.getElementById('newsSearchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const searchTerm = document.getElementById('newsSearch').value.trim();
        if (searchTerm) {
            loadNews(currentCategory, searchTerm);
        }
    });

    // Load tech news by default on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadNews('technology');
    });
    </script>
</body>
</html>