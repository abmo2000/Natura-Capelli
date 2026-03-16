
document.addEventListener('alpine:init' , () => {
    
    Alpine.data('productCard' , (type , product_id) => {
          return {
                adding: false,
                showSuccess: false,
                quantity: 1,
                
                async addToCart() {
                    if (this.adding) return;
                    
                    this.adding = true;
                    
                    try {
                        // Make AJAX request to add to cart
                        const response = await fetch('/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                type : type,
                                product_id: product_id,
                                quantity: this.quantity
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Update cart count
                            const newCount = data.data.cart_count;
                            
                            this.updateCartCount(newCount);
                            
                            // Show success message
                            this.showSuccess = true;
                            setTimeout(() => {
                                this.showSuccess = false;
                            }, 2000);
                        } else {
                            alert(data.message || 'Failed to add item to cart');
                        }
                    } catch (error) {
                        console.log(error.json);
                        console.error('Error adding to cart:', error);
                        // alert('An error occurred. Please try again.');
                    } finally {
                        this.adding = false;
                    }
                },
                
                async buyNow() {
                    if (this.adding) return;
                    
                    this.adding = true;
                    
                    try {
                        const response = await fetch('/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                type: type,
                                product_id: product_id,
                                quantity: this.quantity
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.updateCartCount(data.data.cart_count);
                            window.location.href = '/checkout';
                        } else {
                            alert(data.message || 'Failed to add item to cart');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    } finally {
                        this.adding = false;
                    }
                },

                updateCartCount(count) {
                    // Dispatch event to update nav
                    window.dispatchEvent(new CustomEvent('cart-updated', {
                        detail: { count: parseInt(count) }
                    }));
                }
            }
    })
})