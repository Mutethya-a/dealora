<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MyLearningController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get courses the learner is enrolled in
        $courses = Course::query()
            ->where('is_published', true)
            ->whereHas('learningPath', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with([
                'learningPath',
                'lessons' => function ($query) {
                    $query->where('is_published', true)
                        ->orderBy('position');
                },
            ])
            ->get();

        $allLessonIds = $courses
            ->flatMap(fn ($course) => $course->lessons->pluck('id'))
            ->unique();

        $completedLessonIds = LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereIn('lesson_id', $allLessonIds)
            ->pluck('lesson_id')
            ->unique();

        $results = $courses->map(function ($course) use ($completedLessonIds) {
            $total = $course->lessons->count();

            $completed = $course->lessons
                ->filter(fn ($lesson) =>
                    $completedLessonIds->contains($lesson->id)
                )
                ->count();

            $percentage = $total > 0
                ? round(($completed / $total) * 100)
                : 0;

            $nextLesson = $course->lessons
                ->first(fn ($lesson) =>
                    !$completedLessonIds->contains($lesson->id)
                );

            $status = $total > 0 && $completed === $total
                ? 'completed'
                : ($completed > 0 ? 'in_progress' : 'not_started');

            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'level' => $course->level,
                'duration_minutes' => $course->duration_minutes,
                'learning_path' => [
                    'title' => $course->learningPath?->title,
                    'icon' => $course->learningPath?->icon,
                ],
                'total_lessons' => $total,
                'completed_lessons' => $completed,
                'progress_percentage' => $percentage,
                'status' => $status,
                'next_lesson' => $nextLesson ? [
                    'id' => $nextLesson->id,
                    'title' => $nextLesson->title,
                    'slug' => $nextLesson->slug,
                ] : null,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'enrolled_courses' => $results->count(),
                    'in_progress' => $results
                        ->where('status', 'in_progress')->count(),
                    'completed' => $results
                        ->where('status', 'completed')->count(),
                    'not_started' => $results
                        ->where('status', 'not_started')->count(),
                ],
                'courses' => $results,
            ],
        ]);
    }
}
