<div id="checkoutModal" x-data="checkoutForm({{ $total }})" class="hidden fixed inset-0 backdrop-blur-lg bg-opacity-30 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Checkout</h2>
                <button @click="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Success Message -->
            <div x-show="success" x-transition class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Order placed successfully check the email inbox ..!</span>
                </div>
            </div>

            <form @submit.prevent="submitOrder">
                
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Full Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        x-model="form.name"
                        :class="{ 'border-red-500': errors.name }"
                        @blur="validateField('name')"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="John Doe"
                    >
                    <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-sm mt-1"></p>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email Address *</label>
                    <input 
                        type="email" 
                        id="email" 
                        x-model="form.email"
                        :class="{ 'border-red-500': errors.email }"
                        @blur="validateField('email')"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="john@example.com"
                    >
                    <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-sm mt-1"></p>
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number *</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        x-model="form.phone"
                        :class="{ 'border-red-500': errors.phone }"
                        @blur="validateField('phone')"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="+201148992811"
                    >
                    <p x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-sm mt-1"></p>
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label for="address" class="block text-gray-700 font-medium mb-2">Address *</label>
                    <input 
                        type="text" 
                        id="address" 
                        x-model="form.address"
                        :class="{ 'border-red-500': errors.address }"
                        @blur="validateField('address')"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="مثال : شارع النصر , مدينة نصر - القاهرة"
                    >
                    <p x-show="errors.address" x-text="errors.address" class="text-red-500 text-sm mt-1"></p>
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-3">Payment Method *</label>
                    
                    <div class="space-y-3">
                        <label 
                            class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition"
                            :class="form.payment_method === 'cash_on_delivery' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
                            @click="form.payment_method = 'cash_on_delivery'; validateField('payment_method')"
                        >
                            <input 
                                type="radio" 
                                name="payment_method" 
                                value="cash_on_delivery"
                                x-model="form.payment_method"
                                class="mt-1 mr-3"
                            >
                            <div>
                                <div class="font-medium text-gray-800">Cash on Delivery</div>
                                <div class="text-sm text-gray-500">Pay when you receive your order</div>
                            </div>
                        </label>

                        <label 
                            class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition"
                            :class="form.payment_method === 'instapay' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
                            @click="form.payment_method = 'instapay'; validateField('payment_method')"
                        >
                            <input 
                                type="radio" 
                                name="payment_method" 
                                value="instapay"
                                x-model="form.payment_method"
                                class="mt-1 mr-3"
                            >
                            <div>
                                <div class="font-medium text-gray-800">InstaPay</div>
                                <div class="text-sm text-gray-500">Pay instantly via InstaPay</div>
                            </div>
                        </label>
                    </div>
                    <p x-show="errors.payment_method" x-text="errors.payment_method" class="text-red-500 text-sm mt-1"></p>
                </div>

                <!-- Order Total -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Order Total:</span>
                        <span class="text-green-600">EGP<span x-text="total.toFixed(2)"></span></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    :disabled="loading"
                    :class="loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                    class="w-full text-white py-3 rounded-lg transition font-semibold flex items-center justify-center"
                >
                    <span x-show="!loading">Place Order</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- <script>
function checkoutForm() {
    return {
        form: {
            name: '',
            email: '',
            phone: '',
            address: '',
            payment_method: ''
        },
        errors: {
            name: '',
            email: '',
            phone: '',
            address: '',
            payment_method: ''
        },
        total: {{ $total ?? 0 }}, // Get total from Laravel
        loading: false,
        success: false,
        orderId: '',
        
        validateField(field) {
            this.errors[field] = '';
            
            switch(field) {
                case 'name':
                    if (!this.form.name.trim()) {
                        this.errors.name = 'Full name is required';
                    } else if (this.form.name.trim().length < 3) {
                        this.errors.name = 'Full name must be at least 3 characters';
                    }
                    break;
                    
                case 'email':
                    if (!this.form.email.trim()) {
                        this.errors.email = 'Email address is required';
                    } else if (!this.isValidEmail(this.form.email)) {
                        this.errors.email = 'Please enter a valid email address';
                    }
                    break;
                    
                case 'phone':
                    if (!this.form.phone.trim()) {
                        this.errors.phone = 'Phone number is required';
                    } else if (!this.isValidPhone(this.form.phone)) {
                        this.errors.phone = 'Please enter a valid phone number (at least 10 digits)';
                    }
                    break;
                    
                case 'address':
                    if (!this.form.address.trim()) {
                        this.errors.address = 'Address is required';
                    } else if (this.form.address.trim().length < 10) {
                        this.errors.address = 'Please enter a complete address';
                    }
                    break;
                    
                case 'payment_method':
                    if (!this.form.payment_method) {
                        this.errors.payment_method = 'Please select a payment method';
                    }
                    break;
            }
        },
        
        validateAll() {
            this.validateField('name');
            this.validateField('email');
            this.validateField('phone');
            this.validateField('address');
            this.validateField('payment_method');
            
            return !Object.values(this.errors).some(error => error !== '');
        },
        
        isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        },
        
        isValidPhone(phone) {
            const digitsOnly = phone.replace(/\D/g, '');
            return digitsOnly.length >= 10;
        },
        
        async submitOrder() {
            // Validate all fields
            if (!this.validateAll()) {
                return;
            }
            
            this.loading = true;
            this.success = false;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                document.querySelector('input[name="_token"]')?.value;
                
                const response = await fetch('/order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: this.form.name,
                        email: this.form.email,
                        phone: this.form.phone,
                        address: this.form.address,
                        payment_method: this.form.payment_method,
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    this.success = true;
                    this.orderId = data.order_id || data.id || 'N/A';
                    
                    // Reset form
                    this.form = {
                        name: '',
                        email: '',
                        phone: '',
                        address: '',
                        payment_method: ''
                    };
                    
                    // Close modal after 3 seconds and redirect if needed
                    setTimeout(() => {
                        this.closeModal();
                        // Optional: Redirect to order confirmation page
                        // window.location.href = '/order-confirmation/' + this.orderId;
                    }, 3000);
                    
                } else {
                    // Handle validation errors from server
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            if (this.errors.hasOwnProperty(key)) {
                                this.errors[key] = Array.isArray(data.errors[key]) 
                                    ? data.errors[key][0] 
                                    : data.errors[key];
                            }
                        });
                    } else {
                        alert(data.message || 'An error occurred. Please try again.');
                    }
                }
                
            } catch (error) {
                console.error('Error submitting order:', error);
                alert('Network error. Please check your connection and try again.');
            } finally {
                this.loading = false;
            }
        },
        
        closeModal() {
            document.getElementById('checkoutModal').classList.add('hidden');
        }
    }
}

// Function to open the modal (call this from your button)
function openCheckoutModal() {
    document.getElementById('checkoutModal').classList.remove('hidden');
}

// Function to close the modal (alternative method)
function closeCheckoutModal() {
    document.getElementById('checkoutModal').classList.add('hidden');
}
</script> --}}