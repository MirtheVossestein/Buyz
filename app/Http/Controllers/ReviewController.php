<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|exists:ads,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:50',
        ]);

        $ad = Ad::findOrFail($request->ad_id);

        if (
            !in_array($ad->status, ['verkocht', 'gereserveerd']) ||
            $ad->buyer_id !== Auth::id()
        ) {
            return back()->with('error', 'Je mag deze advertentie niet reviewen.');
        }

        $existingReview = Review::where('ad_id', $ad->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Je hebt deze advertentie al beoordeeld.');
        }

        Review::create([
            'ad_id' => $ad->id,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $ad->user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $conversation = \App\Models\Conversation::where('ad_id', $ad->id)
            ->where(function ($query) use ($ad) {
                $query->where('user_one_id', $ad->user_id)
                    ->where('user_two_id', $ad->buyer_id);
            })->orWhere(function ($query) use ($ad) {
                $query->where('user_one_id', $ad->buyer_id)
                    ->where('user_two_id', $ad->user_id);
            })->first();


        if ($conversation) {
            \App\Models\Message::where('conversation_id', $conversation->id)
                ->where('content', '[review_invite]')
                ->delete();
        }


        return back()->with('success', 'Bedankt voor je review!');
    }

    public function index()
    {
        $reviews = Review::where('reviewer_id', auth()->id())
            ->with(['ad', 'reviewee']) 
            ->latest()
            ->get();

        return view('profile.my-reviews', compact('reviews'));
    }




}
