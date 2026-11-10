<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseProgressController extends Controller
{
    public function show(
        Request $request,
        string $slug
    ): JsonResponse {
        $user = $request->user();

        // Find the published course
        $course = Course::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->whereHas('learningPath', function ($query) {
                $query->where('is_active', true);
            })
            ->firstOrFail();

        // Check learner enrollment
        $isEnrolled = $course->enrollments()
            ->where('user_id', $user->id)
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'You must enroll to view course progress.',
            ], 403);
        }

        // Get published lessons in order
        $lessons = $course->lessons()
            ->where('is_published', true)
            ->orderBy('position')
            ->get(['id', 'title', 'slug', 'position']);

        // Retrieve completed lesson IDs
        $completedLessonIds = LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->pluck('lesson_id')
            ->unique();

        $totalLessons = $lessons->count();

        $completedLessons = $completedLessonIds->count();

        $percentage = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100)
            : 0;

        // Find the next unfinished lesson
        $nextLesson = $lessons->first(function ($lesson) use ($completedLessonIds) {
            return !$completedLessonIds->contains($lesson->id);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'course_id' => $course->id,
                'enrolled' => true,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'progress_percentage' => $percentage,
                'is_course_completed' =>
                    $totalLessons > 0 &&
                    $completedLessons === $totalLessons,
                'completed_lesson_ids' => $completedLessonIds->values(),
                'next_lesson' => $nextLesson ? [
                    'id' => $nextLesson->id,
                    'title' => $nextLesson->title,
                    'slug' => $nextLesson->slug,
                ] : null,
            ],
        ]);
    }
}
