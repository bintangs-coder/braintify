<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Exchange;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all conversations where user is either user1 or user2
        $conversations = Conversation::with(['user1', 'user2', 'messages' => function ($q) {
            $q->orderBy('created_at', 'desc')->limit(1);
        }])
            ->where(function ($q) use ($user) {
                $q->where('user1_id', $user->id)->orWhere('user2_id', $user->id);
            })
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        // Check if user is part of this conversation
        if ($conversation->user1_id !== $user->id && $conversation->user2_id !== $user->id) {
            abort(403, 'You are not part of this conversation.');
        }

        // Load relationships
        $conversation->load(['user1', 'user2']);
        $otherUser = $conversation->getOtherParticipant($user);

        // Mark all messages from the other user as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = $conversation->messages()->with('sender')->get();

        return view('conversations.show', compact('conversation', 'messages', 'otherUser'));
    }

    /**
     * Start or get a conversation with another user
     */
    public function startWithUser(User $user)
    {
        $currentUser = Auth::user();

        // Can't chat with yourself
        if ($currentUser->id === $user->id) {
            return redirect()->back()->with('error', 'You cannot start a conversation with yourself.');
        }

        // Find existing conversation or create new one
        $conversation = Conversation::findByParticipants($currentUser->id, $user->id);

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $currentUser->id,
                'user2_id' => $user->id,
            ]);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    /**
     * Start conversation from an exchange (for backward compatibility)
     */
    public function store(Exchange $exchange)
    {
        $user = Auth::user();

        // Check if user is part of this exchange
        if ($exchange->requester_id !== $user->id && $exchange->provider_id !== $user->id) {
            abort(403, 'You are not part of this exchange.');
        }

        // Check if exchange has a provider (proposal exists)
        if (!$exchange->provider_id) {
            return redirect()->back()->with('error', 'No proposal to chat about.');
        }

        // Get the other user
        $otherUser = $exchange->requester_id === $user->id
            ? $exchange->provider
            : $exchange->requester;

        // Find or create conversation by user pair
        $conversation = Conversation::findByParticipants($user->id, $otherUser->id);

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $user->id,
                'user2_id' => $otherUser->id,
            ]);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        // Check if user is part of this conversation
        if ($conversation->user1_id !== $user->id && $conversation->user2_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'content' => $request->content,
        ]);

        // Update last_message_at
        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'sender_id' => $message->sender_id,
                'sender_name' => $user->name,
                'sender_avatar' => $user->avatar_url,
                'created_at' => $message->created_at->toIso8601String(),
                'is_mine' => true,
            ],
        ]);
    }

    public function getMessages(Conversation $conversation)
    {
        $user = Auth::user();

        // Check if user is part of this conversation
        if ($conversation->user1_id !== $user->id && $conversation->user2_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $after = request('after', '1970-01-01');

        $messages = $conversation->messages()
            ->with('sender')
            ->where('created_at', '>', $after)
            ->get()
            ->map(function ($msg) use ($user) {
                return [
                    'id' => $msg->id,
                    'content' => $msg->content,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->name,
                    'sender_avatar' => $msg->sender->avatar_url,
                    'created_at' => $msg->created_at->toIso8601String(),
                    'is_mine' => $msg->sender_id === $user->id,
                ];
            });

        return response()->json(['messages' => $messages]);
    }
}