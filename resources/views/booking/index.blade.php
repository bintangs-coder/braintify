@extends('layouts.dashboard')
@section('title', 'My Bookings')

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
.glass-card-static {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold mb-2 text-white">My Bookings</h1>
        <p class="text-gray-300">Manage your mentoring sessions</p>
    </div>
</div>

<div class="glass-card rounded-xl p-4 mb-8">
    <p class="text-sm text-blue-400">💡 Student-Friendly! Sessions start from Rp 20,000</p>
</div>

{{-- Upcoming --}}
<div class="glass-card-static rounded-2xl p-6 mb-8">
    <h2 class="font-semibold mb-4 text-white">Upcoming Sessions</h2>
    @forelse($upcomingBookings as $booking)
    <div class="flex items-center justify-between p-4 border-b border-white/10 last:border-0 hover:bg-white/5 transition-colors rounded-lg">
        <div class="flex items-center gap-4">
            <img src="{{ $booking->mentor->avatar_url }}" class="w-12 h-12 rounded-full">
            <div>
                <h3 class="font-medium text-white">{{ $booking->package_name }}</h3>
                <p class="text-sm text-gray-300">with {{ $booking->mentor->name }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="font-medium text-white">{{ $booking->scheduled_at->format('d M, Y') }}</p>
            <p class="text-sm text-gray-300">{{ $booking->scheduled_at->format('H:i') }} ({{ $booking->duration }} min)</p>
        </div>
        <span class="px-3 py-1 text-sm rounded-full bg-blue-500/20 text-blue-400">{{ $booking->status->label() }}</span>
    </div>
    @empty
    <p class="text-center py-8 text-gray-300">No upcoming sessions</p>
    @endforelse
</div>

{{-- History --}}
<div class="glass-card-static rounded-2xl p-6">
    <h2 class="font-semibold mb-4 text-white">Booking History</h2>
    @forelse($pastBookings as $booking)
    <div class="flex items-center justify-between p-4 border-b border-white/10 last:border-0 hover:bg-white/5 transition-colors rounded-lg">
        <div class="flex items-center gap-4">
            <img src="{{ $booking->mentor->avatar_url }}" class="w-12 h-12 rounded-full">
            <div>
                <h3 class="font-medium text-white">{{ $booking->package_name }}</h3>
                <p class="text-sm text-gray-300">with {{ $booking->mentor->name }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="font-medium text-white">Rp {{ number_format($booking->price, 0, ',', ',') }}</p>
            <p class="text-sm text-gray-300">{{ $booking->scheduled_at->format('d M Y') }}</p>
        </div>
        @if($booking->status === 'completed' && !$booking->review)
        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="reviewed_id" value="{{ $booking->mentor_id }}">
            <input type="hidden" name="reviewable_type" value="booking">
            <input type="hidden" name="reviewable_id" value="{{ $booking->id }}">
            <input type="hidden" name="overall_rating" value="5">
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-lg text-sm">Leave Review</button>
        </form>
        @else
        <span class="px-3 py-1 text-sm rounded-full bg-green-500/20 text-green-400">{{ $booking->status->label() }}</span>
        @endif
    </div>
    @empty
    <p class="text-center py-8 text-gray-300">No booking history</p>
    @endforelse
</div>
@endsection