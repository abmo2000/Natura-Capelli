@extends('web.layouts.main')

@section('title')
product
@endsection

@section('content')

<x-navbar></x-navbar>

<section class="py-16 md:py-24 bg-black" x-data="productCard({{ $product->id }})">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-start">
            <!-- Product Image - Left Side -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="sticky top-20 bg-gray-900 rounded-lg overflow-hidden aspect-square lg:aspect-[3/4] relative">
                    <!-- Sale Badge -->
                    @if($product->sale)
                    <div class="absolute top-6 left-6 bg-red-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg z-10">
                        {{ trans('shop.sale') }}
                    </div>
                    @endif

                    @if($product->isTrial())
                        
                    <div class="absolute top-6 left-6 bg-blue-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg z-10">
                        {{ trans('shop.trial') }}
                    </div>
                        
                    @endif
                    
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-contain p-8">
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
                        <i class="fas fa-flask me-2"></i>{{ $product->capacity }} ml
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
                    <h3 class="text-white text-lg font-semibold mb-3">Description</h3>
                    <p class="text-gray-400 leading-relaxed">
                        {!! $product->description !!}
                    </p>
                </div>

                <div>
                    <!-- Quantity Selector -->
                    <div class="space-y-4">
                        <label class="block text-white font-semibold">Quantity</label>
                        
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
                        <span x-show="!adding">Add to Cart</span>
                        <span x-show="adding">Adding...</span>
                    </button>
                    
                    <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 mt-4">
                        <i class="fas fa-check-circle"></i>
                        <span class="text-sm font-medium">Added to cart!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection