
document.addEventListener("alpine:init", () => {

    Alpine.data('checkoutForm', (total = 0) => ({
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
        total: total, 
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
                        this.errors.phone = 'Please enter a valid phone number (begin with +2)';
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
            const regex = /^\+201[0125]\d{8}$/;
            return regex.test(phone);
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
                    const modalContent = document.querySelector('#checkoutModal .overflow-y-auto');
                    modalContent.scrollTo({ top: 0, behavior: 'smooth' });
                    // Close modal after 3 seconds and redirect if needed
                    setTimeout(() => {
                        this.closeModal();
                       
                        if(data.payment_method === 'instapay'){
                           window.open("heidi.a.rezk@instapay", '_blank');
                        }
                         window.location.reload()
                         
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
            const modal = document.getElementById('checkoutModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    }));
});