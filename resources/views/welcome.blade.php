@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container mt-5">
        <div class="text-left mb-5">
            @if ($user)
                <h1 class="text-4xl font-bold">Welkom op Buyz, {{ $user->first_name }} {{ $user->last_name }} - {{ $user->email }}</h1>
            @else
                <h1 class="text-4xl font-bold">Welkom op Buyz!</h1>
            @endif
            <p class="text-lg mt-2 text-gray-600">Het platform voor nieuwe en tweedehands producten en koop eenvoudig online.</p>
        </div>
    </div>
@endsection
