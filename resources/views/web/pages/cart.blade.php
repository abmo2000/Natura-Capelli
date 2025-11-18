@extends('web.layouts.main')

@section('title')
Cart
@endsection

@section('content')
<x-header></x-header>

<section class="py-16 md:py-24 bg-black min-h-screen" x-data="cartManager()">
    <h2 class="heading text-white text-center text-4xl font-bold mb-8 py-8">Cart</h2>
    <div class="container mx-auto px-4">
        @if($items->isEmpty())
            <!-- Empty Cart State -->
            <div class="flex flex-col items-center justify-center py-20">
                <div class="text-center max-w-md">
                    <!-- Empty Cart Icon -->
                    <div class="mb-8">
                        <i class="fas fa-shopping-cart text-gray-700 text-8xl"></i>
                    </div>
                    
                    <!-- Empty Cart Message -->
                    <h3 class="text-white text-3xl font-bold mb-4">Your Cart is Empty</h3>
                    <p class="text-gray-400 text-lg mb-8">
                        Looks like you haven't added anything to your cart yet. Start shopping to fill it up!
                    </p>
                    
                    <!-- Continue Shopping Button -->
                    <a href="{{ route('shop') }}" 
                       class="inline-flex items-center gap-2 bg-orange-100 text-black font-bold px-8 py-4 rounded-lg hover:bg-orange-200 transition-all duration-300 hover:scale-105">
                        <i class="fas fa-arrow-left"></i>
                        Continue Shopping
                    </a>
                </div>
            </div>
        @else
            <!-- Cart Items -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Side - Cart Items (Scrollable) -->
                <div class="lg:col-span-2">
                    <div class="max-h-[calc(100vh-250px)] overflow-y-auto pr-4 space-y-6 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">

                        @foreach ($items as $item)
                            <div class="rounded-lg bg-grey p-6" x-data="{ updating: false, removing: false }">
                                <div class="flex flex-col md:flex-row gap-6">
                                    <!-- Product Image -->
                                    <div class="w-full md:w-32 h-32 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $item['image']) }}" 
                                             class="w-full h-full object-cover rounded-lg" 
                                             alt="{{ $item['name'] }}">
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <a href="" 
                                                   class="text-white font-semibold text-xl hover:text-orange-100 transition-colors block mb-2">
                                                    {{ $item['name'] }}
                                                </a>
                                                <p class="text-gray-400 text-sm">Price: ${{ number_format($item['price'], 2) }}</p>
                                            </div>
                                            <button @click="removeItem({{ $item['product_id'] }})" 
                                                    :disabled="removing"
                                                    class="text-gray-400 hover:text-red-500 transition-colors disabled:opacity-50">
                                                <i class="fas fa-trash-alt" x-show="!removing"></i>
                                                <i class="fas fa-spinner fa-spin" x-show="removing"></i>
                                            </button>
                                        </div>

                                        <!-- Price and Quantity -->
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div class="flex items-center gap-6">
                                                <!-- Quantity Input -->
                                                <div class="flex items-center border border-gray-700 rounded-lg overflow-hidden">
                                                    <button type="button" 
                                                            @click="decrementQuantity({{ $item['product_id'] }}, {{ $item['quantity'] }})"
                                                            :disabled="updating || {{ $item['quantity'] }} <= 1"
                                                            class="px-4 py-2 text-white hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <div class="w-16 text-center text-white py-2">
                                                        <span x-show="!updating">{{ $item['quantity'] }}</span>
                                                        <i class="fas fa-spinner fa-spin text-sm" x-show="updating"></i>
                                                    </div>
                                                    <button type="button" 
                                                            @click="incrementQuantity({{ $item['product_id'] }}, {{ $item['quantity'] }})"
                                                            :disabled="updating || {{ $item['quantity'] }} >= 99"
                                                            class="px-4 py-2 text-white hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Subtotal -->
                                            <div class="text-right">
                                                <span class="text-orange-100 font-bold text-2xl">${{ number_format($item['subtotal'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        @endforeach
                        
                    </div>
                </div>

                <!-- Right Side - Order Summary (Fixed/Sticky) -->
                <div class="lg:col-span-1">
                    <div class="rounded-lg bg-grey p-6 lg:sticky lg:top-24">
                        <h3 class="text-white text-2xl font-bold mb-6">Order Summary</h3>

                        <!-- Summary Items -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-400">
                                <span>Items ({{ $items->count() }})</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-gray-400">
                                <span>Shipping</span>
                                <span>Calculated at checkout</span>
                            </div>
                            
                            <div class="border-t border-gray-700 pt-4">
                                <div class="flex justify-between text-white text-xl font-bold">
                                    <span>Total</span>
                                    <span class="text-orange-100">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button class="w-full bg-orange-100 text-black font-bold py-4 rounded-lg hover:bg-orange-200 transition-colors mb-4">
                            Proceed to Checkout
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>

                        <!-- Continue Shopping Link -->
                        <a href="{{ route('shop') }}" 
                           class="block text-center text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Continue Shopping
                        </a>

                        <!-- Clear Cart Button -->
                        <button @click="clearCart()" 
                                :disabled="clearing"
                                class="w-full mt-4 border border-red-500 text-red-500 font-semibold py-3 rounded-lg hover:bg-red-500 hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!clearing">
                                <i class="fas fa-trash mr-2"></i>
                                Clear Cart
                            </span>
                            <span x-show="clearing">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Clearing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Toast Notification -->
        <div x-show="showToast" 
             x-transition
             @click="showToast = false"
             class="fixed bottom-8 right-8 z-50 max-w-sm bg-grey border-l-4 p-4 rounded-lg shadow-2xl cursor-pointer"
             :class="toastType === 'success' ? 'border-green-500' : 'border-red-500'">
            <div class="flex items-center gap-3">
                <i class="fas" :class="toastType === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500'"></i>
                <p class="text-white" x-text="toastMessage"></p>
            </div>
        </div>
    </div>
</section>

<script>
function cartManager() {
    return {
        clearing: false,
        showToast: false,
        toastMessage: '',
        toastType: 'success',

        async incrementQuantity(productId, currentQuantity) {
            if (currentQuantity < 99) {
                await this.updateQuantity(productId, currentQuantity + 1);
            }
        },

        async decrementQuantity(productId, currentQuantity) {
            if (currentQuantity > 1) {
                await this.updateQuantity(productId, currentQuantity - 1);
            }
        },

        async updateQuantity(productId, quantity) {
            try {
                const response = await fetch(`/cart/update/${productId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ quantity })
                });

                const data = await response.json();

                if (data.success) {
                    this.showNotification(data.message, 'success');
                    // Reload after short delay to show notification
                    setTimeout(() => location.reload(), 500);
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error updating cart:', error);
                this.showNotification('Failed to update cart', 'error');
            }
        },

        async removeItem(productId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            try {
                const response = await fetch(`/cart/remove/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 500);
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error removing item:', error);
                this.showNotification('Failed to remove item', 'error');
            }
        },

        async clearCart() {
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                return;
            }

            this.clearing = true;

            try {
                const response = await fetch('/cart/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 500);
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error clearing cart:', error);
                this.showNotification('Failed to clear cart', 'error');
            } finally {
                this.clearing = false;
            }
        },

        showNotification(message, type = 'success') {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;

            // Auto-hide after 3 seconds
            setTimeout(() => {
                this.showToast = false;
            }, 3000);
        }
    }
}
</script>
@endsection