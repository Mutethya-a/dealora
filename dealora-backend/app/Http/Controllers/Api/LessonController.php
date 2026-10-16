<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function show(
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

        $isEnrolled = $course->enrollments()
            ->where('user_id', $request->user()->id)
            ->exists();

        if (!$isEnrolled && !$lesson->is_preview) {
            return response()->json([
                'success' => false,
                'message' => 'Please enroll in this course to access this lesson.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'slug' => $lesson->slug,
                'content' => $lesson->content,
                'duration_minutes' => $lesson->duration_minutes,
                'position' => $lesson->position,
                'is_preview' => $lesson->is_preview,
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                ],
            ],
        ]);
    }
}
