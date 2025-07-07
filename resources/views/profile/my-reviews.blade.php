@extends('layouts.app')

@section('content')
    <div class="max-w-3xl">
        
        <h1 class="text-2xl font-bold mb-6">Mijn Reviews</h1>

        <h2 class="text-xl font-semibold mb-3">Reviews die ik heb geplaatst</h2>
        @forelse ($writtenReviews as $review)
            <div class="border border-gray-300 p-4 rounded-lg mb-4 shadow-sm">
                <p class="text-sm text-gray-500">Geplaatst op {{ $review->created_at->format('d-m-Y') }}</p>
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
            <p class="mb-6 text-gray-500">Je hebt nog geen reviews geplaatst.</p>
        @endforelse

        <h2 class="text-xl font-semibold mb-3 mt-8">Reviews die ik heb ontvangen</h2>
        @forelse ($receivedReviews as $review)
            <div class="border border-gray-300 p-4 rounded-lg mb-4 shadow-sm bg-yellow-50">
                <p class="text-sm text-gray-500">Geplaatst op {{ $review->created_at->format('d-m-Y') }}</p>
                <p><strong>Door:</strong> {{ $review->reviewer->first_name }} {{ $review->reviewer->last_name }}</p>
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
            <p class="text-gray-500">Je hebt nog geen reviews ontvangen.</p>
        @endforelse
    </div>
@endsection
