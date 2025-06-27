<?php

namespace App\Http\Controllers;

use App\Mail\AskQuestionMail;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdQuestionController extends Controller
{
    public function sendQuestion(Request $request, Ad $ad)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        Mail::to($ad->user->email)->send(
            new AskQuestionMail($request->question, auth()->user())
        );

        return back()->with('success', 'Je vraag is verstuurd naar de verkoper!');
    }
}
