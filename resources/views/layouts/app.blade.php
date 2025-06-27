<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset(path: 'favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <nav class="w-full h-[5rem] bg-[#00a9a3]  flex items-center justify-between px-4">
        <div class="flex items-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('/images/logo1.svg') }}" alt="Logo" class="h-[5rem] w-[5rem] object-contain">
            </a>
        </div>

        <div class="flex items-center space-x-4">
            @if (Auth::check() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="text-black px-4">Admin Dashboard</a>
            @endif

            <a href="{{ route('ads.index') }}" class="text-black px-4">Advertenties</a>
            <a href="{{ route('contact') }}" class="text-black px-4">Contact</a>


            @if (Auth::check())
                <div class="relative">
                    <button id="dropdownButton" class="text-black px-4">Profiel ▼</button>
                    <div id="dropdownMenu"
                        class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 absolute right-0 top-10">
                        <ul class="py-2 text-sm text-gray-700">
                            <li><a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-gray-100">Mijn
                                    profiel</a></li>
                            <li><a href="{{ route('profile.ads') }}" class="block px-4 py-2 hover:bg-gray-100">Mijn
                                    advertenties
                                </a></li>
                            <li><a href="{{ route('profile.purchases') }}"
                                    class="block px-4 py-2 hover:bg-gray-100">Mijn
                                    aankopen
                                </a></li>
                            <li> <a href="{{ route('profile.messages') }}" class="block px-4 py-2 hover:bg-gray-100">Berichten @if ($unreadCount > 0)
                                        <span class="text-red-600 font-bold">({{ $unreadCount }})</span>
                                    @endif
                                </a></li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block px-4 py-2 hover:bg-gray-100 w-full text-left">Log uit</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-black px-4">Inloggen</a>
            @endif
        </div>
    </nav>

    <div class="container mx-auto mt-4">
        @yield('content')
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dropdownButton = document.getElementById("dropdownButton");
            const dropdownMenu = document.getElementById("dropdownMenu");

            if (dropdownButton) {
                dropdownButton.addEventListener("click", function() {
                    dropdownMenu.classList.toggle("hidden");
                });

                document.addEventListener("click", function(event) {
                    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.add("hidden");
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>


</body>


</html>
