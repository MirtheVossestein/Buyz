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

    // Alle conversaties waar gebruiker bij betrokken is ophalen
    $conversations = Conversation::with('messages', 'userOne', 'userTwo')
        ->where('user_one_id', $userId)
        ->orWhere('user_two_id', $userId)
        ->get();

    return view('profile.messages', compact('conversations'));
}

public function show(Conversation $conversation)
{
    // Check dat gebruiker in dit gesprek zit
    if (!in_array(auth()->id(), [$conversation->user_one_id, $conversation->user_two_id])) {
        abort(403);
    }

    $partner = $conversation->user_one_id === auth()->id() ? $conversation->userTwo : $conversation->userOne;

    // Lazy load berichten en users van berichten
    $conversation->load('messages.sender');

    return view('messages.conversation', compact('conversation', 'partner'));
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

    // Voorkom dat iemand aan zichzelf een bericht stuurt
    if ($userId === $adOwnerId) {
        abort(403, 'Je kunt geen bericht aan jezelf sturen.');
    }

    // Zoek bestaande conversatie tussen gebruiker en ad owner voor deze advertentie
    $conversation = Conversation::where('ad_id', $ad->id)
        ->where(function ($query) use ($userId, $adOwnerId) {
            $query->where(function ($q) use ($userId, $adOwnerId) {
                $q->where('user_one_id', $userId)->where('user_two_id', $adOwnerId);
            })->orWhere(function ($q) use ($userId, $adOwnerId) {
                $q->where('user_one_id', $adOwnerId)->where('user_two_id', $userId);
            });
        })->first();

    // Als geen conversatie bestaat, maak nieuwe aan
    if (!$conversation) {
        $conversation = Conversation::create([
            'user_one_id' => $userId,
            'user_two_id' => $adOwnerId,
            'ad_id' => $ad->id,
        ]);
    }

    // Maak het bericht aan
    Message::create([
        'conversation_id' => $conversation->id,
        'sender_id' => $userId,
        'receiver_id' => $adOwnerId,
        'ad_id' => $ad->id,
        'content' => $request->content,
    ]);

    return redirect()->route('messages.show', $conversation->id)->with('success', 'Bericht verstuurd!');
}

}