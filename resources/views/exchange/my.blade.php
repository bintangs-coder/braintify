@extends('layouts.dashboard')
@section('title', 'My Exchanges')

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}
.glass-card:hover {
    background: rgba(255, 255, 255, 0.08);
}
.star-rating-form {
    display: flex;
    align-items: center;
    gap: 8px;
}
.star-rating-form .stars {
    display: flex;
    gap: 4px;
}
.star-rating-form .star {
    font-size: 24px;
    color: #4b5563;
    cursor: pointer;
    transition: color 0.2s, transform 0.2s;
}
.star-rating-form .star:hover {
    transform: scale(1.2);
}
.star-rating-form .star.filled {
    color: #fbbf24;
}
</style>

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold mb-2 text-white">My Exchanges</h1>
        <p class="text-gray-400">Manage your skill exchange posts and proposals</p>
    </div>
    <a href="{{ route('exchange.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create New Post
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 glass-card rounded-xl text-green-400">{{ session('success') }}</div>
@endif
@if(session('info'))
<div class="mb-6 p-4 glass-card rounded-xl text-blue-400">{{ session('info') }}</div>
@endif
@if(session('error'))
<div class="mb-6 p-4 glass-card rounded-xl text-red-400">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- My Posts --}}
    <div>
        <h2 class="text-lg font-semibold mb-4 text-white">My Posts ({{ $myPosts->count() }})</h2>
        <div class="glass-card rounded-2xl overflow-hidden">
            @forelse($myPosts as $post)
            <div class="p-4 border-b border-white/10 last:border-0">
                <div class="flex flex-wrap items-start justify-between mb-2 gap-2">
                    <div>
                        <div class="flex flex-wrap items-center gap-1">
                            <span class="text-xs text-gray-400">Offers:</span>
                            @foreach(explode(',', $post->requester_skill) as $skill)
                            <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-xs">{{ trim($skill) }}</span>
                            @endforeach
                            <span class="text-gray-500 mx-1">↔</span>
                            <span class="text-xs text-gray-400">Wants:</span>
                            @foreach(explode(',', $post->wanted_skill) as $skill)
                            <span class="px-2 py-0.5 bg-purple-500/20 text-purple-400 rounded text-xs">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-1">{{ $post->requester_note ?? 'No note' }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($post->status->value === 'pending') bg-yellow-500/20 text-yellow-400
                        @elseif($post->status->value === 'accepted') bg-green-500/20 text-green-400
                        @else bg-gray-500/20 text-gray-400
                        @endif">
                        {{ $post->status->label() }}
                    </span>
                </div>

                @if($post->provider)
                <div class="mt-3 p-3 bg-white/5 rounded-xl">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ $post->provider->avatar_url }}" class="w-6 h-6 rounded-full">
                        <span class="text-sm text-white">{{ $post->provider->name }} proposed:</span>
                    </div>
                    @php
                        $parts = explode(' ← Teach: ', $post->provider_skill);
                        $learnPart = str_replace('Learn: ', '', $parts[0] ?? '');
                        $teachPart = $parts[1] ?? $post->provider_skill;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-purple-400 font-medium shrink-0">They want to learn:</span>
                            @foreach(explode(',', $learnPart) as $skill)
                            <span class="px-2 py-0.5 bg-purple-500/20 text-purple-400 rounded text-xs">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-blue-400 font-medium shrink-0">I will teach:</span>
                            @foreach(explode(',', $teachPart) as $skill)
                            <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-xs">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @if($post->provider_note)
                    <p class="text-xs text-gray-500 mt-3">{{ $post->provider_note }}</p>
                    @endif

                    @if($post->status->value === 'pending')
                    <div class="flex gap-2 mt-4 pt-3 border-t border-white/10">
                        <form action="{{ route('exchange.accept', $post) }}" method="POST" class="flex-1">
                            @csrf @method('PUT')
                            <button class="w-full px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs rounded-lg hover:shadow-lg">Accept</button>
                        </form>
                        <form action="{{ route('exchange.decline', $post) }}" method="POST" class="flex-1">
                            @csrf @method('PUT')
                            <button class="w-full px-3 py-2 bg-white/10 text-gray-400 text-xs rounded-lg hover:bg-white/20">Decline</button>
                        </form>
                        @if($post->conversation)
                        <a href="{{ route('conversations.show', $post->conversation) }}" class="flex-1 px-3 py-2 bg-blue-500/20 text-blue-400 text-xs rounded-lg text-center hover:bg-blue-500/30">Chat</a>
                        @else
                        <form action="{{ route('conversations.store', $post) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-3 py-2 bg-blue-500/20 text-blue-400 text-xs rounded-lg hover:bg-blue-500/30">Start Chat</button>
                        </form>
                        @endif
                    </div>
                    @elseif($post->status->value === 'accepted')
                    <div class="flex gap-2 mt-4 pt-3 border-t border-white/10">
                        <form action="{{ route('exchange.complete', $post) }}" method="POST" class="flex-1">
                            @csrf @method('PUT')
                            <button class="w-full px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs rounded-lg">Mark Complete</button>
                        </form>
                        @if($post->conversation)
                        <a href="{{ route('conversations.show', $post->conversation) }}" class="flex-1 px-3 py-2 bg-blue-500/20 text-blue-400 text-xs rounded-lg text-center hover:bg-blue-500/30">Chat</a>
                        @else
                        <form action="{{ route('conversations.store', $post) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-3 py-2 bg-blue-500/20 text-blue-400 text-xs rounded-lg hover:bg-blue-500/30">Start Chat</button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
                @else
                <div class="mt-2 flex justify-end">
                    <form action="{{ route('exchange.cancel', $post) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-400 hover:underline">Delete Post</button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div class="p-8 text-center text-gray-400 text-sm">No posts yet. Create your first exchange!</div>
            @endforelse
        </div>
    </div>

    {{-- Proposals Received --}}
    <div>
        <h2 class="text-lg font-semibold mb-4 text-white">Proposals Received ({{ $incomingProposals->count() }})</h2>
        <div class="glass-card rounded-2xl overflow-hidden">
            @forelse($incomingProposals as $proposal)
            <div class="p-4 border-b border-white/10 last:border-0">
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $proposal->requester->avatar_url }}" class="w-8 h-8 rounded-full">
                    <div class="flex-1">
                        @php
                            $parts = explode(' ← Teach: ', $proposal->provider_skill);
                            $learnPart = str_replace('Learn: ', '', $parts[0] ?? '');
                            $teachPart = $parts[1] ?? $proposal->provider_skill;
                        @endphp
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-purple-400 font-medium shrink-0">Wants to learn:</span>
                                @foreach(explode(',', $learnPart) as $skill)
                                <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-xs">{{ trim($skill) }}</span>
                                @endforeach
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-blue-400 font-medium shrink-0">Will teach:</span>
                                @foreach(explode(',', $teachPart) as $skill)
                                <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-xs">{{ trim($skill) }}</span>
                                @endforeach
                            </div>
                        </div>
                        @if($proposal->provider_note)
                        <p class="text-xs text-gray-500 mt-2">{{ $proposal->provider_note }}</p>
                        @endif
                    </div>
                </div>
                <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">Pending response</span>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400 text-sm">No proposals received</div>
            @endforelse
        </div>
    </div>

    {{-- Proposals Sent --}}
    <div>
        <h2 class="text-lg font-semibold mb-4 text-white">Proposals Sent ({{ $sentProposals->count() }})</h2>
        <div class="glass-card rounded-2xl overflow-hidden">
            @forelse($sentProposals as $proposal)
            <div class="p-4 border-b border-white/10 last:border-0">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ $proposal->requester->avatar_url }}" class="w-8 h-8 rounded-full">
                        <div class="space-y-2">
                            <p class="text-sm text-white">{{ $proposal->requester->name }}</p>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-purple-400">You want to learn:</span>
                                @foreach(explode(',', $proposal->requester_skill) as $skill)
                                <span class="px-2 py-0.5 bg-purple-500/20 text-purple-400 rounded text-xs">{{ trim($skill) }}</span>
                                @endforeach
                            </div>
                            @php
                                $parts = explode(' ← Teach: ', $proposal->provider_skill);
                                $teachPart = $parts[1] ?? $proposal->provider_skill;
                            @endphp
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-blue-400">You will teach:</span>
                                @foreach(explode(',', $teachPart) as $skill)
                                <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-xs">{{ trim($skill) }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($proposal->status->value === 'pending') bg-yellow-500/20 text-yellow-400
                        @elseif($proposal->status->value === 'accepted') bg-green-500/20 text-green-400
                        @else bg-gray-500/20 text-gray-400
                        @endif">
                        {{ $proposal->status->label() }}
                    </span>
                </div>
                @if($proposal->provider_note)
                <p class="text-xs text-gray-500 mt-2 ml-11">{{ $proposal->provider_note }}</p>
                @endif
                @if($proposal->status->value === 'pending')
                <div class="mt-3 pt-2 border-t border-white/10 flex justify-end">
                    <form action="{{ route('exchange.cancel', $proposal) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-400 hover:underline">Cancel Proposal</button>
                    </form>
                </div>
                @elseif($proposal->status->value === 'accepted')
                <div class="flex gap-2 mt-3 pt-2 border-t border-white/10">
                    <form action="{{ route('exchange.complete', $proposal) }}" method="POST" class="flex-1">
                        @csrf @method('PUT')
                        <button class="w-full px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs rounded-lg">Mark Complete</button>
                    </form>
                    @if($proposal->conversation)
                    <a href="{{ route('conversations.show', $proposal->conversation) }}" class="flex-1 px-3 py-2 bg-blue-500/20 text-blue-400 text-xs rounded-lg text-center hover:bg-blue-500/30">Chat</a>
                    @else
                    <form action="{{ route('conversations.store', $proposal) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-3 py-2 bg-blue-500/20 text-blue-400 text-xs rounded-lg hover:bg-blue-500/30">Start Chat</button>
                    </form>
                    @endif
                </div>
                @endif
            </div>
            @empty
            <div class="p-8 text-center text-gray-400 text-sm">No proposals sent yet</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Completed Exchanges --}}
