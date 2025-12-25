@extends('web.layouts.main')

@section('title')
Routine
@endsection

@section('content')
<x-navbar></x-navbar>

<section class="py-16 md:py-24 bg-black min-h-screen">
    <div class="container mx-auto px-4 max-w-7xl">
        
        {{-- Main Product Section --}}
        <div class="grid md:grid-cols-2 gap-8 md:gap-12 mb-16">
            {{-- Image Side --}}
            <div class="bg-zinc-900 rounded-lg overflow-hidden">
                <img 
                    src="{{ asset('storage/' .$routine->image) }}" 
                    alt="{{ $routine->title }}"
                    class="w-full h-full object-cover aspect-square"
                >
            </div>

            {{-- Details Side --}}
            <div class="flex flex-col justify-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    {{ $routine->title }}
                </h1>
                
                <div class="text-gray-300 text-lg leading-relaxed space-y-4">
                    {!! $routine->description !!}
                </div>
            </div>
        </div>

        {{-- Related Products Section --}}
        
        <div class="mt-20">
            <h2 class="text-3xl font-bold text-white mb-8 text-center">Related Products</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
                @foreach ($routine->products as $product )
                  <x-product :product="$product"></x-product> 
                @endforeach
               
            </div>
        </div>


    </div>
</section>