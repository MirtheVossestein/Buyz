@extends('layouts.app')

@section('content')
    <h1>Voer je 2FA-code in</h1>

    @if ($errors->any())
        <div style="color: red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.store') }}">
    @csrf
    <input type="text" name="code" required>
    <button type="submit">Verifiëren</button>
</form>
@endsection
