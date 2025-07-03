<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;



class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.dashboard', ['users' => $users]);
    }

    public function makeAdmin(User $user)
    {
        $user->is_admin = true;
        $user->save();

        return redirect()->back()->with('success', 'Gebruiker is nu admin.');
    }

    public function removeAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Je kunt jezelf niet verwijderen als admin.');
        }

        $user->is_admin = false;
        $user->save();

        return redirect()->back()->with('success', 'Admin-rechten zijn verwijderd.');
    }

    public function adminDashboard()
    {
        $admins = User::where('is_admin', 1)->get();
        $users = User::where('is_admin', 0)->get();

        $reviews = Review::with(['reviewer', 'reviewee'])->latest()->get();

        return view('admin.dashboard', [
            'admins' => $admins,
            'users' => $users,
            'reviews' => $reviews
        ]);
    }

    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Je hebt geen toegang tot deze pagina.');
        }

        $reviews = Review::with(['reviewer', 'reviewee', 'ad'])
            ->latest()
            ->paginate(20);

        return view('admin.dashboard', compact('reviews'));
    }

    public function adminDestroy(Review $review)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Je hebt geen toegang tot deze pagina.');
        }

        $review->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Review succesvol verwijderd.');
    }

    // idem voor adminEdit en adminUpdate
    public function adminEdit(Review $review)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Je hebt geen toegang tot deze pagina.');
        }

        return view('admin.dashboard', compact('review'));
    }

   public function adminUpdate(Request $request, Review $review)
{
    if (!Auth::user()->is_admin) {
        abort(403, 'Je hebt geen toegang tot deze pagina.');
    }

    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string',
    ]);

    $review->update($request->only('rating', 'comment'));

    return redirect()->route('admin.dashboard')->with('success', 'Review succesvol bijgewerkt.');
}

}
