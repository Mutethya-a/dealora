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
        $user = $request->user();

        // Find the published course
        $course = Course::query()
            ->where('slug', $courseSlug)
            ->where('is_published', true)
            ->whereHas('learningPath', function ($query) {
                $query->where('is_active', true);
            })
            ->firstOrFail();

        // Find the lesson belonging to this course
        $lesson = $course->lessons()
            ->where('slug', $lessonSlug)
            ->where('is_published', true)
            ->firstOrFail();

        // Only enrolled learners can complete lessons
        $isEnrolled = $course->enrollments()
            ->where('user_id', $user->id)
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'You must enroll before completing lessons.',
            ], 403);
        }

        // Save completion using your existing table structure
        $progress = LessonProgress::firstOrNew([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        $progress->status = 'completed';

        if (!$progress->completed_at) {
            $progress->completed_at = now();
        }

        $progress->save();

        // Count published lessons in this course
        $totalLessons = $course->lessons()
            ->where('is_published', true)
            ->count();

        // Count lessons completed by this learner
        $completedLessons = LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereIn(
                'lesson_id',
                $course->lessons()
                    ->where('is_published', true)
                    ->select('id')
            )
            ->count();

        // Calculate course progress
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
