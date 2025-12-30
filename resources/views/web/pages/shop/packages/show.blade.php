@extends('web.layouts.main')

@section('title')
package
@endsection

@section('content')
<x-navbar />

<section class="py-16 md:py-24 bg-black" x-data="productCard({{ $package->id }})">
    <div class="container mx-auto px-4 py-8">

        <!-- ========================= -->
        <!-- TWO COLUMN LAYOUT -->
        <!-- ========================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            
            <!-- LEFT: IMAGE SLIDER -->
            <div
                x-data="{
                    active: 0,
                    images: @js($package->products->pluck('image'))
                }"
                class="bg-slate-800 rounded-2xl p-8 order-1 lg:order-1"
            >
                <div class="relative h-[28rem] flex items-center justify-center overflow-hidden">

                    <template x-for="(image, index) in images" :key="index">
                        <img
                            x-show="active === index"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            :src="`{{ asset('storage') }}/${image}`"
                            class="absolute max-w-full max-h-full object-contain"
                            alt=""
                        >
                    </template>

                    <!-- Prev -->
                    <button
                        @click="active = active === 0 ? images.length - 1 : active - 1"
                        class="absolute left-6 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-12 h-12 rounded-full flex items-center justify-center"
                    >
                        ‹
                    </button>

                    <!-- Next -->
                    <button
                        @click="active = active === images.length - 1 ? 0 : active + 1"
                        class="absolute right-6 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-12 h-12 rounded-full flex items-center justify-center"
                    >
                        ›
                    </button>
                </div>

                <!-- Dots -->
                <div class="flex justify-center gap-2 mt-6">
                    <template x-for="(image, index) in images" :key="index">
                        <button
                            @click="active = index"
                            :class="active === index ? 'bg-white' : 'bg-gray-500'"
                            class="w-3 h-3 rounded-full transition"
                        ></button>
                    </template>
                </div>
            </div>

            <!-- RIGHT: PACKAGE DETAILS -->
            <div class="order-2 lg:order-2">
                <h1 class="text-4xl font-bold text-white mb-4">
                    {{ $package->title }}
                </h1>

                <div class="bg-slate-800 rounded-xl p-6 mb-6">
                    <div class="flex items-baseline gap-4">
                        <span class="text-gray-500 line-through text-2xl">
                            {{ $package->original_price }} EGP
                        </span>
                        <span class="text-gray-500 text-4xl font-bold">
                            {{ $package->price }} EGP
                        </span>
                    </div>
                </div>
               
              
                <div class="mb-8">
                    <p class="text-gray-300 leading-relaxed">
                        {!! $package->description !!}
                    </p>
                </div>
                <!-- QUANTITY + ADD TO CART -->
                <div>
                    <label class="block text-white font-semibold mb-2">Quantity</label>

                    <div class="border border-gray-700 rounded-lg overflow-hidden bg-gray-900 w-fit">
                        <button
                            type="button"
                            class="px-4 py-3 text-white hover:bg-gray-800"
                            @click="quantity--"
                            :disabled="quantity <= 1"
                        >
                            −
                        </button>

                        <input
                            x-model.number="quantity"
                            type="number"
                            min="1"
                            class="w-20 text-center bg-transparent text-white py-3 border-x border-gray-700 focus:outline-none"
                        >

                        <button
                            type="button"
                            class="px-4 py-3 text-white hover:bg-gray-800"
                            @click="quantity++"
                            :disabled="quantity >= 99"
                        >
                            +
                        </button>
                    </div>

                    <button
                        @click="addToCart()"
                        :disabled="adding"
                        class="mt-6 w-full bg-gradient-to-r from-orange-500 to-red-500 text-white py-4 px-10 rounded-lg font-semibold text-lg hover:from-orange-600 hover:to-red-600 transition flex items-center justify-center gap-2"
                    >
                        <span x-show="!adding">Add to Cart</span>
                        <span x-show="adding">Adding...</span>
                    </button>

                    <div
                        x-show="showSuccess"
                        x-transition
                        class="mt-4 bg-green-500 text-white px-4 py-2 rounded-lg inline-block"
                    >
                        Added to cart!
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================= -->
        <!-- PRODUCTS GRID -->
        <!-- ========================= -->
        <h2 class="text-2xl font-bold text-white mb-6">
            This Package Includes
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($package->products as $product)
                <div class="bg-slate-800 p-4 rounded-xl">
                    <div class="h-40 bg-slate-700 rounded-lg flex items-center justify-center mb-4">
                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="h-full object-contain"
                        >
                    </div>

                    <h3 class="text-white font-semibold mb-1">
                        {{ $product->name }}
                    </h3>

                    <p class="text-gray-400 text-sm">
                        Regular Price:
                        <span class="line-through">
                            {{ $product->price }} EGP
                        </span>
                    </p>
                </div>
            @endforeach
        </div>

    </div>
</section>
@endsection