<link rel="stylesheet" href="{{ asset('css/test.css') }}">

<section class="token-status-section">
    <div class="token-display-card">
        <div class="no-token-state">
            <div class="icon-box-glow">
                <i class="fas fa-ticket-alt text-accent-cyan"></i>
            </div>
            
            <h2 class="no-token-title">No Active Token</h2>
            <p class="no-token-desc">
                You are not currently in the queue. Join now to get your position and estimated waiting time.
            </p>
            
            <div class="action-area">
                <a href="/get-token" class="btn-get-token">Join Queue Now</a>
            </div>
            
            <div class="no-token-footer">
                <p class="footer-text">
                    Estimated wait time for new patients: <strong>~25 Mins</strong>
                </p>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('js/test.js') }}"></script>