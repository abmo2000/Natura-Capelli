<div>
    {{-- ── Flash Error ── --}}
    @if(session('error'))
    <div class="mb-4 bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    {{-- ── Success Banner (top) ── --}}
    @if($success)
    <div class="mb-4 bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ trans('checkout.order_success') }}</span>
        </div>
    </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-5 sm:space-y-6">
        @csrf

        {{-- ── Full Name ── --}}
        <div>
            <label for="name" class="block text-gray-300 text-sm font-medium mb-2">
                {{ trans('checkout.full_name') }} <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="name"
                wire:model.blur="name"
                placeholder="{{ trans('checkout.full_name_placeholder') ?? 'John Doe' }}"
                class="w-full px-4 py-2.5 sm:py-3 text-sm sm:text-base bg-gray-900 border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition {{ $errors->has('name') ? 'border-red-500' : 'border-gray-600' }}"
            >
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Email ── --}}
        <div>
            <label for="email" class="block text-gray-300 text-sm font-medium mb-2">
                {{ trans('checkout.email_address') }} <span class="text-red-500">*</span>
            </label>
            <input
                type="email"
                id="email"
                wire:model.blur="email"
                placeholder="{{ trans('checkout.email_placeholder') ?? 'john@example.com' }}"
                class="w-full px-4 py-2.5 sm:py-3 text-sm sm:text-base bg-gray-900 border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition {{ $errors->has('email') ? 'border-red-500' : 'border-gray-600' }}"
            >
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Phone ── --}}
        <div>
            <label for="phone" class="block text-gray-300 text-sm font-medium mb-2">
                {{ trans('checkout.phone_number') }} <span class="text-red-500">*</span>
            </label>
            <input
                type="tel"
                id="phoneNumber"
                placeholder="{{ trans('checkout.phone_placeholder') ?? '+201148992811' }}"
                class="w-full px-4 py-2.5 sm:py-3 text-sm sm:text-base bg-gray-900 border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-600' }}"
            >
            @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Address ── --}}
        <div>
            <label for="address" class="block text-gray-300 text-sm font-medium mb-2">
                {{ trans('checkout.address') }} <span class="text-red-500">*</span>
            </label>
            <textarea
                id="address"
                wire:model.blur="address"
                placeholder="{{ trans('checkout.address_placeholder') ?? 'مثال : شارع النصر ، مدينة نصر ، القاهرة' }}"
                rows="3"
                class="w-full px-4 py-2.5 sm:py-3 text-sm sm:text-base bg-gray-900 border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none {{ $errors->has('address') ? 'border-red-500' : 'border-gray-600' }}"
            ></textarea>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── City ── --}}
        <div>
            <label for="city_id" class="block text-gray-300 text-sm font-medium mb-2">
                {{ trans('checkout.city') }} <span class="text-red-500">*</span>
            </label>
            <select
                id="city_id"
                wire:model.live="city_id"
                class="w-full px-4 py-2.5 sm:py-3 text-sm sm:text-base bg-gray-900 border rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition {{ $errors->has('city_id') ? 'border-red-500' : 'border-gray-600' }}"
            >
                <option value="">{{ trans('checkout.city_placeholder') ?? 'Select your city' }}</option>
                @foreach($cities as $city)
                    <option value="{{ $city['id'] }}" {{ (string)$city['id'] === $city_id ? 'selected' : '' }}>
                        {{ $city['value'] }}
                    </option>
                @endforeach
            </select>
            @error('city_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Delivery Options ── --}}
        @if($hasDeliveryOption)
            @if($showDeliveryOptions && $selectedCityHasDiscussion)
            <div class="space-y-3">
                <label class="block text-gray-300 text-sm font-medium mb-3">
                    {{ trans('checkout.delivery_options') }}
                </label>
                <div>
                    <label class="flex items-start p-3 sm:p-4 bg-gray-900 border rounded-lg cursor-pointer hover:border-orange-500 transition {{ $delivery_option === 'discuss' ? 'border-orange-500' : 'border-gray-600' }}">
                        <input type="radio" wire:model.live="delivery_option" value="discuss" class="mt-1 text-orange-500 focus:ring-orange-500 focus:ring-2">
                        <div class="ms-3">
                            <p class="text-white font-medium">{{ trans('checkout.discuss_delivery') }}</p>
                            <p class="text-gray-400 text-sm mt-1">{{ trans('checkout.discuss_delivery_desc') }}</p>
                        </div>
                    </label>
                </div>
                @if($delivery_option === 'discuss')
                <div>
                    <label class="flex items-start p-3 sm:p-4 bg-gray-900 border rounded-lg cursor-pointer hover:border-orange-500 transition {{ $delivery_option === 'proceed' ? 'border-orange-500' : 'border-gray-600' }}">
                        <input type="radio" wire:model.live="delivery_option" value="proceed" class="mt-1 text-orange-500 focus:ring-orange-500 focus:ring-2">
                        <div class="ms-3 flex-1">
                            <p class="text-white font-medium">{{ trans('checkout.proceed_with_delivery') }}</p>
                            <p class="text-gray-400 text-sm mt-1">
                                {{ trans('checkout.proceed_delivery_desc') }}
                                @if($deliveryPrice > 0)
                                    @if($isFirstOrder)
                                        <span class="text-gray-500 line-through mr-2">(+ EGP {{ number_format($deliveryPrice, 2) }})</span>
                                        <span class="text-green-400 font-semibold">(FREE Delivery - First Order!)</span>
                                    @else
                                        <span class="text-orange-400 font-semibold">(+ EGP {{ number_format($deliveryPrice, 2) }})</span>
                                    @endif
                                @endif
                            </p>
                        </div>
                    </label>
                </div>
                @endif
                @error('delivery_option')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            @if($showDeliveryOptions && !$selectedCityHasDiscussion && $deliveryPrice > 0)
            <div class="p-4 bg-blue-900 bg-opacity-30 border border-blue-600 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 me-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        @if($isFirstOrder)
                            <span class="text-blue-300">First Order - Free Delivery!</span>
                            <p class="text-xs mt-1 text-green-300">
                                {{ trans('checkout.delivery_fee_auto_added') }}:
                                <span class="line-through text-gray-400 mr-2">EGP {{ number_format($deliveryPrice, 2) }}</span>
                                <span class="font-semibold text-green-400">FREE</span>
                            </p>
                        @else
                            <span class="text-blue-300">{{ trans('checkout.delivery_included') }}</span>
                            <p class="text-xs mt-1 text-blue-300">
                                {{ trans('checkout.delivery_fee_auto_added') }}:
                                <span class="font-semibold">EGP {{ number_format($deliveryPrice, 2) }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        @else
            @if($deliveryPrice > 0)
            <div class="p-4 bg-blue-900 bg-opacity-30 border border-blue-600 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 me-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        @if($isFirstOrder)
                            <span class="text-blue-300">First Order - Free Delivery!</span>
                            <p class="text-xs mt-1 text-green-300">
                                {{ trans('checkout.delivery_fee_auto_added') }}:
                                <span class="line-through text-gray-400 mr-2">EGP {{ number_format($deliveryPrice, 2) }}</span>
                                <span class="font-semibold text-green-400">FREE</span>
                            </p>
                        @else
                            <span class="text-blue-300">{{ trans('checkout.delivery_included') }}</span>
                            <p class="text-xs mt-1 text-blue-300">
                                {{ trans('checkout.delivery_fee_auto_added') }}:
                                <span class="font-semibold">EGP {{ number_format($deliveryPrice, 2) }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @endif

        {{-- ── Payment Method ── --}}
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-4">
                {{ trans('checkout.payment_method') }} <span class="text-red-500">*</span>
            </label>
            <div class="mb-3">
                <label class="flex items-start p-3 sm:p-4 bg-gray-900 border rounded-lg cursor-pointer hover:border-orange-500 transition {{ $payment_method === 'cash_on_delivery' ? 'border-orange-500' : 'border-gray-600' }}">
                    <input type="radio" wire:model.live="payment_method" value="cash_on_delivery" class="mt-1 text-orange-500 focus:ring-orange-500 focus:ring-2">
                    <div class="ms-3">
                        <p class="text-white font-medium">{{ trans('checkout.cash_on_delivery') }}</p>
                        <p class="text-gray-400 text-sm mt-1">{{ trans('checkout.pay_when_receive') }}</p>
                    </div>
                </label>
            </div>
            <div>
                <label class="flex items-start p-3 sm:p-4 bg-gray-900 border rounded-lg cursor-pointer hover:border-orange-500 transition {{ $payment_method === 'instapay' ? 'border-orange-500' : 'border-gray-600' }}">
                    <input type="radio" wire:model.live="payment_method" value="instapay" class="mt-1 text-orange-500 focus:ring-orange-500 focus:ring-2">
                    <div class="ms-3">
                        <p class="text-white font-medium">{{ trans('checkout.instapay') }}</p>
                        <p class="text-gray-400 text-sm mt-1">{{ trans('checkout.pay_instantly') }}</p>
                    </div>
                </label>
            </div>
            @error('payment_method')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── InstaPay Account ── --}}
        @if($payment_method === 'instapay')
        <div>
            <label for="insta_account" class="block text-gray-300 text-sm font-medium mb-2">
                {{ trans('checkout.instapay_account') }} <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="insta_account"
                wire:model.blur="insta_account"
                placeholder="{{ trans('checkout.instapay_account_placeholder') ?? 'example@instapay' }}"
                class="w-full px-4 py-2.5 sm:py-3 text-sm sm:text-base bg-gray-900 border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition {{ $errors->has('insta_account') ? 'border-red-500' : 'border-gray-600' }}"
            >
            @error('insta_account')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        @endif

        {{-- ── Order Total ── --}}
        <div class="pt-6 border-t border-gray-600 space-y-2">
            @if($delivery_option !== 'discuss')
            <div class="flex justify-between items-center">
                <span class="text-gray-400">{{ trans('checkout.delivery_fee') }}:</span>
                @if($isFirstOrder)
                    <span>
                        <span class="line-through text-gray-500 mr-2">EGP {{ number_format($deliveryPrice, 2) }}</span>
                        <span class="text-green-400 font-semibold">FREE</span>
                    </span>
                @else
                    <span class="text-gray-400">EGP {{ number_format($deliveryPrice, 2) }}</span>
                @endif
            </div>
            @endif
            <div class="flex justify-between items-center">
                <span class="text-gray-300 text-base sm:text-lg font-medium">{{ trans('checkout.order_total') }}:</span>
                <span class="text-green-500 text-xl sm:text-2xl font-bold">
                    EGP {{ number_format($calculatedTotal, 2) }}
                </span>
            </div>
        </div>

        {{-- ── Submit Button ── --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full py-3.5 sm:py-4 bg-orange-600 hover:bg-orange-700 text-white text-base sm:text-lg font-semibold rounded-lg cursor-pointer transition duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-orange-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span wire:loading.remove wire:target="submit">{{ trans('checkout.place_order') }}</span>
            <span wire:loading wire:target="submit" class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 me-3" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ trans('checkout.processing') }}...
            </span>
        </button>

        {{-- ── Success Message (bottom) ── --}}
      @if($success)
            <div
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                wire:key="success-modal"
            >
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

                {{-- Modal --}}
                <div class="relative w-full max-w-md bg-gray-800 border border-gray-700 rounded-2xl shadow-2xl p-8 text-center">

                    {{-- Icon --}}
                    <div class="flex items-center justify-center w-16 h-16 rounded-full bg-green-900 border border-green-700 mx-auto mb-5">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-xl font-bold text-white mb-2">
                        {{ trans('checkout.order_success') }}
                    </h3>

                    {{-- Order ID --}}
                    <p class="text-gray-400 text-sm mb-1">
                        {{ trans('checkout.order_id') }}:
                        <span class="text-orange-400 font-semibold">#{{ $orderId }}</span>
                    </p>

                    {{-- Email notice --}}
                    <p class="text-gray-400 text-sm mb-3">
                        {{ trans('checkout.order_email_sent') ?? 'A confirmation email has been sent to' }}
                        <span class="text-white font-medium">{{ $email }}</span>
                        {{ trans('checkout.order_email_sent_suffix') ?? 'with your order details.' }}
                    </p>

                    {{-- Delivery discussion notice --}}
                    @if($delivery_option === 'discuss')
                    <div class="flex items-start gap-3 bg-orange-950/50 border border-orange-700/50 rounded-xl p-3 mb-6 text-left">
                        <svg class="w-5 h-5 text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-orange-300 text-sm">
                            {{ trans('checkout.discuss_delivery_modal') ?? 'Our team will contact you shortly to discuss the delivery details for your area.' }}
                        </p>
                    </div>
                    @else
                    <div class="mb-6"></div>
                    @endif

                    {{-- CTA --}}
                    
                        <a href="{{ route('cart') }}"
                        class="inline-flex items-center justify-center w-full py-3 px-6 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl transition duration-200"
                    >
                        {{ trans('checkout.continue_shopping') ?? 'Continue Shopping' }}
                    </a>

                </div>
            </div>
     @endif

    </form>
</div>

@script
<script>
(function() {
    function getFullPhone(input, iti) {
        const countryData = iti.getSelectedCountryData();
        const dialCode = countryData && countryData.dialCode ? '+' + countryData.dialCode : '';
        const national = input.value.trim().replace(/^0+/, '');

        return dialCode && national ? dialCode + national : input.value.trim();
    }

    function initPhoneInput() {
        const input = document.querySelector('#phoneNumber');
        if (!input || !window._checkoutIti) return;

        // Keep existing instance to avoid losing focus during Livewire updates.
        if (input._iti) {
            return;
        }

        try {
            const iti = window._checkoutIti.intlTelInput(input, {
                initialCountry: 'eg',
                preferredCountries: ['eg', 'sa', 'ae'],
                separateDialCode: true,
                nationalMode: false,
                autoPlaceholder: 'polite',
            });

            input._iti = iti;

            // Use current Livewire value (correct on re-renders after validation)
            const currentPhone = $wire.phone;
            if (currentPhone && /^\+?\d/.test(currentPhone)) {
                iti.setNumber(currentPhone);
            }

            // While typing, update Livewire state without firing a request each key.
            const syncHandler = () => {
                $wire.set('phone', getFullPhone(input, iti), false);
            };

            // Send the latest value on blur so validation and submit always see final phone.
            const blurHandler = () => {
                $wire.set('phone', getFullPhone(input, iti));
            };

            input.addEventListener('input', syncHandler);
            input.addEventListener('countrychange', syncHandler);
            input.addEventListener('blur', blurHandler);
        } catch (e) {
            console.error('Error initializing intl-tel-input:', e);
        }
    }

    // Wait for intlTelInput to be available from app.js
    const checkInterval = setInterval(() => {
        if (window._checkoutIti) {
            clearInterval(checkInterval);
            initPhoneInput();
        }
    }, 50);

    // Reinit on Livewire updates (validation errors, etc.)
    document.addEventListener('livewire:updated', initPhoneInput);
    Livewire.hook('morph.updated', initPhoneInput);
})();
</script>
@endscript
