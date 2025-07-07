@extends('layouts.app')

@section('title', 'Mijn advertenties')

@section('content')
    @php
        $soldAds = $ads->where('status', 'verkocht');
        $activeAds = $ads->whereIn('status', ['te_koop', 'gereserveerd']);

    @endphp

    <h1 class="mb-4">Mijn advertenties</h1>

    <a href="{{ route('ads.create') }}" class="bg-[#00A9A3] text-white px-4 py-2 rounded w-full  hover:bg-[#019A95] transition mb-4">Nieuwe advertentie plaatsen</a>

    @if ($ads->isEmpty())
        <p>Je hebt nog geen advertenties geplaatst.</p>
    @else
        <h2 class="mt-5 mb-3">Advertenties - Actieve producten</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Advertentie</th>
                    <th>Status</th>
                    <th>Wijzigingen</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activeAds as $ad)
                    <tr>
                        <td>
                            <a href="{{ route('ads.show', $ad->id) }}">
                                {{ $ad->title }}
                            </a>
                        </td>
                        <td>{{ $ad->status }} </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $ad->id }}">
                                Wijzigen
                            </button>

                            <form action="{{ route('ads.destroy', $ad) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Weet je zeker dat je deze advertentie wilt verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Verwijderen</button>
                            </form>
                            <div class="modal fade" id="editModal-{{ $ad->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel-{{ $ad->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('ads.update', $ad) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel-{{ $ad->id }}">
                                                    Advertentie
                                                    bewerken: {{ $ad->title }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Sluiten"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="title-{{ $ad->id }}">Titel</label>
                                                    <input type="text" name="title" id="title-{{ $ad->id }}"
                                                        class="form-control" value="{{ old('title', $ad->title) }}"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="description-{{ $ad->id }}">Beschrijving</label>
                                                    <textarea name="description" id="description-{{ $ad->id }}" class="form-control" rows="4" required>{{ old('description', $ad->description) }}</textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="price-{{ $ad->id }}">Prijs (€)</label>
                                                    <input type="number" name="price" id="price-{{ $ad->id }}"
                                                        step="0.01" class="form-control"
                                                        value="{{ old('price', $ad->price) }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="category_id-{{ $ad->id }}">Categorie</label>
                                                    <select name="category_id" id="category_id-{{ $ad->id }}"
                                                        class="form-select" required>
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
                                                    <select name="status" id="status-{{ $ad->id }}"
                                                        class="form-select" required>
                                                        <option value="te_koop"
                                                            {{ old('status', $ad->status) === 'te_koop' ? 'selected' : '' }}>
                                                            Te koop</option>
                                                        <option value="gereserveerd"
                                                            {{ old('status', $ad->status) === 'gereserveerd' ? 'selected' : '' }}>
                                                            Gereserveerd</option>
                                                        <option value="verkocht"
                                                            {{ old('status', $ad->status) === 'verkocht' ? 'selected' : '' }}>
                                                            Verkocht</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="images-{{ $ad->id }}">Upload nieuwe foto's
                                                        (optioneel)
                                                    </label>
                                                    <input type="file" name="images[]" id="images-{{ $ad->id }}"
                                                        multiple accept="image/*" class="form-control">
                                                    <small>Laat leeg om geen foto's te wijzigen.</small>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Sluiten</button>
                                                <button type="submit" class="btn btn-primary">Opslaan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    @endif

    @if ($soldAds->isNotEmpty())

        <h2 class="mt-5 mb-3">Advertenties - Verkochte producten</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Advertentie</th>
                    <th>Prijs</th>
                    <th>Verkocht op</th>
                    <th>Verkocht aan</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($soldAds as $ad)
                    <tr>
                        <td> <a href="{{ route('ads.show', $ad->id) }}">
                                {{ $ad->title }}
                            </a> </td>
                        <td>€{{ number_format($ad->price, 2, ',', '.') }}</td>
                        <td>{{ $ad->updated_at->format('d-m-Y') }}</td>
                        @if ($ad->status === 'verkocht' && $ad->buyer)
                            <td>{{ $ad->buyer->first_name }} {{ $ad->buyer->last_name }}</td>
                        @else
                            <td>Onbekend</td>
                        @endif
                    </tr>
                @endforeach

            </tbody>
        </table>
    @endif

@endsection
