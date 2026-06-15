@extends('layouts.dashboard')
@section('title', 'Edit Profile')

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
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-8 text-white">Edit Profile</h1>
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        <div class="glass-card rounded-2xl p-6 hover:bg-white/10">
            <h2 class="font-semibold mb-4 text-white">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Location</label>
                    <input type="text" name="location" value="{{ old('location', $user->location) }}" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2 text-gray-300">Bio</label>
                    <textarea name="bio" rows="3" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400">{{ old('bio', $user->bio) }}</textarea>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6 hover:bg-white/10">
            <h2 class="font-semibold mb-4 text-white">Profile Details</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Headline</label>
                    <input type="text" name="headline" value="{{ old('headline', $user->profile?->headline) }}" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400" placeholder="e.g., Senior Laravel Developer">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Teaching Style</label>
                    <select name="teaching_style" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white">
                        <option value="casual" {{ old('teaching_style', $user->profile?->teaching_style)=='casual'?'selected':'' }}>Casual</option>
                        <option value="structured" {{ old('teaching_style', $user->profile?->teaching_style)=='structured'?'selected':'' }}>Structured</option>
                        <option value="project_based" {{ old('teaching_style', $user->profile?->teaching_style)=='project_based'?'selected':'' }}>Project-Based</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg hover:shadow-blue-500/30 font-medium">Save Changes</button>
    </form>
</div>
@endsection