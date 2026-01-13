@extends('web.layouts.main')

@section('title')
Checkout
@endsection

@section('content')

<x-navbar></x-navbar>
<section class="py-16 md:py-24 bg-black min-h-screen" x-data="checkoutForm({{ $total }}, {
    name: '{{ auth()->check() ? addslashes(auth()->user()->name ?? '') : '' }}',
    email: '{{ auth()->check() ? auth()->user()->email ?? '' : '' }}',
    phone: '{{ auth()->check() ? auth()->user()->phone ?? '' : '' }}',
    address: '{{ auth()->check() ? addslashes(auth()->user()->address ?? '') : '' }}',
    'city_id': '{{  auth()->check() ? auth()->user()->city_id ?? '' : '' }}',
    'instapay': '{{ $buisnessSettings->instapay_account ?? '' }}',
})">
  <div class="container mx-auto px-4">
    <h2 class="text-white text-center text-4xl md:text-5xl font-bold mb-16">{{ trans('checkout.checkout') }}</h2>

    <div class="max-w-md mx-auto">
      <div class="bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-3xl shadow-2xl border border-gray-700 p-8 md:p-12">
    
        <div x-show="success" x-transition class="mb-4 bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ trans('checkout.order_success') }}</span>
                </div>
            </div>
        <form @submit.prevent="submitOrder" class="space-y-6">
          @csrf

          <!-- Full Name -->
          <div>
            <label for="name" class="block text-gray-300 text-sm font-medium mb-2">
              {{ trans('checkout.full_name') }} <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              id="name" 
              name="name"
              x-model="form.name"
              @blur="validateField('name')"
              :placeholder="'{{ trans('checkout.full_name_placeholder') ?? 'John Doe' }}'"
              required
              class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
              :class="{ 'border-red-500': errors.name }"
            >
            <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-sm mt-1"></p>
          </div>

          <!-- Email Address -->
          <div>
            <label for="email" class="block text-gray-300 text-sm font-medium mb-2">
              {{ trans('checkout.email_address') }} <span class="text-red-500">*</span>
            </label>
            <input 
              type="email" 
              id="email" 
              name="email"
              x-model="form.email"
              @blur="validateField('email')"
              :placeholder="'{{ trans('checkout.email_placeholder') ?? 'john@example.com' }}'"
              required
              class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
              :class="{ 'border-red-500': errors.email }"
            >
            <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-sm mt-1"></p>
          </div>
      
          <!-- Phone Number -->
          <div>
            <label for="phone" class="block text-gray-300 text-sm font-medium mb-2">
              {{ trans('checkout.phone_number') }} <span class="text-red-500">*</span>
            </label>
            <div>
              <input 
                type="tel" 
                id="phone" 
                name="phone"
                :placeholder="'{{ trans('checkout.phone_placeholder') ?? '+201148992811' }}'"
                required
                class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                :class="{ 'border-red-500': errors.phone }"
              >
            </div>

            <p x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-sm mt-1"></p>
          </div>

          <!-- Address -->
          <div>
            <label for="address" class="block text-gray-300 text-sm font-medium mb-2">
              {{ trans('checkout.address') }} <span class="text-red-500">*</span>
            </label>
            <textarea 
              id="address" 
              name="address"
              x-model="form.address"
              @blur="validateField('address')"
              :placeholder="'{{ trans('checkout.address_placeholder') ?? 'مثال : شارع النصر ، مدينة نصر ، القاهرة' }}'"
              rows="3"
              class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none"
              :class="{ 'border-red-500': errors.address }"
            ></textarea>
            <p x-show="errors.address" x-text="errors.address" class="text-red-500 text-sm mt-1"></p>
          </div>

          <!-- City -->
          <div>
            <label for="city_id" class="block text-gray-300 text-sm font-medium mb-2">
              {{ trans('checkout.city') }} <span class="text-red-500">*</span>
            </label>
            <select 
              id="city_id" 
              name="city_id"
               x-ref="citySelect"
              x-model="form.city_id"
              @change="onCityChange"
              required
              class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
              :class="{ 'border-red-500': errors.city_id }"
            >
              <option value="">{{ trans('checkout.city_placeholder') ?? 'Select your city' }}</option>
              @foreach(getCities() as $city)
                <option 
                  value="{{ $city['id'] }}"
                  data-has-discussion="{{ $city['has_discussion_for_delivery'] ? 'true' : 'false' }}"
                  data-delivery-price="{{ $city['price'] ?? 0 }}"
                >
                  {{ $city['value'] }}
                </option>
              @endforeach
            </select>
            <p x-show="errors.city_id" x-text="errors.city_id" class="text-red-500 text-sm mt-1"></p>
          </div>

          @if($orderSettings->has_delivery_option)
          <!-- Delivery Options (Only shown for cities with discussion option) -->
          <div x-show="showDeliveryOptions && selectedCityHasDiscussion" x-transition class="space-y-3">
            <label class="block text-gray-300 text-sm font-medium mb-3">
              {{ trans('checkout.delivery_options') }} <span class="text-red-500">*</span>
            </label>

            <!-- Discuss Delivery Option -->
            <div>
              <label class="flex items-start p-4 bg-gray-900 border rounded-lg cursor-pointer hover:border-orange-500 transition"
                :class="form.delivery_option === 'discuss' ? 'border-orange-500' : 'border-gray-600'">
                <input 
                  type="radio" 
                  x-model="form.delivery_option"
                  @change="validateField('delivery_option')"
                  value="discuss" 
                  class="mt-1 text-orange-500 focus:ring-orange-500 focus:ring-2"
                >
                <div class="ml-3">
                  <p class="text-white font-medium">{{ trans('checkout.discuss_delivery') }}</p>
                  <p class="text-gray-400 text-sm mt-1">{{ trans('checkout.discuss_delivery_desc') }}</p>
                </div>
              </label>
            </div>

            <!-- Proceed with Delivery Price -->
            <div>
              <label class="flex items-start p-4 bg-gray-900 border rounded-lg cursor-pointer hover:border-orange-500 transition"
                :class="form.delivery_option === 'proceed' ? 'border-orange-500' : 'border-gray-600'">
                <input 
                  type="radio" 
                  x-model="form.delivery_option"
                  @change="validateField('delivery_option')"
                  value="proceed" 
                  class="mt-1 text-orange-500 focus:ring-orange-500 focus:ring-2"
                >
                <div class="ml-3 flex-1">
                  <p class="text-white font-medium">{{ trans('checkout.proceed_with_delivery') }}</p>
                  <p class="text-gray-400 text-sm mt-1">
                    {{ trans('checkout.proceed_delivery_desc') }}
                    <span x-show="deliveryPrice > 0" class="text-orange-400 font-semibold">
                      (+ EGP <span x-text="deliveryPrice.toFixed(2)"></span>)
                    </span>
                  </p>
                </div>
              </label>
            </div>

            <p x-show="errors.delivery_option" x-text="errors.delivery_option" class="text-red-500 text-sm mt-1"></p>
          </div>

          <!-- Auto Delivery Notice (For cities without discussion option) -->
          <div x-show="showDeliveryOptions && !selectedCityHasDiscussion && deliveryPrice > 0" x-transition class="p-4 bg-blue-900 bg-opacity-30 border border-blue-600 rounded-lg">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
              </svg>
              <div class="flex-1">
                <p class="text-blue-200 text-sm font-medium">{{ trans('checkout.delivery_included') }}</p>
                <p class="text-blue-300 text-xs mt-1">
                  {{ trans('checkout.delivery_fee_auto_added') }}: 
                  <span class="font-semibold">EGP <span x-text="deliveryPrice.toFixed(2)"></span></span>
                </p>
              </div>
            </div>
          </div>
          @endif

          <!-- Payment Method -->
          <div>
            <label class="block text-gray-300 text-sm font-medium mb-4">
              {{ trans('checkout.payment_method') }} <span class="text-red-500">*</span>
            </label>
            
            <!-- Cash on Delivery -->
            <div class="mb-3">
              <label class="flex items-start p-4 bg-gray-900 border rounded-lg cursor-pointer hover:border-orange-500 transition"
                :class="form.payment_method === 'cash_on_delivery' ? 'border-orange-500' : 'border-gray-600'">
                <input 
                  type="radio" 
                  x-model="form.payment_method"
                  @change="validateField('payment_method')"
                  value="cash_on_delivery" 
                  class="mt-1 text-orange-500 focus:ring-orange-500 focus:ring-2"
                >
                <div class="ml-3">
                  <p class="text-white font-medium">{{ trans('checkout.cash_on_delivery') }}</p>
                  <p class="text-gray-400 text-sm mt-1">{{ trans('checkout.pay_when_receive') }}</p>
                </div>
              </label>
            </div>

            <!-- InstaPay -->
            <div>
              <label class="flex items-start p-4 bg-gray-900 border rounded-lg cursor-pointer hover:border-orange-500 transition"
                :class="form.payment_method === 'instapay' ? 'border-orange-500' : 'border-gray-600'">
                <input 
                  type="radio" 
                  x-model="form.payment_method"
                  @change="validateField('payment_method')"
                  value="instapay"
                  class="mt-1 text-orange-500 focus:ring-orange-500 focus:ring-2"
                >
                <div class="ml-3">
                  <p class="text-white font-medium">{{ trans('checkout.instapay') }}</p>
                  <p class="text-gray-400 text-sm mt-1">{{ trans('checkout.pay_instantly') }}</p>
                </div>
              </label>
            </div>
            
            <p x-show="errors.payment_method" x-text="errors.payment_method" class="text-red-500 text-sm mt-2"></p>
          </div>

          <!-- Order Total -->
          <div class="pt-6 border-t border-gray-600 space-y-2">
            @if($orderSettings->has_delivery_option)
            <!-- Show breakdown when delivery is added (either auto or selected) -->
            <div x-show="shouldShowDeliveryFee()" class="flex justify-between items-center text-gray-400">
              <span>{{ trans('checkout.subtotal') }}:</span>
              <span x-text="'EGP ' + total.toFixed(2)"></span>
            </div>
            <div x-show="shouldShowDeliveryFee()" class="flex justify-between items-center text-gray-400">
              <span>{{ trans('checkout.delivery_fee') }}:</span>
              <span x-text="'EGP ' + deliveryPrice.toFixed(2)"></span>
            </div>
            @endif
            <div class="flex justify-between items-center">
              <span class="text-gray-300 text-lg font-medium">{{ trans('checkout.order_total') }}:</span>
              <span class="text-green-500 text-2xl font-bold" x-text="'EGP ' + calculateTotal().toFixed(2)"></span>
            </div>
          </div>

          <!-- Place Order Button -->
          <button 
            type="submit"
            :disabled="loading"
            class="w-full py-4 bg-orange-600 hover:bg-orange-700 text-white text-lg font-semibold rounded-lg transition duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-orange-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span x-show="!loading">{{ trans('checkout.place_order') }}</span>
            <span x-show="loading" class="flex items-center justify-center">
              <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ trans('checkout.processing') }}...
            </span>
          </button>

          <!-- Success Message -->
          <div x-show="success" 
               x-transition
               class="mt-4 p-4 bg-green-900 border border-green-600 rounded-lg text-green-200">
            <p class="font-semibold">{{ trans('checkout.order_success') }}</p>
            <p class="text-sm mt-1">{{ trans('checkout.order_id') }}: <span x-text="orderId"></span></p>
          </div>

        </form>
      </div>
    </div>

  </div>
</section>
@endsection