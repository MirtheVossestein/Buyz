@extends('layouts.app')

@section('content')
    <h1 class="mt-2">Voer je 2FA-code in</h1>
    <p class="">Deze heb je via je e-mail ontvangen. Zo houden we jouw account veilig! </br>
    Vul hem hieronder in om door te gaan.</p> 

    @if ($errors->any())
        <div style="color: red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.store') }}">
        @csrf
        <input type="text" name="code" required class="border rounded px-3 py-2  bg-white text-black shadow" /> <button
            type="submit"
            class="bg-[#00A9A3] text-white rounded py-2 px-4 hover:bg-[#019A95] transition mb-4">Verifiëren</button>
    </form>
@endsection
