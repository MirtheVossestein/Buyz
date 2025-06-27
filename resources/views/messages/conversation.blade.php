@extends('layouts.app')

@section('title', 'Gesprek')

@section('content')
    <h1>Gesprek met {{ $partner->name }}</h1>

    @foreach($conversation->messages as $message)
        <div class="{{ $message->sender_id === auth()->id() ? 'text-end' : 'text-start' }}">
            <p><strong>{{ $message->sender->name }}:</strong> {{ $message->content }}</p>
            <small>{{ $message->created_at->diffForHumans() }}</small>
        </div>
    @endforeach

<form method="POST" action="{{ route('conversations.messages.store', $conversation->id) }}">
        @csrf
        <textarea name="content" rows="3" required class="w-full border p-2 mt-4" placeholder="Typ een bericht..."></textarea>
        <button type="submit" class="btn btn-primary mt-2">Verstuur</button>
    </form>
@endsection
