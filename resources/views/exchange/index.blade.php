@extends('layouts.dashboard')
@section('title', 'Browse Exchanges')

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
.propose-section {
    display: none;
}
.propose-section.active {
    display: block;
}
.skill-select-btn {
    padding: 6px 12px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: white;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 13px;
}
.skill-select-btn:hover {
    background: rgba(59, 130, 246, 0.3);
    border-color: rgba(59, 130, 246, 0.5);
}
.skill-select-btn.selected {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.4), rgba(139, 92, 246, 0.4));
    border-color: rgba(139, 92, 246, 0.6);
}
.skill-select-btn.other-selected {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.4), rgba(239, 68, 68, 0.4));
    border-color: rgba(245, 158, 11, 0.6);
}
.custom-skill-input {
    display: none;
    margin-top: 8px;
}
.custom-skill-input.active {
    display: block;
}
</style>

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold mb-2 text-white">Browse Exchanges</h1>
        <p class="text-gray-400">Find skill exchange opportunities</p>
    </div>
    <a href="{{ route('exchange.my') }}" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707-.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
        </svg>
        My Exchanges
    </a>
</div>

{{-- Search Bar --}}
<div class="mb-6">
    <form action="{{ route('exchange.index') }}" method="GET" class="relative">
        <input type="text"
               name="search"
               value="{{ $searchQuery ?? '' }}"
               placeholder="Search by skill or username..."
               class="w-full px-4 py-3 pl-12 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        @if(($searchQuery ?? ''))
        <a href="{{ route('exchange.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
        @endif
    </form>
    @if(($searchQuery ?? ''))
    <p class="mt-2 text-sm text-gray-400">Showing results for "{{ $searchQuery }}" ({{ $allPosts->count() }} found)</p>
    @endif
</div>

@if(session('success'))
<div class="mb-6 p-4 glass-card rounded-xl text-green-400">{{ session('success') }}</div>
@endif

{{-- Browse Exchange Posts --}}
<div class="glass-card rounded-2xl p-6">
    <h2 class="text-lg font-semibold mb-4 text-white">Available Exchange Posts</h2>
    @forelse($allPosts as $post)
    <div class="glass-card rounded-xl p-4 border border-white/10 mb-4 hover:border-blue-500/30 transition-all" id="post-{{ $post->id }}">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-3">
                <a href="{{ route('profile.show', $post->requester) }}">
                    <img src="{{ $post->requester->avatar_url }}" class="w-12 h-12 rounded-full hover:ring-2 hover:ring-blue-500 transition-all">
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('profile.show', $post->requester) }}" class="font-medium text-white hover:text-blue-400 transition-colors">{{ $post->requester->name }}</a>
                        <span class="px-2 py-0.5 text-xs rounded-full
                            @if($post->status->value === 'pending') bg-yellow-500/20 text-yellow-400
                            @elseif($post->status->value === 'accepted') bg-green-500/20 text-green-400
                            @else bg-gray-500/20 text-gray-400
                            @endif">
                            {{ $post->status->label() }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <span class="text-xs text-gray-400">Offers:</span>
                        @foreach(explode(',', $post->requester_skill) as $skill)
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs">{{ trim($skill) }}</span>
                        @endforeach
                        <span class="text-gray-500 mx-1">↔</span>
                        <span class="text-xs text-gray-400">Wants:</span>
                        @foreach(explode(',', $post->wanted_skill) as $skill)
                        <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded text-xs">{{ trim($skill) }}</span>
                        @endforeach
                    </div>
                    @if($post->requester_note)
                    <p class="text-sm text-gray-400 mt-2">{{ $post->requester_note }}</p>
                    @endif
                </div>
            </div>
            <button onclick="togglePropose({{ $post->id }})" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white text-sm rounded-xl hover:shadow-lg">
                Propose Exchange
            </button>
        </div>

        {{-- Propose Section --}}
        <div class="propose-section mt-4 pt-4 border-t border-white/10" id="propose-{{ $post->id }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- What user wants to LEARN (can select multiple) --}}
                <div>
                    <p class="text-sm text-purple-400 mb-2 font-medium">What you want to LEARN: (select one or more)</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $post->requester_skill) as $skill)
                        <button type="button" class="skill-select-btn learn-btn" data-skill="{{ trim($skill) }}" onclick="toggleLearnSkill(this, {{ $post->id }})">
                            {{ trim($skill) }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- What user wants to TEACH (can select multiple + other) --}}
                <div>
                    <p class="text-sm text-green-400 mb-2 font-medium">What you will TEACH: (select one or more)</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $post->wanted_skill) as $skill)
                        <button type="button" class="skill-select-btn teach-btn" data-skill="{{ trim($skill) }}" onclick="toggleTeachSkill(this, {{ $post->id }})">
                            {{ trim($skill) }}
                        </button>
                        @endforeach
                        <button type="button" class="skill-select-btn other-btn" onclick="toggleOtherSkill({{ $post->id }})">
                            + Other
                        </button>
                    </div>
                    <div class="custom-skill-input" id="other-input-{{ $post->id }}">
                        <input type="text" id="custom-skill-{{ $post->id }}" class="w-full px-3 py-2 text-sm rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-400 mt-2" placeholder="Type your custom skill..." oninput="updateOtherSkill({{ $post->id }})">
                    </div>
                </div>
            </div>

            <form action="{{ route('exchange.propose', $post) }}" method="POST" class="flex flex-wrap items-center gap-3">
                @csrf
                <input type="hidden" name="their_skill" id="learn-skills-{{ $post->id }}" value="" required>
                <input type="hidden" name="my_skill" id="teach-skills-{{ $post->id }}" value="" required>
                <input type="text" name="message" class="px-3 py-2 text-sm rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400 w-64" placeholder="Add a message (optional)">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm rounded-xl hover:shadow-lg" id="submit-btn-{{ $post->id }}" disabled>
                    Send Proposal
                </button>
                <button type="button" onclick="togglePropose({{ $post->id }})" class="px-4 py-2 bg-white/10 text-gray-400 text-sm rounded-xl hover:bg-white/20">
                    Cancel
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="p-12 text-center">
        <div class="text-4xl mb-4">🔍</div>
        <p class="text-gray-400 mb-2">No exchange posts available</p>
        <p class="text-sm text-gray-500">Be the first to create an exchange post!</p>
        <a href="{{ route('exchange.my') }}" class="inline-block mt-4 text-blue-400 hover:text-blue-300">Create Exchange →</a>
    </div>
    @endforelse
