@extends('layouts.auth')
@section('title', 'Sign Up')

@section('content')
<h1 class="text-2xl font-bold text-center mb-6 gradient-text">Create Account</h1>

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
        <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
    </div>
    <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg hover:shadow-blue-500/30 font-medium transition-all">
        Create Account
    </button>
</form>

<p class="mt-6 text-center text-gray-400 text-sm">
    Already have an account? <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Sign in</a>
</p>
@endsection