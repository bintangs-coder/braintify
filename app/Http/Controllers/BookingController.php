<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('booking.index', [
            'upcomingBookings' => $user->bookingsAsLearner()
                ->with('mentor:id,name,avatar', 'skill')
                ->upcoming()
                ->orderBy('scheduled_at')
                ->get(),
            'pastBookings' => $user->bookingsAsLearner()
                ->with('mentor:id,name,avatar', 'skill')
                ->past()
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }

    public function showMentor(User $mentor): View
    {
        // Check if mentor exists and has a role
        if (!$mentor || !$mentor->role || !$mentor->canTeach()) {
            abort(404);
        }

        return view('booking.mentor-profile', [
            'mentor' => $mentor->load('profile', 'skillsOffered.category'),
            'reviews' => $mentor->reviewsReceived()
                ->with('reviewer:id,name,avatar')
                ->latest()
                ->limit(6)
                ->get(),
        ]);
    }

    public function book(User $mentor, Request $request)
    {
        $validated = $request->validate([
            'skill_id' => 'required|exists:skills,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required',
            'duration' => 'required|integer|min:15|max:120',
            'session_method' => 'nullable|in:video,voice',
            'notes' => 'nullable|string|max:500',
        ]);

        // Combine date dan time
        $scheduledAt = $validated['booking_date'] . ' ' . $validated['scheduled_time'] . ':00';

        // Get skill untuk price
        $skill = Skill::find($validated['skill_id']);
        $price = $skill->avg_price ?? 30000;

        // Hitung fee (10%) dan earning mentor (90%)
        $platformFee = $price * 0.10;
        $mentorEarnings = $price * 0.90;

        Booking::create([
            'learner_id' => Auth::id(),
            'mentor_id' => $mentor->id,
            'skill_id' => $validated['skill_id'],
            'package_name' => $skill->name,
            'duration' => $validated['duration'],
            'scheduled_at' => $scheduledAt,
            'price' => $price,
            'platform_fee' => $platformFee,
            'mentor_earnings' => $mentorEarnings,
            'session_method' => $validated['session_method'] ?? 'video',
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('booking.index')
            ->with('success', 'Booking berhasil! Mentor akan segera merespons.');
    }
}