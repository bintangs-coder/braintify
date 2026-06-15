<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExchangeController extends Controller
{
    // Browse all exchange posts (public/external posts)
    public function index(Request $request): View
    {
        $user = Auth::user();
        $search = $request->input('search', '');

        // Get all exchanges where:
        // 1. User is not the requester (can't browse own posts)
        // 2. Show exchanges that are:
        //    - Still pending (no provider yet)
        //    - Completed (allow propose again for new exchange)
        $query = Exchange::where('requester_id', '!=', $user->id)
            ->where(function($q) use ($user) {
                // Show pending exchanges (no provider yet)
                $q->where(function($q2) {
                    $q2->where('status', 'pending')
                       ->whereNull('provider_id');
                })
                // OR show completed exchanges (allow propose again)
                ->orWhere('status', 'completed');
            })
            ->with('requester');

        // Search filter
        if ($search) {
            $searchLower = strtolower($search);

            // Get user IDs that match the search (by name)
            $matchingUserIds = User::whereRaw('LOWER(name) LIKE ?', ['%' . $searchLower . '%'])
                ->pluck('id')
                ->toArray();

            $query->where(function($q) use ($searchLower, $matchingUserIds) {
                // Search in requester_skill (offers)
                $q->whereRaw('LOWER(requester_skill) LIKE ?', ['%' . $searchLower . '%'])
                    // Search in wanted_skill (wants)
                    ->orWhereRaw('LOWER(wanted_skill) LIKE ?', ['%' . $searchLower . '%']);

                // Search by username (only if we have matching users)
                if (!empty($matchingUserIds)) {
                    $q->orWhereIn('requester_id', $matchingUserIds);
                }
            });
        }

        // Show only pending exchanges without provider (not completed, not accepted)
        $allPosts = $query->where(function($q) use ($user) {
            $q->where('status', 'pending')
              ->whereNull('provider_id');
        })->latest()->get();

        return view('exchange.index', [
            'allPosts' => $allPosts,
            'searchQuery' => $search,
        ]);
    }

    // My exchanges (user's own exchanges)
    public function myExchanges(): View
    {
        $user = Auth::user();

        return view('exchange.my', [
            'myPosts' => Exchange::where('requester_id', $user->id)
                ->with('provider')
                ->latest()
                ->get(),
            'incomingProposals' => Exchange::where('requester_id', $user->id)
                ->whereNotNull('provider_id')
                ->pending()
                ->with('provider')
                ->latest()
                ->get(),
            'sentProposals' => Exchange::where('provider_id', $user->id)
                ->with('requester')
                ->latest()
                ->get(),
            'completedExchanges' => Exchange::where(function ($q) use ($user) {
                $q->where('requester_id', $user->id)
                  ->orWhere('provider_id', $user->id);
            })
            ->completed()
            ->with('requester', 'provider')
            ->latest()
            ->get(),
        ]);
    }

    public function create(): View
    {
        return view('exchange.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'my_skills' => ['required', 'string', 'max:255'],
            'wanted_skills' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Exchange::create([
            'requester_id' => Auth::id(),
            'requester_skill' => $validated['my_skills'],
            'wanted_skill' => $validated['wanted_skills'],
            'requester_note' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('exchange.my')
            ->with('success', 'Exchange post created!');
    }

    // User propose to someone else's post
    public function propose(Exchange $originalExchange, Request $request)
    {
        try {
            $validated = $request->validate([
                'their_skill' => ['required', 'string', 'max:500'],
                'my_skill' => ['required', 'string', 'max:500'],
                'message' => ['nullable', 'string', 'max:500'],
            ]);

            // Log for debugging
            \Log::info('Propose called', [
                'exchange_id' => $originalExchange->id,
                'requester_id' => $originalExchange->requester_id,
                'current_user' => Auth::id(),
                'their_skill' => $validated['their_skill'],
                'my_skill' => $validated['my_skill'],
            ]);

            // Format: "Learn: skill1, skill2 ← Teach: skill1, skill2"
            $formattedSkill = 'Learn: ' . $validated['their_skill'] . ' ← Teach: ' . $validated['my_skill'];

            // If original exchange is completed or already has a provider, create a new exchange
            if ($originalExchange->isCompleted() || $originalExchange->provider_id !== null) {
                // Create a new exchange post for the original requester
                Exchange::create([
                    'requester_id' => $originalExchange->requester_id,
                    'requester_skill' => $originalExchange->requester_skill,
                    'wanted_skill' => $originalExchange->wanted_skill,
                    'requester_note' => $originalExchange->requester_note,
                    'provider_id' => Auth::id(),
                    'provider_skill' => $formattedSkill,
                    'provider_note' => $validated['message'] ?? null,
                ]);

                return back()->with('success', 'New exchange proposal sent!');
            }

            // For pending exchanges without provider, update the existing exchange
            $originalExchange->update([
                'provider_id' => Auth::id(),
                'provider_skill' => $formattedSkill,
                'provider_note' => $validated['message'] ?? null,
            ]);

            return back()->with('success', 'Exchange proposal sent!');
        } catch (\Exception $e) {
            \Log::error('Propose error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to send proposal: ' . $e->getMessage());
        }
    }

    public function accept(Exchange $exchange)
    {
        $user = Auth::id();

        if ($exchange->requester_id !== $user || !$exchange->isPending()) {
            abort(403);
        }

        $exchange->update(['status' => 'accepted']);

        return back()->with('success', 'Exchange accepted!');
    }

    public function decline(Exchange $exchange)
    {
        if (!in_array(Auth::id(), [$exchange->requester_id, $exchange->provider_id]) || !$exchange->isPending()) {
            abort(403);
        }

        if ($exchange->requester_id === Auth::id()) {
            $exchange->update([
                'provider_id' => null,
                'provider_skill' => null,
                'provider_note' => null,
                'status' => 'pending',
            ]);
        } else {
            abort(403);
        }

        return back()->with('info', 'Proposal declined');
    }

    public function cancel(Exchange $exchange)
    {
        // Can't cancel completed exchanges
        if ($exchange->isCompleted()) {
            return back()->with('error', 'Cannot cancel a completed exchange');
        }

        // If user is the requester (post owner), delete the post or reset if has proposal
        if ($exchange->requester_id === Auth::id()) {
            if ($exchange->provider_id) {
                // Reset the post to pending if there's a proposal
                $exchange->update([
                    'provider_id' => null,
                    'provider_skill' => null,
                    'provider_note' => null,
                    'status' => 'pending',
                ]);
            } else {
                // Delete post if no proposals
                $exchange->delete();
            }
            return redirect()->route('exchange.my')->with('info', 'Post cancelled');
        }

        // If user is the provider (proposer), cancel their proposal
        if ($exchange->provider_id === Auth::id()) {
            $exchange->update([
                'provider_id' => null,
                'provider_skill' => null,
                'provider_note' => null,
                'status' => 'pending',
            ]);
            return back()->with('info', 'Proposal cancelled');
        }

        abort(403);
    }

    public function complete(Exchange $exchange)
    {
        if (!in_array(Auth::id(), [$exchange->requester_id, $exchange->provider_id])) {
            abort(403);
        }

        $exchange->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Exchange completed!');
    }

    public function rate(Request $request, Exchange $exchange)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $userId = Auth::id();

        // Check if user is part of the exchange
        if (!in_array($userId, [$exchange->requester_id, $exchange->provider_id])) {
            abort(403);
        }

        // Check if exchange is completed
        if (!$exchange->isCompleted()) {
            return back()->with('error', 'Exchange must be completed first');
        }

        // Determine if user is requester or provider and save rating
        if ($exchange->requester_id === $userId) {
            if ($exchange->requester_rating !== null) {
                return back()->with('info', 'You have already rated');
            }
            $exchange->update(['requester_rating' => $validated['rating']]);

            // Update provider's trust score
            if ($exchange->provider_id) {
                $this->updateTrustScore($exchange->provider_id, 'provider');
            }
        } else {
            if ($exchange->provider_rating !== null) {
                return back()->with('info', 'You have already rated');
            }
            $exchange->update(['provider_rating' => $validated['rating']]);

            // Update requester's trust score
            $this->updateTrustScore($exchange->requester_id, 'requester');
        }

        return back()->with('success', 'Thanks for your rating!');
    }

    private function updateTrustScore(int $userId, string $role)
    {
        $user = \App\Models\User::find($userId);
        if (!$user) return;

        // Get all ratings received by this user in exchanges
        $exchanges = Exchange::where(function ($q) use ($userId, $role) {
            if ($role === 'provider') {
                $q->where('provider_id', $userId)->whereNotNull('requester_rating');
            } else {
                $q->where('requester_id', $userId)->whereNotNull('provider_rating');
            }
        })->get();

        if ($exchanges->isEmpty()) {
            $user->update(['trust_score' => 0]);
            return;
        }

        $totalRating = 0;
        $count = 0;

        foreach ($exchanges as $exchange) {
            if ($role === 'provider') {
                $totalRating += $exchange->requester_rating ?? 0;
            } else {
                $totalRating += $exchange->provider_rating ?? 0;
            }
            $count++;
        }

        $avgRating = $count > 0 ? $totalRating / $count : 0;
        // Trust score = average rating * 20 (so 5 stars = 100)
        $user->update(['trust_score' => $avgRating * 20]);
    }
}
