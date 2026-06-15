@extends('layouts.dashboard')
@section('title', 'Browse Skills & Mentors')

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}
.glass-card:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(99, 102, 241, 0.2);
}
.glass-card-static {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.gradient-text {
    background: linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-2 gradient-text">Browse Skills & Mentors</h1>
    <p class="text-gray-300">Discover skills and book mentoring sessions</p>
</div>

{{-- Tabs --}}
<div class="flex gap-2 mb-6 border-b border-white/10">
    <a href="{{ route('skills.index', ['tab' => 'skills']) }}"
       class="px-4 py-3 font-medium {{ $activeTab === 'skills' ? 'text-white border-b-2 border-blue-500' : 'text-gray-400 hover:text-white' }}">{{ $activeTab === 'skills' ? 'Browse Skills' : 'Browse Skills' }}</a>
    <a href="{{ route('skills.index', ['tab' => 'mentors']) }}"
       class="px-4 py-3 font-medium {{ $activeTab === 'mentors' ? 'text-white border-b-2 border-blue-500' : 'text-gray-400 hover:text-white' }}">{{ $activeTab === 'mentors' ? 'Find Mentors' : 'Find Mentors' }}</a>
</div>

{{-- Browse Skills Tab --}}
@if($activeTab === 'skills')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($skills as $skill)
    <a href="{{ route('skills.show', $skill->slug) }}" class="glass-card rounded-2xl p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-white">{{ $skill->name }}</h3>
                @if($skill->category)
                <p class="text-sm text-gray-300">{{ $skill->category->name }}</p>
                @endif
            </div>
        </div>
        <p class="text-gray-300 text-sm line-clamp-2 mb-4">{{ $skill->description ?? 'No description' }}</p>
        <span class="text-sm font-medium text-blue-400 group-hover:text-blue-300">Explore →</span>
    </a>
    @empty
    <div class="col-span-full text-center py-12 text-gray-300">No skills found</div>
    @endforelse
</div>
@endif

{{-- Find Mentors Tab --}}
@if($activeTab === 'mentors')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($allServices as $service)
    <div class="glass-card-static rounded-2xl p-6">
        <div class="flex items-start gap-4 mb-4">
            <img src="{{ $service->user->avatar_url }}" class="w-16 h-16 rounded-full">
            <div>
                <h3 class="font-semibold text-white">{{ $service->user->name }}</h3>
                <p class="text-sm text-gray-300">{{ $service->title }}</p>
            </div>
        </div>
        <p class="text-gray-300 text-sm line-clamp-2 mb-4">{{ $service->description }}</p>
        <div class="flex items-center justify-between pt-4 border-t border-white/10">
            <span class="text-lg font-bold text-green-400">Rp {{ number_format($service->price, 0, ',', ',') }}</span>
            <span class="text-2xl">{{ $service->method_icon }}</span>
        </div>
        <a href="{{ route('booking.mentor-profile', $service->user) }}" class="block mt-4 w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white text-sm text-center rounded-lg hover:shadow-lg">Book Now</a>
    </div>
    @empty
    <div class="col-span-full text-center py-12 text-gray-300">No mentors available</div>
    @endforelse
</div>
@endif

<div class="mt-8">
    {{ $activeTab === 'skills' ? $skills->withQueryString()->links() : $allServices->withQueryString()->links() }}
</div>
@endsection