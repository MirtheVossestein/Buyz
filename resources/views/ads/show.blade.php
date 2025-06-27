@extends('layouts.app')

@section('title', $ad->title)

@section('content')
    <div class="flex flex-col md:flex-row gap-8">
        <div class="w-3/4 bg-gray-100 p-3 rounded-xl shadow-md">
            <h1 class="text-3xl font-bold mb-4">{{ $ad->title }}</h1>

            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-2/3">
                    <div id="adCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner rounded overflow-hidden shadow-lg">
                            @foreach ($ad->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        class="d-block w-full h-96 object-cover" alt="Advertentie foto {{ $index + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        @if ($ad->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#adCarousel"
                                data-bs-slide="prev">
                                <span aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#000000"
                                        viewBox="0 0 24 24">
                                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                                    </svg>
                                </span>
                                <span class="visually-hidden">Vorige</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#adCarousel"
                                data-bs-slide="next">
                                <span aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#000000"
                                        viewBox="0 0 24 24">
                                        <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z" />
                                    </svg>
                                </span>
                                <span class="visually-hidden">Volgende</span>
                            </button>
                        @endif
                    </div>

                    <p class="text-[21px] mb-2 text-gray-600">Categorie - {{ $ad->category->name ?? 'Onbekende categorie' }}
                    </p>
                    <p class="mb-6 ">{{ $ad->description }}</p>
                </div>

                <div class="w-1/3 ">
                    <p class="text-2xl font-semibold">€ {{ number_format($ad->price, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="w-1/4 flex flex-col bg-gray-100 p-3 rounded-xl shadow-md">
            <div class="">
                <p class="text-3xl font-semibold mb-4">{{ $user->first_name }} {{ $user->last_name }}</p>
                <p class=""> {{ $user->city }}</p>
                <p class=""> <a href="mailto:{{ $user->email }}" class="text-blue-600 ">{{ $user->email }}</a></p>
                <p class=""> {{ $user->phone }}</p>
            </div>
            <div class="">
                <a href="{{ route('ads.buy', $ad->id) }}" class="btn btn-success w-full mb-4">Vraag</a>

            </div>


        </div>


    </div>
    </div>
@endsection
