@extends('layouts.dashboard')
@section('title', 'Dashboard')

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.glass-card-hover {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}
.glass-card-hover:hover {
    background: rgba(255, 255, 255, 0.1);
}
</style>

@section('content')
@if(session('success'))
<div class="mb-6 p-4 glass-card rounded-xl text-green-400">
    {{ session('success') }}
</div>
@endif

{{-- Shortcuts --}}
<div class="mb-8">
    <a href="{{ route('exchange.index') }}" class="block glass-card-hover rounded-2xl p-6 hover:scale-[1.02] transition-all group">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:shadow-blue-500/50 transition-all">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition-colors">Exchange Skill Now!</h3>
                    <p class="text-sm text-gray-400">Browse available skill exchanges and find your perfect match</p>
                </div>
            </div>
            <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-400 group-hover:translate-x-2 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-card-hover rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Total Exchanges</p>
                <p class="text-2xl font-bold text-white">{{ $stats['totalExchanges'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707-.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="glass-card-hover rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">My Posts</p>
                <p class="text-2xl font-bold text-white">{{ $myPosts->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
    </div>

    <div class="glass-card-hover rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Pending Proposals</p>
                <p class="text-2xl font-bold text-white">{{ $stats['pendingProposals'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="glass-card-hover rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Trust Score</p>
                <p class="text-2xl font-bold text-white">{{ $stats['trustScore'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618 4.016A11.955 11.955 0 0122 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- My Posts --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">My Exchange Posts</h2>
            <a href="{{ route('exchange.create') }}" class="text-sm text-blue-400 hover:text-blue-300">+ Create New</a>
        </div>
        <div class="glass-card rounded-2xl overflow-hidden">
            @forelse($myPosts as $post)
            <div class="p-4 border-b border-white/10 last:border-0 hover:bg-white/5 transition-colors">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-sm">{{ $post->requester_skill }}</span>
                        <span class="text-gray-500">↔</span>
                        <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded text-sm">{{ $post->wanted_skill }}</span>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $post->status->value === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : ($post->status->value === 'accepted' ? 'bg-green-500/20 text-green-400' : ($post->status->value === 'completed' ? 'bg-blue-500/20 text-blue-400' : 'bg-gray-500/20 text-gray-400')) }}">
                        {{ $post->status->label() }}
                    </span>
                </div>
                @if($post->provider)
                <p class="text-sm text-gray-400">
                    <span class="text-white">{{ $post->provider->name }}</span> proposed: {{ $post->provider_skill }}
                </p>
                @endif
                @if($post->requester_note)
                <p class="text-xs text-gray-500 mt-2 line-clamp-1">{{ $post->requester_note }}</p>
                @endif
            </div>
            @empty
            <div class="p-8 text-center">
                <p class="text-gray-400 mb-2">No exchange posts yet</p>
                <a href="{{ route('exchange.create') }}" class="text-blue-400 hover:text-blue-300 text-sm">Create your first exchange →</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Proposals Received --}}
    <div>
        <h2 class="text-lg font-semibold text-white mb-4">Proposals Received</h2>
        <div class="glass-card rounded-2xl overflow-hidden">
            @forelse($proposalsReceived as $exchange)
            <div class="p-4 border-b border-white/10 last:border-0 hover:bg-white/5 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $exchange->requester->avatar_url }}" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium text-white text-sm">{{ $exchange->requester->name }}</p>
                        <p class="text-xs text-gray-400">wants to exchange skills</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm mb-3">
                    <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded">{{ $exchange->requester_skill }}</span>
                    <span>↔</span>
                    <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded">{{ $exchange->provider_skill }}</span>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('exchange.accept', $exchange) }}" method="POST" class="flex-1">
                        @csrf @method('PUT')
                        <button class="w-full px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm rounded-lg hover:shadow-lg">Accept</button>
                    </form>
                    <form action="{{ route('exchange.decline', $exchange) }}" method="POST" class="flex-1">
                        @csrf @method('PUT')
                        <button class="w-full px-3 py-2 bg-white/10 text-gray-400 text-sm rounded-lg hover:bg-white/20">Decline</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400 text-sm">No pending proposals</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Completed Exchanges --}}
<div>
    <h2 class="text-lg font-semibold text-white mb-4">Completed Exchanges</h2>
    <div class="glass-card rounded-2xl overflow-hidden">
        @forelse($completedExchanges as $exchange)
        <div class="p-4 border-b border-white/10 last:border-0 hover:bg-white/5 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($exchange->requester_id === auth()->id())
                        <img src="{{ $exchange->provider->avatar_url }}" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-white text-sm">{{ $exchange->provider->name }}</p>
                            <p class="text-xs text-gray-400">
                                You taught: {{ $exchange->requester_skill }} ↔ They taught: {{ $exchange->provider_skill }}
                            </p>
                        </div>
                    @else
                        <img src="{{ $exchange->requester->avatar_url }}" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-white text-sm">{{ $exchange->requester->name }}</p>
                            <p class="text-xs text-gray-400">
                                They taught: {{ $exchange->requester_skill }} ↔ You taught: {{ $exchange->provider_skill }}
                            </p>
                        </div>
                    @endif
                </div>
                <span class="px-3 py-1 text-sm rounded-full bg-green-500/20 text-green-400">✓ Completed</span>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-gray-400 text-sm">No completed exchanges yet</div>
        @endforelse
    </div>
</div>
@endsection