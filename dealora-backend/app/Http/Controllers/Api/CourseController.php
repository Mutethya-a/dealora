<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->whereHas('learningPath', function ($query) {
                $query->where('is_active', true);
            })
            ->with([
                'learningPath',
                'lessons' => function ($query) {
                    $query->where('is_published', true)
                        ->orderBy('position')
                        ->select([
                            'id',
                            'course_id',
                            'title',
                            'slug',
                            'duration_minutes',
                            'position',
                            'is_preview',
                        ]);
                },
            ])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $course,
        ]);
    }
}
