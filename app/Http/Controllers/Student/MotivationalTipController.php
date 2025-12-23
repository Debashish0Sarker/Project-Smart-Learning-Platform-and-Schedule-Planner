<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MotivationalTipController extends Controller
{
    public static function getTip()
    {
        // Cache for 1 hour to avoid too many API calls
        return Cache::remember('motivational_tip', 3600, function () {
            try {
                $response = Http::get('https://api.adviceslip.com/advice');
                if ($response->successful()) {
                    $data = $response->json();
                    return $data['slip']['advice'] ?? 'Keep learning and growing!';
                }
            } catch (\Exception $e) {
                // Fallback tips if API fails
                $fallbackTips = [
                    'The expert in anything was once a beginner.',
                    'Don\'t watch the clock; do what it does. Keep going.',
                    'The only way to learn is to do.',
                    'Success is the sum of small efforts, repeated day in and day out.',
                    'The beautiful thing about learning is that no one can take it away from you.',
                ];
                return $fallbackTips[array_rand($fallbackTips)];
            }
            return 'Stay motivated and keep pushing forward!';
        });
    }
}