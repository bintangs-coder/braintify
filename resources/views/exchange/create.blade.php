@extends('layouts.dashboard')
@section('title', 'Create Exchange Post')

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}
.skill-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(139, 92, 246, 0.3));
    border: 1px solid rgba(139, 92, 246, 0.5);
    border-radius: 20px;
    color: white;
    font-size: 14px;
    animation: fadeIn 0.2s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1); }
}
.skill-tag .remove {
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}
.skill-tag .remove:hover {
    opacity: 1;
}
.skill-input-wrapper {
    position: relative;
}
.skill-suggestions {
    position: fixed;
    background: #1f2937;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 9999;
    display: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    min-width: 200px;
}
.skill-suggestions.active {
    display: block;
}
.skill-suggestion {
    padding: 10px 16px;
    cursor: pointer;
    color: white;
    transition: background 0.2s;
}
.skill-suggestion:hover,
.skill-suggestion.highlighted {
    background: rgba(59, 130, 246, 0.3);
}
.skill-suggestion.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
}
.skill-tags-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    min-height: 48px;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    align-items: center;
}
.skill-tags-container:focus-within {
    border-color: rgba(139, 92, 246, 0.5);
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
}
.skill-input {
    flex: 1;
    min-width: 120px;
    background: transparent;
    border: none;
    outline: none;
    color: white;
    font-size: 16px;
    padding: 4px;
}
.skill-input::placeholder {
    color: #6b7280;
}
.skill-input:disabled {
    cursor: not-allowed;
}
.skill-input:disabled::placeholder {
    color: #4b5563;
}
</style>

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-2 text-white">Create Exchange Post</h1>
    <p class="text-gray-300 mb-8">Post what you can teach and what you want to learn</p>

    <form action="{{ route('exchange.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="glass-card rounded-2xl p-6">
            <h2 class="font-semibold mb-4 text-white">What can you teach?</h2>
            <p class="text-sm text-gray-400 mb-3">Select up to 3 skills (type or click to see suggestions)</p>
            <div class="skill-input-wrapper" id="teach-wrapper">
                <div class="skill-tags-container" id="teach-tags-container">
                    <input type="text" class="skill-input" id="teach-input" placeholder="Type or select a skill..." autocomplete="off">
                </div>
                <input type="hidden" name="my_skills" id="teach-hidden" value="">
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <h2 class="font-semibold mb-4 text-white">What do you want to learn?</h2>
            <p class="text-sm text-gray-400 mb-3">Select up to 3 skills (type or click to see suggestions)</p>
            <div class="skill-input-wrapper" id="learn-wrapper">
                <div class="skill-tags-container" id="learn-tags-container">
                    <input type="text" class="skill-input" id="learn-input" placeholder="Type or select a skill..." autocomplete="off">
                </div>
                <input type="hidden" name="wanted_skills" id="learn-hidden" value="">
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <h2 class="font-semibold mb-4 text-white">Description (optional)</h2>
            <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400" placeholder="Tell others more about what you're looking for..."></textarea>
        </div>

        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg hover:shadow-blue-500/30 font-medium">
            Create Post
        </button>
    </form>
</div>

{{-- Global suggestions dropdown container --}}
<div class="skill-suggestions" id="global-suggestions"></div>

<script>
const allSkills = [
    "JavaScript", "Python", "PHP", "React", "Laravel", "Vue.js", "Node.js", "TypeScript",
    "UI/UX Design", "Figma", "Photography", "Video Editing", "Excel", "Digital Marketing",
    "Content Writing", "Data Analysis", "Machine Learning", "Mobile Development",
    "Cloud Computing", "Cybersecurity", "AWS", "Docker", "Git", "SQL", "MongoDB",
    "Flutter", "Swift", "Kotlin", "React Native", "Angular", "Next.js", "Django",
    "Spring Boot", "Go", "Rust", "C++", "Java", "Ruby on Rails", "WordPress",
    "Shopify", "SEO", "Social Media Marketing", "Copywriting", "Graphic Design",
    "Motion Graphics", "3D Modeling", "Blender", "Adobe Premiere", "Lightroom"
];

const MAX_SKILLS = 3;

// Global suggestions dropdown
const globalSuggestions = document.getElementById('global-suggestions');
let currentInstance = null;
let highlightedIndex = -1;

function showSuggestions(instance) {
    currentInstance = instance;
    const input = instance.input;
    const rect = input.getBoundingClientRect();
    globalSuggestions.style.top = (rect.bottom + 8) + 'px';
    globalSuggestions.style.left = rect.left + 'px';
    globalSuggestions.style.width = rect.width + 'px';
    globalSuggestions.innerHTML = instance.getSuggestionsHTML();
    globalSuggestions.classList.add('active');
}

function hideSuggestions() {
    globalSuggestions.classList.remove('active');
    currentInstance = null;
    highlightedIndex = -1;
}

function updateSuggestions() {
    if (currentInstance) {
        globalSuggestions.innerHTML = currentInstance.getSuggestionsHTML();
        highlightedIndex = -1;
    }
}

// Event listener for global suggestions dropdown
globalSuggestions.addEventListener('click', (e) => {
    const item = e.target.closest('.skill-suggestion');
    if (item && !item.classList.contains('disabled') && currentInstance) {
        const skill = item.dataset.skill;
        currentInstance.addSkill(skill);
        if (currentInstance.selectedSkills.length < MAX_SKILLS) {
            currentInstance.input.focus();
        }
    }
});

