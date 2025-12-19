@extends('web.layouts.main')

@section('title')
shop
@endsection

@section('content')

<x-navbar>

</x-navbar>

<section class="py-16 md:py-24 bg-black" x-data="productShop()">
    <div class="container mx-auto px-4">
        <h2 class="text-center font-bold text-white text-4xl mb-2">Featured Products</h2>
        <p class="text-gray-400 text-center mb-12">Discover our Products</p>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Sidebar Filter -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-gray-900 rounded-lg p-6 sticky top-4">
                    <h3 class="text-white font-bold text-xl mb-6">Filters</h3>
                
                    <!-- Category Filter -->
                    <div class="mb-8">
                        <h4 class="text-white font-semibold mb-4">Category</h4>
                        <div class="space-y-3">
                             <template x-for="category in categories" :key="category.id">
                                <label class="flex items-center text-gray-300 hover:text-white cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        :value="category.id"
                                        x-model="selectedCategories"
                                        @change="applyFilters()"
                                        class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500 focus:ring-offset-gray-900">
                                    <span class="ml-3" x-text="category.title"></span>
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
                        Showing <span x-text="((currentPage - 1) * perPage) + 1"></span>-<span x-text="Math.min(currentPage * perPage, totalProducts)"></span> 
                        of <span x-text="totalProducts"></span> products
                    </p>
                    <div class="flex items-center gap-4">
                        {{-- <select class="bg-gray-900 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option>Sort by: Featured</option>
                            <option>Newest</option>
                        </select> --}}
                    </div>
                </div>

                <!-- Products Grid - 3 Columns -->
                <div x-show="!loading && products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="(product, index) in products" :key="index">
                    <div x-html="renderProduct(product)"></div>
             </template>
                </div>

                 <div x-show="!loading && products.length === 0" class="text-center py-20">
                    <p class="text-gray-400 text-xl">No products found matching your filters.</p>
                </div>
                <!-- Pagination -->
                <div x-show="!loading && totalPages > 1" class="mt-12 flex justify-center">
                    <nav class="flex items-center gap-2">
                        <button 
                            @click="changePage(currentPage - 1)"
                            :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                            class="px-4 py-2 bg-gray-900 text-gray-400 rounded-lg hover:bg-gray-800">
                            Previous
                        </button>
                        
                        <template x-for="page in totalPages" :key="page">
                            <button 
                                @click="changePage(page)"
                                :class="page === currentPage ? 'bg-gradient-to-r from-orange-500 to-red-500' : 'bg-gray-900 hover:bg-gray-800'"
                                class="px-4 py-2 text-white rounded-lg"
                                x-text="page"></button>
                        </template>
                        
                        <button 
                            @click="changePage(currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''"
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">
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
        selectedCategories: [],
        currentPage: 1,
        perPage: 2,
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

                // Fetch from your API endpoint
                const response = await fetch(`/api/products?${params.toString()}`);
                const data = await response.json();
    
                // Update products and pagination info
               this.products = data.data ?? [];
    
                this.totalProducts = data.total || 0;
                this.totalPages = Math.ceil(this.totalProducts / this.perPage);

            } catch (error) {
                console.error('Error fetching products:', error);
                this.products = [];
            } finally {
                this.loading = false;
            }
        },

        applyFilters() {
            this.currentPage = 1; // Reset to first page when filtering
            this.fetchProducts();
        },

        resetFilters() {
            this.selectedCategories = [];
            this.currentPage = 1;
            this.fetchProducts();
        },

         renderProduct(product) {
            console.log(product)
             return product.html || '';
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