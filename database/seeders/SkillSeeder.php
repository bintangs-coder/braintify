<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Programming - Harga terjangkau mahasiswa
            ['name' => 'JavaScript', 'category' => 'Web Development', 'avg_price' => 45000, 'avg_session_duration' => 45],
            ['name' => 'Python', 'category' => 'Web Development', 'avg_price' => 50000, 'avg_session_duration' => 45],
            ['name' => 'Laravel', 'category' => 'Backend Development', 'avg_price' => 55000, 'avg_session_duration' => 45],
            ['name' => 'React', 'category' => 'Web Development', 'avg_price' => 50000, 'avg_session_duration' => 45],
            ['name' => 'PHP', 'category' => 'Backend Development', 'avg_price' => 35000, 'avg_session_duration' => 45],
            ['name' => 'Vue.js', 'category' => 'Web Development', 'avg_price' => 45000, 'avg_session_duration' => 45],
            ['name' => 'Node.js', 'category' => 'Backend Development', 'avg_price' => 50000, 'avg_session_duration' => 45],
            ['name' => 'TypeScript', 'category' => 'Web Development', 'avg_price' => 45000, 'avg_session_duration' => 45],
            ['name' => 'Go', 'category' => 'Backend Development', 'avg_price' => 60000, 'avg_session_duration' => 45],
            ['name' => 'Flutter', 'category' => 'Mobile Development', 'avg_price' => 55000, 'avg_session_duration' => 45],
            ['name' => 'Docker', 'category' => 'DevOps & Cloud', 'avg_price' => 55000, 'avg_session_duration' => 45],
            ['name' => 'SQL', 'category' => 'SQL & Databases', 'avg_price' => 40000, 'avg_session_duration' => 45],
            ['name' => 'Next.js', 'category' => 'Web Development', 'avg_price' => 55000, 'avg_session_duration' => 45],

            // Design
            ['name' => 'Figma', 'category' => 'UI/UX Design', 'avg_price' => 40000, 'avg_session_duration' => 30],
            ['name' => 'UI Design', 'category' => 'UI/UX Design', 'avg_price' => 45000, 'avg_session_duration' => 45],
            ['name' => 'Adobe Photoshop', 'category' => 'Graphic Design', 'avg_price' => 35000, 'avg_session_duration' => 30],
            ['name' => 'Canva', 'category' => 'Graphic Design', 'avg_price' => 30000, 'avg_session_duration' => 30],

            // Marketing
            ['name' => 'Social Media Marketing', 'category' => 'Digital Marketing', 'avg_price' => 40000, 'avg_session_duration' => 45],
            ['name' => 'SEO', 'category' => 'SEO', 'avg_price' => 45000, 'avg_session_duration' => 45],
            ['name' => 'Content Marketing', 'category' => 'Content Marketing', 'avg_price' => 40000, 'avg_session_duration' => 45],

            // Soft Skills
            ['name' => 'Interview Prep', 'category' => 'Communication', 'avg_price' => 30000, 'avg_session_duration' => 30],
            ['name' => 'English Speaking', 'category' => 'Communication', 'avg_price' => 40000, 'avg_session_duration' => 45],
            ['name' => 'Career Coaching', 'category' => 'Leadership', 'avg_price' => 50000, 'avg_session_duration' => 45],
            ['name' => 'Public Speaking', 'category' => 'Public Speaking', 'avg_price' => 40000, 'avg_session_duration' => 45],
            ['name' => 'Excel', 'category' => 'Data Science', 'avg_price' => 30000, 'avg_session_duration' => 30],
            ['name' => 'Presentation Skills', 'category' => 'Public Speaking', 'avg_price' => 35000, 'avg_session_duration' => 45],
        ];

        foreach ($skills as $s) {
            $cat = SkillCategory::where('name', $s['category'])->first();

            Skill::create([
                'name' => $s['name'],
                'slug' => \Illuminate\Support\Str::slug($s['name']),
                'category_id' => $cat?->id,
                'description' => 'Learn ' . $s['name'] . ' dalam sesi ' . $s['avg_session_duration'] . ' menit bersama mentor berpengalaman.',
                'avg_price' => $s['avg_price'],
                'avg_session_duration' => $s['avg_session_duration'],
                'is_exchangeable' => true,
                'is_mentorship' => true,
                'is_active' => true,
            ]);
        }
    }
}
