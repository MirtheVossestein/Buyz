<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\Ad;



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

        $adsPerMonth = Ad::selectRaw("strftime('%m', created_at) as month, COUNT(*) as total")
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $usersPerMonth = User::selectRaw("strftime('%m', created_at) as month, COUNT(*) as total")
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $adsData = [];
        $usersData = [];

        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT); // '01', '02', etc.
            $adsData[] = $adsPerMonth[$month] ?? 0;
            $usersData[] = $usersPerMonth[$month] ?? 0;
        }

        return view('admin.dashboard', [
            'admins' => $admins,
            'users' => $users,
            'reviews' => $reviews,
            'adsData' => $adsData,
            'usersData' => $usersData,
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
