<div class="flex flex-col h-full">
    {{-- Logo --}}
    <div class="p-6 border-b border-white/10">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707-.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <span class="text-xl font-bold gradient-text">Braintify</span>
        </a>
    </div>

    {{-- Main Navigation --}}
    <div class="p-4">
        <p class="px-4 text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Menu</p>
        <nav class="space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('exchange.my') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('exchange.my') ? 'bg-white/10 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>My Exchanges</span>
            </a>

            <a href="{{ route('exchange.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('exchange.index') ? 'bg-white/10 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Browse Exchanges</span>
            </a>
        </nav>
    </div>

    {{-- Quick Links --}}
    <div class="p-4 border-t border-white/10">
        <p class="px-4 text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Quick Links</p>
        <nav class="space-y-1">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('profile.edit') ? 'bg-white/10 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0 4 4 0 01-8 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Edit Profile</span>
            </a>

            <a href="{{ route('conversations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('conversations.*') ? 'bg-white/10 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>Messages</span>
                @if(auth()->check() && auth()->user()->unread_messages_count > 0)
                    <span class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></span>
                @endif
            </a>

            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('notifications.*') ? 'bg-white/10 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span>Notifications</span>
                @if(auth()->user()->unread_notifications_count > 0)
                    <span class="ml-auto w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </a>
        </nav>
    </div>

    {{-- Bottom - Role Badge --}}
    <div class="p-4 border-t border-white/10">
        <div class="glass-card rounded-xl p-4">
            <p class="text-sm font-medium text-gray-300">{{ auth()->user()->role->label() }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format(auth()->user()->trust_score ?? 0, 1) }} Trust Score</p>
        </div>
    </div>
</div>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
}
</style>