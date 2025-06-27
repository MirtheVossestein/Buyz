<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Register</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <nav class="w-full h-[5rem] bg-[#00a9a3] flex items-center justify-between px-4">
        <div class="flex items-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('/images/logo1.svg') }}" alt="Logo" class="h-[5rem] w-[5rem] object-contain" />
            </a>
        </div>
    </nav>

    <div class="max-w-lg mx-auto p-6 bg-white rounded shadow-md mt-20">
        <h2 class="text-2xl text-center font-bold mb-8">Registreer hier.</h2>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf


            <div class="flex flex-col mb-4">
                <label for="first_name" class="font-bold mb-1">Voornaam:</label>
                <input type="text" id="first_name" name="first_name" required
                    class="p-2 border border-gray-300 rounded" value="{{ old('first_name') }}" />
            </div>

            <div class="flex flex-col mb-4">
                <label for="last_name" class="font-bold mb-1">Achternaam:</label>
                <input type="text" id="last_name" name="last_name" required
                    class="p-2 border border-gray-300 rounded" value="{{ old('last_name') }}" />
            </div>

            <div class="flex flex-col mb-4">
                <label for="email" class="font-bold mb-1">E-mail:</label>
                <input type="email" id="email" name="email" required class="p-2 border border-gray-300 rounded"
                    value="{{ old('email') }}" />
            </div>

            <div class="flex flex-col mb-4">
                <label for="phone" class="font-bold mb-1">Telefoonnummer:</label>
                <input type="tel" id="phone" name="phone" required minlength="8"
                    class="p-2 border border-gray-300 rounded" value="{{ old('phone') }}" />
            </div>

            <div class="flex flex-col mb-4">
                <label for="birthdate" class="font-bold mb-1">Geboortedatum:</label>
                <input type="date" id="birthdate" name="birthdate" required
                    class="p-2 border border-gray-300 rounded" value="{{ old('birthdate') }}" />
            </div>

            <div class="flex flex-col mb-4">
                <label for="zipcode" class="font-bold mb-1">Postcode:</label>
                <input type="text" id="zipcode" name="zipcode" required class="p-2 border border-gray-300 rounded"
                    value="{{ old('zipcode') }}" />
            </div>

            <div class="flex flex-col mb-4">
                <label for="city" class="font-bold mb-1">Stad:</label>
                <input type="text" id="city" name="city" required class="p-2 border border-gray-300 rounded"
                    value="{{ old('city') }}" />
            </div>

            <div class="flex flex-col mb-4">
                <label for="password" class="font-bold mb-1">Wachtwoord:</label>
                <input type="password" id="password" name="password" required
                    class="p-2 border border-gray-300 rounded" />
            </div>

            <div class="flex flex-col mb-6">
                <label for="password_confirmation" class="font-bold mb-1">Bevestig wachtwoord:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="p-2 border border-gray-300 rounded" />
            </div>

            <button type="submit"
                class="p-3 hover:bg-[#f5ba36] text-white  bg-[#00a9a3] rounded transition-all cursor-pointer  w-full font-semibold">
                Registreer
            </button>

            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-[#f5ba36]   hover:text-[#00a9a3]">Heb je al een account? Log
                    hier
                    in.</a>
            </div>

            @error('register_error')
                <span class="text-[#df5a53] block mt-4 font-semibold">{{ $message }}</span>
            @enderror
            @if ($errors->any())
                <div class="text-[#df5a53]">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
</body>

</html>
