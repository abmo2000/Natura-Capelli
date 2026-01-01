document.addEventListener('alpine:init', () => {
    Alpine.data('productCarousel', () => ({
        isAtStart: true,
        isAtEnd: false,
        
        init() {
            this.$nextTick(() => {
                this.updateArrows();
                window.addEventListener('resize', () => this.updateArrows());
                
                // Update arrows after images load
                const carousel = this.$refs.carousel;
                const images = carousel.querySelectorAll('img');
                images.forEach(img => {
                    img.addEventListener('load', () => this.updateArrows());
                });
            });
        },
        
        scroll(direction) {
            const carousel = this.$refs.carousel;
            const card = carousel.querySelector('.product-card');
            
            if (card) {
                // Get the actual card width including gap
                const cardWidth = card.offsetWidth;
                const gap = parseInt(window.getComputedStyle(carousel).gap) || 24;
                const scrollAmount = cardWidth + gap;
                
                carousel.scrollBy({
                    left: direction * scrollAmount,
                    behavior: 'smooth'
                });
                
                // Update arrows after scroll completes
                setTimeout(() => this.updateArrows(), 300);
            }
        },

          isRTL() {
        return document.documentElement.dir === 'rtl' || 
               document.body.dir === 'rtl' ||
               getComputedStyle(document.documentElement).direction === 'rtl';
    },
        
        updateArrows() {
            const carousel = this.$refs.carousel;
            if (!carousel) return;
            
            const tolerance = 5; // pixel tolerance for detecting edges
            this.isAtStart = carousel.scrollLeft <= tolerance;
            this.isAtEnd = carousel.scrollLeft >= (carousel.scrollWidth - carousel.clientWidth - tolerance);
            
            console.log('Scroll:', carousel.scrollLeft, 'Max:', carousel.scrollWidth - carousel.clientWidth);
        }
    }));
});