document.addEventListener('keydown', (e) => {
    if (!globalSuggestions.classList.contains('active') || !currentInstance) return;

    const items = globalSuggestions.querySelectorAll('.skill-suggestion:not(.disabled)');

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        highlightedIndex = Math.min(highlightedIndex + 1, items.length - 1);
        items.forEach((item, i) => item.classList.toggle('highlighted', i === highlightedIndex));
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        highlightedIndex = Math.max(highlightedIndex - 1, 0);
        items.forEach((item, i) => item.classList.toggle('highlighted', i === highlightedIndex));
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (highlightedIndex >= 0 && items[highlightedIndex]) {
            currentInstance.addSkill(items[highlightedIndex].dataset.skill);
        } else if (currentInstance.input.value.trim()) {
            currentInstance.addSkill(currentInstance.input.value.trim());
        }
    } else if (e.key === 'Escape') {
        hideSuggestions();
    }
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.skill-input-wrapper') && !e.target.closest('.skill-suggestions')) {
        hideSuggestions();
    }
});

function initSkillInput(config) {
    const { containerId, inputId, hiddenId } = config;
    const container = document.getElementById(containerId);
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);

    const instance = {
        input,
        hidden,
        container,
        selectedSkills: []
    };

    instance.renderTags = function() {
        const tags = container.querySelectorAll('.skill-tag');
        tags.forEach(tag => tag.remove());

        this.selectedSkills.forEach((skill, index) => {
            const tag = document.createElement('span');
            tag.className = 'skill-tag';
            tag.innerHTML = `${skill}<span class="remove" data-index="${index}">×</span>`;
            container.insertBefore(tag, input);
        });

        hidden.value = this.selectedSkills.join(',');

        // Disable input when max skills reached
        if (this.selectedSkills.length >= MAX_SKILLS) {
            input.disabled = true;
            input.placeholder = `${MAX_SKILLS} skills selected`;
        } else {
            input.disabled = false;
            input.placeholder = 'Type or select a skill...';
        }
    };

    instance.getSuggestionsHTML = function() {
        const query = input.value.toLowerCase().trim();
        const filtered = allSkills.filter(skill =>
            skill.toLowerCase().includes(query) && !this.selectedSkills.includes(skill)
        );

        let html = filtered.map((skill) => {
            const disabled = this.selectedSkills.length >= MAX_SKILLS ? 'disabled' : '';
            return `<div class="skill-suggestion" data-skill="${skill}">${skill}</div>`;
        }).join('');

        // Add custom skill option if typed text is not in the list
        if (query && !allSkills.map(s => s.toLowerCase()).includes(query)) {
            const disabled = this.selectedSkills.length >= MAX_SKILLS ? 'disabled' : '';
            html += `<div class="skill-suggestion custom-skill" data-skill="${input.value.trim()}">+ Add "${input.value.trim()}"</div>`;
        }

        if (!html) {
            html = `<div class="skill-suggestion" style="cursor: default; opacity: 0.5;">No suggestions</div>`;
        }

        return html;
    };

    instance.addSkill = function(skill) {
        if (this.selectedSkills.length >= MAX_SKILLS) return;
        if (!skill.trim()) return;

        // Capitalize first letter of each word
        skill = skill.trim().split(' ').map(word =>
            word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
        ).join(' ');

        if (this.selectedSkills.includes(skill)) {
            input.value = '';
            updateSuggestions();
            return;
        }

        this.selectedSkills.push(skill);
        input.value = '';
        this.renderTags();
        hideSuggestions();

        // Re-show suggestions if not at max
        if (this.selectedSkills.length < MAX_SKILLS) {
            showSuggestions(this);
        }
    };

    instance.removeSkill = function(index) {
        this.selectedSkills.splice(index, 1);
        this.renderTags();
    };

    input.addEventListener('input', () => {
        if (instance.selectedSkills.length < MAX_SKILLS) {
            showSuggestions(instance);
        }
    });

    input.addEventListener('focus', () => {
        if (instance.selectedSkills.length < MAX_SKILLS) {
            showSuggestions(instance);
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && input.value === '' && instance.selectedSkills.length > 0) {
            instance.removeSkill(instance.selectedSkills.length - 1);
        }
    });

    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove')) {
            instance.removeSkill(parseInt(e.target.dataset.index));
            input.focus();
            if (instance.selectedSkills.length < MAX_SKILLS) {
                showSuggestions(instance);
            }
        } else if (!input.disabled) {
            input.focus();
        }
    });

    instance.renderTags();
    return instance;
}

const teachInstance = initSkillInput({
    containerId: 'teach-tags-container',
    inputId: 'teach-input',
    hiddenId: 'teach-hidden'
});

const learnInstance = initSkillInput({
    containerId: 'learn-tags-container',
    inputId: 'learn-input',
    hiddenId: 'learn-hidden'
});

// Form validation
document.querySelector('form').addEventListener('submit', (e) => {
    const teachHidden = document.getElementById('teach-hidden');
    const learnHidden = document.getElementById('learn-hidden');

    if (!teachHidden.value) {
        e.preventDefault();
        alert('Please select at least 1 skill you can teach');
    } else if (!learnHidden.value) {
        e.preventDefault();
        alert('Please select at least 1 skill you want to learn');
    }
});
</script>
@endsection