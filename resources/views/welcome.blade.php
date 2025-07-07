@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <style>
        .spellenSwiper,
        .elektronicaSwiper {
            --swiper-navigation-color: #F5BA36;
        }
    </style>

    <div class="w-full h-screen relative">
        <img src="{{ asset('images/homepage.jpg') }}" alt="Homepage Afbeelding" class="w-full h-full ">
        <div class="absolute inset-0 flex items-center justify-start px-8">
            <div class="bg-white text-black rounded-xl p-9  w-[450px] h-[300px]">
                <p class="text-[28px] leading-10 mb-4">Plaats, vind, deal. Zo simpel werkt Buyz.</p>
                <a href="/profile/ads"
                    class="bg-[#00A9A3] !text-[21px] text-white h-[52px] mb-2 rounded w-full flex items-center justify-center hover:bg-[#019A95] transition">
                    Begin met verkopen
                </a>
                <a href="/advertenties"
                    class="!text-[21px] !text-[#00A9A3] h-[52px] rounded w-full flex items-center justify-center hover:bg-gray-50  transition">
                    Zoek je eerste aankoop
                </a>
            </div>
        </div>
    </div>

    <section class="container mx-auto mt-10 px-4">
        <h2 class="text-3xl  text-black mb-6">Nieuwe advertenties in</h2>
        <p class="text-2xl text-black mb-6">Spellen</p>

        <div class="swiper spellenSwiper mb-10 relative">
            <div class="swiper-wrapper">
                @foreach ($spellenAds as $ad)
                    @continue(!in_array($ad->status, ['te_koop', 'gereserveerd']))
                    <div class="swiper-slide">
                        <a href="{{ route('ads.show', $ad) }}"
                            class="block border p-4 rounded shadow hover:shadow-lg transition">
                            @if ($ad->images->count())
                                <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}"
                                    alt="{{ $ad->title }}" class="w-full h-40 object-cover rounded">
                            @endif
                            <p class="text-2xl font-semibold mb-0 mt-2">{{ $ad->title }}</p>
                            <p class="text-sm text-gray-600">{{ $ad->category->name ?? 'Onbekend' }}</p>
                            <p class="text-xl text-gray-800">€ {{ number_format($ad->price, 2, ',', '.') }}</p>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="swiper-button-prev spellen-prev"></div>
            <div class="swiper-button-next spellen-next"></div>
        </div>

        <p class="text-2xl text-black mb-6">Elektronica</p>

        <div class="swiper elektronicaSwiper mb-10 relative">
            <div class="swiper-wrapper">
                @foreach ($elektronicaAds as $ad)
                    @continue(!in_array($ad->status, ['te_koop', 'gereserveerd']))
                    <div class="swiper-slide">
                        <a href="{{ route('ads.show', $ad) }}"
                            class="block border p-4 rounded shadow hover:shadow-lg transition">
                            @if ($ad->images->count())
                                <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}"
                                    alt="{{ $ad->title }}" class="w-full h-40 object-cover rounded">
                            @endif
                            <p class="text-2xl font-semibold mb-0 mt-2">{{ $ad->title }}</p>
                            <p class="text-sm text-gray-600">{{ $ad->category->name ?? 'Onbekend' }}</p>
                            <p class="text-xl text-gray-800">€ {{ number_format($ad->price, 2, ',', '.') }}</p>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="swiper-button-prev elektronica-prev"></div>
            <div class="swiper-button-next elektronica-next"></div>
        </div>

    </section>

    <script>
        new Swiper('.spellenSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
                640: {
                    slidesPerView: 1.5
                },
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                },
            },
            loop: true,
            grabCursor: true,
            navigation: {
                nextEl: '.spellen-next',
                prevEl: '.spellen-prev',
            }
        });

        new Swiper('.elektronicaSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
                640: {
                    slidesPerView: 1.5
                },
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                },
            },
            loop: true,
            grabCursor: true,
            navigation: {
                nextEl: '.elektronica-next',
                prevEl: '.elektronica-prev',
            }
        });
    </script>


@endsection
