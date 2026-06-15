@extends('layouts.dashboard')
@section('title', 'My Services')

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}
.glass-card:hover {
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 8px 32px rgba(99, 102, 241, 0.15);
}
</style>

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold mb-2 text-white">My Services</h1>
        <p class="text-gray-300">Manage your mentoring sessions</p>
    </div>
    <a href="{{ route('services.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg flex items-center gap-2">
        + Create Mentoring
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 glass-card rounded-xl text-green-400 mb-8">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- My Services --}}
    <div class="glass-card rounded-2xl p-6 hover:bg-white/10">
        <h2 class="font-semibold mb-4 text-white">My Services ({{ $myServices->count() }})</h2>
        @forelse($myServices as $service)
        <div class="p-4 border-b border-white/10 last:border-0 mb-4 last:mb-0 hover:bg-white/5 transition-colors rounded-lg">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="font-medium text-white">{{ $service->title }}</h3>
                    <div class="flex items-center gap-3 mt-1 text-sm text-gray-300">
                        <span class="flex items-center gap-1">
                            💰 Rp {{ number_format($service->price, 0, ',', ',') }}
                        </span>
                        <span class="flex items-center gap-1">
                            ⏱️ {{ $service->duration_label }}
                        </span>
                        <span class="flex items-center gap-1">
                            {{ $service->method_icon }} {{ $service->method_label }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $service->description }}</p>
                </div>
                <span class="px-3 py-1 text-xs rounded-full {{ $service->status->value === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                    {{ $service->status->label() }}
                </span>
            </div>
            <div class="flex gap-2 mt-4">
                <a href="{{ route('services.edit', $service) }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white text-sm rounded-lg hover:shadow-lg">
                    ✏️ Edit
                </a>
                <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600">
                        🗑️ Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <p class="text-gray-300 mb-4">No mentoring sessions yet</p>
            <a href="{{ route('services.create') }}" class="text-blue-400 hover:text-blue-300">Create first mentoring →</a>
        </div>
        @endforelse
    </div>

    {{-- Incoming Orders --}}
    <div class="glass-card rounded-2xl p-6 hover:bg-white/10">
        <h2 class="font-semibold mb-4 text-white">Incoming Orders ({{ $ordersReceived->count() }})</h2>
        @forelse($ordersReceived as $order)
        <div class="flex items-center gap-4 p-4 border-b border-white/10 last:border-0 hover:bg-white/5 transition-colors rounded-lg">
            <img src="{{ $order->buyer->avatar_url }}" class="w-10 h-10 rounded-full">
            <div class="flex-1">
                <h3 class="font-medium text-sm text-white">{{ $order->service->title }}</div>
                <p class="text-xs text-gray-300">from {{ $order->buyer->name }}</div>
            </div>
            <div class="text-right">
                <p class="font-medium text-sm text-white">Rp {{ number_format($order->price, 0, ',', ',') }}</p>
                <span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">{{ $order->status->label() }}</span>
            </div>
        </div>
        @empty
        <p class="text-center py-8 text-gray-300">No incoming orders</p>
        @endforelse
    </div>
</div>
@endsection