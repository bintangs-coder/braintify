<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewed_id' => ['required', 'exists:users,id'],
            'reviewable_type' => ['required', 'in:booking,exchange'],
            'reviewable_id' => ['required', 'integer'],
            'overall_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'teaching_quality' => ['nullable', 'integer', 'min:1', 'max:5'],
            'reliability' => ['nullable', 'integer', 'min:1', 'max:5'],
            'communication' => ['nullable', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $exists = Review::where('reviewer_id', Auth::id())
            ->where('reviewable_type', $validated['reviewable_type'])
            ->where('reviewable_id', $validated['reviewable_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already reviewed this session.');
        }

        Review::create([
            'reviewer_id' => Auth::id(),
            'reviewed_id' => $validated['reviewed_id'],
            'reviewable_type' => $validated['reviewable_type'],
            'reviewable_id' => $validated['reviewable_id'],
            'overall_rating' => $validated['overall_rating'],
            'teaching_quality' => $validated['teaching_quality'] ?? null,
            'reliability' => $validated['reliability'] ?? null,
            'communication' => $validated['communication'] ?? null,
            'comment' => $validated['comment'] ?? null,
        ]);

        $reviewedUser = \App\Models\User::find($validated['reviewed_id']);
        $reviewsCount = $reviewedUser->reviewsReceived()->count();
        $avgRating = $reviewedUser->reviewsReceived()->avg('overall_rating');

        if ($reviewedUser->profile) {
            $reviewedUser->profile->update([
                'avg_rating' => $avgRating,
                'total_reviews' => $reviewsCount,
            ]);
        }

        return back()->with('success', 'Review submitted successfully!');
    }
}
