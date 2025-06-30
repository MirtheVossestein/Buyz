<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdQuestionMail;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Conversation;

class AdController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('ads.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|array|min:1|max:6',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $ad = Ad::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'location' => auth()->user()->city,
            'status' => 'te_koop',
        ]);

        foreach ($request->file('images') as $image) {
            $path = $image->store('ads', 'public');
            $ad->images()->create([
                'image_path' => $path,
            ]);
        }

        return redirect()->route('profile.ads')->with('success', 'Advertentie succesvol geplaatst!');
    }
    public function myAds()
    {
        $ads = Ad::where('user_id', auth()->id())->get();
        $categories = Category::all();

        return view('profile.my-ads', compact('ads', 'categories'));
    }

    public function edit(Ad $ad)
    {
        $this->authorize('update', $ad);

        $categories = Category::all();

        return view('ads.edit', compact('ad', 'categories'));
    }

    public function destroy(Ad $ad)
    {
        $this->authorize('delete', $ad);

        $ad->delete();

        return redirect()->route('profile.ads')->with('success', 'Advertentie succesvol verwijderd!');
    }


    public function update(Request $request, Ad $ad)
    {
        $this->authorize('update', $ad);

        $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'sometimes|array|min:1|max:6',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $ad->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('images')) {
            $ad->images()->delete();

            foreach ($request->file('images') as $image) {
                $path = $image->store('ads', 'public');

                $ad->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('ads.my')->with('success', 'Advertentie succesvol aangepast!');
    }

    public function index()
    {
    $ads = Ad::where('status', '!=', 'verkocht')->get();
        return view('ads.ads', compact('ads'));
    }

    public function category()
{
    return $this->belongsTo(Category::class);
}



public function show($id)
{
    $ad = Ad::with(['images', 'category', 'user'])->findOrFail($id);
    $user = $ad->user;

    return view('ads.show', compact('ad', 'user'));
}

public function buy($id)
{
    $ad = Ad::with(['images', 'user', 'category'])->findOrFail($id);
    return view('ads.buy', compact('ad'));
}



public function sendQuestion(Request $request, $id)
{
    $request->validate([
        'question' => 'required|string|max:1000',
    ]);

    $ad = Ad::with('user')->findOrFail($id);
    $owner = $ad->user;

    $conversation = Conversation::firstOrCreate([
        'user_one_id' => min(auth()->id(), $owner->id),
        'user_two_id' => max(auth()->id(), $owner->id),
    ]);

    Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $owner->id,
        'content' => $request->question,
        'conversation_id' => $conversation->id,
        'ad_id' => $ad->id, 
    ]);

    Mail::to($owner->email)->send(new AdQuestionMail($ad, $request->question, auth()->user()));

    return redirect()->back()->with('success', 'Je vraag is succesvol verzonden!');
}

public function updateStatus(Request $request, Ad $ad)
{
    if ($ad->user_id !== auth()->id()) {
        abort(403, 'Je bent geen eigenaar van deze advertentie.');
    }

    $request->validate([
        'status' => 'required|in:te_koop,verkocht,gereserveerd',
    ]);

    $ad->status = $request->status;
    $ad->save();

    return redirect()->back()->with('success', 'Status advertentie is bijgewerkt.');
}

public function myPurchases()
{
    $user = auth()->user();

    $purchasedAds = Ad::where('owner_id', $user->id)
                      ->where('status', 'verkocht')
                      ->get();

    return view('profile.my-purchases', compact('purchasedAds'));
}

}