
import Alpine from 'alpinejs'
import intlTelInput from 'intl-tel-input';

const input = document.querySelector("#phone");
intlTelInput(input, {
  initialCountry: "eg",
  nationalMode: true,
    hiddenInput: () => ({ phone: "full_phone", country: "country_code" }),
  loadUtils: () => import("intl-tel-input/utils"),
});
 
window.Alpine = Alpine
 
Alpine.start()

import '@web/slider'
import '@web/cart'
import '@web/order'