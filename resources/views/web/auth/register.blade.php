@extends('web.layouts.main')


@section('title')
  Register
@endsection

@section('content')
 <x-navbar></x-navbar>

 <section class="py-16 md:py-24 bg-black min-h-screen">
   <div class="container mx-auto px-4">
    <h2 class="text-white text-center text-4xl md:text-5xl font-bold mb-16">{{ trans('auth.register') }}</h2>

    <div class="max-w-md mx-auto">
      <!-- Registration Card -->
      <div class="bg-zinc-900 rounded-2xl shadow-2xl p-8 border border-zinc-800">
        
        <!-- Display Validation Errors -->
        <x-errors></x-errors>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
          @csrf

          <!-- Name Field -->
          <x-web-input  
            name="name"
            :label="trans('auth.name') ?? 'Name'"
            :placeholder="trans('auth.name-placeholder')"
            required
            autofocus></x-web-input>




          <!-- Email Field -->

          <x-web-input
            type="email"  
            name="email"
            :label="trans('auth.email') ?? 'Email'"
            :placeholder=" trans('auth.email-placeholder') "
            required
            autofocus></x-web-input>
        
            <x-web-input
            type="tel"  
            name="phone"
            :label="trans('auth.phone') ?? 'Email'"
            :placeholder=" trans('auth.phone-placeholder') "
            required
            autofocus></x-web-input>

            <x-web-textarea   
             name="address"
            :label="trans('auth.address') ?? 'Address'"
            :placeholder="trans('auth.address-placeholder')"
            rows="3"
            ></x-web-textarea>
       

          <x-web-input 
            type="password"
            name="password"
            :label="trans('auth.pass') ?? 'Password'"
            :placeholder="trans('auth.password-placeholder')"
            required
          />

          <!-- Password Confirmation Field -->
          <x-web-input 
            type="password"
            name="password_confirmation"
            :label="trans('auth.confirm_password') ?? 'Confirm Password'"
            :placeholder="trans('auth.confirm-password-placeholder')"
            required
          />

          <!-- Submit Button -->
          <button 
            type="submit" 
            class="custom-btn text-center w-full cursor-pointer"
          >
            {{ trans('auth.register') ?? 'Create Account' }}
          </button>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
          <p class="text-white text-sm">
            {{ trans('auth.have-account') }} 
            <a href="{{ route('login') }}" class="text-white hover:text-orange-100 font-medium transition">
              {{ trans('auth.login') ?? 'Login here' }}
            </a>
          </p>
        </div>

        <!-- Social Login (Optional) -->
        <div class="mt-8">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-zinc-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-4 bg-zinc-900 text-zinc-500">{{ trans('auth.continue-with') }}</span>
            </div>
          </div>

          <div class="mt-6 grid grid-cols-1 gap-4">
            <a href="{{  route('google.login')  }}" class="flex items-center justify-center px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white hover:bg-zinc-750 transition duration-200 cursor-pointer">
              <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
              </svg>
              {{ trans('auth.google') }}
            </a>
          </div>
        </div>
      </div>
    </div>

   </div>
 </section>
@endsection