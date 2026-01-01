<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class NewsService
{
    protected $apiKey;
    protected $baseUrl = 'https://newsapi.org/v2/';
    protected $useMockData = false;

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
        
        // Check if API key is valid (not placeholder)
        if (empty($this->apiKey) || $this->apiKey === 'your_newsapi_key_here') {
            $this->useMockData = true;
            Log::info('NewsService: Using mock data - API key not configured');
        } else {
            Log::info('NewsService: API key found', [
                'key_preview' => substr($this->apiKey, 0, 8) . '...',
            ]);
        }
    }

    /**
     * Get news articles
     */
    public function getNews($category = null, $search = null, $pageSize = 10)
    {
        if ($this->useMockData) {
            Log::info('NewsService: Returning mock data');
            return $this->getMockArticles($category);
        }

        try {
            // Build parameters
            $params = [
                'apiKey' => $this->apiKey,
                'pageSize' => min($pageSize, 20),
                'language' => 'en',
            ];

            // Determine endpoint
            if ($search) {
                $endpoint = 'everything';
                $params['q'] = $search;
                $params['sortBy'] = 'publishedAt';
            } else {
                $endpoint = 'top-headlines';
                $params['country'] = 'us';
                if ($category) {
                    $params['category'] = $category;
                }
            }

            // Make API request with proper headers
            $response = Http::withOptions([
                'verify' => app()->environment('production'), // SSL for production
                'timeout' => 15,
                'connect_timeout' => 10,
            ])->withHeaders([
                'User-Agent' => 'SmartLearningPlatform/1.0 (https://smart-learning.test)',
                'Accept' => 'application/json',
            ])->get($this->baseUrl . $endpoint, $params);

            if ($response->successful()) {
                $data = $response->json();
                
                if (($data['status'] ?? '') === 'ok' && !empty($data['articles'])) {
                    Log::info('NewsService: API success', [
                        'articles' => count($data['articles']),
                    ]);
                    return $this->formatArticles($data['articles']);
                } else {
                    Log::warning('NewsService: API returned no articles', [
                        'status' => $data['status'] ?? 'unknown',
                    ]);
                    return $this->getMockArticles($category);
                }
            } else {
                Log::error('NewsService: API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return $this->getMockArticles($category);
            }

        } catch (\Exception $e) {
            Log::error('NewsService: Exception', [
                'message' => $e->getMessage(),
            ]);
            return $this->getMockArticles($category);
        }
    }

    /**
     * Get top headlines
     */
    public function getTopHeadlines($category = 'technology', $country = 'us', $pageSize = 10)
    {
        return $this->getNews($category, null, $pageSize);
    }

    /**
     * Search for news
     */
    public function searchNews($query, $pageSize = 10)
    {
        return $this->getNews(null, $query, $pageSize);
    }

    /**
     * Compatibility method
     */
    public function searchEducationNews($query = 'education technology', $pageSize = 10)
    {
        return $this->searchNews($query, $pageSize);
    }

    /**
     * Check if using real API
     */
    public function isUsingRealApi()
    {
        return !$this->useMockData;
    }

    /**
     * Format articles
     */
    protected function formatArticles($articles)
    {
        return array_map(function ($article) {
            // Clean up data
            $title = $article['title'] ?? 'No title available';
            $description = $article['description'] ?? 'No description available';
            
            // Remove placeholder text
            if (strpos($title, '[Removed]') !== false) {
                $title = 'Article removed from source';
            }
            
            // Truncate if needed
            if (str_ends_with($title, ' - ...')) {
                $title = substr($title, 0, -6);
            }

            return [
                'title' => $title,
                'description' => $description,
                'url' => $article['url'] ?? '#',
                'image' => $article['urlToImage'] ?? $this->getRandomPlaceholderImage(),
                'source' => $article['source']['name'] ?? 'Unknown Source',
                'published_at' => isset($article['publishedAt']) 
                    ? date('M d, Y', strtotime($article['publishedAt'])) 
                    : 'Recently',
                'author' => $article['author'] ?? 'Unknown Author',
            ];
        }, $articles);
    }

    /**
     * Mock articles for fallback
     */
    protected function getMockArticles($category = null)
    {
        $allArticles = [
            'technology' => [
                [
                    'title' => 'AI Revolution in Education Technology',
                    'description' => 'How artificial intelligence is transforming classroom learning and personalized education.',
                    'url' => 'https://example.com/ai-education',
                    'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=800&q=80',
                    'source' => 'EdTech Magazine',
                    'published_at' => 'Today',
                    'author' => 'Sarah Johnson'
                ],
                [
                    'title' => 'Latest Trends in Online Learning Platforms',
                    'description' => 'New features and technologies shaping digital education.',
                    'url' => 'https://example.com/online-learning-trends',
                    'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80',
                    'source' => 'Education Weekly',
                    'published_at' => 'Yesterday',
                    'author' => 'Michael Chen'
                ],
            ],
            'education' => [
                [
                    'title' => 'Coding Bootcamps vs Traditional CS Degrees',
                    'description' => 'Comparing educational paths for aspiring software developers.',
                    'url' => 'https://example.com/coding-bootcamps',
                    'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80',
                    'source' => 'Tech Education Review',
                    'published_at' => '2 days ago',
                    'author' => 'David Wilson'
                ],
                [
                    'title' => 'The Future of STEM Education',
                    'description' => 'Innovative approaches to teaching science, technology, engineering, and math.',
                    'url' => 'https://example.com/stem-future',
                    'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=800&q=80',
                    'source' => 'STEM Journal',
                    'published_at' => '3 days ago',
                    'author' => 'Dr. Lisa Wang'
                ],
            ],
            'science' => [
                [
                    'title' => 'Breakthrough in Quantum Computing',
                    'description' => 'Scientists achieve new milestone in quantum processing power.',
                    'url' => 'https://example.com/quantum-breakthrough',
                    'image' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?auto=format&fit=crop&w=800&q=80',
                    'source' => 'Science Daily',
                    'published_at' => 'Today',
                    'author' => 'Robert Kim'
                ],
            ],
            'business' => [
                [
                    'title' => 'EdTech Startups Raise Record Funding',
                    'description' => 'Education technology companies attract billions in venture capital.',
                    'url' => 'https://example.com/edtech-funding',
                    'image' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=800&q=80',
                    'source' => 'Business Tech',
                    'published_at' => '1 day ago',
                    'author' => 'Amanda Zhang'
                ],
            ],
        ];

        // Return category-specific articles or mixed
        if ($category && isset($allArticles[$category])) {
            return $allArticles[$category];
        }

        // Return mixed articles
        $mixedArticles = [];
        foreach ($allArticles as $catArticles) {
            $mixedArticles = array_merge($mixedArticles, $catArticles);
        }
        
        return $mixedArticles;
    }

    /**
     * Get random placeholder image
     */
    protected function getRandomPlaceholderImage()
    {
        $images = [
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1558021212-51b6ecfa0db9?auto=format&fit=crop&w=800&q=80',
        ];
        
        return $images[array_rand($images)];
    }
}