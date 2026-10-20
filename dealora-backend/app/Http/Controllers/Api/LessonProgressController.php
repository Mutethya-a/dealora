<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonProgressController extends Controller
{
    public function complete(
        Request $request,
        string $courseSlug,
        string $lessonSlug
    ): JsonResponse {
        
        $course = Course::query()
            ->where('slug', $courseSlug)
            ->where('is_published', true)
            ->whereHas('learningPath', function ($query) {
                $query->where('is_active', true);
            })
            ->firstOrFail();

        
        $lesson = $course->lessons()
            ->where('slug', $lessonSlug)
            ->where('is_published', true)
            ->firstOrFail();

        
        $enrollment = $course->enrollments()
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'You must enroll before completing lessons.',
            ], 403);
        }

       
        $progress = LessonProgress::firstOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        
        if (!$progress->is_completed) {
            $progress->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);
        }

        
        $totalLessons = $course->lessons()
            ->where('is_published', true)
            ->count();

        $completedLessons = LessonProgress::query()
            ->where('enrollment_id', $enrollment->id)
            ->where('is_completed', true)
            ->whereHas('lesson', function ($query) use ($course) {
                $query->where('course_id', $course->id)
                    ->where('is_published', true);
            })
            ->count();

        $percentage = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100)
            : 0;

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as completed!',
            'data' => [
                'lesson_id' => $lesson->id,
                'is_completed' => true,
                'completed_lessons' => $completedLessons,
                'total_lessons' => $totalLessons,
                'progress_percentage' => $percentage,
            ],
        ]);
    }
}
