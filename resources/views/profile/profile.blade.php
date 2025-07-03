@extends('layouts.app')

@section('title', 'Profiel')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Profiel</h1>

    <div class="bg-white shadow-md rounded p-4 max-w-md">
        <p><strong>Naam:</strong> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        <p><strong>Telefoonnummer:</strong> {{ Auth::user()->phone }}</p>
        <p><strong>Geboortedatum:</strong> {{ Auth::user()->birthdate }}</p>
        <p><strong>Postcode:</strong> {{ Auth::user()->zipcode ?? 'Niet ingevuld' }}</p>
        <p><strong>Stad:</strong> {{ Auth::user()->city ?? 'Niet ingevuld' }}</p>
    </div>

    {{-- Wijzig-knop --}}
    <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" data-bs-toggle="modal" data-bs-target="#editProfileModal">
        Wijzig gegevens
    </button>

    {{-- Wijziggegevens-Modal --}}
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Profielgegevens wijzigen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Voornaam</label>
                            <input type="text" class="form-control" name="first_name" value="{{ Auth::user()->first_name }}">
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Achternaam</label>
                            <input type="text" class="form-control" name="last_name" value="{{ Auth::user()->last_name }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefoonnummer</label>
                            <input type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
                        </div>
                        <div class="mb-3">
                            <label for="zipcode" class="form-label">Postcode</label>
                            <input type="text" class="form-control" name="zipcode" value="{{ Auth::user()->zipcode }}">
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">Stad</label>
                            <input type="text" class="form-control" name="city" value="{{ Auth::user()->city }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleer</button>
                        <button type="submit" class="btn btn-primary">Opslaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
