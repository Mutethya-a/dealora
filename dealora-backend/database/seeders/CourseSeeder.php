<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\LearningPath;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            'sales' => [
                [
                    'title' => 'Sales Fundamentals',
                    'description' => 'Learn the basics of selling, building trust, and closing your first deal.',
                    'duration_minutes' => 90,
                ],
                [
                    'title' => 'Understanding Customers',
                    'description' => 'Discover customer needs and learn how to offer valuable solutions.',
                    'duration_minutes' => 75,
                ],
            ],

            'negotiation' => [
                [
                    'title' => 'Negotiation Fundamentals',
                    'description' => 'Learn how to prepare, negotiate confidently, and reach fair agreements.',
                    'duration_minutes' => 90,
                ],
                [
                    'title' => 'Communication & Persuasion',
                    'description' => 'Build communication skills that help you negotiate effectively.',
                    'duration_minutes' => 80,
                ],
            ],

            'procurement' => [
                [
                    'title' => 'Procurement Essentials',
                    'description' => 'Understand purchasing processes, sourcing, and procurement principles.',
                    'duration_minutes' => 100,
                ],
                [
                    'title' => 'Supplier Management',
                    'description' => 'Learn how to evaluate suppliers and build strong business relationships.',
                    'duration_minutes' => 85,
                ],
            ],

            'importation' => [
                [
                    'title' => 'Importation Basics',
                    'description' => 'Learn the fundamentals of sourcing and importing products internationally.',
                    'duration_minutes' => 110,
                ],
                [
                    'title' => 'Shipping & Customs',
                    'description' => 'Understand shipping methods, customs documentation, and import procedures.',
                    'duration_minutes' => 95,
                ],
            ],
        ];

        foreach ($courses as $pathSlug => $pathCourses) {
            $learningPath = LearningPath::where(
                'slug',
                $pathSlug
            )->firstOrFail();

            foreach ($pathCourses as $courseData) {
                $slug = Str::slug($courseData['title']);

                Course::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'learning_path_id' => $learningPath->id,
                        'title' => $courseData['title'],
                        'description' => $courseData['description'],
                        'level' => 'beginner',
                        'duration_minutes' => $courseData['duration_minutes'],
                        'is_published' => true,
                    ]
                );
            }
        }
    }
}
