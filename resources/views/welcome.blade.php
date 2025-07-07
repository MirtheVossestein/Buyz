@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <div class="w-full h-screen relative">
        <img src="{{ asset('images/homepage.jpg') }}" alt="Homepage Afbeelding" class="w-full h-full ">
        <div class="absolute inset-0 flex items-center justify-start px-8">
            <div class="bg-white text-black rounded-xl p-9  w-[450px] h-[300px]">
                <p class="text-[28px] leading-10 mb-4">Plaats, vind, deal. Zo simpel werkt Buyz.</p>
                <a href="/register"
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
        <h2 class="text-3xl  text-black mb-6">Nieuw</h2>
        <p class="text-2xl text-black mb-6">Spellen</p>

        <div class="swiper mySwiper mb-10">
            <div class="swiper-wrapper">
                @foreach ($spellenAds as $ad)
                    <div class="swiper-slide">
                        <a href="{{ route('ads.show', $ad) }}"
                            class="block border p-4 rounded shadow hover:shadow-lg transition">
                            @if ($ad->images->count())
                                <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}"
                                    alt="{{ $ad->title }}" class="w-full h-40 object-cover rounded">
                            @endif
                            <p class="text-lg font-semibold mt-2">{{ $ad->title }}</p>
                            <p class="text-sm text-gray-600">{{ $ad->category->name ?? 'Onbekend' }}</p>
                            <p class="text-md text-gray-800">€ {{ number_format($ad->price, 2, ',', '.') }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="text-2xl text-black mb-6">Elektronica</p>

        <div class="swiper mySwiper mb-10">
            <div class="swiper-wrapper">
                @foreach ($elektronicaAds as $ad)
                    <div class="swiper-slide">
                        <a href="{{ route('ads.show', $ad) }}"
                            class="block border p-4 rounded shadow hover:shadow-lg transition">
                            @if ($ad->images->count())
                                <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}"
                                    alt="{{ $ad->title }}" class="w-full h-40 object-cover rounded">
                            @endif
                            <p class="text-lg font-semibold mt-2">{{ $ad->title }}</p>
                            <p class="text-sm text-gray-600">{{ $ad->category->name ?? 'Onbekend' }}</p>
                            
                            <p class="text-md text-gray-800">€ {{ number_format($ad->price, 2, ',', '.') }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        new Swiper('.mySwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
                640: {
                    slidesPerView: 1.2
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
        });
    </script>
@endsection
