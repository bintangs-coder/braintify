@extends('layouts.dashboard')
@section('title', $mentor->name)

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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="glass-card rounded-2xl p-6 mb-6 hover:bg-white/10">
            <div class="flex items-start gap-6">
                <img src="{{ $mentor->avatar_url }}" class="w-24 h-24 rounded-full ring-4 ring-blue-500/30">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $mentor->name }}</h1>
                    <p class="text-gray-300">{{ $mentor->profile?->headline }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        @for($i=1;$i<=5;$i++)
                        <svg class="w-5 h-5 {{ $i<=($mentor->profile?->avg_rating??0)?'text-yellow-400':'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="text-gray-300">({{ $mentor->reviewsReceived()->count() }} reviews)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 mb-6 hover:bg-white/10">
            <h2 class="text-lg font-semibold mb-4 text-white">Services Offered</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($mentor->skillsOffered as $skill)
                <div class="p-4 bg-blue-500/10 rounded-xl border border-blue-500/20">
                    <h3 class="font-medium text-white">{{ $skill->name }}</h3>
                    <p class="text-sm text-gray-400">Level: {{ ucfirst($skill->pivot->proficiency) }}</p>
                    @if($skill->pivot->hourly_rate)
                    <p class="text-lg font-bold text-green-400 mt-2">Rp {{ number_format($skill->pivot->hourly_rate, 0, ',', ',') }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 hover:bg-white/10">
            <h2 class="text-lg font-semibold mb-4 text-white">Reviews</h2>
            @forelse($reviews as $review)
            <div class="border-b border-white/10 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                <div class="flex items-center gap-3 mb-2">
                    <img src="{{ $review->reviewer->avatar_url }}" class="w-8 h-8 rounded-full">
                    <div>
                        <p class="font-medium text-sm text-white">{{ $review->reviewer->name }}</p>
                        <div class="flex items-center gap-1">
                            @for($i=1;$i<=5;$i++)
                            <svg class="w-3 h-3 {{ $i<=$review->overall_rating?'text-yellow-400':'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                </div>
                @if($review->comment)
                <p class="text-sm text-gray-300">{{ $review->comment }}</p>
                @endif
            </div>
            @empty
            <p class="text-gray-300">No reviews yet</p>
            @endforelse
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="glass-card rounded-2xl p-6 sticky top-24 hover:bg-white/10">
            <h2 class="text-lg font-semibold mb-4 text-white">Book Session</h2>

            <form action="{{ route('booking.book', $mentor) }}" method="POST" class="space-y-4">
                @csrf

                {{-- Select Service --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Select Service</label>
                    <select name="skill_id" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white" required>
                        <option value="">-- Select Service --</option>
                        @foreach($mentor->skillsOffered as $skill)
                        <option value="{{ $skill->id }}" {{ old('skill_id') == $skill->id ? 'selected' : '' }}>
                            {{ $skill->name }}
                            @if($skill->pivot->hourly_rate)
                                - Rp {{ number_format($skill->pivot->hourly_rate, 0, ',', ',') }}
                            @endif
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Select Date --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Select Date</label>
                    <input type="date" name="booking_date"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           value="{{ old('booking_date') }}"
                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white" required>
                </div>

                {{-- Select Time --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Select Time</label>
                    <input type="time" name="scheduled_time" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white" required>
                </div>

                {{-- Duration --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Duration</label>
                    <select name="duration" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white">
                        <option value="30">30 minutes</option>
                        <option value="45">45 minutes</option>
                        <option value="60">60 minutes</option>
                    </select>
                </div>

                {{-- Method --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Session Method</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 text-gray-300">
                            <input type="radio" name="session_method" value="video" checked class="w-4 h-4">
                            <span>📹 Video Call</span>
                        </label>
                        <label class="flex items-center gap-2 text-gray-300">
                            <input type="radio" name="session_method" value="voice" class="w-4 h-4">
                            <span>📞 Voice Call</span>
                        </label>
                    </div>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-300">Notes (optional)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400" placeholder="Tell us about your learning goals...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg hover:shadow-blue-500/30 font-medium">
                    Book Now
                </button>
            </form>

            <p class="text-sm text-gray-400 text-center mt-4">
                Mentor will confirm your booking
            </p>
        </div>
    </div>
</div>
@endsection