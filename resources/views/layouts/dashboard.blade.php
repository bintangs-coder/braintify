<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Braintify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-950 dark:text-white">
    <div class="flex min-h-screen">
        {{-- Sidebar Desktop --}}
        <aside class="hidden lg:flex flex-col w-64 bg-gray-900 border-r border-white/10 fixed h-full">
            @include('layouts.partials.sidebar')
        </aside>

        {{-- Mobile Sidebar Overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

        {{-- Mobile Sidebar --}}
        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transform -translate-x-full transition-transform lg:hidden">
            @include('layouts.partials.sidebar')
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 lg:ml-64">
            {{-- Top Header --}}
            <header class="bg-gray-900/80 backdrop-blur-lg border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4">
                    <div class="flex items-center gap-4">
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-white/10 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-white">@yield('title', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- Messages --}}
                        <a href="{{ route('conversations.index') }}" class="relative p-2 rounded-lg hover:bg-white/10 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            @php
                                $unreadMessages = \App\Models\Conversation::where(function($q) {
                                    $q->where('user1_id', auth()->id())->orWhere('user2_id', auth()->id());
                                })->withCount(['messages' => function($q) {
                                    $q->where('sender_id', '!=', auth()->id())->where('is_read', false);
                                }])->get()->sum('messages_count');
                            @endphp
                            @if($unreadMessages > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center">{{ $unreadMessages > 9 ? '9+' : $unreadMessages }}</span>
                            @endif
                        </a>

                        {{-- Notifications --}}
                        <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg hover:bg-white/10 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(auth()->user()->unread_notifications_count > 0)
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </a>

                        {{-- User Menu --}}
                        <div class="relative">
                            <button onclick="document.getElementById('user-menu').classList.toggle('hidden')" class="flex items-center gap-2 p-2 rounded-lg hover:bg-white/10">
                                <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full">
                                <span class="hidden sm:block text-sm font-medium text-white">{{ auth()->user()->name }}</span>
                            </button>

                            <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-xl shadow-lg border border-white/10 hidden z-50">
                                <div class="py-2">
                                    <a href="{{ route('profile.show', auth()->id()) }}" class="block px-4 py-2 text-sm text-white hover:bg-white/10">My Profile</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-white hover:bg-white/10">Edit Profile</a>
                                    <hr class="my-2 border-white/10">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-white/10">Sign Out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        function toggleSidebar() {
            document.getElementById('mobile-sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }
    </script>
</body>
</html>