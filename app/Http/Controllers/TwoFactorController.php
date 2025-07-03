<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.2fa'); // Form waar je de code invult
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'numeric'],
        ]);

        $userId = session('2fa:user:id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['code' => 'Gebruiker niet gevonden.']);
        }

        // Check of code klopt én niet verlopen is
        if (
            $request->code == $user->two_factor_code &&
            now()->lessThan($user->two_factor_expires_at)
        ) {
            // Opschonen
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            Auth::login($user); // Log gebruiker definitief in
            session()->forget('2fa:user:id');

            return redirect()->intended(route('home'));
        }

        return redirect()->route('2fa.index')->withErrors(['code' => 'Ongeldige of verlopen code.']);
    }
}