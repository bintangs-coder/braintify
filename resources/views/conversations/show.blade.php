@extends('layouts.dashboard')
@section('title', 'Chat with ' . ($otherUser?->name ?? 'User'))

<style>
.message-bubble {
    max-width: 70%;
    word-wrap: break-word;
}
.message-mine {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    border-radius: 18px 18px 4px 18px;
}
.message-other {
    background: rgba(255, 255, 255, 0.08);
    color: white;
    border-radius: 18px 18px 18px 4px;
}
.chat-container {
    height: calc(100vh - 280px);
    min-height: 400px;
    background: transparent !important;
}
#message-form input[type="text"] {
    background: transparent !important;
    color: white !important;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}
#message-form input[type="text"]::placeholder {
    color: rgba(255, 255, 255, 0.4) !important;
}
#message-form > div:first-child {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 24px !important;
}
</style>

@section('content')
{{-- Header --}}
<div class="mb-4 flex items-center gap-4 px-2">
    <a href="{{ route('conversations.index') }}" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <img src="{{ $otherUser?->avatar_url ?? 'https://ui-avatars.com/api/?name=User&background=6366f1&color=fff' }}" class="w-10 h-10 rounded-full">
    <div class="flex-1">
        <h2 class="font-semibold text-white">{{ $otherUser?->name ?? 'Unknown User' }}</h2>
        @if($otherUser?->profile?->bio)
        <p class="text-xs text-gray-400">{{ Str::limit($otherUser->profile->bio, 50) }}</p>
        @endif
    </div>
</div>

{{-- Chat Messages --}}
<div class="rounded-2xl overflow-hidden flex flex-col">
    <div id="messages-container" class="chat-container overflow-y-auto p-4 space-y-4">
        @forelse($messages as $message)
            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                @if($message->sender_id !== auth()->id())
                    <img src="{{ $message->sender->avatar_url }}" class="w-8 h-8 rounded-full mr-2 self-end">
                @endif
                <div class="message-bubble message-{{ $message->sender_id === auth()->id() ? 'mine' : 'other' }} px-4 py-2">
                    <p>{{ $message->content }}</p>
                    <p class="text-xs mt-1 {{ $message->sender_id === auth()->id() ? 'text-white/60' : 'text-gray-400' }}">
                        {{ $message->created_at->format('H:i') }}
                        @if($message->sender_id === auth()->id() && $message->is_read)
                            <span class="ml-1">✓✓</span>
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-2">💬</div>
                <p>No messages yet. Say hello!</p>
            </div>
        @endforelse
    </div>

    {{-- Input Area --}}
    <div class="p-4">
        <form id="message-form" class="flex items-center gap-3">
            @csrf
            <div class="flex-1 flex items-center px-4 py-3">
                <input type="text"
                       id="message-input"
                       name="content"
                       placeholder="Type a message..."
                       autocomplete="off">
            </div>
            <button type="submit"
                    class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center hover:shadow-lg hover:shadow-blue-500/30 transition-all">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const conversationId = {{ $conversation->id }};
const messagesContainer = document.getElementById('messages-container');
const messageForm = document.getElementById('message-form');
const messageInput = document.getElementById('message-input');

// Scroll to bottom on load
messagesContainer.scrollTop = messagesContainer.scrollHeight;

// Send message
messageForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const content = messageInput.value.trim();
    if (!content) return;

    const submitBtn = messageForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50');

    try {
        const response = await fetch(`/conversations/${conversationId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ content })
        });

        const data = await response.json();

        if (data.success) {
            addMessageToUI(data.message);
            messageInput.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        } else {
            alert('Failed to send message: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50');
    }
});

// Add message to UI
function addMessageToUI(message) {
    const isMine = message.is_mine;
    const html = `
        <div class="flex ${isMine ? 'justify-end' : 'justify-start'}">
            ${!isMine ? `<img src="${message.sender_avatar}" class="w-8 h-8 rounded-full mr-2 self-end">` : ''}
            <div class="message-bubble message-${isMine ? 'mine' : 'other'} px-4 py-2">
                <p>${escapeHtml(message.content)}</p>
                <p class="text-xs mt-1 ${isMine ? 'text-white/60' : 'text-gray-400'}">
                    ${formatTime(message.created_at)}
                </p>
            </div>
        </div>
    `;
    messagesContainer.insertAdjacentHTML('beforeend', html);

    // Update lastMessageTime for polling
    if (new Date(message.created_at) > new Date(lastMessageTime)) {
        lastMessageTime = message.created_at;
    }
}

// Poll for new messages every 5 seconds
let lastMessageTime = new Date('{{ $messages->max("created_at")?->toIso8601String() ?? "1970-01-01" }}').toISOString();

setInterval(async () => {
    try {
        const response = await fetch(`/conversations/${conversationId}/messages?after=${encodeURIComponent(lastMessageTime)}`);
        const data = await response.json();

        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(message => {
                addMessageToUI(message);
                // Update lastMessageTime to the newest message
                if (new Date(message.created_at) > new Date(lastMessageTime)) {
                    lastMessageTime = message.created_at;
                }
            });
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    } catch (error) {
        console.error('Error polling messages:', error);
    }
}, 5000);

// Helper functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatTime(isoString) {
    const date = new Date(isoString);
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
}
</script>
@endpush