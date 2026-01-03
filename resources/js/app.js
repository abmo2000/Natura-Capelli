
import Alpine from 'alpinejs'
import intlTelInput from 'intl-tel-input';

// Check if Alpine is already initialized
document.addEventListener('DOMContentLoaded' , function(){
  if (!window.Alpine) {
      window.Alpine = Alpine
      Alpine.start()
  }
});

// Initialize intl-tel-input after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.querySelector("#phone");
    
    if (phoneInput) {
        intlTelInput(phoneInput, {
            initialCountry: "eg",
            nationalMode: true,
            hiddenInput: () => ({ phone: "full_phone", country: "country_code" }),
            loadUtils: () => import("intl-tel-input/utils"),
        });
    }
});

import '@web/slider'
import '@web/cart'
import '@web/order'