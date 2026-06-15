<!DOCTYPE html>
<html class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Braintify - Skill Exchange Platform</title>
    @vite(['resources/css/app.css'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
            50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .float-animation { animation: float 6s ease-in-out infinite; }
        .glow-animation { animation: pulse-glow 3s ease-in-out infinite; }
        .fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(99, 102, 241, 0.3);
            transform: translateY(-4px);
        }
        .grid-bg {
            background-image:
                linear-gradient(rgba(99, 102, 241, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-950 text-white min-h-screen">
    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-gray-950/90 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707-.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold gradient-text">Braintify</span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('how-it-works') }}" class="text-gray-400 hover:text-white transition-colors">How It Works</a>
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg font-medium hover:shadow-lg hover:shadow-blue-500/30 transition-all">Get Started</a>
            </div>
            <button class="md:hidden text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="min-h-screen flex items-center justify-center relative overflow-hidden pt-16">
        <div class="absolute inset-0 grid-bg"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-blue-600/30 via-purple-600/20 to-gray-950"></div>
        <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-blue-500/40 rounded-full blur-[120px] float-animation"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-purple-500/40 rounded-full blur-[120px] float-animation" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-blue-500/10 to-purple-500/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 max-w-3xl mx-auto text-center px-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 mb-8 fade-in-up">
                <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                <span class="text-sm text-blue-300">Free skill exchange for students</span>
            </div>

            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight fade-in-up" style="animation-delay: 0.1s;">
                Exchange Skills.<br>
                <span class="gradient-text">Learn Together.</span>
            </h1>

            <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-xl mx-auto leading-relaxed fade-in-up" style="animation-delay: 0.2s;">
                Trade skills for free with other students. Teach what you know, learn what you need.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up" style="animation-delay: 0.3s;">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl font-medium text-lg hover:shadow-2xl hover:shadow-blue-500/40 transition-all glow-animation">
                    Start Exchanging Free
                </a>
                <a href="{{ route('how-it-works') }}" class="px-8 py-4 glass-card rounded-xl font-medium text-lg hover:bg-white/5 transition-all">
                    How It Works
                </a>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-24 px-6 relative">
        <div class="absolute inset-0 grid-bg"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-gray-950 via-purple-600/10 to-gray-950"></div>
        <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-blue-500/30 rounded-full blur-[100px] float-animation"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-purple-500/30 rounded-full blur-[100px] float-animation" style="animation-delay: -3s;"></div>
        <div class="max-w-5xl mx-auto relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 gradient-text">How It Works</h2>
            <p class="text-gray-400 text-center mb-16 max-w-xl mx-auto">Three simple steps to start exchanging skills with fellow students</p>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/20">
                        <span class="text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Create Profile</h3>
                    <p class="text-gray-400">Sign up and list the skills you can teach and want to learn</p>
                </div>

                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-purple-500/20">
                        <span class="text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Find Match</h3>
                    <p class="text-gray-400">Browse posts or wait for someone to propose an exchange</p>
                </div>

                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-purple-500/20">
                        <span class="text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Start Exchange</h3>
                    <p class="text-gray-400">Accept proposals, chat, and start learning together</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Free --}}
    <section class="py-24 px-6 relative">
        <div class="absolute inset-0 grid-bg"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-gray-950 via-blue-600/10 to-gray-950"></div>
        <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-blue-500/30 rounded-full blur-[100px] float-animation"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-purple-500/30 rounded-full blur-[100px] float-animation" style="animation-delay: -3s;"></div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 gradient-text">100% Free</h2>
            <p class="text-gray-400 text-lg mb-12">No fees, no subscriptions, no hidden costs. Just pure knowledge sharing.</p>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">No Payment</h3>
                    <p class="text-sm text-gray-400">Exchange skills directly</p>
                </div>

                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Chat Included</h3>
                    <p class="text-sm text-gray-400">Built-in messaging</p>
                </div>

                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Flexible</h3>
                    <p class="text-sm text-gray-400">Your own schedule</p>
                </div>

                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Trust System</h3>
                    <p class="text-sm text-gray-400">Build reputation</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 px-6 relative">
        <div class="absolute inset-0 grid-bg"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-gray-950 via-purple-600/15 to-gray-950"></div>
        <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-blue-500/30 rounded-full blur-[100px] float-animation"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-purple-500/30 rounded-full blur-[100px] float-animation" style="animation-delay: -3s;"></div>
        <div class="max-w-2xl mx-auto text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 gradient-text">Ready to start?</h2>
            <p class="text-gray-400 text-lg mb-8">Join students exchanging skills every day.</p>
            <a href="{{ route('register') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl font-medium text-lg hover:shadow-2xl hover:shadow-blue-500/40 transition-all glow-animation">
                Create Free Account
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 px-6 border-t border-white/5">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707-.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <span class="font-bold">Braintify</span>
            </div>
            <p class="text-sm text-gray-500">© 2026 Braintify. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
