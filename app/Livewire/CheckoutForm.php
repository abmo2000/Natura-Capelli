<?php

namespace App\Livewire;

use App\Rules\PhoneValidationRule;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CheckoutForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $address = '';

    public string $city_id = '';

    public string $delivery_option = '';

    public string $payment_method = '';

    public string $insta_account = '';

    // ── City / Delivery State ──────────────────────────────────────
    public bool $showDeliveryOptions = false;

    public bool $selectedCityHasDiscussion = false;

    public float $deliveryPrice = 0.0;

    // ── UI State ───────────────────────────────────────────────────
    public bool $loading = false;

    public bool $success = false;

    public string $orderId = '';

    // ── Passed from view ───────────────────────────────────────────
    public float $total = 0.0;

    public bool $isFirstOrder = false;

    public bool $hasDeliveryOption = false;

    // ──────────────────────────────────────────────────────────────
    public function mount(): void
    {
        // ── Replaces OrderController@index ────────────────────────
        $orderSettings = getBuisnessSettings('order_settings');
        $buisnessSettings = getBuisnessSettings('buisness-info');

        $this->total = app(\App\Services\CartService::class)->getTotal();
        $this->isFirstOrder = ! Auth::user()->orders()->exists() && ($orderSettings?->allow_first_order_for_free);
        $this->hasDeliveryOption = (bool) ($orderSettings?->has_delivery_option);
        $this->instapayAccount = $buisnessSettings?->instapay_account ?? '';

        // ── Pre-fill from authenticated user ──────────────────────
        if (auth()->check()) {
            $user = auth()->user();
            $this->name = $user->name ?? '';
            $this->email = $user->email ?? '';
            $this->phone = $user->phone ?? '';
            $this->address = $user->address ?? '';
            $this->city_id = (string) ($user->city_id ?? '');
            $this->insta_account = $user->insta_account ?? '';
        }

        if ($this->city_id) {
            $this->syncCityDelivery($this->city_id);
        }
    }

    // ── Validation Rules ───────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => ['required', 'string', new PhoneValidationRule],
            'address' => 'nullable|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'delivery_option' => 'sometimes|in:proceed,discuss',
            'payment_method' => 'required|string',
            'insta_account' => [
                Rule::requiredIf(fn () => $this->payment_method === 'instapay'),
                'nullable',
                'string',
                'max:50',
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'city_id.required' => 'Please select a city.',
            'city_id.exists' => 'Selected city is invalid.',
            'payment_method.required' => 'Please select a payment method.',
            'insta_account.required' => 'InstaPay account is required when paying via InstaPay.',
        ];
    }

    // ── City Change ────────────────────────────────────────────────
    /**
     * Called via wire:change on the city select.
     * We receive the city ID, look up its data from getCities(),
     * and update delivery state — exactly like checkCityDeliveryOptions() in Alpine.
     */
    public function updatedCityId(string $value): void
    {
        $this->syncCityDelivery($value);
        $this->validateOnly('city_id');
    }

    protected function syncCityDelivery(string $cityId): void
    {
        if (! $cityId) {
            $this->showDeliveryOptions = false;
            $this->selectedCityHasDiscussion = false;
            $this->deliveryPrice = 0;
            $this->delivery_option = '';

            return;
        }

        // Find city from the helper (same data source as the blade view)
        $city = collect(getCities())->firstWhere('id', (int) $cityId);

        if (! $city) {
            return;
        }

        $this->selectedCityHasDiscussion = (bool) ($city['has_discussion_for_delivery'] ?? false);
        $this->deliveryPrice = (float) ($city['price'] ?? 0);
        $this->showDeliveryOptions = true;

        // Mirror Alpine logic: auto-set to 'proceed' if no discussion option
        if (! $this->selectedCityHasDiscussion) {
            $this->delivery_option = 'proceed';
        } else {
            $this->delivery_option = '';
        }
    }

    // ── Computed Properties ────────────────────────────────────────
    #[Computed]
    public function shouldShowDeliveryFee(): bool
    {
        return $this->deliveryPrice > 0 &&
               ($this->delivery_option !== 'discuss' ||
               ($this->showDeliveryOptions && ! $this->selectedCityHasDiscussion));
    }

    #[Computed]
    public function calculatedTotal(): float
    {
        $finalTotal = $this->total;

        if ($this->shouldShowDeliveryFee) {
            $finalTotal += $this->deliveryPrice;
        }

        // First order: delivery is free — subtract it back
        if ($finalTotal > $this->total && $this->isFirstOrder) {
            $finalTotal -= $this->deliveryPrice;
        }

        return $finalTotal;
    }

    // ── Real-time validation on field update ───────────────────────
    public function updatedPaymentMethod(): void
    {
        $this->validateOnly('payment_method');
    }

    public function updatedInstaAccount(): void
    {
        if ($this->payment_method === 'instapay') {
            $this->validateOnly('insta_account');
        }
    }

    // ── Submit ─────────────────────────────────────────────────────
    public function submit(OrderService $orderService): void
    {
        // Merge delivery_option default (mirrors prepareForValidation in FormRequest)
        if (! $this->delivery_option) {
            $this->delivery_option = 'proceed';
        }

        $this->validate();

        $this->loading = true;

        try {
            $result = $orderService->create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city_id' => $this->city_id,
                'delivery_option' => $this->delivery_option,
                'payment_method' => $this->payment_method,
                'insta_account' => $this->insta_account,
            ]);
            $order = $result['order'];
            $this->orderId = $order?->order_id ?? (string) ($order?->id ?? 'N/A');
            $this->success = true;

        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage() ?: 'An error occurred. Please try again.');
        } finally {
            $this->loading = false;
        }
    }

    // ── Render ─────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.checkout-form', [
            'cities' => getCities(),
            'calculatedTotal' => $this->calculatedTotal,
            'shouldShowDeliveryFee' => $this->shouldShowDeliveryFee,
        ]);
    }
}
