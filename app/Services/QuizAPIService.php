<?php
// app/Services/QuizAPIService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class QuizAPIService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('QUIZAPI_KEY');
        $this->baseUrl = env('QUIZAPI_BASE_URL', 'https://quizapi.io/api/v1');
    }

    /**
     * Get available categories/tags from QuizAPI
     */
    public function getCategories()
    {
        // Cache categories for 24 hours
        return Cache::remember('quizapi_categories', 86400, function () {
            $commonCategories = [
                'Linux', 'DevOps', 'Docker', 'Code', 'SQL', 'CMS', 
                'Bash', 'PHP', 'JavaScript', 'HTML', 'MySQL', 'Laravel',
                'Kubernetes', 'WordPress', 'AWS', 'Python', 'Java'
            ];
            
            try {
                // Try to fetch some questions to see available categories
                $response = Http::get($this->baseUrl . '/questions', [
                    'apiKey' => $this->apiKey,
                    'limit' => 30
                ]);
                
                if ($response->successful()) {
                    $questions = $response->json();
                    $categories = [];
                    
                    foreach ($questions as $question) {
                        if (isset($question['category'])) {
                            $categories[] = $question['category'];
                        }
                        if (isset($question['tags']) && is_array($question['tags'])) {
                            foreach ($question['tags'] as $tag) {
                                if (isset($tag['name'])) {
                                    $categories[] = $tag['name'];
                                }
                            }
                        }
                    }
                    
                    // Combine and deduplicate
                    $allCategories = array_unique(array_merge($commonCategories, $categories));
                    sort($allCategories);
                    return $allCategories;
                }
            } catch (\Exception $e) {
                // Fallback to common categories
            }
            
            return $commonCategories;
        });
    }

    /**
     * Fetch questions from QuizAPI
     */
    public function fetchQuestions($category, $difficulty = 'medium', $limit = 10)
    {
        try {
            $params = [
                'apiKey' => $this->apiKey,
                'limit' => $limit,
            ];

            if ($category !== 'random') {
                // Try as tag first, then as category
                $params['tags'] = $category;
            }

            if ($difficulty !== 'any') {
                $params['difficulty'] = $difficulty;
            }

            $response = Http::get($this->baseUrl . '/questions', $params);
            
            if ($response->successful()) {
                $questions = $response->json();
                
                // If no questions with tag, try category
                if (empty($questions) && $category !== 'random') {
                    unset($params['tags']);
                    $params['category'] = $category;
                    $response = Http::get($this->baseUrl . '/questions', $params);
                    $questions = $response->successful() ? $response->json() : [];
                }
                
                return $questions;
            }
            
            return [];
            
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Calculate score for answers
     */
    public function calculateScore($questions, $userAnswers)
    {
        $score = 0;
        $results = [];

        foreach ($questions as $index => $question) {
            $correctAnswers = $this->getCorrectAnswers($question);
            $userAnswer = $userAnswers[$index] ?? null;

            $isCorrect = $this->checkAnswer($correctAnswers, $userAnswer);
            
            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question' => $question['question'] ?? 'No question text',
                'user_answer' => $userAnswer,
                'correct_answers' => $correctAnswers,
                'is_correct' => $isCorrect,
                'explanation' => $question['explanation'] ?? null
            ];
        }

        return [
            'score' => $score,
            'total' => count($questions),
            'percentage' => count($questions) > 0 ? ($score / count($questions)) * 100 : 0,
            'results' => $results
        ];
    }

    /**
     * Extract correct answers from question
     */
    private function getCorrectAnswers($question)
    {
        $correctAnswers = [];
        
        if (isset($question['correct_answers'])) {
            foreach ($question['correct_answers'] as $key => $value) {
                if ($value === 'true') {
                    $answerKey = str_replace('_correct', '', $key);
                    $correctAnswers[] = $question['answers'][$answerKey] ?? null;
                }
            }
        }

        return array_filter($correctAnswers);
    }

    /**
     * Check if user answer is correct
     */
    private function checkAnswer($correctAnswers, $userAnswer)
    {
        if (empty($correctAnswers) || $userAnswer === null) {
            return false;
        }

        if (is_array($userAnswer)) {
            $userAnswer = array_filter($userAnswer);
            sort($userAnswer);
            sort($correctAnswers);
            return $userAnswer == $correctAnswers;
        }

        return in_array($userAnswer, $correctAnswers);
    }
}