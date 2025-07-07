<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ad;


class HomeController extends Controller
{
   public function index()
{
    $spellenAds = Ad::whereHas('category', function($q) {
        $q->where('name', 'Spellen');
    })->latest()->take(10)->get();

    $elektronicaAds = Ad::whereHas('category', function($q) {
        $q->where('name', 'Elektronica');
    })->latest()->take(10)->get();

    return view('welcome', compact('spellenAds', 'elektronicaAds'));
}
}
