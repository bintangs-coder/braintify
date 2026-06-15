<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\UserSkill;
use App\Models\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(string $identifier)
    {
        $user = \App\Models\User::where('username', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        // Get user's completed exchange posts only
        $userPosts = Exchange::where('requester_id', $user->id)
            ->completed()
            ->latest()
            ->limit(10)
            ->get();

        return view('profile.show', [
            'profileUser' => $user,
            'skillsOffered' => $user->skillsOffered,
            'skillsWanted' => $user->skillsWanted,
            'userPosts' => $userPosts,
            'reviews' => $user->reviewsReceived()
                ->with('reviewer:id,name,avatar')
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', [
            'user' => $user,
            'profile' => $user->profile,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'teaching_style' => ['nullable', Rule::in(['casual', 'structured', 'project_based'])],
            'languages' => ['nullable', 'array'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:50'],
            'linkedin_url' => ['nullable', 'url'],
            'website_url' => ['nullable', 'url'],
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?? null,
            'location' => $validated['location'] ?? null,
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'headline' => $validated['headline'] ?? null,
                'teaching_style' => $validated['teaching_style'] ?? 'casual',
                'languages' => $validated['languages'] ?? ['English'],
                'years_experience' => $validated['years_experience'] ?? 0,
                'linkedin_url' => $validated['linkedin_url'] ?? null,
                'website_url' => $validated['website_url'] ?? null,
            ]
        );

        return redirect()
            ->route('profile.show', $user->id)
            ->with('success', 'Profile updated successfully');
    }

    public function addSkill(Request $request)
    {
        $validated = $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'type' => ['required', Rule::in(['offer', 'want'])],
            'proficiency' => ['required', Rule::in(['beginner', 'intermediate', 'advanced', 'expert'])],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        UserSkill::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'skill_id' => $validated['skill_id'],
                'type' => $validated['type'],
            ],
            [
                'proficiency' => $validated['proficiency'],
                'hourly_rate' => $validated['hourly_rate'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_active' => true,
            ]
        );

        return back()->with('success', 'Skill added successfully');
    }

    public function removeSkill(int $userSkillId)
    {
        UserSkill::where('id', $userSkillId)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Skill removed');
    }
}
