@extends('layouts.app')

@section('title', 'Advertenties')

@section('content')
    <h1 class="text-2xl font-bold mb">Advertenties</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($ads as $ad)
            <a href="{{ route('ads.show', $ad) }}" class="block border p-4 rounded shadow hover:shadow-lg transition"
                style="text-decoration: none; color: inherit;">
                @if ($ad->images->count())
                    <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}"
                        class="w-full h-40 object-cover rounded mt-2" alt="Foto">
                @endif
                <p class="text-[25px] mb-0 font-semibold truncate">{{ $ad->title }}</p>
                <p class="text-[16px] mb-0  truncate">{{ $ad->category->name ?? 'Onbekende categorie' }}</p>
                <p class="text-[18px] text-gray-600 mt-2">€ {{ number_format($ad->price, 2, ',', '.') }}</p>
            </a>
        @endforeach
    </div>
@endsection
