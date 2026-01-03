<nav x-data="{
        open: false, 
        userDropdown: false,
        scrolled: false, 
        cartCount: {{ $cartCount }},
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.pageYOffset > 50;
            });
            window.addEventListener('cart-updated', (e) => {
                this.cartCount = e.detail.count;
            });
        },
        scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                const navHeight = 80;
                const elementPosition = section.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - navHeight;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                this.open = false;
            }
        }
     }" 
         :class="scrolled ? 'bg-gray-900/95 backdrop-blur-md shadow-lg' : 'bg-transparent'"
         class="fixed top-0 start-0 end-0 z-50 transition-all duration-300">
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
                    <a href="{{ route('home') }}" class="nav-link">{{ trans('navbar.home') }}</a>
                    @if(Request::is('/'))    
                    <a href="#about-section" @click.prevent="scrollToSection('about-section')" class="nav-link cursor-pointer">{{ trans('navbar.about') }}</a>
                    @endif
                    <a href="{{ route('shop') }}" class="nav-link">{{ trans('navbar.shop') }}</a>
                    <a href="{{ route('routines.index') }}" class="nav-link">{{ trans('navbar.routine') }}</a>
                    <a href="{{ route('contact') }}" class="nav-link">{{ trans('navbar.contact') }}</a>
                    @if(Request::is('/'))    
                    <a href="#fearured-products" @click.prevent="scrollToSection('fearured-products')" class="nav-link cursor-pointer">{{ trans('navbar.featured-products') }}</a>
                    @endif
                    <a href="{{ route('about-us') }}" class="nav-link">{{ trans('navbar.concepts') }}</a>
                </div>

                <!-- CTA Button -->
                <div class="hidden md:flex items-center space-x-10">
                    <!-- Cart Icon with Badge -->
                    <a href="{{ route('cart') }}" class="icon">
                        <i class="fas fa-shopping-bag text-2xl"></i>
                        <span x-show="cartCount > 0"
                              x-text="cartCount"
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 scale-50"
                              x-transition:enter-end="opacity-100 scale-100"
                              class="absolute -top-2 -end-2 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-lg">
                        </span>
                    </a>

                    <!-- User Dropdown -->
                    @auth    
                    <div class="relative" @click.away="userDropdown = false">
                        <button @click="userDropdown = !userDropdown" 
                                class="flex items-center justify-center w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 transition-all focus:outline-none focus:ring-2 focus:ring-orange-100">
                            <i class="fas fa-user text-white text-lg"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userDropdown"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute end-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            
                            <a href="" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-user-circle w-4"></i>
                                <span>Profile</span>
                            </a>
                            
                            <div class="border-t border-gray-200 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth

                    <!-- Language Selector -->
                    <div class="relative">
                        <select 
                            x-on:change="window.location.href = $event.target.value" 
                            class="bg-white/5 backdrop-blur-md text-white border border-white/20 rounded-lg px-4 py-2 pe-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 cursor-pointer appearance-none hover:bg-white/10 transition-all"
                        >
                            <option value="{{ route('lang-switch', 'en') }}" {{ app()->getLocale() === 'en' ? 'selected' : '' }} class="bg-gray-900 text-white">
                                English
                            </option>
                            
                            <option value="{{ route('lang-switch', 'ar') }}" {{ app()->getLocale() === 'ar' ? 'selected' : '' }} class="bg-gray-900 text-white">
                                العربية
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
               <div class="md:hidden flex items-center space-x-4">
                    <a href="{{ route('cart') }}" class="icon">
                        <i class="fas fa-shopping-bag text-2xl"></i>
                        <span x-show="cartCount > 0"
                              x-text="cartCount"
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 scale-50"
                              x-transition:enter-end="opacity-100 scale-100"
                              class="absolute -top-2 -end-2 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-lg">
                        </span>
                    </a>

                    <!-- User Dropdown - Mobile -->
                    @auth
                        
                    <div class="relative" @click.away="userDropdown = false">
                        <button @click="userDropdown = !userDropdown" 
                                class="flex items-center justify-center w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 transition-all focus:outline-none">
                            <i class="fas fa-user text-white text-lg"></i>
                        </button>

                        <!-- Dropdown Menu - Mobile -->
                        <div x-show="userDropdown"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute end-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            
                            <a href="" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-user-circle w-4"></i>
                                <span>Profile</span>
                            </a>
                            
                            <div class="border-t border-gray-200 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth

                    <!-- Language Selector - Mobile -->
                    <div class="relative">
                        <select 
                            x-on:change="window.location.href = $event.target.value" 
                            class="bg-white/5 backdrop-blur-md text-white border border-white/20 rounded-lg px-4 py-2 pe-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 cursor-pointer appearance-none hover:bg-white/10 transition-all"
                        >
                            <option value="{{ route('lang-switch', 'en') }}" {{ app()->getLocale() === 'en' ? 'selected' : '' }} class="bg-gray-900 text-white">
                                English
                            </option>
                            
                            <option value="{{ route('lang-switch', 'ar') }}" {{ app()->getLocale() === 'ar' ? 'selected' : '' }} class="bg-gray-900 text-white">
                                العربية
                            </option>
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
        <div x-show="open" @click.away="open = false" x-transition class="md:hidden bg-footer backdrop-blur-md">
            <div class="px-4 pt-2 pb-4 space-y-2 bg-footer">
                <a href="{{ route('home') }}" class="block nav-link">{{ trans('navbar.home') }}</a>
                @if (Request::is('/'))    
                <a href="#about-section" @click.prevent="scrollToSection('about-section')" class="block nav-link cursor-pointer">{{ trans('navbar.about') }}</a>
                @endif
               <a href="{{ route('about-us') }}" class="block nav-link">{{ trans('navbar.concepts') }}</a>

                <a href="{{ route('shop') }}" class="block nav-link">{{ trans('navbar.shop') }}</a>
                <a href="{{ route('routines.index') }}" class="block nav-link">{{ trans('navbar.routine') }}</a>
                <a href="{{ route('contact') }}" class="block nav-link">{{ trans('navbar.contact') }}</a>
                 @if(Request::is('/'))  
                <a href="#fearured-products" @click.prevent="scrollToSection('fearured-products')" class="block nav-link cursor-pointer">{{ trans('navbar.featured-products') }}</a>
                @endif
            </div>
        </div>
    </nav>