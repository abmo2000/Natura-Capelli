
@props(['header_bg_image' => ''])
<section class="relative h-[500px] md:h-[600px] lg:h-[700px] overflow-hidden" x-data="{cartCount: {{ $cartCount }}}">

    <!-- Background Image with Overlay -->
    @if($header_bg_image)
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset($header_bg_image) }}" 
                alt="Header background"
                class="w-full h-full object-[center_30%]"
            >
            <div class="absolute inset-0 bg-black/30 z-10"></div>
        </div>
    @endif

    <!-- Hero Content -->
    <div class="relative z-20 h-full">
        {{ $slot }}
    </div>
</section>