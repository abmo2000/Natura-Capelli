@extends('web.layouts.main')

@section('title')
Contact
@endsection

@section('content')

<x-navbar></x-navbar>
<x-header header_bg_image="assets/images/headers/contact.jpeg">
</x-header>

<section class="py-16 md:py-24 bg-black">

   <div class="container mx-auto px-4">
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 max-w-6xl mx-auto">
                
                <!-- Phone Card -->
                <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg shadow-lg hover:shadow-2xl border-2 border-gray-300 hover:border-orange-500 transition-all duration-300 transform hover:-translate-y-2 p-8">
                    <div class="flex items-start space-x-4">
                        <div class="bg-gray-900 text-white p-4 rounded-lg flex-shrink-0">
                            <i class="fas fa-phone text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Phone</h3>
                            <p class="text-gray-700 text-lg">0000 - 123 - 456789</p>
                        </div>
                    </div>
                </div>

                <!-- Email Card -->
                <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg shadow-lg hover:shadow-2xl border-2 border-gray-300 hover:border-orange-500 transition-all duration-300 transform hover:-translate-y-2 p-8">
                    <div class="flex items-start space-x-4">
                        <div class="bg-gray-900 text-white p-4 rounded-lg flex-shrink-0">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Email</h3>
                            <p class="text-gray-700 text-lg">heidiadel32@gmail.com</p>
                        </div>
                    </div>
                </div>

                

         </div>

         <div class="mx-auto my-5 ">
            <div class="bg-footer  shadow-lg hover:shadow-2xl  border-gray-300 hover:border-orange-500 transition-all duration-300 p-8 md:p-10">
                <h2 class="heading">Send us a Message</h2>
                
                <form action="" method="POST">
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-white font-semibold mb-2">Name</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-300 bg-white text-gray-900"
                            placeholder="Your Name">
                    </div>

                    <!-- Email Field -->
                    <div class="mb-6">
                        <label for="email" class="block text-white font-semibold mb-2">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-300 bg-white text-gray-900"
                            placeholder="your.email@example.com">
                    </div>

                    <!-- Message Field -->
                    <div class="mb-6">
                        <label for="message" class="block text-white font-semibold mb-2">Message</label>
                        <textarea id="message" name="message" rows="6" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-300 bg-white text-gray-900 resize-none"
                            placeholder="Write your message here..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit"
                            class="custom-btn cursor-pointer">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
         </div>
    </div>


</section>
@endsection
