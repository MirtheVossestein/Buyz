<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</head>
@if (!request()->cookie('cookies_accepted'))
    <div id="cookie-banner" class="fixed bottom-0 left-0 right-0 bg-gray-800 text-white p-4 z-50">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
            <span class="text-sm sm:text-base">
                We gebruiken cookies om je ervaring te verbeteren. Door verder te gaan accepteer je ons cookiebeleid.
            </span>

            <div class="flex items-center gap-3">
                <a href="{{ route('cookie.info') }}" class="text-blue-300 hover:underline text-sm">
                    Meer informatie
                </a>

                <form method="POST" action="{{ route('cookie.accept') }}">
                    @csrf
                    <button type="submit" class="bg-[#00A9A3] hover:bg-[#019A95] text-white px-4 py-2 rounded">
                        Akkoord
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<body>
    @php
        $isHome = Route::currentRouteName() === 'home';
    @endphp

    <nav
        class="w-full h-[6rem] flex items-center justify-between px-4 {{ $isHome ? 'absolute' : 'relative' }} top-0 left-0 z-50 bg-transparent">
        <div class="logo-container relative">
            <a href="{{ route('home') }}">
                <img src="{{ asset('/images/buyz-logo4.svg') }}" alt="Logo"
                    class="h-[8rem] w-[17rem] object-contain ">
            </a>
        </div>

        <div class="flex items-center space-x-4 text-[15px] font-bold  {{ $isHome ? 'text-white' : 'text-[#00A9A3]' }}">
            @if (Auth::check() && auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="text-[#00A9A3] px-4">ADMIN DASHBOARD</a>
            @endif

            <a href="{{ route('ads.index') }}" class=" px-4 ">ADVERTENTIES</a>
            <a href="{{ route('contact') }}" class=" px-4">CONTACT</a>

            @if (Auth::check())
                <div class="relative">
                    <button id="dropdownButton" class=" px-4">PROFIEL ▼</button>
                    <div id="dropdownMenu"
                        class="hidden  divide-y divide-gray-100 rounded-lg shadow w-44 absolute right-0 top-10">
                        <ul class="py-2 text-sm 'text-white' : 'text-[#F5BA36]' }}">
                            <li><a href="{{ route('profile.show') }}" class="block py-2 ">
                                    Mijn profiel</a></li>
                            <li><a href="{{ route('profile.ads') }}" class="block py-2 ">
                                    Mijn advertenties</a></li>
                            <li><a href="{{ route('profile.messages') }}" class="block py-2 ">Mijn
                                    berichten</a></li>
                            <li><a href="{{ route('profile.reviews.index') }}" class="block py-2 ">Mijn
                                    reviews</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block py-2  w-full text-left">Log
                                        uit</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm  }} px-4">LOGIN</a>
            @endif
        </div>
    </nav>

    @if (Route::currentRouteName() !== 'home')
        <div class="container mx-auto mt-4">
            @yield('content')
        </div>
    @else
        @yield('content')
    @endif

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


    @stack('scripts')


</body>


</html>
