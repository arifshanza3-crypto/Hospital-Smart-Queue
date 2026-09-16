/**
 * Patient Token Status - JavaScript
 * Countdown only DECREASES (never increases)
 */

(function() {
    'use strict';

    const tokenNumberElement = document.getElementById('patientTokenNumber');
    const tokenNumber = tokenNumberElement ? tokenNumberElement.textContent.trim() : null;

    let remainingSeconds = 0;
    let countdownTimer = null;
    let tokenStatus = 'waiting';
    let isFirstLoad = true;

    /**
     * Fetch token status from server
     */
    function fetchTokenStatus() {
        if (!tokenNumber || tokenNumber === '--' || tokenNumber === 'N/A' || tokenNumber === '') {
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

        // ✅ Get remaining seconds from server
        let serverSeconds = 0;
        if (token.remaining_seconds !== undefined && token.remaining_seconds !== null) {
            serverSeconds = parseInt(token.remaining_seconds);
        }

        console.log('🕐 Server seconds:', serverSeconds, '| Client remaining:', remainingSeconds);

        // ✅ ONLY update countdown if:
        // 1. First load, OR
        // 2. Server says LESS than current (real decrease)
        if (isFirstLoad) {
            remainingSeconds = serverSeconds;
            isFirstLoad = false;
            startCountdown();
        } else if (serverSeconds < remainingSeconds) {
            // ✅ Server value is less → trust it
            console.log('📉 Updating: ' + remainingSeconds + 's → ' + serverSeconds + 's');
            remainingSeconds = serverSeconds;
            startCountdown();
        } else {
            // ✅ Server value is >= current → IGNORE (countdown continues)
            console.log('⏭️ Server value (' + serverSeconds + 's) >= client (' + remainingSeconds + 's) — ignored');
        }
    }

    /**
     * Start countdown (ticks every 1 second)
     */
    function startCountdown() {
        if (countdownTimer) {
            clearInterval(countdownTimer);
        }

        displayTime(remainingSeconds);

        countdownTimer = setInterval(function() {
            if (remainingSeconds > 0) {
                remainingSeconds--;
                displayTime(remainingSeconds);
            } else {
                clearInterval(countdownTimer);
                displayTime(0);
            }
        }, 1000);
    }

    /**
     * Display HH:MM:SS
     */
    function displayTime(totalSecs) {
        const hours = Math.floor(totalSecs / 3600);
        const minutes = Math.floor((totalSecs % 3600) / 60);
        const seconds = totalSecs % 60;

        setElementText('waitHours', String(hours).padStart(2, '0'));
        setElementText('waitMinutes', String(minutes).padStart(2, '0'));
        setElementText('waitSeconds', String(seconds).padStart(2, '0'));
    }

    function capitalize(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

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

    function setElementText(id, text) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = text;
        }
    }

    window.refreshStatus = function() {
        fetchTokenStatus();
    };

    // Initial load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fetchTokenStatus);
    } else {
        fetchTokenStatus();
    }

    // Refresh from server every 30 seconds
    setInterval(fetchTokenStatus, 30000);

})();