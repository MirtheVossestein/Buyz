@extends('layouts.app')

@section('title', 'Mijn advertenties')

@section('content')
    <h1 class="mb-4">Mijn advertenties</h1>

    <a href="{{ route('ads.create') }}" class="btn btn-success mb-4">Nieuwe advertentie plaatsen</a>

    @if ($ads->isEmpty())
        <p>Je hebt nog geen advertenties geplaatst.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Advertenties</th>
                    <th class="text-end">Wijzigingen</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ads as $ad)
                    <tr>
                        <td>{{ $ad->title }}</td>
                        <td class="text-end">
                            <!-- Wijzigen knop -->
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $ad->id }}">
                                Wijzigen
                            </button>

                            <!-- Delete formulier -->
                            <form action="{{ route('ads.destroy', $ad) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Weet je zeker dat je deze advertentie wilt verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Verwijderen</button>
                            </form>
                             <!-- Edit modal -->
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
@endsection
