<?php

namespace Database\Seeders;

use App\Models\SkillCategory;
use Illuminate\Database\Seeder;

class SkillCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Programming & Development',
                'slug' => 'programming-development',
                'icon' => 'code',
                'description' => 'Software development, web development, and programming languages',
                'children' => [
                    ['name' => 'Web Development', 'slug' => 'web-development', 'sort_order' => 0],
                    ['name' => 'Mobile Development', 'slug' => 'mobile-development', 'sort_order' => 1],
                    ['name' => 'Backend Development', 'slug' => 'backend-development', 'sort_order' => 2],
                    ['name' => 'DevOps & Cloud', 'slug' => 'devops-cloud', 'sort_order' => 3],
                ]
            ],
            [
                'name' => 'Design & Creative',
                'slug' => 'design-creative',
                'icon' => 'palette',
                'description' => 'Graphic design, UI/UX, and creative skills',
                'children' => [
                    ['name' => 'UI/UX Design', 'slug' => 'ui-ux-design', 'sort_order' => 0],
                    ['name' => 'Graphic Design', 'slug' => 'graphic-design', 'sort_order' => 1],
                    ['name' => 'Motion Design', 'slug' => 'motion-design', 'sort_order' => 2],
                ]
            ],
            [
                'name' => 'Business & Marketing',
                'slug' => 'business-marketing',
                'icon' => 'briefcase',
                'description' => 'Business skills, marketing, and entrepreneurship',
                'children' => [
                    ['name' => 'Digital Marketing', 'slug' => 'digital-marketing', 'sort_order' => 0],
                    ['name' => 'SEO', 'slug' => 'seo', 'sort_order' => 1],
                    ['name' => 'Content Marketing', 'slug' => 'content-marketing', 'sort_order' => 2],
                ]
            ],
            [
                'name' => 'Data & Analytics',
                'slug' => 'data-analytics',
                'icon' => 'chart',
                'description' => 'Data science, analytics, and business intelligence',
                'children' => [
                    ['name' => 'Data Science', 'slug' => 'data-science', 'sort_order' => 0],
                    ['name' => 'SQL & Databases', 'slug' => 'sql-databases', 'sort_order' => 1],
                ]
            ],
            [
                'name' => 'Soft Skills',
                'slug' => 'soft-skills',
                'icon' => 'users',
                'description' => 'Communication, leadership, and personal development',
                'children' => [
                    ['name' => 'Communication', 'slug' => 'communication', 'sort_order' => 0],
                    ['name' => 'Leadership', 'slug' => 'leadership', 'sort_order' => 1],
                    ['name' => 'Public Speaking', 'slug' => 'public-speaking', 'sort_order' => 2],
                ]
            ],
        ];

        foreach ($categories as $catData) {
            $children = $catData['children'] ?? [];
            unset($catData['children']);

            $parent = SkillCategory::create($catData);

            foreach ($children as $child) {
                SkillCategory::create(array_merge($child, ['parent_id' => $parent->id]));
            }
        }
    }
}
