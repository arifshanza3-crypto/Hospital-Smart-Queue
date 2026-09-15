/**
 * Get Token - JavaScript
 * Handles "No Token" state and live countdown
 */

document.addEventListener('DOMContentLoaded', () => {
    const tokenCard = document.getElementById('tokenCardContainer');

    if (!tokenCard) {
        console.error('❌ tokenCardContainer element not found!');
        return;
    }
    
    // 1. Check if user has a token (Simulated)
    // Change this to true to see the live countdown
    let hasToken = false; 

    if (!hasToken) {
        renderNoTokenView();
    } else {
        initCountdown(12, 24);
    }

    // ============================================
    // 2. Render "No Token" View
    // ============================================
    function renderNoTokenView() {
        tokenCard.innerHTML = `
            <div class="no-token-view">
                <div class="no-token-icon-box">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#00d4ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 9V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4M2 15v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4M2 12h20"></path>
                    </svg>
                </div>
                <h2 class="no-token-title">No Active Token</h2>
                <p class="no-token-desc">You don't have an active appointment. Join the queue to see a specialist.</p>
                <button id="goToServices" class="btn-get-token">Join Queue Now</button>
            </div>
        `;

        // REDIRECTION LOGIC
        const goToServices = document.getElementById('goToServices');
        if (goToServices) {
            goToServices.addEventListener('click', () => {
                window.location.href = "/services";
            });
        }
    }

    // ============================================
    // 3. Countdown Logic
    // ============================================
    function initCountdown(mins, secs) {
        let total = (mins * 60) + secs;
        const display = document.getElementById('remainingTime');

        if (!display) {
            console.warn('⚠️ remainingTime element not found');
            return;
        }

        const timer = setInterval(() => {
            let m = Math.floor(total / 60);
            let s = total % 60;

            display.innerHTML = `${m < 10 ? '0' + m : m}:${s < 10 ? '0' + s : s}`;

            if (total <= 0) {
                clearInterval(timer);
                display.innerHTML = "READY";
                display.style.color = "#00ff88";
            }
            total--;
        }, 1000);
    }
});