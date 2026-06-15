@extends('layouts.dashboard')
@section('title', 'Create Service')

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
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-2 text-white">Create Mentoring Session</h1>
    <p class="text-gray-300 mb-8">Offer your mentoring session to the community</p>

    <div class="glass-card rounded-xl p-4 mb-8 border border-blue-500/30">
        <p class="text-sm text-blue-400">
            💡 <strong>Tips:</strong> Mentoring session prices range from Rp 10,000 - 500,000!
        </p>
    </div>

    <form id="service-form" action="{{ route('services.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="glass-card rounded-2xl p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-300">Session Title *</label>
                <input type="text" name="title" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400" placeholder="Example: Laravel Coding from Scratch" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-300">Description *</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400" placeholder="Explain what you will teach in this mentoring session..." required></textarea>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <h3 class="font-medium mb-4 text-white">Session Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Price per Session (Rp) *</label>
                    <input type="number" name="price" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400" placeholder="50000" min="10000" max="500000" required>
                    <p class="text-xs text-gray-400 mt-1">Rp 10,000 - 500,000</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Session Duration *</label>
                    <select name="session_duration" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white" required>
                        <option value="">-- Select Duration --</option>
                        <option value="30">30 minutes</option>
                        <option value="45">45 minutes</option>
                        <option value="60">60 minutes</option>
                        <option value="90">90 minutes</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Choose the appropriate duration</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2 text-gray-300">Session Method *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex flex-col items-center gap-2 p-4 border border-white/10 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                            <input type="radio" name="session_method" value="video" checked class="w-5 h-5">
                            <span class="text-2xl">📹</span>
                            <span class="text-sm font-medium text-white">Video Call</span>
                        </label>
                        <label class="flex flex-col items-center gap-2 p-4 border border-white/10 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                            <input type="radio" name="session_method" value="voice" class="w-5 h-5">
                            <span class="text-2xl">📞</span>
                            <span class="text-sm font-medium text-white">Voice Call</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg hover:shadow-blue-500/30 font-medium">
            Publish Mentoring
        </button>
    </form>
</div>
@endsection