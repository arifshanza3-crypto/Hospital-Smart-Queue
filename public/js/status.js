/**
 * Patient Token Status - JavaScript
 * Real-time countdown + Browser Notifications (for THIS token only)
 */

(function() {
    'use strict';

    const tokenNumberElement = document.getElementById('patientTokenNumber');
    const tokenNumber = tokenNumberElement ? tokenNumberElement.textContent.trim() : null;

    let remainingSeconds = 0;
    let countdownTimer = null;
    let tokenStatus = 'waiting';
    let isFirstLoad = true;
    let previousStatus = null;

    // ============================================ //
    // ✅ BROWSER NOTIFICATION SETUP                //
    // ============================================ //

    // Request notification permission on page load
    function requestNotificationPermission() {
        if (!('Notification' in window)) {
            console.warn('⚠️ This browser does not support notifications');
            return;
        }

        if (Notification.permission === 'default') {
            // Delay 2 seconds so page loads first
            setTimeout(() => {
                Notification.requestPermission().then(permission => {
                    console.log('📬 Notification permission:', permission);
                    if (permission === 'granted') {
                        showNotification(
                            '🔔 Notifications Enabled',
                            'Aap ko aap ke token ' + tokenNumber + ' ke updates milenge.'
                        );
                    }
                });
            }, 2000);
        }
    }

    // Show a browser notification
    function showNotification(title, body, tag = null) {
        if (!('Notification' in window)) return;
        if (Notification.permission !== 'granted') return;

        try {
            const notification = new Notification(title, {
                body: body,
                icon: '/Assert/logo.png',
                badge: '/Assert/logo.png',
                tag: tag || 'token-' + tokenNumber,  // Prevents duplicate notifications
                requireInteraction: false,
                silent: false
            });

            // Auto close after 10 seconds
            setTimeout(() => notification.close(), 10000);

            // Click → focus on page
            notification.onclick = function() {
                window.focus();
                notification.close();
            };

            console.log('✅ Notification shown:', title);

        } catch (error) {
            console.error('❌ Notification error:', error);
        }
    }

    // ✅ Check for status changes and notify
    function checkAndNotify(token) {
        const currentStatus = token.status;

        // First load → just store, don't notify
        if (isFirstLoad) {
            previousStatus = currentStatus;
            return;
        }

        // ✅ Only notify if status ACTUALLY CHANGED
        if (previousStatus !== currentStatus) {
            console.log('🔔 Status changed:', previousStatus, '→', currentStatus);

            switch (currentStatus) {
                case 'calling':
                    showNotification(
                        '🔔 Aap ka Number Aa Gaya!',
                        'Token ' + token.token_number + ' (' + token.patient_name + ') — Please counter par jaayein.',
                        'calling-' + token.token_number
                    );
                    break;

                case 'serving':
                    showNotification(
                        '✅ Service Start Ho Gayi',
                        'Token ' + token.token_number + ' ke liye service start ho gayi hai.',
                        'serving-' + token.token_number
                    );
                    break;

                case 'completed':
                    showNotification(
                        '✅ Service Complete',
                        'Token ' + token.token_number + ' ki service complete ho gayi. Shukriya!',
                        'completed-' + token.token_number
                    );
                    break;

                case 'cancelled':
                case 'missed':
                    showNotification(
                        '❌ Token Cancel Ho Gaya',
                        'Aap ka token ' + token.token_number + ' cancel kar diya gaya hai.',
                        'cancelled-' + token.token_number
                    );
                    break;
            }

            previousStatus = currentStatus;
        }

        // ✅ Special case: Position 1 or 2 → "Almost your turn"
        if (token.status === 'waiting' && token.position <= 2 && token.position > 0) {
            const almostKey = 'almost-notified-' + token.token_number;
            if (!sessionStorage.getItem(almostKey)) {
                showNotification(
                    '⏰ Aap ki Baari Aa Rahi Hai',
                    'Aap ka token ' + token.token_number + ' jald hi call hoga. Please tayyar rahein.',
                    'almost-' + token.token_number
                );
                sessionStorage.setItem(almostKey, 'true');
            }
        }
    }

    // ============================================ //
    // ✅ FETCH TOKEN STATUS                        //
    // ============================================ //

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

    // ============================================ //
    // ✅ UPDATE UI                                 //
    // ============================================ //

    function updatePatientUI(token) {
        // Basic Info
        setElementText('patientTokenNumber', token.token_number || '--');
        setElementText('patientName', token.patient_name || '--');
        setElementText('patientPosition', '#' + (token.position || '--'));
        setElementText('patientServing', token.now_serving || '--');

        // Status Badge
        tokenStatus = token.status || 'waiting';
        updateStatusBadge(tokenStatus);

        // ✅ Browser Notification Check
        checkAndNotify(token);

        // ✅ Wait Time Countdown
        let serverSeconds = 0;
        if (token.remaining_seconds !== undefined && token.remaining_seconds !== null) {
            serverSeconds = parseInt(token.remaining_seconds);
        }

        if (isFirstLoad) {
            remainingSeconds = serverSeconds;
            startCountdown();
        } else if (serverSeconds < remainingSeconds) {
            console.log('📉 Updating: ' + remainingSeconds + 's → ' + serverSeconds + 's');
            remainingSeconds = serverSeconds;
            startCountdown();
        } else {
            console.log('⏭️ Server value >= client — ignored');
        }
    }

    // ============================================ //
    // ✅ COUNTDOWN                                 //
    // ============================================ //

    function startCountdown() {
        if (countdownTimer) clearInterval(countdownTimer);
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

    function displayTime(totalSecs) {
        const hours = Math.floor(totalSecs / 3600);
        const minutes = Math.floor((totalSecs % 3600) / 60);
        const seconds = totalSecs % 60;

        setElementText('waitHours', String(hours).padStart(2, '0'));
        setElementText('waitMinutes', String(minutes).padStart(2, '0'));
        setElementText('waitSeconds', String(seconds).padStart(2, '0'));
    }

    // ============================================ //
    // ✅ HELPERS                                   //
    // ============================================ //

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
        if (element) element.textContent = text;
    }

    window.refreshStatus = function() {
        fetchTokenStatus();
    };

    // ============================================ //
    // ✅ INITIALIZATION                            //
    // ============================================ //

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            requestNotificationPermission();
            fetchTokenStatus();
        });
    } else {
        requestNotificationPermission();
        fetchTokenStatus();
    }

    // Refresh from server every 15 seconds
    setInterval(fetchTokenStatus, 15000);

})();