/**
 * Token Form - JavaScript
 * Mobile number validation + Direct form submit
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('tokenRequestForm');
    const mobileInput = document.getElementById('mobileNumber');
    const mobileError = document.getElementById('mobileError');

    // Agar form ya input mojood nahi, toh kuch mat karo
    if (!form || !mobileInput) return;

    let isSubmitting = false;

    // ============================================
    // ✅ Mobile Number Validation
    // Only numbers, max 11 digits, starts with 03
    // ============================================
    mobileInput.addEventListener('input', function() {
        // Sirf numbers allow karo
        this.value = this.value.replace(/[^0-9]/g, '');

        // Max 11 digits
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }

        // Validation check
        if (this.value.length > 0) {
            const isValid = /^(03)\d{9}$/.test(this.value);
            if (!isValid) {
                mobileError.classList.remove('d-none');
                this.style.border = "1.5px solid #ff4b2b";
            } else {
                mobileError.classList.add('d-none');
                this.style.border = "1.5px solid #1a7a82";
            }
        } else {
            // Khaali ho toh error hata do
            mobileError.classList.add('d-none');
            this.style.border = "1.5px solid rgba(11, 46, 51, 0.08)";
        }
    });

    // ============================================
    // ✅ Form Submit - Direct Submit (No Modal)
    // ============================================
    form.addEventListener('submit', function(e) {
        // Double submit rokne ke liye
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        const mobileVal = mobileInput.value;
        const isValid = /^(03)\d{9}$/.test(mobileVal);

        // Agar mobile valid nahi hai
        if (!isValid) {
            e.preventDefault();
            mobileError.classList.remove('d-none');
            mobileInput.style.border = "1.5px solid #ff4b2b";
            mobileInput.focus();
            return;
        }

        // ✅ Sab theek hai - form submit hone do
        isSubmitting = true;

        // Button disable karo (visual feedback)
        const submitBtn = form.querySelector('.btn-token-generate');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Generating...';
        }
    });

   
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            isSubmitting = false;
            const submitBtn = form.querySelector('.btn-token-generate');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Generate Token';
            }
        }
    });
});

