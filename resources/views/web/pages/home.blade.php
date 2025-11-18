@extends('web.layouts.main')

@section('title')
home
@endsection

@section('content')

<!-- Hero Section with Background -->
<x-header header_bg_image="assets/images/headers/seascape-texture-waves-water-generative-ai.jpg">

 <div class="relative z-40 container mx-auto px-4 flex items-center justify-center" style="height: calc(100vh - 5rem);">
    <div class="text-center space-y-8">
        <h1 class="text-white text-6xl md:text-7xl lg:text-8xl font-bold tracking-wider uppercase mx-4">
            NATURA CAPELLI
        </h1>

        <h2 class="text-white text-xl md:text-2xl mx-4 font-medium">
            Hydrate & Nourish your Hair
        </h2>

        <div class="pt-4">
            <a href="#" class="custom-btn">
                We Support Your Choice
            </a>
        </div>
    </div>
</div>
</x-header>

<!-- about us -->
<section class="py-16 md:py-24 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="heading">WHO ARE WE</h2>
        <p class="sub-heading">Discover Natura Capelli</p>
        <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
            
            <!-- Image -->
            <div>
                <img src="{{ asset('assets/images/hair.jpg') }}" alt="hair image" class="w-full h-auto rounded-lg shadow-lg">
            </div>

            <!-- Content -->
            <div class="space-y-6">
                
                <div class="space-y-4 text-[#c3c3c3] leading-relaxed">
                    <p>
                        Fridal is a privately owned Egyptian company established in 1957 with its headquarters located in 6th of October city.
                    </p>
                    
                    <p>
                        The company started by growing Aromatic plants in the fifties in response to the increasing demand of essential oils. Accordingly, Fridal became one of the biggest producers and exporters of essential oils, absolutes, concretes as well as Herbs & Spices.
                    </p>
                    
                    <p>
                        Fridal found itself fully involved in the perfumery world which led to a deeper interest in growing floral plants for concretes and absolutes. This was when Fridal increased its activities by producing perfumes, flavors & fragrances in the early eighties. Throughout the following ten years Fridal expanded its range of products in all its divisions. It also became one of the major producers and exporters of herbs & spices especially to the United States of America.
                    </p>
                    
                    <p>
                        Today, Fridal is a major supplier of a wide range of products and offers an outstanding worldwide service to its customers. It has also started its expansions within the FMCG market through household and personal care products.
                    </p>
                </div>

                <div class="pt-4">
            <a href="#" class="custom-btn">
                See More
            </a>
        </div>
            </div>
            
        </div>
    </div>
</section>

<!-- products -->

   <section class="py-16 md:py-24 bg-black" x-data="productCarousel()">
        <div class="container mx-auto px-4">
            <h2 class="text-center font-bold text-white text-4xl mb-2">Featured Products</h2>
            <p class="text-gray-400 text-center mb-12">Discover our Products</p>

            <div class="relative max-w-7xl mx-auto">
                <!-- Left Arrow -->
                <button  @click="scroll(-1)" x-show="!isAtStart" x-transition class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-gray-100 shadow-lg p-4 rounded-full transition-all duration-300 hover:scale-110">
                    <i class="fas fa-arrow-left text-xl text-gray-800"></i>
                </button>

                <!-- Right Arrow -->
                <button  @click="scroll(1)" x-show="!isAtEnd" x-transition class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-gray-100 shadow-lg p-4 rounded-full transition-all duration-300 hover:scale-110">
                    <i class="fas fa-arrow-right text-xl text-gray-800"></i>
                </button>

                <!-- Carousel Container -->
                <div class="overflow-hidden px-12">
                    <div  x-ref="carousel" @scroll="updateArrows()" class="carousel-container flex gap-6 overflow-x-auto py-4">
                        @foreach ($products as $product )
                            
                        <div class="product-card" x-data="productCard({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                            <div class="relative group">
                                <img class="w-full h-64 object-contain" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                
                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <!-- Action Buttons -->
                                <div class="absolute top-4 right-4 flex flex-col gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    {{-- <a href="#" class="bg-white hover:bg-orange-500 p-3 rounded-full text-gray-800 hover:text-white shadow-lg transition-all duration-300 hover:scale-110">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </a> --}}

                                      <button @click="addToCart()" 
                                            :disabled="adding"
                                            class="bg-white hover:bg-orange-500 p-3 rounded-full text-gray-800 hover:text-white shadow-lg transition-all duration-300 hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed relative">
                                        <i class="fa-solid fa-cart-shopping" x-show="!adding"></i>
                                        <!-- Loading Spinner -->
                                        <svg x-show="adding" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                  </button>

                                    <a href="{{ route('products.show' , $product->slug) }}" class="bg-white hover:bg-orange-500 p-3 rounded-full text-gray-800 hover:text-white shadow-lg transition-all duration-300 hover:scale-110">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                                  <div x-show="showSuccess" 
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="absolute top-4 left-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="text-sm font-medium">Added to cart!</span>
                                   </div>
                                
                            </div>

                            <div class="p-5">
                                <a href="#" class="text-white font-semibold text-lg hover:text-orange-100 transition-colors block mb-2">{{ $product->name }}</a>
                                <div class="flex items-center justify-between">
                                    <span class="text-orange-100 font-bold text-xl">{{ $product->price . ' EGP ' }}</span>
                                    
                                </div>
                            </div>
                        </div>
                        @endforeach
                       

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- @push('scripts_bottom')

<script>
function productCard(productId, productName, productPrice) {
    return {
        adding: false,
        showSuccess: false,
        
        async addToCart() {
            if (this.adding) return;
            
            this.adding = true;
            
            try {
                // Make AJAX request to add to cart
                const response = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update cart count
                    const newCount = data.cartCount || this.getCurrentCartCount() + 1;
                    this.updateCartCount(newCount);
                    
                    // Show success message
                    this.showSuccess = true;
                    setTimeout(() => {
                        this.showSuccess = false;
                    }, 2000);
                } else {
                    alert(data.message || 'Failed to add item to cart');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                alert('An error occurred. Please try again.');
            } finally {
                this.adding = false;
            }
        },
        
        getCurrentCartCount() {
            return parseInt(localStorage.getItem('cartCount') || '0');
        },
        
        updateCartCount(count) {
            localStorage.setItem('cartCount', count);
            // Dispatch event to update nav
            window.dispatchEvent(new CustomEvent('cart-updated', {
                detail: { count: count }
            }));
        }
    }
}
</script>
    
@endpush --}}