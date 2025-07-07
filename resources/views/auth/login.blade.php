<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body>
    <nav class="w-full h-[5rem] bg-[#00a9a3] flex items-center justify-between px-4">
        <div class="flex items-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('/images/logo1.svg') }}" alt="Logo" class="h-[5rem] w-[5rem] object-contain" />
            </a>
        </div>
    </nav>

    <div class="max-w-lg mx-auto p-4 bg-blue rounded shadow-md mt-20">
        <h1 class="text-2xl text-center font-bold mb-5">Log hier in.</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="flex flex-col mb-4">
                <label for="email" class="font-bold mb-1">E-mail:</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    class="p-2 border border-gray-300 rounded"
                    value="{{ old('email') }}"
                />
                @error('email')
                    <span class="text-[#df5a53] text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col mb-4">
                <label for="password" class="font-bold mb-1">Wachtwoord:</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="p-2 border border-gray-300 rounded"
                />
                @error('password')
                    <span class="text-[#df5a53] text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col">
                <button
                    type="submit"
                    class="p-2  text-white rounded bg-[#F5BA36] hover:bg-[#F5B21D] "
                >
                    Login
                </button>

                <div class="mt-8 text-center">
                    <a href="{{ route('register.show') }}" class="text-[#f5ba36] hover:text-[#00a9a3]">
                        Nog geen account? Registreer hier.
                    </a>
                </div>
            </div>

            @error('login')
                <span class="text-[#df5a53] text-sm">{{ $message }}</span>
            @enderror
        </form>
    </div>
</body>
</html>
