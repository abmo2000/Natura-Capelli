@extends('web.layouts.main')

@section('title')
shop
@endsection

@section('content')

<x-navbar>

</x-navbar>

<section class="py-16 md:py-24 bg-black" x-data="productShop()">
    <div class="container mx-auto px-4">
        <h2 class="text-center font-bold text-white text-4xl mb-2">{{ trans('shop.products') }}</h2>
        <p class="text-gray-400 text-center mb-12">{{ trans('shop.discover-products') }}</p>

        <!-- Navigation Tabs -->
        <div class="flex justify-center gap-4 mb-8 flex-wrap">
            <button 
                @click="productType = 'products'; applyFilters()"
                :class="productType === 'products' ? 'bg-orange-400 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'"
                class="px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                Products 
            </button>
            <button 
                @click="productType = 'packages'; applyFilters()"
                :class="productType === 'packages' ? 'bg-orange-400 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'"
                class="px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                  Packages 
            </button>

            <button 
                @click="productType = 'trials'; applyFilters()"
                :class="productType === 'trials' ? 'bg-orange-400 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'"
                class="px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                  Trials
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Sidebar Filter -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-gray-900 rounded-lg p-6 sticky top-4">
                    <h3 class="text-white font-bold text-xl mb-6">{{ trans('shop.filters') }}</h3>
                
                    <!-- Category Filter -->
                    <div class="mb-8">
                        <h4 class="text-white font-semibold mb-4">{{ trans('shop.categories') }}</h4>
                        <div class="space-y-3">
                             <template x-for="category in categories" :key="category.id">
                                <label class="flex items-center text-gray-300 hover:text-white cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        :value="category.id"
                                        x-model="selectedCategories"
                                        @change="applyFilters()"
                                        class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500 focus:ring-offset-gray-900">
                                    <span class="ms-3" x-text="category.title"></span>
                                </label>
                            </template>
                            
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-white font-semibold mb-4">{{ trans('shop.routine') }}</h4>
                        <div class="space-y-3">
                             <template x-for="routine in routines" :key="routine.id">
                                <label class="flex items-center text-gray-300 hover:text-white cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        :value="routine.id"
                                        x-model="selectedRoutines"
                                        @change="applyFilters()"
                                        class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500 focus:ring-offset-gray-900">
                                    <span class="ms-3" x-text="routine.title"></span>
                                </label>
                            </template>
                            
                        </div>
                    </div>

                    <!-- Apply Filter Button -->
                    <button 
                        @click="resetFilters()"
                        class="w-full bg-gray-800 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition-all mb-3">
                        Reset Filters
                    </button>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                <!-- Sort and View Options -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                     <p class="text-gray-400">
                        {{ trans('shop.showing') }} <span x-text="((currentPage - 1) * perPage) + 1"></span>-<span x-text="Math.min(currentPage * perPage, totalProducts)"></span> 
                         {{ trans('shop.of') }} 
                        <span x-text="totalProducts"></span>
                        <span x-text="productType === 'packages' ? 'Packages' : 'Products'"></span>
                    </p>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="text-center py-20">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500"></div>
                    <p class="text-gray-400 mt-4">Loading...</p>
                </div>

                <!-- Products Grid - 3 Columns -->
                <div x-show="!loading && products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="(product, index) in products" :key="product.id || index">
                      <div x-html="renderProduct(product)"></div>
                   </template>
                </div>

                 <div x-show="!loading && products.length === 0" class="text-center py-20">
                    <p class="text-gray-400 text-xl">No <span x-text="productType === 'packages' ? 'packages' : 'products'"></span> found matching your filters.</p>
                </div>

                <!-- Pagination -->
                <div x-show="!loading && totalPages > 1" class="mt-12 flex justify-center">
                    <nav class="flex items-center gap-2">
                        <button 
                            @click="changePage(currentPage - 1)"
                            :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                            class="px-4 py-2 bg-gray-900 text-gray-400 rounded-lg hover:bg-gray-800 transition-colors">
                            Previous
                        </button>
                        
                        <template x-for="page in totalPages" :key="page">
                            <button 
                                @click="changePage(page)"
                                :class="page === currentPage ? 'bg-gradient-to-r from-orange-500 to-red-500' : 'bg-gray-900 hover:bg-gray-800'"
                                class="px-4 py-2 text-white rounded-lg transition-colors"
                                x-text="page"></button>
                        </template>
                        
                        <button 
                            @click="changePage(currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''"
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            Next
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts_bottom')

<script>

    function productShop() {
    return {
        products: [],
        categories: @json($categories ?? []),
        routines: @json($routines ?? []),
        selectedCategories: [],
        selectedRoutines: [],
        productType: 'products', 
        currentPage: 1,
        perPage: 5,
        totalProducts: 0,
        totalPages: 0,
        loading: true,

        init() {
            this.fetchProducts();
        },

        async fetchProducts() {
            this.loading = true;
            
            try {
                // Build query parameters
                const params = new URLSearchParams({
                    page: this.currentPage,
                    per_page: this.perPage,
                });

                // Add category filters
                if (this.selectedCategories.length > 0) {
                    this.selectedCategories.forEach(cat => {
                        params.append('categories[]', cat);
                    });
                }

                if(this.selectedRoutines.length > 0){
                    this.selectedRoutines.forEach(routine => {
                        params.append('routines[]', routine);
                    });
                }

                let isTrial = this.productType === 'trials';

                // Fetch from your API endpoint based on type
                let url = this.productType === 'products'|| this.productType === 'trials'  
                    ? `/api/products/${isTrial}/?${params.toString()}` 
                    : `/api/packages?${params.toString()}`;
                    
                const response = await fetch(url);
                const data = await response.json();
    
                // Update products and pagination info
                
                this.products = data.data ?? [];
    
                this.totalProducts = data.total || 0;
                this.totalPages = Math.ceil(this.totalProducts / this.perPage);

            } catch (error) {
                console.error('Error fetching:', error);
                this.products = [];
            } finally {
                this.loading = false;
            }
        },

        applyFilters() {
            this.currentPage = 1; 
            this.fetchProducts();
        },

        resetFilters() {
            this.selectedCategories = [];
            this.selectedRoutines = [];
            this.currentPage = 1;
            this.fetchProducts();
        },

        renderProduct(product) {
            if (product.html) {
                return product.html;
            }
        },

        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.fetchProducts();
               // window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

    }
}
</script>
    
@endpush