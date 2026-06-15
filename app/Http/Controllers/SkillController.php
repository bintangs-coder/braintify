<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'skills');

        // Skills data (all available skills)
        $skills = Skill::active()
            ->orderBy('name')
            ->paginate(24);

        // All services (for Find Mentors tab)
        $allServices = Service::active()
            ->with('user.profile')
            ->latest()
            ->paginate(24);

        $categories = SkillCategory::active()->root()->with('children')->get();

        return view('skills.index', [
            'skills' => $skills,
            'allServices' => $allServices,
            'categories' => $categories,
            'filters' => $request->only(['skill', 'tab']),
            'activeTab' => $tab,
        ]);
    }

    public function show(Skill $skill): View
    {
        $skill->load('category');

        $mentors = User::mentors()->active()
            ->whereHas('services', fn($q) => $q->where('skill_id', $skill->id)->where('status', 'active'))
            ->with(['profile', 'services' => fn($q) => $q->where('skill_id', $skill->id)->where('status', 'active')])
            ->paginate(12);

        $relatedSkills = Skill::where('category_id', $skill->category_id)
            ->where('id', '!=', $skill->id)
            ->active()
            ->limit(6)
            ->get();

        return view('skills.show', [
            'skill' => $skill,
            'mentors' => $mentors,
            'relatedSkills' => $relatedSkills,
        ]);
    }
}