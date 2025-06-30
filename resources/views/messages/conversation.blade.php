@extends('layouts.app')

@section('title', 'Gesprek')

@section('content')
    <h1>Gesprek met {{ $partner->first_name }} {{ $partner->last_name }}</h1>
<h2>Advertentie: 
    <a href="{{ route('ads.show', $advertentie->id) }}" class="">
        {{ $advertentie->title }}
    </a>
</h2>
@if(auth()->id() === $advertentie->user_id)
    <form method="POST" action="{{ route('ads.updateStatus', $advertentie->id) }}" class="">
        @csrf
        @method('PATCH')
        <select name="status" onchange="this.form.submit()" class="border p-1 rounded">
            <option value="te_koop" {{ $advertentie->status === 'te_koop' ? 'selected' : '' }}>Te koop</option>
            <option value="verkocht" {{ $advertentie->status === 'verkocht' ? 'selected' : '' }}>Verkocht</option>
            <option value="gereserveerd" {{ $advertentie->status === 'gereserveerd' ? 'selected' : '' }}>Gereserveerd</option>
        </select>
    </form>
@endif




    @foreach ($conversation->messages as $message)
        <div class="{{ $message->sender_id === auth()->id() ? 'text-end' : 'text-start' }} group relative">
            <p>
                <strong>
                    {{ $message->sender_id === auth()->id() ? 'Jij' : $message->sender->first_name . ' ' . $message->sender->last_name }}
                </strong>: {{ $message->content }}
            </p>
            <small>{{ $message->created_at->diffForHumans() }}</small>

            @if ($message->sender_id === auth()->id())
                <form method="POST" action="{{ route('messages.destroy', $message->id) }}"
                    onsubmit="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?')"
                    class="absolute top-0 right-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 opacity-50 mt-3 text-sm">Verwijderen</button>
                </form>
            @endif
        </div>
    @endforeach

    <form method="POST" action="{{ route('conversations.messages.store', $conversation->id) }}">
        @csrf
        <textarea name="content" rows="3" required class="w-full border p-2 mt-4" placeholder="Typ een bericht..."></textarea>
        <button type="submit" class="btn btn-primary mt-2">Verstuur</button>
    </form>
@endsection
