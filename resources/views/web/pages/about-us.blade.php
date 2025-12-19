@extends('web.layouts.main')

@section('title')
About us
@endsection

@section('content')

<x-navbar></x-navbar>

<section class="py-16 md:py-24 bg-black min-h-screen">
    <div class="container mx-auto px-4">
        <h2 class="text-white text-center text-4xl md:text-5xl font-bold mb-16">About Us</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center max-w-7xl mx-auto">
            <!-- Image Section -->
            <div class="order-2 lg:order-1">
                <div class="relative rounded-lg overflow-hidden shadow-2xl">
                    <img 
                        src="{{ asset('assets/images/hair.jpg') }}" 
                        alt="Woman at beach sunset" 
                        class="w-full h-auto object-cover"
                    >
                    <!-- Optional overlay effect -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="order-1 lg:order-2 text-gray-300 space-y-6">
                <p class="text-base md:text-lg leading-relaxed">
                    Fridal is a privately owned Egyptian company established in 1957 with its headquarters located in 6th of October city.

                     The company started by growing Aromatic plants in the fifties in response to the increasing demand of essential oils. Accordingly, Fridal became one of the biggest producers and exporters of essential oils, absolutes, concretes as well as Herbs & Spices.
                     Fridal found itself fully involved in the perfumery world which led to a deeper interest in growing floral plants for concretes and absolutes. This was when Fridal increased its activities by producing perfumes, flavors & fragrances in the early eighties. Throughout the following ten years Fridal expanded its range of products in all its divisions. It also became one of the major producers and exporters of herbs & spices especially to the United States of America.
                      Today, Fridal is a major supplier of a wide range of products and offers an outstanding worldwide service to its customers. It has also started its expansions within the FMCG market through household and personal care products.

                </p>
            </div>
        </div>
    </div>
</section>
@endsection