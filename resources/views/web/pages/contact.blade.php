@extends('web.layouts.main')

@section('title')
Contact
@endsection

@section('content')

<x-navbar></x-navbar>

<!-- Background Image Section -->
<section class="relative py-16 md:py-24 min-h-screen overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute right-0 left-0 top-0 h-full w-full">
        <img src="{{ asset('assets/images/headers/contactBackground.jfif') }}" 
             alt="Contact Background" 
             class="w-full h-full object-cover brightness-75 contrast-110">
        <div class="absolute top-0 left-0 w-full h-full bg-opacity-70 bg-gradient-to-b from-black/30 via-black/30 to-black/50"></div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 relative z-10">
           <h2 class="heading text-white text-center text-4xl font-bold mb-8 py-8">Contact</h2>
        <!-- Contact Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-12">
            
            <!-- Phone Card -->
            <div class="bg-gray-800 bg-opacity-90 backdrop-blur-sm rounded-2xl shadow-2xl hover:shadow-orange-500/20 border border-gray-700 hover:border-orange-500 transition-all duration-300 transform hover:-translate-y-2 p-8">
                <div class="flex items-start space-x-4">
                    <div class="bg-orange-500 text-white p-4 rounded-xl flex-shrink-0">
                        <i class="fas fa-phone text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white mb-3">Phone</h3>
                        <p class="text-gray-300 text-lg">0000 - 123 - 456789</p>
                    </div>
                </div>
            </div>

            <!-- Email Card -->
            <div class="bg-gray-800 bg-opacity-90 backdrop-blur-sm rounded-2xl shadow-2xl hover:shadow-orange-500/20 border border-gray-700 hover:border-orange-500 transition-all duration-300 transform hover:-translate-y-2 p-8">
                <div class="flex items-start space-x-4">
                    <div class="bg-orange-500 text-white p-4 rounded-xl flex-shrink-0">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white mb-3">Email</h3>
                        <p class="text-gray-300 text-lg">heidiadel32@gmail.com</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Contact Form -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-3xl shadow-2xl border border-gray-700 p-8 md:p-12">
                <h2 class="text-4xl font-bold text-white text-center mb-8">Send us a Message</h2>
                
                <form action="" method="POST">
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-white font-semibold mb-2 text-lg">Name</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-5 py-4 border-2 border-gray-600 bg-gray-700 text-white rounded-xl focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50 transition-all duration-300 placeholder-gray-400"
                            placeholder="Your Name">
                    </div>

                    <!-- Email Field -->
                    <div class="mb-6">
                        <label for="email" class="block text-white font-semibold mb-2 text-lg">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-5 py-4 border-2 border-gray-600 bg-gray-700 text-white rounded-xl focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50 transition-all duration-300 placeholder-gray-400"
                            placeholder="your.email@example.com">
                    </div>

                    <!-- Message Field -->
                    <div class="mb-8">
                        <label for="message" class="block text-white font-semibold mb-2 text-lg">Message</label>
                        <textarea id="message" name="message" rows="6" required
                            class="w-full px-5 py-4 border-2 border-gray-600 bg-gray-700 text-white rounded-xl focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50 transition-all duration-300 resize-none placeholder-gray-400"
                            placeholder="Write your message here..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit"
                            class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-10 rounded-xl shadow-lg hover:shadow-orange-500/50 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-paper-plane me-2"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection