/**
 * Reset Password Page - JavaScript
 * Password strength checker, match validator, and toggle visibility
 */

document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const matchIndicator = document.getElementById('matchIndicator');

    // Agar elements nahi hain, toh kuch mat karo (safety check)
    if (!password || !confirmPassword) return;

    // Requirements elements
    const reqLength = document.getElementById('reqLength');
    const reqNumber = document.getElementById('reqNumber');
    const reqUpper = document.getElementById('reqUpper');
    const reqSpecial = document.getElementById('reqSpecial');

    // ============================================
    // ✅ Toggle Password Visibility
    // ============================================
    const togglePwdBtn = document.getElementById('togglePassword');
    if (togglePwdBtn) {
        togglePwdBtn.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            document.getElementById('eyeIcon').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
    }

    const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');
    if (toggleConfirmBtn) {
        toggleConfirmBtn.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            document.getElementById('eyeIconConfirm').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
    }

    // ============================================
    // ✅ Password Strength Check
    // ============================================
    password.addEventListener('input', function() {
        const val = this.value;
        let strength = 0;
        const checks = {
            length: val.length >= 8,
            number: /\d/.test(val),
            upper: /[A-Z]/.test(val),
            special: /[^a-zA-Z0-9]/.test(val)
        };

        // Update requirements
        updateRequirement(reqLength, checks.length, '✅ Min 8 characters', '❌ Min 8 characters');
        updateRequirement(reqNumber, checks.number, '✅ Contains number', '❌ Contains number');
        updateRequirement(reqUpper, checks.upper, '✅ Uppercase letter', '❌ Uppercase letter');
        updateRequirement(reqSpecial, checks.special, '✅ Special character', '❌ Special character');

        // Calculate strength
        if (checks.length) strength++;
        if (checks.number) strength++;
        if (checks.upper) strength++;
        if (checks.special) strength++;

        const percentage = (strength / 4) * 100;
        strengthFill.style.width = percentage + '%';

        const colors = ['#e2e8f0', '#f56565', '#ed8936', '#ecc94b', '#48bb78'];
        const labels = ['Enter password', 'Weak', 'Fair', 'Good', 'Strong'];
        
        const index = Math.min(Math.floor(percentage / 25), 4);
        strengthFill.style.background = colors[index];
        strengthText.textContent = labels[index];
        strengthText.style.color = colors[index];

        // Check password match
        checkMatch();
    });

    // ============================================
    // ✅ Confirm Password Check
    // ============================================
    confirmPassword.addEventListener('input', function() {
        checkMatch();
    });

    function checkMatch() {
        const pwd = password.value;
        const confirm = confirmPassword.value;
        
        if (confirm.length === 0) {
            matchIndicator.innerHTML = '';
            confirmPassword.classList.remove('is-valid', 'is-invalid');
            return;
        }
        
        if (pwd === confirm) {
            matchIndicator.innerHTML = '<span class="match-success"><i class="fas fa-check-circle"></i> Passwords match</span>';
            confirmPassword.classList.add('is-valid');
            confirmPassword.classList.remove('is-invalid');
        } else {
            matchIndicator.innerHTML = '<span class="match-error"><i class="fas fa-times-circle"></i> Passwords do not match</span>';
            confirmPassword.classList.add('is-invalid');
            confirmPassword.classList.remove('is-valid');
        }
    }

    function updateRequirement(element, isValid, validText, invalidText) {
        if (!element) return;
        element.textContent = isValid ? validText : invalidText;
        element.className = 'requirement ' + (isValid ? 'valid' : 'invalid');
    }

    // ============================================
    // ✅ Form Validation Before Submit
    // ============================================
    const resetForm = document.getElementById('resetPasswordForm');
    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            const pwd = password.value;
            const confirm = confirmPassword.value;
            
            if (pwd.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                return false;
            }
            
            if (pwd !== confirm) {
                e.preventDefault();
                alert('Passwords do not match. Please check and try again.');
                return false;
            }
        });
    }

    // ============================================
    // ✅ Auto-dismiss Alerts (5 seconds)
    // ============================================
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
});