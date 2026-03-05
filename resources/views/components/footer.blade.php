<!-- footer -->
<footer class="bg-footer">
    <!-- Main Footer Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8 justify-between items-center pb-6 border-b border-gray-700">
            <!-- Logo -->
            <img class="w-16 h-16 rounded-full object-cover shadow-lg" src="{{ asset('assets/images/logos/logo.jpg') }}" alt="logo">

            <!-- Middle Section - Navigation -->
            <div class="flex flex-col gap-3 items-center">
                <h3 class="text-white font-bold text-xl tracking-wide">Qaid-eg</h3>
                <div class="flex gap-6">
                    <a href="{{ route('home') }}" class="footer-link">{{ trans('navbar.home') }}</a>
                    <a href="{{ route('shop') }}" class="footer-link">{{ trans('navbar.shop') }}</a>
                    <a href="{{ route('contact') }}" class="footer-link">{{ trans('navbar.contact') }}</a>
                </div>
            </div>

            <!-- Social Media Icons -->
            <div class="flex gap-4 items-center">
                <!-- Facebook -->
                <a href="{{ getBuisnessSettings('buisness-info')?->facebook_link }}" class="icon" target="_blank">
                    <i class="fab fa-facebook text-lg"></i>
                </a>
                
                <!-- Instagram -->
                <a href="{{ getBuisnessSettings('buisness-info')?->instagram_link }}" class="icon" target="_blank">
                    <i class="fab fa-instagram text-lg"></i>
                </a>
                
        
                <!-- Phone -->
                <a href={{ "https://wa.me/+20" . getBuisnessSettings('buisness-info')?->whatsapp_number}}  target="_blank" class="icon">
                    <i class="fab fa-whatsapp text-lg"></i>
                </a>
            </div>
        </div>

        <!-- Bottom Section - Copyright & Credits -->
        <div class="pt-6 flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
            <p class="text-gray-500">
                Copyright © <span id="currentYear">2026</span> Qaid-eg. All rights reserved.
            </p>
            <p class="text-gray-500">
                Developed by <a href="#" class="text-white font-semibold hover:text-gray-300 transition">Qaid-eg</a>
            </p>
        </div>
    </div>
</footer>