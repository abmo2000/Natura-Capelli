// Checkout form Alpine.js component
import intlTelInput from 'intl-tel-input';
document.addEventListener("alpine:init", () => {
    Alpine.data('checkoutForm', (total = 0, userData = {}, hasDeliveryOption = false) => ({
        form: {
            name: userData.name || '',
            email: userData.email || '',
            phone: userData.phone || '',
            address: userData.address || '',
            city_id: userData.city_id || '',
            delivery_option: '',
            payment_method: ''
        },
        errors: {
            name: '',
            email: '',
            phone: '',
            address: '',
            city_id: '',
            delivery_option: '',
            payment_method: ''
        },
        total: total,
        deliveryPrice: 0,
        selectedCityHasDiscussion: false,
        showDeliveryOptions: false,
        hasDeliveryOption: hasDeliveryOption,
        loading: false,
        success: false,
        orderId: '',
        
        init() {
            this.checkCityDeliveryOptions();
            this.$nextTick(() => {
                setTimeout(() => this.setupPhoneInput(), 100);
            });
        },

         setupPhoneInput() {
            const input = document.querySelector("#phone");
         
            if (!input) return;

            this.iti = intlTelInput(input, {
                initialCountry: "eg",
                preferredCountries: ["eg", "sa", "ae"],
                separateDialCode: true,
                nationalMode: false,
                autoPlaceholder: "polite",
                loadUtils: () => import("intl-tel-input/utils")
            });

            // Set initial value if exists
        
            if (this.form.phone) {
                this.iti.setNumber(this.form.phone);
            }

            // Update Alpine form data whenever user types or changes country
            input.addEventListener('input', () => {
                this.form.phone = this.iti.getNumber();
            });

            input.addEventListener('countrychange', () => {
                this.form.phone = this.iti.getNumber();
            });
        },

       checkCityDeliveryOptions() {
        this.$refs.citySelect.value = this.form.city_id || '';
        const selectedOption = this.$refs.citySelect.selectedOptions[0];
        if (!selectedOption || !selectedOption.value) {
            this.showDeliveryOptions = false;
            this.selectedCityHasDiscussion = false;
            this.deliveryPrice = 0;
            this.form.delivery_option = '';
            return;
    }

    const hasDiscussion = selectedOption.dataset.hasDiscussion === 'true';
    const deliveryPrice = parseFloat(selectedOption.dataset.deliveryPrice) || 0;

    this.selectedCityHasDiscussion = hasDiscussion;
    this.deliveryPrice = deliveryPrice;
    this.showDeliveryOptions = true;

    if (hasDiscussion) {
        this.form.delivery_option = '';
        this.errors.delivery_option = '';
    } else {
        this.form.delivery_option = 'proceed';
        this.errors.delivery_option = '';
    }
},


        onCityChange() {
            this.validateField('city_id');
            this.checkCityDeliveryOptions();
        },

        shouldShowDeliveryFee() {
            // Show delivery fee breakdown if:
            // 1. User selected "proceed" option (for cities with discussion)
            // 2. City doesn't have discussion (auto-added delivery)
            return this.deliveryPrice > 0 && 
                   (this.form.delivery_option === 'proceed' || 
                   (this.showDeliveryOptions && !this.selectedCityHasDiscussion));
        },

        calculateTotal() {
            let finalTotal = this.total;
            
            // Add delivery price if:
            // 1. User selected "proceed" option
            // 2. OR city doesn't have discussion (auto-added)
            if (this.deliveryPrice > 0 && 
                (this.form.delivery_option === 'proceed' || 
                (this.showDeliveryOptions && !this.selectedCityHasDiscussion))) {
                finalTotal += this.deliveryPrice;
            }
            
            return finalTotal;
        },
        
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
                        this.errors.phone = 'Please enter a valid phone number (begin with +20)';
                    }
                    break;
                    
                case 'address':
                    if (!this.form.address.trim()) {
                        this.errors.address = 'Address is required';
                    } else if (this.form.address.trim().length < 10) {
                        this.errors.address = 'Please provide a detailed address';
                    }
                    break;
                    
                case 'city_id':
                    if (!this.form.city_id) {
                        this.errors.city_id = 'Please select a city';
                    }
                    break;
                    
                case 'delivery_option':
                    // Only validate if city has discussion option
                    if (this.showDeliveryOptions && this.selectedCityHasDiscussion && !this.form.delivery_option) {
                        this.errors.delivery_option = 'Please select a delivery option';
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
            this.validateField('city_id');
            
            // Only validate delivery option if city has discussion option
            if (this.showDeliveryOptions && this.selectedCityHasDiscussion) {
                this.validateField('delivery_option');
            }
            
            this.validateField('payment_method');
            
            return !Object.values(this.errors).some(error => error !== '');
        },
        
        isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        },
        
        isValidPhone(phone) {
           return true;
        },
        
        async submitOrder() {
            // Validate all fields
            if (!this.validateAll()) {
                // Scroll to first error
                const firstError = document.querySelector('.text-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
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
                        city_id: this.form.city_id,
                        delivery_option: this.form.delivery_option || 'proceed',
                        payment_method: this.form.payment_method,
                    })
                });
                
                const data = await response.json();
             
                if (response.ok) {
                    this.success = true;
                    this.orderId = data.order_id || data.id || 'N/A';
                    
                    // Scroll to success message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    // Handle redirect based on payment method
                    setTimeout(() => {
                        if(data.payment_method === 'instapay'){
                           window.open(userData.instapay, '_blank');
                        }
                        
                        // Redirect to order confirmation page or reload
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                         
                    }, 2000);
                    
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
                        
                        // Scroll to first error
                        setTimeout(() => {
                            const firstError = document.querySelector('.text-red-500');
                            if (firstError) {
                                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }, 100);
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
        }
    }));
});