@extends('web.layouts.main')

@section('title')
{{ $seoData['meta_title'] ?: config('app.name') }}
@endsection

@push('meta')
<meta name="description" content="{{ $seoData['meta_description'] ?: 'Welcome to our e-commerce store' }}">
@if($seoData['meta_keywords'])
<meta name="keywords" content="{{ $seoData['meta_keywords'] }}">
@endif
<meta name="robots" content="index, follow">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $seoData['meta_title'] ?: config('app.name') }}">
<meta property="og:description" content="{{ $seoData['meta_description'] ?: 'Welcome to our e-commerce store' }}">
<meta property="og:url" content="{{ request()->url() }}">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $seoData['meta_title'] ?: config('app.name') }}">
<meta name="twitter:description" content="{{ $seoData['meta_description'] ?: 'Welcome to our e-commerce store' }}">
@endpush

@section('content')

<x-navbar></x-navbar>
<!-- Hero Section with Background -->
<x-header header_bg_video="assets/images/headers/Hero.mp4">
    <div class="relative z-40 container mx-auto px-4 h-full">
</div>
</x-header>

<section class="py-10 md:py-14 bg-black border-y border-gray-800">
    <div class="container mx-auto px-4">
        <div class="overflow-hidden" id="categories-strip-viewport" dir="ltr">
            <div class="flex flex-nowrap items-start gap-6 md:gap-8" id="categories-strip-track">
            @forelse ($categories as $category)
                <div class="text-center category-slide-item shrink-0">
                    <a href="{{ route('shop', ['category' => $category->title]) }}" class="block group">
                        <div class="mx-auto w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden bg-gray-800 shadow-lg ring-1 ring-gray-700 transition-transform duration-300 group-hover:scale-105">
                            @if (!empty($category->image))
                                <img
                                    src="{{ asset('storage/' . ltrim($category->image, '/')) }}"
                                    alt="{{ $category->title }}"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-900 text-white text-3xl font-bold">
                                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(trim($category->title ?? ''), 0, 1)) ?: '?' }}
                                </div>
                            @endif
                        </div>
                        <p class="mt-3 text-sm md:text-xl font-medium text-gray-200 group-hover:text-white transition-colors">{{ $category->title }}</p>
                    </a>
                </div>
            @empty
                <p class="text-center text-gray-400 w-full">No categories found.</p>
            @endforelse
            </div>
        </div>
    </div>
</section>

@push('scripts_bottom')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const track = document.getElementById('categories-strip-track');
        const items = track ? Array.from(track.querySelectorAll('.category-slide-item')) : [];
        const transitionMs = 500;
        const changeEveryMs = 2000;

        if (!track || items.length <= 1) {
            return;
        }

        let isAnimating = false;

        const stepRight = () => {
            if (isAnimating) return;

            const lastItem = track.lastElementChild;
            if (!lastItem) return;

            const gap = parseInt(window.getComputedStyle(track).columnGap || window.getComputedStyle(track).gap || '0', 10) || 0;
            const moveBy = lastItem.getBoundingClientRect().width + gap;

            isAnimating = true;
            track.style.transition = `transform ${transitionMs}ms ease`;
            track.style.transform = `translateX(${moveBy}px)`;

            window.setTimeout(() => {
                track.style.transition = 'none';
                track.style.transform = 'translateX(0)';
                track.prepend(lastItem);

                void track.offsetHeight;
                isAnimating = false;
            }, transitionMs);
        };

        setInterval(() => {
            stepRight();
        }, changeEveryMs);
    });
</script>
@endpush

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
                        <div class="overflow-hidden">
                            <div x-ref="carousel" @scroll="updatePagination()" class="carousel-container flex gap-4 md:gap-6 overflow-x-scroll py-4">
                                @foreach ($section['products'] as $product)
                                    <x-product :product="$product"></x-product>
                                @endforeach
                            </div>
                        </div>

                        <div x-show="pages.length > 1" class="mt-4 flex items-center justify-center gap-3">
                            <template x-for="page in pages" :key="page">
                                <button
                                    type="button"
                                    @click="goToPage(page)"
                                    :class="currentPage === page ? 'bg-white w-3 h-3' : 'bg-gray-600 hover:bg-gray-400 w-2.5 h-2.5'"
                                    class="rounded-full transition-all duration-300"
                                    aria-label="Go to slide">
                                </button>
                            </template>
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

