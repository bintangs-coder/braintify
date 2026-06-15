@extends('layouts.dashboard')
@section('title', 'Messages')

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
.conversation-card {
    transition: all 0.3s ease;
}
.conversation-card:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(4px);
}
</style>

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-2 text-white">Messages</h1>
    <p class="text-gray-300">Chat with your connections</p>
</div>

<div class="glass-card rounded-2xl overflow-hidden">
    @forelse($conversations as $conversation)
        @php
            $otherUser = $conversation->getOtherParticipant(auth()->user());
            $lastMessage = $conversation->messages->sortByDesc('created_at')->first();
            $unreadCount = $conversation->getUnreadCount(auth()->user());
        @endphp
        <a href="{{ route('conversations.show', $conversation) }}"
           class="conversation-card block p-4 border-b border-white/10 last:border-0 {{ $unreadCount > 0 ? 'bg-blue-500/5' : '' }}">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ $otherUser?->avatar_url ?? 'https://ui-avatars.com/api/?name=User&background=6366f1&color=fff' }}" class="w-12 h-12 rounded-full">
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium text-white truncate">{{ $otherUser?->name ?? 'Unknown User' }}</h3>
                        @if($lastMessage)
                            <span class="text-xs text-gray-400">{{ $lastMessage->created_at->diffForHumans() }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-400 truncate">
                        @if($lastMessage)
                            @if($lastMessage->sender_id === auth()->id())
                                <span class="text-gray-500">You:</span>
                            @endif
                            {{ Str::limit($lastMessage->content, 50) }}
                        @else
                            <span class="text-gray-500">No messages yet. Start the conversation!</span>
                        @endif
                    </p>
                </div>
            </div>
        </a>
    @empty
        <div class="p-12 text-center">
            <div class="text-5xl mb-4">💬</div>
            <p class="text-gray-400 mb-2">No conversations yet</p>
            <p class="text-sm text-gray-500">Start chatting by proposing an exchange or accepting a proposal</p>
        </div>
    @endforelse
</div>
@endsection