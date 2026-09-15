/**
 * Sign Up Page - JavaScript
 * Handles role-based field toggle and success modal
 */

document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('roleSelect');
    const signupForm = document.getElementById('signupForm');

    // ============================================
    // ✅ Toggle Staff Fields Based on Role
    // ============================================
    function toggleFields() {
        const role = roleSelect ? roleSelect.value : '';

        // Get all staff-only fields
        const staffFields = document.querySelectorAll('.staff-fields');
        const employeeInput = document.querySelector('input[name="employee_id"]');
        const departmentInput = document.querySelector('input[name="department"]');

        if (role === 'staff') {
            // Show staff fields
            staffFields.forEach(function(field) {
                field.classList.remove('d-none');
            });
            if (employeeInput) employeeInput.required = true;
            if (departmentInput) departmentInput.required = false;
        } else {
            // Hide staff fields
            staffFields.forEach(function(field) {
                field.classList.add('d-none');
            });
            if (employeeInput) employeeInput.required = false;
            if (departmentInput) departmentInput.required = false;
        }
    }

    // Attach event listener
    if (roleSelect) {
        roleSelect.addEventListener('change', toggleFields);
        toggleFields(); // Initial call on page load
    }

    // ============================================
    // ✅ Form Submission
    // ============================================
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            // Let the form submit naturally
            // Server will redirect after successful signup
            // Success modal will show on next page via query param
            
            const submitBtn = signupForm.querySelector('.btn-signup-submit');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Creating Account...';
                submitBtn.style.opacity = '0.7';
                submitBtn.style.cursor = 'wait';
            }
        });
    }

    // ============================================
    // ✅ Show Success Modal (If Query Param Exists)
    // ============================================
    if (window.location.search.includes('success=1')) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="success-card">
                <div class="loader-ring"></div>
                <h3 style="color: #ffffff; margin-bottom: 10px; font-weight: 700;">Account Created!</h3>
                <p style="color: rgba(255,255,255,0.7); font-size: 14px;">Redirecting you to login...</p>
            </div>
        `;
        document.body.appendChild(modal);

        setTimeout(function() {
            window.location.href = '/login';
        }, 2500);
    }
});