<div>
    <h2 class="text-lg font-semibold mb-4 text-white">Completed Exchanges ({{ $completedExchanges->count() }})</h2>
    <div class="glass-card rounded-2xl overflow-hidden">
        @forelse($completedExchanges as $exchange)
        @php
            $isRequester = $exchange->requester_id === auth()->id();
            $partner = $isRequester ? $exchange->provider : $exchange->requester;
            $partnerAvatar = $partner ? ($isRequester ? ($exchange->provider->avatar_url ?? 'https://ui-avatars.com/api/?name=U&background=6366f1&color=fff') : ($exchange->requester->avatar_url ?? 'https://ui-avatars.com/api/?name=U&background=6366f1&color=fff')) : 'https://ui-avatars.com/api/?name=U&background=6366f1&color=fff';
            $myRating = $isRequester ? $exchange->requester_rating : $exchange->provider_rating;
            $partnerGaveRating = $isRequester ? $exchange->provider_rating : $exchange->requester_rating;
            $parts = explode(' ← Teach: ', $exchange->provider_skill);
            $learnPart = str_replace('Learn: ', '', $parts[0] ?? '');
            $teachPart = $parts[1] ?? '';
        @endphp
        <div class="p-4 border-b border-white/10 last:border-0">
            <div class="flex items-start gap-3 mb-3">
                <img src="{{ $partnerAvatar }}" class="w-8 h-8 rounded-full shrink-0">
                <div class="flex-1">
                    <p class="text-sm text-white">{{ $partner ? $partner->name : 'Unknown' }}</p>
                    @if($isRequester)
                    <div class="space-y-1 mt-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-gray-400 shrink-0">You learned:</span>
                            @foreach(explode(',', $learnPart) as $skill)
                            <span class="px-2 py-0.5 bg-purple-500/20 text-purple-400 rounded text-xs">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-gray-400 shrink-0">They taught:</span>
                            @foreach(explode(',', $teachPart) as $skill)
                            <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-xs">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="space-y-1 mt-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-gray-400 shrink-0">You taught:</span>
                            @foreach(explode(',', $teachPart) as $skill)
                            <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-xs">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-gray-400 shrink-0">You learned:</span>
                            @foreach(explode(',', $learnPart) as $skill)
                            <span class="px-2 py-0.5 bg-purple-500/20 text-purple-400 rounded text-xs">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($myRating !== null)
            <div class="mt-2 p-2 bg-green-500/10 rounded-lg">
                <p class="text-xs text-green-400">You rated: {{ $myRating }}/5</p>
                @if($partnerGaveRating !== null)
                <p class="text-xs text-green-400 mt-1">Both rated</p>
                @else
                <p class="text-xs text-gray-400 mt-1">Waiting for partner to rate...</p>
                @endif
            </div>
            @else
            <div class="mt-2">
                <p class="text-xs text-gray-400 mb-2">Rate this exchange:</p>
                <form action="{{ route('exchange.rate', $exchange) }}" method="POST" class="star-rating-form">
                    @csrf @method('PUT')
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                        <span class="star" data-value="{{ $i }}">&#9733;</span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" class="rating-input" value="" required>
                    <button type="submit" class="px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-lg hover:bg-yellow-500/30">Rate</button>
                </form>
            </div>
            @endif
        </div>
        @empty
        <div class="p-8 text-center text-gray-400 text-sm">No completed exchanges yet</div>
        @endforelse
    </div>
</div>

<script>
document.querySelectorAll('.star-rating-form').forEach(form => {
    const stars = form.querySelectorAll('.star');
    const input = form.querySelector('.rating-input');
    let selectedRating = 0;

    function updateStars(rating) {
        stars.forEach((s, i) => {
            if (i < rating) {
                s.classList.add('filled');
                s.style.color = '#fbbf24';
            } else {
                s.classList.remove('filled');
                s.style.color = '';
            }
        });
    }

    stars.forEach((star, starIndex) => {
        star.addEventListener('mouseenter', () => {
            if (selectedRating === 0) {
                updateStars(starIndex + 1);
            }
        });

        star.addEventListener('mouseleave', () => {
            if (selectedRating === 0) {
                updateStars(0);
            }
        });

        star.addEventListener('click', () => {
            selectedRating = starIndex + 1;
            input.value = selectedRating;
            updateStars(selectedRating);
        });
    });
});
</script>
@endsection