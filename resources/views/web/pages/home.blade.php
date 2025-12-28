@extends('web.layouts.main')

@section('title')
home
@endsection

@section('content')

<x-navbar></x-navbar>
<!-- Hero Section with Background -->
<x-header header_bg_image="assets/images/headers/homeHeader.jpg">
  <div class="relative z-40 container mx-auto px-4 py-6 lg:py-8 flex items-center lg:items-center justify-center min-h-[500px] lg:min-h-[calc(100vh-5rem)]">
    <div class="text-center space-y-3 sm:space-y-4 md:space-y-6 lg:space-y-8 w-full max-w-5xl pt-8 lg:pt-0">
        <h1 class="text-white text-3xl sm:text-4xl md:text-5xl lg:text-7xl xl:text-8xl font-bold tracking-wider uppercase leading-tight">
            NATURA CAPELLI
        </h1>

        <h2 class="text-white text-base sm:text-lg md:text-xl lg:text-2xl font-medium">
            Hydrate & Nourish your Hair
        </h2>

        <div class="pt-2 sm:pt-3 md:pt-4 pb-4">
            <a href="#" class="custom-btn inline-block">
                We Support Your Choice
            </a>
        </div>
    </div>
</div>
</x-header>

<!-- about us -->
<section id="about-section" class="py-16 md:py-24 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="heading">{{ trans('home.about-products-section') }}</h2>
        <p class="sub-heading">{{ trans('home.discover-natura') }}</p>
        <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
            
            <!-- Image -->
            <div>
                <img src="{{ asset('assets/images/aboutProducts.jpeg') }}" alt="hair image" class="w-full h-auto rounded-lg shadow-lg">
            </div>

            <!-- Content -->
            <div class="space-y-6">
                
                <div class="space-y-4 text-[#c3c3c3] leading-relaxed">
                    <p>
                        {{ trans('home.about-products') }}
                    </p>                   
                </div>

                <div class="pt-4">
            <a href="{{ route("shop") }}" class="custom-btn">
                {{ trans('home.see-more') }}
            </a>
        </div>
            </div>
            
        </div>
    </div>
</section>

<!-- products -->

<section id="fearured-products" class="py-16 md:py-24 bg-black" x-data="productCarousel()">
        <div class="container mx-auto px-4">
            <h2 class="text-center font-bold text-white text-4xl mb-2">{{ trans('home.featured-products') }}</h2>
            <p class="text-gray-400 text-center mb-12">{{ trans('home.discover-products') }}</p>

            <div class="relative max-w-7xl mx-auto">
            
                <button  
                    @click="scroll(-1)" 
                    x-show="!isAtStart" 
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
                <!-- Carousel Container -->
                <div class="overflow-hidden px-12">
                    <div x-ref="carousel" @scroll="updateArrows()" class="carousel-container flex gap-4 md:gap-6 overflow-x-scroll py-4">
                        @foreach ($products as $product )
                            <x-product :product="$product"></x-product>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

