@extends('layouts.dashboard')
@section('title', $skill->name)

@section('content')
{{-- Header --}}
<div class="bg-gradient-to-r from-primary-500 to-secondary-500 rounded-2xl p-8 text-white mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $skill->name }}</h1>
            @if($skill->is_verified)
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-white/20 rounded-full text-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Verified Skill
            </span>
            @endif
        </div>
        @if($skill->avg_price)
        <div class="text-right">
            <p class="text-3xl font-bold">${{ $skill->avg_price }}</p>
            <p class="text-sm opacity-80">per hour</p>
        </div>
        @endif
    </div>
    <p class="mt-4 opacity-90">{{ $skill->description }}</p>
    <div class="flex items-center gap-6 mt-6">
        <span class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $skill->avg_session_duration }} min avg session
        </span>
        <span class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $skill->total_mentors }} mentors
        </span>
        <span class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $skill->total_sessions }} sessions
        </span>
    </div>
</div>

{{-- Mentors List --}}
<div class="lg:col-span-2">
    <h2 class="text-xl font-bold mb-6">Available Mentors ({{ $mentors->total() }})</h2>
    @forelse($mentors as $mentor)
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-4">
        <div class="flex items-center gap-4">
            <img src="{{ $mentor->avatar_url }}" class="w-16 h-16 rounded-full">
            <div class="flex-1">
                <h3 class="font-semibold text-lg">{{ $mentor->name }}</h3>
                <p class="text-sm text-gray-500">{{ $mentor->profile?->headline ?? 'Mentor' }}</p>
                <div class="flex items-center gap-2 mt-1">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= ($mentor->profile?->avg_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                    <span class="text-sm text-gray-500">({{ $mentor->reviewsReceived()->count() }})</span>
                </div>
            </div>
            <a href="{{ route('booking.mentor-profile', $mentor) }}" class="px-6 py-3 bg-primary-500 text-white rounded-xl hover:bg-primary-600">Book Session</a>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-gray-500">No mentors available for this skill yet</div>
    @endforelse
    <div class="mt-6">{{ $mentors->links() }}</div>
</div>

{{-- Related Skills --}}
@if($relatedSkills->count() > 0)
<h2 class="text-xl font-bold mt-12 mb-6">Related Skills</h2>
<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    @foreach($relatedSkills as $rel)
    <a href="{{ route('skills.show', $rel->slug) }}" class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-500 transition-all">
        <h3 class="font-medium">{{ $rel->name }}</h3>
        @if($rel->avg_price)<p class="text-sm text-primary-600 mt-1">${{ $rel->avg_price }}/hr</p>@endif
    </a>
    @endforeach
</div>
@endif
@endsection
