@extends('layouts.app')

@section('title', $ad->title)

@section('content')
    @if ($ad->status === 'verkocht')
        <p class="bg-red-200 text-red-800 p-3 rounded mb-4 font-semibold">
            Deze advertentie is al verkocht.
        </p>
    @elseif($ad->status === 'gereserveerd')
        <p class="bg-yellow-200 text-yellow-800 p-3 rounded mb-4 font-semibold">
            Deze advertentie is gereserveerd.
        </p>
    @endif
    {{-- Admin panel  --}}
    <div class="flex flex-col md:flex-row gap-8">
        <div class="w-3/4 bg-gray-100 p-3 rounded-xl shadow-md">
            @if (auth()->user()?->is_admin)
                <div class="mb-4 flex gap-4">
                    <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        data-bs-toggle="modal" data-bs-target="#editModal-{{ $ad->id }}">
                        Wijzigen
                    </button>

                    <form action="{{ route('ads.destroy', $ad) }}" method="POST"
                        onsubmit="return confirm('Weet je zeker dat je deze advertentie wilt verwijderen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Verwijderen</button>
                    </form>
                </div>

                <div class="modal fade" id="editModal-{{ $ad->id }}" tabindex="-1"
                    aria-labelledby="editModalLabel-{{ $ad->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.ads.update', $ad) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel-{{ $ad->id }}">
                                        Advertentie bewerken: {{ $ad->title }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Sluiten"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="title-{{ $ad->id }}">Titel</label>
                                        <input type="text" name="title" id="title-{{ $ad->id }}"
                                            class="form-control" value="{{ old('title', $ad->title) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description-{{ $ad->id }}">Beschrijving</label>
                                        <textarea name="description" id="description-{{ $ad->id }}" class="form-control" rows="4" required>{{ old('description', $ad->description) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="price-{{ $ad->id }}">Prijs (€)</label>
                                        <input type="number" name="price" id="price-{{ $ad->id }}" step="0.01"
                                            class="form-control" value="{{ old('price', $ad->price) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id-{{ $ad->id }}">Categorie</label>
                                        <select name="category_id" id="category_id-{{ $ad->id }}" class="form-select"
                                            required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $ad->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status-{{ $ad->id }}">Status</label>
                                        <select name="status" id="status-{{ $ad->id }}" class="form-select"
                                            required>
                                            <option value="te_koop"
                                                {{ old('status', $ad->status) == 'te_koop' ? 'selected' : '' }}>Te koop
                                            </option>
                                            <option value="gereserveerd"
                                                {{ old('status', $ad->status) == 'gereserveerd' ? 'selected' : '' }}>
                                                Gereserveerd</option>
                                            <option value="verkocht"
                                                {{ old('status', $ad->status) == 'verkocht' ? 'selected' : '' }}>Verkocht
                                            </option>
                                        </select>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Annuleren</button>
                                    <button type="submit" class="btn btn-primary">Opslaan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif


            <h1 class="text-3xl font-bold mb-4">{{ $ad->title }}</h1>

            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-2/3">
                    <div id="adCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner rounded overflow-hidden shadow-lg">
                            @foreach ($ad->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        class="d-block w-full h-96 object-cover"
                                        alt="Advertentie foto {{ $index + 1 }}">
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

                    <p class="text-[21px] mb-2 text-gray-600">Categorie -
                        {{ $ad->category->name ?? 'Onbekende categorie' }}
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
                <p class=""> <a href="mailto:{{ $user->email }}" class="text-blue-600 ">{{ $user->email }}</a>
                </p>
                <p class=""> {{ $user->phone }}</p>

                {{-- Kaartje locatie verkoper --}}

                <div class="mt-6">
                    <p class="text-2xl font-semibold mb-4">Reviews van klanten over deze verkoper</p>

                    @if ($reviews->isEmpty())
                        <p>Er zijn nog geen reviews voor deze verkoper.</p>
                    @else
                        <div id="reviewCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($reviews as $index => $review)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="bg-white p-4 rounded shadow text-sm h-40 overflow-auto">
                                            <p class="mb-2">
                                                <strong>{{ $review->reviewer->first_name ?? 'Onbekend' }}
                                                    {{ $review->reviewer->last_name ?? '' }}</strong>
                                                gaf <strong>{{ $review->rating }}</strong> sterren
                                            </p>
                                            <p class="text-gray-700">"{{ $review->comment }}"</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($reviews->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#reviewCarousel"
                                    data-bs-slide="prev">
                                    <span aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            fill="#000000" viewBox="0 0 24 24">
                                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                                        </svg>
                                    </span>
                                    <span class="visually-hidden">Vorige</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#reviewCarousel"
                                    data-bs-slide="next">
                                    <span aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            fill="#000000" viewBox="0 0 24 24">
                                            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z" />
                                        </svg>
                                    </span>
                                    <span class="visually-hidden">Volgende</span>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="">
                <div class="">
                    @if ($ad->status !== 'verkocht')
                        <a href="{{ route('ads.buy', $ad->id) }}" class="btn btn-success w-full mb-4">Vraag</a>
                    @endif
                </div>
            </div>


        </div>


    </div>
    </div>
@endsection
