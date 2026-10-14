<?php

namespace Database\Seeders;

use App\Models\LearningPath;
use Illuminate\Database\Seeder;

class LearningPathSeeder extends Seeder
{
    public function run(): void
    {
        $paths = [
            [
                'title' => 'Sales',
                'slug' => 'sales',
                'description' => 'Learn how to sell confidently, understand customers, and close deals.',
                'icon' => 'mdi-handshake-outline',
                'color' => '#7048D9',
                'is_active' => true,
            ],
            [
                'title' => 'Negotiation',
                'slug' => 'negotiation',
                'description' => 'Build negotiation skills, communicate effectively, and create win-win agreements.',
                'icon' => 'mdi-forum-outline',
                'color' => '#E879A7',
                'is_active' => true,
            ],
            [
                'title' => 'Procurement',
                'slug' => 'procurement',
                'description' => 'Understand sourcing, supplier relationships, purchasing, and procurement processes.',
                'icon' => 'mdi-clipboard-check-outline',
                'color' => '#9575CD',
                'is_active' => true,
            ],
            [
                'title' => 'Importation',
                'slug' => 'importation',
                'description' => 'Explore international sourcing, shipping, customs, and importing goods.',
                'icon' => 'mdi-earth',
                'color' => '#F0A45D',
                'is_active' => true,
            ],
        ];

        foreach ($paths as $path) {
            LearningPath::updateOrCreate(
                ['slug' => $path['slug']],
                $path
            );
        }
    }
}
