@extends('web.layouts.main')

@section('title')
  Profile
@endsection

@section('content')
@vite('resources/js/web/profile.js')
<x-navbar></x-navbar>

<section class="py-16 md:py-24 bg-black min-h-screen">
  <div class="container mx-auto px-4">
    <h2 class="text-white text-center text-4xl md:text-5xl font-bold mb-16">{{ trans('auth.profile') ?? 'My Profile' }}</h2>

    <div class="max-w-4xl mx-auto">
        <div x-data="profilePage({{ Js::from([
          'name' => old('name', $user->name),
          'email' => old('email', $user->email),
          'phone' => old('phone', $user->phone),
          'insta_account' => old('insta_account', $user->insta_account),
          'city_id' => (string) old('city_id', $user->city_id),
          'address' => old('address', $user->address),
        ]) }}, '{{ route('users.profile.update') }}')" class="bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-3xl shadow-2xl border border-gray-700 p-6 md:p-10">
        <div class="flex flex-col sm:flex-row gap-3 mb-8">
          <button
            type="button"
            x-on:click="switchTab('personal')"
            :class="isActive('personal') ? 'bg-orange-500' : 'bg-gray-700 hover:bg-gray-600'"
            class="profile-tab-btn text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300"
          >
            Personal Informations
          </button>

          <button
            type="button"
            x-on:click="switchTab('orders')"
            :class="isActive('orders') ? 'bg-orange-500' : 'bg-gray-700 hover:bg-gray-600'"
            class="profile-tab-btn text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300"
          >
            User Orders
          </button>
        </div>

        <div x-show="isActive('personal')" class="profile-tab-panel space-y-6">
          <h3 class="text-2xl text-white font-bold">Personal Informations</h3>

          <div x-show="successMessage" x-transition class="bg-green-900/40 border border-green-700 text-green-200 px-4 py-3 rounded-lg" x-text="successMessage"></div>
          <div x-show="errorMessage" x-transition class="bg-red-900/40 border border-red-700 text-red-200 px-4 py-3 rounded-lg" x-text="errorMessage"></div>

          <form @submit.prevent="submitProfile" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="name" class="block text-gray-300 text-sm font-medium mb-2">Name</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  x-model="form.name"
                  @blur="validateField('name')"
                  required
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                  :class="{ 'border-red-500': errors.name }"
                >
                <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-sm mt-1"></p>
              </div>

              <div>
                <label for="email" class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  x-model="form.email"
                  @blur="validateField('email')"
                  required
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                  :class="{ 'border-red-500': errors.email }"
                >
                <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-sm mt-1"></p>
              </div>

              <div>
                <label for="phone" class="block text-gray-300 text-sm font-medium mb-2">Phone</label>
                <input
                  type="tel"
                  id="phone"
                  name="phone"
                  value="{{ old('phone', $user->phone) }}"
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                >
                <p x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-sm mt-1"></p>
              </div>

              <div>
                <label for="insta_account" class="block text-gray-300 text-sm font-medium mb-2">InstaPay Account</label>
                <input
                  type="text"
                  id="insta_account"
                  name="insta_account"
                  x-model="form.insta_account"
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                  :class="{ 'border-red-500': errors.insta_account }"
                  placeholder="example@instapay"
                >
                <p x-show="errors.insta_account" x-text="errors.insta_account" class="text-red-500 text-sm mt-1"></p>
              </div>

             
            </div>
             <div>
                <label for="city_id" class="block text-gray-300 text-sm font-medium mb-2">City</label>
                <select
                  id="city_id"
                  name="city_id"
                  x-model="form.city_id"
                  @change="validateField('city_id')"
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                  :class="{ 'border-red-500': errors.city_id }"
                >
                  <option value="">Select your city</option>
                  @foreach(getCities() as $city)
                    <option value="{{ (string) $city['id'] }}">
                      {{ $city['value'] }}
                    </option>
                  @endforeach
                </select>
                <p x-show="errors.city_id" x-text="errors.city_id" class="text-red-500 text-sm mt-1"></p>
              </div>

            <div>
              <label for="address" class="block text-gray-300 text-sm font-medium mb-2">Address</label>
              <textarea
                id="address"
                name="address"
                x-model="form.address"
                rows="3"
                class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none"
                :class="{ 'border-red-500': errors.address }"
              ></textarea>
              <p x-show="errors.address" x-text="errors.address" class="text-red-500 text-sm mt-1"></p>
            </div>

            <button
              type="submit"
              :disabled="loading"
              class="bg-orange-500 w-full md:w-auto cursor-pointer hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-orange-500/50 transition-all duration-300"
              :class="{ 'opacity-60 cursor-not-allowed': loading }"
            >
              <span x-show="!loading">Update Profile</span>
              <span x-show="loading">Updating...</span>
            </button>
          </form>
        </div>

        <div x-show="isActive('orders')" x-cloak class="profile-tab-panel">
          <h3 class="text-2xl text-white font-bold mb-6">Orders</h3>

          @if(session('order_status'))
            <div class="mb-4 bg-green-900/40 border border-green-700 text-green-200 px-4 py-3 rounded-lg">
              {{ session('order_status') }}
            </div>
          @endif

          @if(session('order_error'))
            <div class="mb-4 bg-red-900/40 border border-red-700 text-red-200 px-4 py-3 rounded-lg">
              {{ session('order_error') }}
            </div>
          @endif

          @if($orders->count())
            <div class="overflow-x-auto border border-gray-700 rounded-xl">
              <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-900">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Order ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                  @foreach($orders as $order)
                    <tr class="hover:bg-gray-750 transition">
                      <td class="px-4 py-3 text-sm text-white">{{ $order->order_id ?? ('#' . $order->id) }}</td>
                      <td class="px-4 py-3 text-sm text-gray-300">{{ $order->created_at?->format('Y-m-d h:i A') }}</td>
                      <td class="px-4 py-3 text-sm">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 uppercase">
                          {{ $order->status }}
                        </span>
                      </td>
                      <td class="px-4 py-3 text-sm text-white">EGP {{ number_format((float) $order->amount, 2) }}</td>
                      <td class="px-4 py-3 text-sm text-white">
                        @if($order->status === 'pending')
                          <button
                            type="button"
                            onclick="openCancelModal('{{ route('users.orders.cancel', $order) }}', '{{ $order->order_id ?? ('#' . $order->id) }}')"
                            class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-2 px-3 rounded-lg transition"
                          >
                            Cancel Order
                          </button>
                        @else
                          <span class="text-gray-400 text-xs">-</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="mt-6 [&_nav]:text-gray-200 [&_span]:text-gray-300">
              {{ $orders->links() }}
            </div>
          @else
            <div class="bg-gray-900 border border-gray-700 rounded-xl p-6 text-center text-gray-300">
              No orders found yet.
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<div id="cancelOrderModal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/70" onclick="closeCancelModal()"></div>

  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-gray-900 border border-gray-700 rounded-2xl p-6 shadow-2xl">
      <h4 class="text-white text-xl font-bold mb-2">Confirm Cancellation</h4>
      <p class="text-gray-300 mb-6">Are you sure you want to cancel order <span id="cancelOrderNumber" class="font-semibold text-white"></span>?</p>

      <div class="flex gap-3 justify-end">
        <button
          type="button"
          onclick="closeCancelModal()"
          class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition"
        >
          Keep Order
        </button>

        <form id="cancelOrderForm" method="POST" action="">
          @csrf
          @method('PATCH')
          <button
            type="submit"
            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition"
          >
            Yes, Cancel
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function openCancelModal(actionUrl, orderNumber) {
    const modal = document.getElementById('cancelOrderModal');
    const form = document.getElementById('cancelOrderForm');
    const orderNumberLabel = document.getElementById('cancelOrderNumber');

    form.action = actionUrl;
    orderNumberLabel.textContent = orderNumber;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeCancelModal() {
    const modal = document.getElementById('cancelOrderModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
  }
</script>
@endsection
