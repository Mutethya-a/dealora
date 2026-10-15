<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningPath;
use Illuminate\Http\JsonResponse;

class LearningPathController extends Controller
{
    public function index(): JsonResponse
    {
        $learningPaths = LearningPath::query()
            ->where('is_active', true)
            ->with([
                'courses' => function ($query) {
                    $query->where('is_published', true)
                        ->orderBy('id');
                }
            ])
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $learningPaths,
        ]);
    }
}
