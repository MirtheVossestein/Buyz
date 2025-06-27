@extends('layouts.app')

@section('title', 'Berichten Overzicht')

@section('content')
<h1>Mijn Conversaties</h1>

@if ($conversations->isEmpty())
    <p>Je hebt nog geen gesprekken.</p>
@else
    <ul>
        @foreach ($conversations as $conversation)
            @php
                $partner = $conversation->user_one_id === auth()->id() ? $conversation->userTwo : $conversation->userOne;
                $lastMessage = $conversation->messages->last();
            @endphp
            <li>
                <a href="{{ route('messages.show', $conversation->id) }}">
                    {{ $partner->first_name }} {{ $partner->last_name }} - {{ $partner->email }}
                </a> - 
                <small>{{ $lastMessage ? $lastMessage->created_at->diffForHumans() : 'Geen berichten' }}</small> <br>
                <em>{{ $lastMessage ? Str::limit($lastMessage->content, 50) : '' }}</em>
            </li>
        @endforeach
    </ul>
@endif
@endsection
