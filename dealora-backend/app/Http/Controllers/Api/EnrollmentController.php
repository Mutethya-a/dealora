<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request, string $slug): JsonResponse
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->whereHas('learningPath', function ($query) {
                $query->where('is_active', true);
            })
            ->firstOrFail();

        $enrollment = Enrollment::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'course_id' => $course->id,
            ],
            [
                'status' => 'active',
                'enrolled_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $enrollment->wasRecentlyCreated
                ? 'Successfully enrolled in course.'
                : 'You are already enrolled in this course.',
            'data' => $enrollment,
        ], $enrollment->wasRecentlyCreated ? 201 : 200);
    }

    public function status(Request $request, string $slug): JsonResponse
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->whereHas('learningPath', function ($query) {
                $query->where('is_active', true);
            })
            ->firstOrFail();

        $enrollment = Enrollment::query()
            ->where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->first();

        return response()->json([
            'success' => true,
            'enrolled' => $enrollment !== null,
            'data' => $enrollment,
        ]);
    }
}
