<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ProfileController extends Controller
{

   public function update(Request $request)

{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'zipcode' => 'nullable|string|max:10',
        'city' => 'nullable|string|max:255',
    ]);

    $user = Auth::user();
    $user->update($request->only(['first_name', 'last_name', 'email', 'phone', 'zipcode', 'city']));

    return back()->with('success', 'Je gegevens zijn bijgewerkt.');
}
}
