<!DOCTYPE html>
<html class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>How It Works - Braintify</title>
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
        .float-animation { animation: float 6s ease-in-out infinite; }
        .glow-animation { animation: pulse-glow 3s ease-in-out infinite; }
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-950 text-white min-h-screen">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-gray-950/80 backdrop-blur-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707-.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold gradient-text">Braintify</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">Home</a>
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg font-medium hover:shadow-lg hover:shadow-blue-500/30 transition-all">Get Started</a>
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-20 px-6 min-h-screen relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-600/20 via-purple-600/10 to-pink-600/20"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/30 rounded-full blur-3xl float-animation" style="animation-delay: -3s;"></div>

        <div class="max-w-4xl mx-auto relative z-10">
            <h1 class="text-4xl font-bold text-center mb-16 gradient-text">How It Works</h1>

            <div class="space-y-8">
                <div class="glass-card rounded-2xl p-8 flex items-start gap-6 hover:bg-white/10 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0 shadow-lg shadow-blue-500/30">1</div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Create Your Profile</h3>
                        <p class="text-gray-400 leading-relaxed">Sign up and add your skills - both what you can teach and what you want to learn.</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-8 flex items-start gap-6 hover:bg-white/10 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0 shadow-lg shadow-purple-500/30">2</div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Post Your Exchange Request</h3>
                        <p class="text-gray-400 leading-relaxed">Create a post saying what skill you offer and what you want to learn.</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-8 flex items-start gap-6 hover:bg-white/10 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0 shadow-lg shadow-green-500/30">3</div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Accept Proposals</h3>
                        <p class="text-gray-400 leading-relaxed">When someone proposes to exchange with you, accept if it looks good.</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-8 flex items-start gap-6 hover:bg-white/10 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0 shadow-lg shadow-yellow-500/30">4</div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Chat & Exchange</h3>
                        <p class="text-gray-400 leading-relaxed">Use our built-in chat to coordinate, then start exchanging skills!</p>
                    </div>
                </div>
            </div>

            {{-- Free Section --}}
            <div class="mt-16 glass-card rounded-3xl p-8 bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-500/30">
                <h2 class="text-2xl font-bold mb-4 text-green-400">100% Free</h2>
                <p class="text-gray-400 mb-6">Skill Exchange is completely free! No fees, no subscriptions, no hidden costs.</p>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-green-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>No payment required</span>
                    </div>
                    <div class="flex items-center gap-3 text-green-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Free chat messaging included</span>
                    </div>
                    <div class="flex items-center gap-3 text-green-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Build trust through successful exchanges</span>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="mt-8 text-center">
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl font-medium text-lg hover:shadow-2xl hover:shadow-blue-500/40 transition-all glow-animation">
                    Get Started Free
                </a>
            </div>
        </div>
    </section>
</body>
</html>