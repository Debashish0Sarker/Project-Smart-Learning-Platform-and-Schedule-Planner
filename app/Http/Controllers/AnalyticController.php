<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseResource;

class AnalyticController extends Controller
{
    public function courseStats($courseCode)
    {
         $activityHoursMap = [
        'CSE471' => 6,   
        'CSE420' => 4,  
        'CSE423' => 3.8,   
        'CSE472' => 5.2,
        'CSE110' => 4.5
    ];
        $activityHoursAverage = $activityHoursMap[$courseCode];
        return response()->json([ 
            'success' => true,
            "course_code" => $courseCode,  
            "total students" => 120,        
            "average grade" => 85,         
            "highest grade" => 98,         
            "lowest grade" => 60,  
            "activity_hours_average" => $activityHoursAverage,        
            "topic_wise_stats" => [
                [
                    "topic" => "topic 1",
                    "average_score" => 82
                ],
                [
                    "topic" => "topic 2", 
                    "average_score" => 70
                ],
                [
                    "topic" => "topic 3",
                    "average_score" => 77
                ]
            ]
        ]);
    }  
}
