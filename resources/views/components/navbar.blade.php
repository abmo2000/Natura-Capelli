  <nav x-data="{
        open: false, 
        scrolled: false, 
        cartCount: {{ $cartCount }},
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.pageYOffset > 50;
            });
            window.addEventListener('cart-updated', (e) => {
                this.cartCount = e.detail.count;
            });
        }
     }" 
         :class="scrolled ? 'bg-gray-900/95 backdrop-blur-md shadow-lg' : 'bg-transparent'"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                  
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="inline-block">
                        <img class="w-12 h-12 rounded-full object-cover" src="{{ asset('assets/images/logos/logo.jpg') }}" alt="logo">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                    <a href="{{ route('about-us') }}" class="nav-link">About</a>
                    <a href="{{ route('shop') }}" class="nav-link">Shop</a>
                    <a href="#Routine" class="nav-link">Routine</a>
                    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                </div>

                <!-- CTA Button -->
                <div class="hidden  md:flex items-center space-x-10">
                    <!-- Cart Icon with Badge -->
                    <a href="{{ route('cart') }}" class="icon">
                        <i class="fas fa-shopping-bag text-2xl"></i>
                        <!-- Dynamic Cart Count -->
                         <span x-show="cartCount > 0"
                              x-text="cartCount"
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 scale-50"
                              x-transition:enter-end="opacity-100 scale-100"
                              class="absolute -top-2 -right-2 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-lg">
                        </span>
                    </a>

                    <!-- Language Selector -->
                    <div class="relative">
                        <select class="bg-white/5 backdrop-blur-md text-white border border-white/20 rounded-lg px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 cursor-pointer appearance-none hover:bg-white/10 transition-all">
                            <option value="en" class="bg-gray-900 text-white">English</option>
                            <option value="ar" class="bg-gray-900 text-white">العربية</option>
                        </select>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
               <div  class="md:hidden flex items-center space-x-4">
                    <a href="#" class="icon">
                        <i class="fas fa-shopping-bag text-2xl"></i>
                        <!-- Dynamic Cart Count -->
                        <span class="absolute -top-2 -right-2 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-lg">
                          {{ $cartCount }}
                        </span>
                    </a>

                <!-- Language Selector - Mobile -->
              <div class="relative">
                        <select class="bg-white/5 backdrop-blur-md text-white border border-white/20 rounded-lg px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 cursor-pointer appearance-none hover:bg-white/10 transition-all">
                            <option value="en" class="bg-gray-900 text-white">English</option>
                            <option value="ar" class="bg-gray-900 text-white">العربية</option>
                        </select>
               </div>


                    <button x-on:click="open = ! open" class="text-white hover:text-orange-100 focus:outline-none cursor-pointer">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
      <div x-show="open"  @click.away="open = false" x-transition class="md:hidden bg-footer backdrop-blur-md">
    <div class="px-4 pt-2 pb-4 space-y-2 bg-footer">
        <a href="{{ route('home') }}" class="block nav-link">Home</a>
        <a href="#about" class="block nav-link">About</a>
        <a href="{{ route('shop') }}" class="block nav-link">Shop</a>
        <a href="#Routine" class="block nav-link">Routine</a>
        <a href="{{ route('contact') }}" class="block nav-link">Contact</a>
    </div>
</div>
    </nav>