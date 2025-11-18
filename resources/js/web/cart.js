
document.addEventListener('alpine:init' , () => {
    Alpine.data('cartStore' , () => {
    return {
        cartCount: parseInt(localStorage.getItem('cartCount') || '0'),
        
        init() {
            // Listen for cart updates from other components
            window.addEventListener('cart-updated', (event) => {
                this.cartCount = event.detail.count;
                localStorage.setItem('cartCount', this.cartCount);
            });
            
            // Sync cart count on page load
            this.syncCartCount();
        },
        
        syncCartCount() {
            // Get cart count from localStorage or server
            const storedCount = localStorage.getItem('cartCount');
            if (storedCount) {
                this.cartCount = parseInt(storedCount);
            }
        }
    }
    })

    Alpine.data('productCard' , (product_id , product_name , price) => {
          return {
                adding: false,
                showSuccess: false,
                
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
                                product_id: product_id,
                                quantity: 1
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Update cart count
                            const newCount = data.cartCount || this.getCurrentCartCount() + 1;
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
                        console.error('Error adding to cart:', error);
                        alert('An error occurred. Please try again.');
                    } finally {
                        this.adding = false;
                    }
                },
                
                getCurrentCartCount() {
                    return parseInt(localStorage.getItem('cartCount') || '0');
                },
                
                updateCartCount(count) {
                    localStorage.setItem('cartCount', count);
                    // Dispatch event to update nav
                    window.dispatchEvent(new CustomEvent('cart-updated', {
                        detail: { count: count }
                    }));
                }
            }
    })
})