@extends('layouts.app')

@section('content')
    <div class="max-w-3xl ">
        <h1 class="text-2xl font-bold mb-6">Mijn reviews</h1>

        @forelse ($reviews as $review)
            <div class="border border-gray-300 p-4 rounded-lg mb-4 shadow-sm">
                <div class="mb-2">
                    <p class="text-sm text-gray-500">Geplaatst op {{ $review->created_at->format('d-m-Y') }}</p>
                </div>
                <p><strong>Over:</strong> {{ $review->reviewee->first_name }} {{ $review->reviewee->last_name }}</p>
                <p><strong>Advertentie:</strong>
                    @if ($review->ad)
                        <a href="{{ route('ads.show', $review->ad->id) }}" class="text-blue-600 hover:underline">
                            {{ $review->ad->title }}
                        </a>
                    @else
                        Verwijderd
                    @endif
                </p>
                <p><strong>Rating:</strong> {{ $review->rating }}/5</p>
                <p><strong>Commentaar:</strong> {{ $review->comment }}</p>
            </div>
        @empty
            <p>Je hebt nog geen reviews geplaatst.</p>
        @endforelse
    </div>
@endsection
