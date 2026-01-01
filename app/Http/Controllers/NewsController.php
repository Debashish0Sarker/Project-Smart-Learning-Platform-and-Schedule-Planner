<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Show news dashboard
     */
    public function index()
    {
        // Show empty page initially - JavaScript will load content
        return view('news.index', [
            'hasApiKey' => !empty(config('services.newsapi.key')) && config('services.newsapi.key') !== 'your_newsapi_key_here',
            'isRealApi' => $this->newsService->isUsingRealApi(),
        ]);
    }

    /**
     * API endpoint for AJAX news
     */
    public function getNews(Request $request)
    {
        $category = $request->get('category', 'technology');
        $search = $request->get('search');
        
        if ($search) {
            $articles = $this->newsService->searchNews($search, 12);
        } else {
            $articles = $this->newsService->getTopHeadlines($category, 'us', 12);
        }
        
        return response()->json([
            'success' => true,
            'articles' => $articles,
            'count' => count($articles),
            'category' => $category,
            'search' => $search
        ]);
    }

    /**
     * Check API status
     */
    public function checkApi()
    {
        return response()->json([
            'api_key_configured' => !empty(config('services.newsapi.key')) && config('services.newsapi.key') !== 'your_newsapi_key_here',
            'using_real_api' => $this->newsService->isUsingRealApi(),
            'sample_request' => $this->newsService->getTopHeadlines('technology', 'us', 2),
        ]);
    }
}