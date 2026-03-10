import intlTelInput from 'intl-tel-input';

document.addEventListener('alpine:init', () => {
    Alpine.data('profilePage', (userData = {}, updateUrl = '') => ({
        activeTab: 'personal',
        form: {
            name: userData.name || '',
            email: userData.email || '',
            phone: userData.phone || '',
            insta_account: userData.insta_account || '',
            city_id: userData.city_id || '',
            address: userData.address || ''
        },
        errors: {
            name: '',
            email: '',
            phone: '',
            insta_account: '',
            city_id: '',
            address: ''
        },
        loading: false,
        successMessage: '',
        errorMessage: '',
        iti: null,

        init() {
            this.$nextTick(() => {
                setTimeout(() => this.setupPhoneInput(), 100);
            });
        },

        switchTab(tabName) {
            this.activeTab = tabName;
            this.successMessage = '';
            this.errorMessage = '';
        },

        isActive(tabName) {
            return this.activeTab === tabName;
        },

        validateField(field) {
            this.errors[field] = '';

            switch (field) {
                case 'name':
                    if (!this.form.name.trim()) {
                        this.errors.name = 'Name is required';
                    }
                    break;

                case 'email':
                    if (!this.form.email.trim()) {
                        this.errors.email = 'Email is required';
                    } else if (!this.isValidEmail(this.form.email)) {
                        this.errors.email = 'Please enter a valid email address';
                    }
                    break;

                case 'phone':
                    if (!this.form.phone.trim()) {
                        this.errors.phone = 'Phone is required';
                    } else if (!this.isValidPhone(this.form.phone)) {
                        this.errors.phone = 'Please enter a valid phone number';
                    }
                    break;
            }
        },

        validateAll() {
            this.validateField('name');
            this.validateField('email');
            this.validateField('phone');

            return !Object.values(this.errors).some((error) => error !== '');
        },

        isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        },

        isValidPhone(phone) {
            return true;
        },

        setupPhoneInput() {
            const input = document.querySelector('#phone');

            if (!input) return;

            this.iti = intlTelInput(input, {
                initialCountry: 'eg',
                preferredCountries: ['eg', 'sa', 'ae'],
                separateDialCode: true,
                nationalMode: false,
                autoPlaceholder: 'polite',
                loadUtils: () => import('intl-tel-input/utils')
            });

            if (this.form.phone) {
                this.iti.setNumber(this.form.phone);
            }

            input.addEventListener('input', () => {
                this.form.phone = this.iti.getNumber();
            });

            input.addEventListener('countrychange', () => {
                this.form.phone = this.iti.getNumber();
            });
        },

        async submitProfile() {
            if (!this.validateAll()) {
                return;
            }

            this.loading = true;
            this.successMessage = '';
            this.errorMessage = '';

            Object.keys(this.errors).forEach((key) => {
                this.errors[key] = '';
            });

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                    document.querySelector('input[name="_token"]')?.value;

                const payload = {
                    ...this.form,
                    phone: this.iti ? this.iti.getNumber() : this.form.phone
                };

                this.form.phone = payload.phone;

                const response = await fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok) {
                    this.successMessage = data.message || 'Profile updated successfully.';
                    return;
                }

                if (data.errors) {
                    Object.keys(data.errors).forEach((key) => {
                        if (Object.prototype.hasOwnProperty.call(this.errors, key)) {
                            this.errors[key] = Array.isArray(data.errors[key])
                                ? data.errors[key][0]
                                : data.errors[key];
                        }
                    });

                    this.errorMessage = data.message || 'Please fix the highlighted fields.';
                } else {
                    this.errorMessage = data.message || 'Unable to update profile right now.';
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                this.errorMessage = 'Network error. Please try again.';
            } finally {
                this.loading = false;
            }
        }
    }));
});
