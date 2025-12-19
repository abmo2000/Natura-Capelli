
@props(['header_bg_image' => ''])
<section class="relative min-h-screen" x-data="{cartCount: {{ $cartCount }}}"  >

    <!-- Background Image with Overlay -->
    @if($header_bg_image)
   <div class="absolute inset-0 z-0 bg-center bg-cover bg-no-repeat" 
     style="background-image: url('{{ asset($header_bg_image) }}');">
    <div class="absolute inset-0 bg-black/50 z-10"></div>
</div>
    @endif


    <!-- Hero Content -->
    {{ $slot }}
</section>