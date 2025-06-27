<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|min:8',
            'birthdate' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            ],
            'zipcode' => 'required|string',
            'city' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ], [
            'password.min' => 'Het wachtwoord moet minimaal 8 tekens bevatten.',
            'password.confirmed' => 'De wachtwoorden komen niet overeen.',
            'birthdate.before_or_equal' => 'Je moet minstens 16 jaar oud zijn.',
            'birthdate.after_or_equal' => 'Je mag niet ouder zijn dan 100 jaar.',
            'birthdate.required' => 'Geboortedatum is verplicht.',
            'birthdate.date' => 'Voer een geldige geboortedatum in.',
            'email.unique' => 'Dit e-mailadres is al in gebruik.',
        ]);
        User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'birthdate' => $data['birthdate'],
            'zipcode' => $data['zipcode'],
            'city' => $data['city'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('login')->with('success', 'Account aangemaakt, log in!');
    }
}
