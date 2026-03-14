@extends('web.layouts.main')

@section('title')
  {{ trans('profile.page_title') }}
@endsection

@section('content')
@vite('resources/js/web/profile.js')
<x-navbar></x-navbar>

<div x-data="profilePage({{ Js::from([
  'name' => old('name', $user->name),
  'email' => old('email', $user->email),
  'phone' => old('phone', $user->phone),
  'insta_account' => old('insta_account', $user->insta_account),
  'city_id' => (string) old('city_id', $user->city_id),
  'address' => old('address', $user->address),
]) }}, '{{ route('users.profile.update') }}', '{{ route('users.orders.cancel') }}')">

<section class="py-16 md:py-24 bg-black min-h-screen">
  <div class="container mx-auto px-4">
    <h2 class="text-white text-center text-4xl md:text-5xl font-bold mb-16">{{ trans('profile.heading') }}</h2>

    <div class="max-w-4xl mx-auto">
        <div class="bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-3xl shadow-2xl border border-gray-700 p-6 md:p-10">
        <div class="flex flex-col sm:flex-row gap-3 mb-8">
          <button
            type="button"
            x-on:click="switchTab('personal')"
            :class="isActive('personal') ? 'bg-orange-500' : 'bg-gray-700 hover:bg-gray-600'"
            class="profile-tab-btn text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300"
          >
            {{ trans('profile.tabs.personal_info') }}
          </button>

          <button
            type="button"
            x-on:click="switchTab('orders')"
            :class="isActive('orders') ? 'bg-orange-500' : 'bg-gray-700 hover:bg-gray-600'"
            class="profile-tab-btn text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300"
          >
             {{ trans('profile.tabs.orders') }}
          </button>
        </div>

        @include('web.pages.users.partials.profile-form')
        @include('web.pages.users.partials.profile-orders')
      </div>
    </div>
  </div>
</section>

@include('web.pages.users.partials.profile-cancel-modal')
</div>
@endsection
