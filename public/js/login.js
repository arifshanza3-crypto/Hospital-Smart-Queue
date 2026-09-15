/**
 * Login Page - JavaScript
 * Handles password toggle, form submission, and particles
 */

document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // ✅ Elements
    // ============================================
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');

    // ============================================
    // ✅ PASSWORD TOGGLE
    // ============================================
    window.togglePassword = function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (!passwordInput || !eyeIcon) return;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.add('fa-eye');
            eyeIcon.classList.remove('fa-eye-slash');
        }
    };

    // ============================================
    // ✅ FORM SUBMIT - Loading State
    // ============================================
    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            if (loginBtn) {
                loginBtn.classList.add('loading');
            }
        });
    }
});