<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Message;
use App\Models\Review;

use App\Models\Conversation;


class AdController extends Controller
{

    public function __construct()
{
    $this->middleware('auth')->except(['index', 'show']);
}
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
            'images' => 'required|array|min:1|max:5',
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
        $ads = Ad::with('buyer')
            ->where('user_id', auth()->id())
            ->get();
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
            'status' => 'required|in:te_koop,gereserveerd,verkocht',
            'images' => 'sometimes|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $ad->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'status' => $request->status,
        ]);

        if ($request->hasFile('images')) {
            $ad->images()->delete();

            foreach ($request->file('images') as $image) {
                $path = $image->store('ads', 'public');

                $ad->images()->create([
                    'image_path' => $path,
                ]);
            }
            $ad->save();
        }

        return redirect()->route('ads.my')->with('success', 'Advertentie succesvol aangepast!');
    }


    public function adminUpdate(Request $request, Ad $ad)
    {
        $this->authorize('update', $ad);

        $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:te_koop,gereserveerd,verkocht',
            'images' => 'sometimes|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $ad->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'status' => $request->status,
        ]);

        if ($request->hasFile('images')) {
            $ad->images()->delete();

            foreach ($request->file('images') as $image) {
                $path = $image->store('ads', 'public');

                $ad->images()->create([
                    'image_path' => $path,
                ]);
            }
            $ad->save();
        }

        return redirect()->route('ads.show', $ad)->with('success', 'Advertentie succesvol aangepast!');
    }


    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|exists:categories,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'sort_price' => 'nullable|in:asc,desc',
        ]);

        $query = Ad::with('buyer')->whereIn('status', ['te_koop', 'gereserveerd']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('price', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('sort_price') && in_array($request->sort_price, ['asc', 'desc'])) {
            $query->orderBy('price', $request->sort_price);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $ads = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('ads.ads', compact('ads', 'categories'));
    }



    public function show($id)
    {
        $ad = Ad::with(['images', 'category', 'user', 'buyer'])->findOrFail($id);
        $user = $ad->user;
        $categories = Category::all();


        $reviews = Review::with('reviewer')
            ->where('reviewee_id', $user->id)
            ->get();

        return view('ads.show', compact('ad', 'user', 'categories', 'reviews'));
    }

    public function buy($id)
    {
        $ad = Ad::with(['images', 'user', 'category'])->findOrFail($id);
        return view('ads.buy', compact('ad'));
    }




    public function updateStatus(Request $request, Ad $advertentie)
    {
        \Log::info('UpdateStatus called for ad', ['ad_id' => $advertentie->id ?? 'null']);

        $request->validate([
            'buyer_id' => 'nullable|exists:users,id',
            'status' => 'required|string',
        ]);

        $advertentie->buyer_id = $request->input('buyer_id');
        $advertentie->status = $request->input('status');
        $advertentie->save();

        \Log::info("Advertentie status updated", [
            'ad_id' => $advertentie->id,
            'buyer_id' => $advertentie->buyer_id,
            'status' => $advertentie->status,
        ]);

        if ($advertentie->status === 'verkocht' && $advertentie->buyer_id) {
            $conversation = Conversation::where(function ($query) use ($advertentie) {
                $query->where('user_one_id', $advertentie->user_id)
                    ->where('user_two_id', $advertentie->buyer_id);
            })->orWhere(function ($query) use ($advertentie) {
                $query->where('user_two_id', $advertentie->user_id)
                    ->where('user_one_id', $advertentie->buyer_id);
            })->first();

            if ($conversation) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $advertentie->user_id,
                    'receiver_id' => $advertentie->buyer_id,
                    'ad_id' => $advertentie->id,
                    'content' => 'De advertentie is verkocht aan u.',


                ]);
            }
        }

        return back()->with('success', 'Advertentie status bijgewerkt');
    }







}