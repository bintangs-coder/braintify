<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', fn() => view('pages.home'))->name('home');
Route::get('/how-it-works', fn() => view('pages.how-it-works'))->name('how-it-works');

// Authentication
require __DIR__ . '/auth.php';

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/skills', [ProfileController::class, 'addSkill'])->name('profile.skills.add');
    Route::delete('/profile/skills/{id}', [ProfileController::class, 'removeSkill'])->name('profile.skills.remove');

    // Skills
    Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
    Route::get('/skills/{skill}', [SkillController::class, 'show'])->name('skills.show');

    // Exchange
    Route::get('/exchange', [ExchangeController::class, 'index'])->name('exchange.index');
    Route::get('/exchange/my', [ExchangeController::class, 'myExchanges'])->name('exchange.my');
    Route::get('/exchange/create', [ExchangeController::class, 'create'])->name('exchange.create');
    Route::post('/exchange', [ExchangeController::class, 'store'])->name('exchange.store');
    Route::post('/exchange/{exchange}/propose', [ExchangeController::class, 'propose'])->name('exchange.propose');
    Route::put('/exchange/{exchange}/accept', [ExchangeController::class, 'accept'])->name('exchange.accept');
    Route::put('/exchange/{exchange}/decline', [ExchangeController::class, 'decline'])->name('exchange.decline');
    Route::put('/exchange/{exchange}/complete', [ExchangeController::class, 'complete'])->name('exchange.complete');
    Route::put('/exchange/{exchange}/rate', [ExchangeController::class, 'rate'])->name('exchange.rate');
    Route::delete('/exchange/{exchange}/cancel', [ExchangeController::class, 'cancel'])->name('exchange.cancel');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Conversations (Chat)
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/exchange/{exchange}', [ConversationController::class, 'store'])->name('conversations.store');
    Route::post('/conversations/user/{user}', [ConversationController::class, 'startWithUser'])->name('conversations.start');
    Route::post('/conversations/{conversation}/send', [ConversationController::class, 'sendMessage'])->name('conversations.send');
    Route::get('/conversations/{conversation}/messages', [ConversationController::class, 'getMessages'])->name('conversations.messages');

    // Booking
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{user}', [BookingController::class, 'showMentor'])->name('booking.mentor-profile');
    Route::post('/booking/{user}', [BookingController::class, 'book'])->name('booking.store');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.admin.index'))->name('admin.dashboard');
    });
});