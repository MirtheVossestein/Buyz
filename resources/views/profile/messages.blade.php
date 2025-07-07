@extends('layouts.app')

@section('title', 'Berichten Overzicht')

@section('content')
<h1 class="text-2xl font-bold mb-6">Mijn Gesprekken </h1>

@if ($conversations->isEmpty())
    <p class="text-gray-600">Je hebt nog geen gesprekken.</p>
@else
    <ul class="space-y-4">
        @foreach ($conversations as $conversation)
            @php
                $partner = $conversation->user_one_id === auth()->id() ? $conversation->userTwo : $conversation->userOne;
                $lastMessage = $conversation->messages->last();
                $ad = $conversation->ad; 
            @endphp

            <li>
                <a href="{{ route('messages.show', $conversation->id) }}"
                   class="block p-4 bg-white rounded-lg shadow hover:bg-gray-50 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-2xl text-black">
                                {{ $partner->first_name }} {{ $partner->last_name }}
                            </br>
                            <span class="text-xl text-gray-500">{{ $partner->email }} </span> </p>
                        </div>
                        <small class="text-gray-400">
                            {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : 'Geen berichten' }}
                        </small>
                    </div>

                    @if ($ad)
                        <p class=" text-lg text-black">
                            <strong> Advertentie:</strong> {{ $ad->title }} <br>
                            <strong> Status: </strong> {{ ucfirst(str_replace('_', ' ', $ad->status)) }}
                        </p>
                    @endif

                    @if ($lastMessage)
                        <p class="text-lg  text-black"> Meest recente bericht: <span class="text-gray-700 text-base italic">
                            {{ Str::limit($lastMessage->content, 80) }} <span>
                        </p>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
@endif
@endsection
