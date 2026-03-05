@extends('web.layouts.main')

@section('title')
product
@endsection

@section('content')

<x-navbar></x-navbar>

 @php
    $type = (isset($product) && method_exists($product, 'isTrial') && $product->isTrial()) ? 'trial' : 'product';
    $productId = isset($product->id) ? $product->id : null;
    $galleryImages = collect($product->gallery_images)
        ->map(fn (string $image) => asset('storage/' . $image))
        ->values()
        ->all();
@endphp

<section
    class="py-16 md:py-24 bg-black"
    x-data="productCard('{{ $type }}', {{ json_encode($productId) }})"
>
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-start">
            <!-- Product Image - Left Side -->
            <div
                class="space-y-4 md:sticky md:top-20"
                x-data="{
                    images: @js($galleryImages),
                    activeImage: 0,
                    next() {
                        if (this.images.length < 2) {
                            return;
                        }

                        this.activeImage = (this.activeImage + 1) % this.images.length;
                    },
                    previous() {
                        if (this.images.length < 2) {
                            return;
                        }

                        this.activeImage = (this.activeImage - 1 + this.images.length) % this.images.length;
                    }
                }"
            >
                <!-- Main Image -->
                <div class="bg-gray-900 rounded-lg overflow-hidden relative p-3 flex items-center justify-center min-h-[24rem] md:min-h-[28rem]">
                    <!-- Sale Badge -->
                    @if($product->sale)
                    <div class="absolute top-6 left-6 bg-red-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg z-10">
                        {{ trans('shop.sale') }}
                    </div>
                    @endif

                    <img
                        x-show="images.length > 0"
                        :src="images[activeImage]"
                        alt="{{ $product->name }}"
                        class="w-full h-auto max-h-[70vh] object-contain"
                    >

                    <div
                        x-show="images.length === 0"
                        class="w-full py-24 flex items-center justify-center text-gray-500"
                    >
                        <i class="fas fa-image text-4xl"></i>
                    </div>

                    <button
                        type="button"
                        x-show="images.length > 1"
                        @click="previous()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors z-20"
                    >
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <button
                        type="button"
                        x-show="images.length > 1"
                        @click="next()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors z-20"
                    >
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div x-show="images.length > 1" class="grid grid-cols-5 gap-3">
                    <template x-for="(image, index) in images" :key="image + '-' + index">
                        <button
                            type="button"
                            @click="activeImage = index"
                            class="aspect-square rounded-md overflow-hidden border-2 transition-colors"
                            :class="activeImage === index ? 'border-orange-500' : 'border-gray-700'"
                        >
                            <img
                                :src="image"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover"
                            >
                        </button>
                    </template>
                </div>
            </div>

            <!-- Product Details - Right Side -->
            <div class="space-y-6">
                <!-- Product Title -->
                <div>
                    <h1 class="text-white text-3xl md:text-4xl font-bold mb-2">
                        {{ $product->name }}
                    </h1>
                    
                    <!-- Capacity -->
                    @if($product->capacity)
                    <p class="text-gray-400 text-lg mt-2">
                        <i class="fas fa-microchip me-2"></i>{{ trans('shop.specs') }}: {{ $product->capacity }}
                    </p>
                    @endif
                </div>

                <!-- Price -->
                <div class="flex items-center gap-4">
                    @if($product->hasSale())
                        <!-- Old Price (Slashed) -->
                        <span class="text-gray-500 text-2xl line-through">{{ $product->price . ' EGP' }}</span>
                        <!-- Sale Price -->
                        <span class="text-orange-500 text-4xl font-bold">{{ $product?->sale?->sale_price . ' EGP' }}</span>
                        <!-- Discount Percentage (Optional) -->
                        @php
                            $discount = round((($product->price - $product->sale->sale_price) / $product->price) * 100);
                        @endphp
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            -{{ $discount }}%
                        </span>
                    @else
                        <!-- Regular Price -->
                        <span class="text-white text-4xl font-bold">{{ $product->price . ' EGP' }}</span>
                    @endif
                </div>

                <!-- Description -->
                <div class="border-t border-gray-800 pt-6 text-white">
                    <h3 class="text-white text-lg font-semibold mb-3">{{ trans('shop.desc') }}</h3>
                    <p class="text-gray-400 leading-relaxed">
                        {!! $product->description !!}
                    </p>
                </div>

                <div>
                    <!-- Quantity Selector -->
                    <div class="space-y-4">
                        <label class="block text-white font-semibold">{{ trans('shop.quantity') }}</label>
                        
                        <div class="flex items-center border border-gray-700 rounded-lg overflow-hidden bg-gray-900 w-fit">
                            <button type="button" 
                                    class="px-4 py-3 text-white hover:bg-gray-800 transition-colors disabled:opacity-50"
                                    @click="quantity--"
                                    :disabled="quantity <= 1">
                                <i class="fas fa-minus"></i>
                            </button>
                            
                            <input x-model.number="quantity" 
                                type="number" 
                                name="quantity"
                                min="1" 
                                :max="maxStock"
                                class="w-20 text-center bg-transparent text-white py-3 focus:outline-none border-x border-gray-700"
                                @change="quantity = Math.max(1, Math.min(1, quantity))">
                            
                            <button type="button" 
                                    class="px-4 py-3 text-white hover:bg-gray-800 transition-colors disabled:opacity-50"
                                    @click="quantity++"
                                    :disabled="quantity >= 99">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <button @click="addToCart()" :disabled="adding" type="submit" 
                            class="w-full mt-5 bg-gradient-to-r from-orange-500 to-red-500 text-white py-4 rounded-lg font-semibold text-lg hover:from-orange-600 hover:to-red-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span x-show="!adding">{{ trans('shop.addtocart') }}</span>
                        <span x-show="adding">Adding...</span>
                    </button>
                    
                    <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 mt-4">
                        <i class="fas fa-check-circle"></i>
                        <span class="text-sm font-medium">{{ trans('shop.addtocart') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection