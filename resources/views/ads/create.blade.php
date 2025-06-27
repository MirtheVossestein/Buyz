@extends('layouts.app')
@section('title', 'Nieuwe advertentie')

@section('content')
    <h1>Nieuwe advertentie plaatsen</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title">Titel</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description">Beschrijving</label>
            <textarea name="description" class="form-control" rows="4" required minlength="20" maxlength="1000"></textarea>
        </div>

        <div class="mb-3">
            <label for="price">Prijs (€)</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
        </div>

        <select name="category_id" required>
            <option value="">Kies een categorie</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <div class="mb-3">
            <label for="images">Upload foto's (min 1, max 6):</label>
            <input type="file" name="images[]" multiple accept="image/*" required>
        </div>

        <div class="mb-3">

            <button type="submit" class="btn btn-success mb-4">Advertentie toevoegen</button>
        </div>
    </form>
@endsection
