<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\SkillCategory;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('services.index', [
            'myServices' => $user->services()->latest()->get(),
            'ordersReceived' => $user->ordersAsSeller()
                ->with('service', 'buyer:id,name,avatar')
                ->latest()
                ->get(),
            'ordersPlaced' => $user->ordersAsBuyer()
                ->with('service', 'seller:id,name,avatar')
                ->latest()
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('services.create', [
            'categories' => SkillCategory::active()->root()->with('children')->get(),
            'skills' => Skill::active()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'category_id' => 'nullable|exists:skill_categories,id',
            'price' => 'required|numeric|min:10000|max:500000',
            'session_duration' => 'required|in:30,45,60,90',
            'session_method' => 'required|in:video,voice',
        ]);

        Service::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'status' => 'active',
        ]));

        return redirect()
            ->route('services.index')
            ->with('success', 'Service berhasil dibuat!');
    }

    public function edit(Service $service): View
    {
        // Only allow owner to edit
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        return view('services.edit', [
            'service' => $service,
            'categories' => SkillCategory::active()->root()->with('children')->get(),
        ]);
    }

    public function update(Request $request, Service $service)
    {
        // Only allow owner to update
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'category_id' => 'nullable|exists:skill_categories,id',
            'price' => 'required|numeric|min:10000|max:500000',
            'session_duration' => 'required|in:30,45,60,90',
            'session_method' => 'required|in:video,voice',
            'status' => 'required|in:active,paused',
        ]);

        $service->update($validated);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service berhasil diupdate!');
    }

    public function destroy(Service $service)
    {
        // Only allow owner to delete
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service berhasil dihapus!');
    }

    public function show(Service $service): View
    {
        return view('services.show', [
            'service' => $service->load('user.profile', 'category'),
        ]);
    }

    public function browse(Request $request): View
    {
        $query = Service::active()->with('user.profile', 'category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $services = $query->orderBy('total_orders', 'desc')->paginate(24);
        $categories = SkillCategory::active()->root()->with('children')->get();

        return view('services.browse', [
            'services' => $services,
            'categories' => $categories,
        ]);
    }
}