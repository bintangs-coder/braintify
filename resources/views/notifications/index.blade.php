@extends('layouts.dashboard')
@section('title', 'Notifications')

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

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-white">Notifications</h1>
    @if($notifications->where('is_read', false)->count() > 0)
    <form action="{{ route('notifications.read-all') }}" method="POST">@csrf @method('PUT')
        <button class="text-sm text-blue-400 hover:text-blue-300">Mark all as read</button>
    </form>
    @endif
</div>

<div class="space-y-4">
    @forelse($notifications as $notification)
    <a href="{{ $notification->link ?? '#' }}" class="block glass-card rounded-2xl border {{ $notification->is_read?'border-white/5':'border-blue-500/30' }} p-6 hover:bg-white/10">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full {{ $notification->is_read?'bg-white/5':'bg-blue-500/20' }} flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div class="flex-1">
                <h3 class="font-medium text-white">{{ $notification->title }}</h3>
                @if($notification->message)<p class="text-sm text-gray-300 mt-1">{{ $notification->message }}</p>@endif
                <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @if(!$notification->is_read)<span class="w-2 h-2 bg-blue-500 rounded-full"></span>@endif
        </div>
    </a>
    @empty
    <div class="text-center py-12 text-gray-300">No notifications yet</div>
    @endforelse
</div>
<div class="mt-8">{{ $notifications->links() }}</div>
@endsection
