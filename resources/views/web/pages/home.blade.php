@extends('web.layouts.main')

@section('title')
home
@endsection

@section('content')

<x-navbar></x-navbar>
<!-- Hero Section with Background -->
<x-header header_bg_video="assets/images/headers/Hero.mp4">
    <div class="relative z-40 container mx-auto px-4 h-full">
</div>
</x-header>

<section class="py-10 md:py-14 bg-black border-y border-gray-800">
    <div class="container mx-auto px-4">
        @php
            $categoryCards = [
                ['file' => 'Monitors.jpeg', 'label' => 'Monitors', 'category' => 'Monitors'],
                ['file' => 'Gaming Accessories.jpeg', 'label' => 'Gaming Accessories', 'category' => 'Gaming Accessories'],
                ['file' => 'ALL IN ONE.jpeg', 'label' => 'ALL IN ONE', 'category' => 'ALL IN ONE'],
                ['file' => 'Apple.jpeg', 'label' => 'Apple', 'category' => 'Apple'],
                ['file' => 'PC.jpeg', 'label' => 'PC', 'category' => 'PC'],
                ['file' => 'Laptops.jpg', 'label' => 'Laptops', 'category' => 'Laptops'],
            ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 md:gap-8">
            @foreach ($categoryCards as $card)
                <div class="text-center">
                    <a href="{{ route('shop', ['category' => $card['category']]) }}" class="block group">
                        <div class="mx-auto w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden bg-gray-800 shadow-lg ring-1 ring-gray-700 transition-transform duration-300 group-hover:scale-105">
                            <img
                                src="{{ asset('assets/images/Body/' . rawurlencode($card['file'])) }}"
                                alt="{{ $card['label'] }}"
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <p class="mt-3 text-sm md:text-xl font-medium text-gray-200 group-hover:text-white transition-colors">{{ $card['label'] }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- products -->

<section id="fearured-products" class="py-16 md:py-24 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="text-center font-bold text-white text-4xl mb-2">{{ trans('home.featured-products') }}</h2>
        <p class="text-gray-400 text-center mb-12">{{ trans('home.discover-products') }}</p>

        <div class="space-y-14">
            @forelse ($categorySections as $section)
                <div x-data="productCarousel()">
                    <h3 class="text-white text-2xl font-bold mb-6">{{ $section['title'] }}</h3>

                    <div class="relative max-w-7xl mx-auto">
                        <button
                            @click="scroll(-1)"
                            x-show="isRTL() ? isAtStart : !isAtStart"
                            x-transition
                            class="absolute start-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-gray-100 shadow-lg p-4 rounded-full transition-all duration-300 hover:scale-110"
                            style="display: block !important;">
                            <i class="fas fa-arrow-left text-xl text-gray-800"></i>
                        </button>

                        <button
                            @click="scroll(1)"
                            x-show="!isAtEnd"
                            x-transition
                            class="absolute end-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-gray-100 shadow-lg p-4 rounded-full transition-all duration-300 hover:scale-110"
                            style="display: block !important;">
                            <i class="fas fa-arrow-right text-xl text-gray-800"></i>
                        </button>

                        <div class="overflow-hidden px-12">
                            <div x-ref="carousel" @scroll="updateArrows()" class="carousel-container flex gap-4 md:gap-6 overflow-x-scroll py-4">
                                @foreach ($section['products'] as $product)
                                    <x-product :product="$product"></x-product>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-center">No products available right now.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

