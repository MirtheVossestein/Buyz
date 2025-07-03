@extends('layouts.app')

@section('title', 'Advertenties')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Advertenties</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <aside class="p-4 border rounded shadow bg-white">
            <h2 class="text-xl font-semibold mb-4">Filters</h2>

            <form method="GET" action="{{ route('ads.index') }}" class="mb-6">
                <input type="text" name="search" placeholder="Zoeken op naam of prijs" value="{{ request('search') }}"
                    class="mb-2 w-full rounded border px-3 py-1" />

                <select name="category" class="mb-2 w-full rounded border px-3 py-1">
                    <option value="">Alle categorieën</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <input type="number" name="price_min" placeholder="Min prijs" value="{{ request('price_min') }}"
                    class="mb-2 w-full rounded border px-3 py-1" />
                <input type="number" name="price_max" placeholder="Max prijs" value="{{ request('price_max') }}"
                    class="mb-2 w-full rounded border px-3 py-1" />

                <select name="sort_price" class="mb-2 w-full rounded border px-3 py-1">
                    <option value="">Sorteer op prijs</option>
                    <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>Laag naar Hoog</option>
                    <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>Hoog naar Laag</option>
                </select>

                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700 transition">Filteren</button>
            </form>
        </aside>

        <section class="md:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($ads as $ad)
                <a href="{{ route('ads.show', $ad) }}" class="block border p-4 rounded shadow hover:shadow-lg transition"
                    style="text-decoration: none; color: inherit;">
                    @if ($ad->images->count())
                        <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}"
                            class="w-full h-40 object-cover rounded mt-2" alt="Foto">
                    @endif
                    <p class="text-[25px] mb-0 font-semibold truncate">{{ $ad->title }}</p>
                    <p class="text-sm font-medium mb-1">
                        Status: {{ ucfirst(str_replace('_', ' ', $ad->status)) }}
                    </p>
                    <p class="text-[16px] mb-0 truncate">{{ $ad->category->name ?? 'Onbekende categorie' }}</p>
                    <p class="text-[18px] text-gray-600 mt-2">€ {{ number_format($ad->price, 2, ',', '.') }}</p>
                </a>
            @empty
                <p>Geen advertenties gevonden.</p>
            @endforelse
        </section>

    </div>

    <div class="mt-8">
        {{ $ads->links() }}
    </div>
@endsection
