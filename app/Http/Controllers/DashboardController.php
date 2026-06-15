<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // My posts (exchanges I created)
        $myPosts = $user->exchangesAsRequester()
            ->with(['provider:id,name,avatar'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Exchanges where I am the provider (someone wants to exchange with me)
        $proposalsReceived = $user->exchangesAsProvider()
            ->pending()
            ->with('requester:id,name,avatar')
            ->get();

        // All completed exchanges (both as requester and provider)
        $completedAsRequester = $user->exchangesAsRequester()->completed()->get();
        $completedAsProvider = $user->exchangesAsProvider()->completed()->get();
        $allCompletedExchanges = $completedAsRequester->merge($completedAsProvider);

        // Limited for display
        $completedExchanges = $allCompletedExchanges
            ->sortByDesc('completed_at')
            ->take(5);

        return view('dashboard.index', [
            'user' => $user,
            'stats' => [
                'totalExchanges' => $allCompletedExchanges->count(),
                'completedExchanges' => $allCompletedExchanges->count(),
                'pendingProposals' => $proposalsReceived->count(),
                'trustScore' => number_format($user->trust_score ?? 0, 2),
            ],
            'myPosts' => $myPosts,
            'proposalsReceived' => $proposalsReceived,
            'completedExchanges' => $completedExchanges,
        ]);
    }
}