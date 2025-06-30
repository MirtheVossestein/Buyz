<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\Ad;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $conversations = Conversation::with('messages', 'userOne', 'userTwo')
            ->where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->get();

        return view('profile.messages', compact('conversations'));
    }

   public function show(Conversation $conversation)
{
    if (!in_array(auth()->id(), [$conversation->user_one_id, $conversation->user_two_id])) {
        abort(403);
    }

    $partner = $conversation->user_one_id === auth()->id() ? $conversation->userTwo : $conversation->userOne;

    $conversation->load('messages.sender');

    $advertentie = $conversation->ad;

    return view('messages.conversation', compact('conversation', 'partner', 'advertentie'));
}


    public function store(Request $request, Conversation $conversation)
    {
        $userId = auth()->id();

        if (!in_array($userId, [$conversation->user_one_id, $conversation->user_two_id])) {
            abort(403, 'Je bent geen deelnemer van dit gesprek.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $receiverId = $conversation->user_one_id === $userId ? $conversation->user_two_id : $conversation->user_one_id;

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'ad_id' => $conversation->ad_id,
            'content' => $request->content,
        ]);

        return redirect()->route('messages.show', $conversation->id)->with('success', 'Bericht verstuurd!');
    }


    public function storeFromAd(Request $request, Ad $ad)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $userId = auth()->id();
        $adOwnerId = $ad->user_id;

        if ($userId === $adOwnerId) {
            abort(403, 'Je kunt geen bericht aan jezelf sturen.');
        }

        $conversation = Conversation::where('ad_id', $ad->id)
            ->where(function ($query) use ($userId, $adOwnerId) {
                $query->where(function ($q) use ($userId, $adOwnerId) {
                    $q->where('user_one_id', $userId)->where('user_two_id', $adOwnerId);
                })->orWhere(function ($q) use ($userId, $adOwnerId) {
                    $q->where('user_one_id', $adOwnerId)->where('user_two_id', $userId);
                });
            })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $userId,
                'user_two_id' => $adOwnerId,
                'ad_id' => $ad->id,
            ]);
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'receiver_id' => $adOwnerId,
            'ad_id' => $ad->id,
            'content' => $request->content,
        ]);

        return redirect()->route('messages.show', $conversation->id)->with('success', 'Bericht verstuurd!');
    }
    public function destroy(Message $message)
    {
        if ($message->sender_id !== auth()->id()) {
            abort(403);
        }

        $message->delete();

        return back()->with('success', 'Bericht verwijderd.');
    }




}