</div>

<script>
function togglePropose(postId) {
    const proposeSection = document.getElementById('propose-' + postId);
    proposeSection.classList.toggle('active');

    // Reset selection when closing
    if (!proposeSection.classList.contains('active')) {
        resetProposeSelection(postId);
    }
}

function resetProposeSelection(postId) {
    const proposeSection = document.getElementById('propose-' + postId);
    const learnBtns = proposeSection.querySelectorAll('.learn-btn');
    const teachBtns = proposeSection.querySelectorAll('.teach-btn');
    const otherBtn = proposeSection.querySelector('.other-btn');
    const otherInput = document.getElementById('other-input-' + postId);
    const customSkillInput = document.getElementById('custom-skill-' + postId);
    const learnInput = document.getElementById('learn-skills-' + postId);
    const teachInput = document.getElementById('teach-skills-' + postId);
    const submitBtn = document.getElementById('submit-btn-' + postId);

    learnBtns.forEach(btn => btn.classList.remove('selected'));
    teachBtns.forEach(btn => btn.classList.remove('selected'));
    if (otherBtn) otherBtn.classList.remove('other-selected');
    if (otherInput) otherInput.classList.remove('active');
    if (customSkillInput) customSkillInput.value = '';
    learnInput.value = '';
    teachInput.value = '';
    submitBtn.disabled = true;
}

function toggleLearnSkill(btn, postId) {
    btn.classList.toggle('selected');
    updateSelectedSkills(postId);
}

function toggleTeachSkill(btn, postId) {
    btn.classList.toggle('selected');
    updateSelectedSkills(postId);
}

function toggleOtherSkill(postId) {
    const otherBtn = document.querySelector(`#propose-${postId} .other-btn`);
    const otherInput = document.getElementById('other-input-' + postId);

    if (otherBtn.classList.contains('other-selected')) {
        // Deselect
        otherBtn.classList.remove('other-selected');
        otherInput.classList.remove('active');
    } else {
        // Select and show input
        otherBtn.classList.add('other-selected');
        otherInput.classList.add('active');
        document.getElementById('custom-skill-' + postId).focus();
    }
    updateSelectedSkills(postId);
}

function updateOtherSkill(postId) {
    updateSelectedSkills(postId);
}

function updateSelectedSkills(postId) {
    const proposeSection = document.getElementById('propose-' + postId);
    const learnBtns = proposeSection.querySelectorAll('.learn-btn.selected');
    const teachBtns = proposeSection.querySelectorAll('.teach-btn.selected');
    const otherBtn = proposeSection.querySelector('.other-btn.other-selected');
    const customSkillInput = document.getElementById('custom-skill-' + postId);
    const learnInput = document.getElementById('learn-skills-' + postId);
    const teachInput = document.getElementById('teach-skills-' + postId);
    const submitBtn = document.getElementById('submit-btn-' + postId);

    // Collect learn skills
    const learnSkills = Array.from(learnBtns).map(btn => btn.dataset.skill);
    learnInput.value = learnSkills.join(',');

    // Collect teach skills
    let teachSkills = Array.from(teachBtns).map(btn => btn.dataset.skill);

    // Add custom skill if "Other" is selected and has value
    if (otherBtn && customSkillInput && customSkillInput.value.trim()) {
        teachSkills.push(customSkillInput.value.trim());
    }

    teachInput.value = teachSkills.join(',');

    // Enable submit only if both have at least one skill
    submitBtn.disabled = !(learnInput.value && teachInput.value);
}

// Form validation
document.querySelectorAll('form[action*="exchange.propose"]').forEach(form => {
    form.addEventListener('submit', (e) => {
        const postId = form.closest('.propose-section').id.replace('propose-', '');
        const learnInput = document.getElementById('learn-skills-' + postId);
        const teachInput = document.getElementById('teach-skills-' + postId);

        if (!learnInput.value || !teachInput.value) {
            e.preventDefault();
            alert('Please select at least one skill you want to learn and one skill you will teach');
        }
    });
});
</script>
@endsection