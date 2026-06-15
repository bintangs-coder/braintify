@extends('layouts.dashboard')
@section('title', $profileUser->name . "'s Profile")

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}
.glass-card:hover {
    background: rgba(255, 255, 255, 0.1);
}
</style>

{{-- Flash messages --}}
@if(session('success'))
<div id="flash-success" class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-xl text-green-400">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div id="flash-error" class="mb-4 p-4 bg-red-500/20 border border-red-500/30 rounded-xl text-red-400">
    {{ session('error') }}
</div>
@endif

@section('content')
<div class="glass-card rounded-2xl p-8 mb-8 hover:bg-white/10">
    <div class="flex items-start gap-6">
        <img src="{{ $profileUser->avatar_url }}" class="w-32 h-32 rounded-full ring-4 ring-blue-500/30">
        <div class="flex-1">
            <div class="flex items-center gap-4 mb-2">
                <h1 class="text-2xl font-bold text-white">{{ $profileUser->name }}</h1>
                <span class="px-3 py-1 bg-blue-500/20 rounded-full text-sm text-blue-400">{{ $profileUser->role->label() }}</span>
            </div>
            @if($profileUser->profile?->headline)
            <p class="text-lg text-gray-300">{{ $profileUser->profile->headline }}</p>
            @endif
            @if($profileUser->bio)
            <p class="mt-4 text-gray-300">{{ $profileUser->bio }}</p>
            @endif
        </div>
        @if(Auth::id() !== $profileUser->id)
        <form action="{{ route('conversations.start', $profileUser) }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg">
                Start Chat
            </button>
        </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        {{-- Skills I Can Teach --}}
        @if($skillsOffered->count() > 0)
        <div class="glass-card rounded-2xl p-6 mb-6 hover:bg-white/10">
            <h2 class="font-semibold mb-4 text-white">Skills I Can Teach</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach($skillsOffered as $skill)
                <div class="p-4 bg-blue-500/10 rounded-xl border border-blue-500/20">
                    <h3 class="font-medium text-white">{{ $skill->name }}</h3>
                    <p class="text-sm text-gray-400">{{ is_string($skill->pivot->proficiency) ? ucfirst($skill->pivot->proficiency) : $skill->pivot->proficiency->label() }}</p>
                    @if($skill->pivot->hourly_rate)
                    <p class="text-lg font-bold text-green-400 mt-2">${{ $skill->pivot->hourly_rate }}/hr</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Completed Exchanges --}}
        @if($userPosts->count() > 0)
        <div class="glass-card rounded-2xl p-6 hover:bg-white/10">
            <h2 class="font-semibold mb-4 text-white">Completed Skill Exchanges</h2>
            <div class="space-y-4">
                @foreach($userPosts as $post)
                <div class="p-4 bg-white/5 rounded-xl border border-white/10 hover:border-blue-500/30 transition-all">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded text-xs">Completed</span>
                        <span class="text-xs text-gray-400">Gave:</span>
                        @foreach(explode(',', $post->requester_skill) as $skill)
                        <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-xs">{{ trim($skill) }}</span>
                        @endforeach
                        <span class="text-gray-500 mx-1">↔</span>
                        <span class="text-xs text-gray-400">Received:</span>
                        @foreach(explode(',', $post->wanted_skill) as $skill)
                        <span class="px-2 py-0.5 bg-purple-500/20 text-purple-400 rounded text-xs">{{ trim($skill) }}</span>
                        @endforeach
                    </div>
                    @if($post->provider)
                    <p class="text-sm text-gray-400 mt-2">With: <span class="text-white">{{ $post->provider->name }}</span></p>
                    @endif
                    <span class="text-xs text-gray-500 mt-2 block">{{ $post->completed_at ? $post->completed_at->diffForHumans() : $post->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="glass-card rounded-2xl p-6 hover:bg-white/10 text-center">
            <p class="text-gray-400">No completed exchanges yet</p>
        </div>
        @endif
    </div>
    <div class="lg:col-span-1">
        <div class="glass-card rounded-2xl p-6 hover:bg-white/10">
            <h2 class="font-semibold mb-4 text-white">About</h2>
            @if($profileUser->profile?->teaching_style)
            <p class="text-sm text-gray-400">Teaching Style</p>
            <p class="font-medium text-white mb-2">{{ ucfirst($profileUser->profile->teaching_style->label()) }}</p>
            @endif
            @if($profileUser->profile?->years_experience)
            <p class="text-sm text-gray-400">Experience</p>
            <p class="font-medium text-white">{{ $profileUser->profile->years_experience }} years</p>
            @endif
            @if($profileUser->profile?->location)
            <p class="text-sm text-gray-400 mt-3">Location</p>
            <p class="font-medium text-white">{{ $profileUser->profile->location }}</p>
            @endif
        </div>

        {{-- Start Chat Button --}}
        @if(Auth::id() !== $profileUser->id)
        <div class="mt-4">
            <form action="{{ route('conversations.start', $profileUser) }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg">
                    Start Chat
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

@endsection