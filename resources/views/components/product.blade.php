@props(['product' => (object)[]])
<div class="product-card cursor-pointer min-w-full md:min-w-0 md:flex-1 snap-start" x-data="productCard({{ $product->id }})">
    <div class="relative group">
        <!-- Main clickable link overlay -->
        <a href="{{route('products.show', $product->slug) . ($product->isTrial() ? '?trial=1' : '') }}" class="absolute inset-0 z-10 cursor-pointer"></a>
        
         @if($product->isTrial())
        <div class="absolute top-4 start-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg z-20 pointer-events-none">
            {{ trans('shop.trial') }}
        </div>
        @endif
        <!-- Sale Badge -->
        @if($product->hasSale())
        <div class="absolute top-4 start-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg z-20 pointer-events-none">
            {{ trans('shop.sale') }}
        </div>
        @endif
        
        <img class="w-full h-64 object-contain pointer-events-none" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">

        <!-- Overlay -->
         <div class="absolute inset-0 bg-black/40 opacity-0 md:group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
        <!-- Action Buttons -->
        <div class="absolute top-4 end-4 flex flex-col gap-3 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300 z-20">
            <button 
                @click.stop="addToCart()" 
                :disabled="adding" 
                class="bg-white hover:bg-orange-500 p-3 rounded-full text-gray-800 hover:text-white shadow-lg transition-all duration-300 hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed relative cursor-pointer">
                <i class="fa-solid fa-cart-shopping" x-show="!adding"></i>
                <!-- Loading Spinner -->
                <svg x-show="adding" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>

            <a 
                href="{{ route('products.show', $product->slug) }}" 
                class="bg-white hover:bg-orange-500 p-3 rounded-full text-gray-800 hover:text-white shadow-lg transition-all duration-300 hover:scale-110 relative cursor-pointer">
                <i class="fa-solid fa-eye"></i>
            </a>
        </div>

        <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-4 start-4 end-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 z-20 pointer-events-none">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm font-medium">Added to cart!</span>
        </div>
    </div>

    <div class="p-5 relative cursor-pointer">
        <a href="{{route('products.show', $product->slug) . ($product->isTrial() ? '?trial=1' : '') }}" class="text-white font-semibold text-lg hover:text-orange-100 transition-colors block mb-2 relative z-10">
            {{ $product->name }}
        </a>

        
        <div class="flex items-center gap-3 pointer-events-none">
            @if($product->hasSale())
                <!-- Old Price (Slashed) -->
                <span class="text-gray-400 line-through text-lg">{{ $product->price . ' EGP' }}</span>
                <!-- Sale Price -->
                <span class="text-orange-500 font-bold text-xl">{{ $product?->sale?->sale_price . ' EGP' }}</span>
            @elseif($product->isTrial())
                 <!-- Original Product Price (Slashed) -->
                <span class="text-gray-400 line-through text-lg">{{$product->product->price . ' EGP' }}</span>
                <!-- Trial Price -->
                <span class="text-blue-400 font-bold text-xl">{{ $product->price . ' EGP' }}</span>

             @else
              <span class="text-orange-100 font-bold text-xl">{{ $product->price . ' EGP' }}</span>   
            @endif
        </div>
    </div>
</div>