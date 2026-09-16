/**
 * Patient Token Status - JavaScript
 * Real-time countdown + Dynamic wait time (HH:MM:SS)
 */

(function() {
    'use strict';

    const tokenNumberElement = document.getElementById('patientTokenNumber');
    const tokenNumber = tokenNumberElement ? tokenNumberElement.textContent.trim() : null;

    // Store total seconds remaining
    let remainingSeconds = 0;
    let countdownTimer = null;
    let tokenStatus = 'waiting';

    /**
     * Fetch token status from server
     */
    function fetchTokenStatus() {
        if (!tokenNumber || tokenNumber === '--' || tokenNumber === 'N/A' || tokenNumber === '') {
            console.warn('⚠️ Invalid token number');
            return;
        }

        fetch(`/patient/token-status?token=${encodeURIComponent(tokenNumber)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.token) {
                    updatePatientUI(data.token);
                }
            })
            .catch(error => console.error('❌ Error:', error));
    }

    /**
     * Update UI with token data
     */
    function updatePatientUI(token) {
        // ✅ Basic Info
        setElementText('patientTokenNumber', token.token_number || '--');
        setElementText('patientName', token.patient_name || '--');
        setElementText('patientPosition', '#' + (token.position || '--'));
        setElementText('patientServing', token.now_serving || '--');

        // ✅ Status
        tokenStatus = token.status || 'waiting';
        updateStatusBadge(tokenStatus);

        // ✅ Calculate remaining seconds
        // Priority: waiting_time > estimated_time
        let totalMinutes = 0;
        if (token.waiting_time !== undefined && token.waiting_time > 0) {
            totalMinutes = token.waiting_time;
        } else if (token.estimated_time && token.estimated_time > 0) {
            totalMinutes = token.estimated_time;
        }

        // ✅ Set remaining seconds (minutes * 60)
        remainingSeconds = totalMinutes * 60;

        // ✅ Start countdown
        startCountdown();
    }

    /**
     * ✅ Start countdown timer (decreases every second)
     */
    function startCountdown() {
        // Clear existing timer
        if (countdownTimer) {
            clearInterval(countdownTimer);
        }

        // Show initial values
        displayTime(remainingSeconds);

        // Update every 1 second
        countdownTimer = setInterval(function() {
            if (remainingSeconds > 0) {
                remainingSeconds--;
                displayTime(remainingSeconds);
            } else {
                // Time up - stop countdown
                clearInterval(countdownTimer);
                displayTime(0);
            }
        }, 1000);
    }

    /**
     * ✅ Display time in HH:MM:SS format
     */
    function displayTime(totalSecs) {
        const hours = Math.floor(totalSecs / 3600);
        const minutes = Math.floor((totalSecs % 3600) / 60);
        const seconds = totalSecs % 60;

        // Pad with leading zeros
        setElementText('waitHours', String(hours).padStart(2, '0'));
        setElementText('waitMinutes', String(minutes).padStart(2, '0'));
        setElementText('waitSeconds', String(seconds).padStart(2, '0'));
    }

    /**
     * Capitalize first letter
     */
    function capitalize(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    /**
     * Update status badge
     */
    function updateStatusBadge(status) {
        const statusBadge = document.getElementById('patientStatus');
        const badge = document.getElementById('tokenBadge');

        if (!statusBadge) return;

        const statusLower = status.toLowerCase();
        statusBadge.className = 'value status-' + statusLower;
        statusBadge.textContent = capitalize(status);

        if (badge) {
            badge.className = 'badge status-' + statusLower;
            badge.textContent = capitalize(status);
        }
    }

    /**
     * Helper: Set element text safely
     */
    function setElementText(id, text) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = text;
        }
    }

    /**
     * Manual refresh
     */
    window.refreshStatus = function() {
        fetchTokenStatus();
    };

    // ============================================ //
    // INITIAL LOAD                                 //
    // ============================================ //
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fetchTokenStatus);
    } else {
        fetchTokenStatus();
    }

    // ✅ Refresh data every 30 seconds (server se latest position)
    setInterval(fetchTokenStatus, 30000);

})();