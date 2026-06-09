@extends('web.layouts.main')

@section('title')
Checkout
@endsection

@section('content')
<x-navbar></x-navbar>

<section class="checkout-page py-10 sm:py-14 md:py-20 bg-black min-h-screen">
    <div class="container mx-auto px-3 sm:px-4">
        <h2 class="text-white text-center text-2xl sm:text-3xl md:text-4xl font-bold mb-8 sm:mb-12">
            {{ trans('checkout.checkout') }}
        </h2>
        <div class="max-w-2xl mx-auto">
            <div class="bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-2xl sm:rounded-3xl shadow-xl border border-gray-700 p-4 sm:p-6 md:p-8">

                {{-- ← Livewire component dropped in here --}}
                @livewire('checkout-form')

            </div>
        </div>
    </div>
</section>
@endsection

