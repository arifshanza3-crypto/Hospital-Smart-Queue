<div class="sidebar" id="mainSidebar">
    <!-- Animated Background -->
    <div class="sidebar-bg-glow"></div>
    <div class="sidebar-bg-glow-2"></div>

    <!-- Close Button (Mobile Only) -->
    <button class="sidebar-close-btn" onclick="toggleSidebar()" aria-label="Close Sidebar">
        <i class="fas fa-times"></i>
    </button>

    <!-- Brand Section -->
    <div class="brand-section">
        <div class="brand-logo-wrapper">
            <div class="logo-container">
                <img src="{{ asset('Assert/logo.png') }}" alt="SmartQueue" class="brand-logo">
                <div class="logo-ring-pulse"></div>
            </div>
            <div class="brand-text">
                <h1 class="brand-title">Smart<span>Queue</span></h1>
                <p class="brand-subtitle">Management System</p>
            </div>
        </div>
        <div class="brand-status">
            <span class="status-dot"></span>
            <span class="status-text">● Live</span>
        </div>
    </div>

    <!-- Navigation -->
    <div class="nav-section">
        <div class="nav-label">
            <span>Main Menu</span>
            <span class="nav-label-line"></span>
        </div>
        
        <a href="{{ url('/admin/doctor-management') }}" class="nav-item {{ request()->is('*doctor-management*') ? 'active' : '' }}">
            <span class="nav-icon">
                <i class="fas fa-user-md"></i>
            </span>
            <span class="nav-text">Doctors Management</span>
            <span class="nav-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>
        </a>

        <a href="{{ url('/admin/services-management') }}" class="nav-item {{ request()->is('*services-management*') ? 'active' : '' }}">
            <span class="nav-icon">
                <i class="fas fa-hand-holding-medical"></i>
            </span>
            <span class="nav-text">Services Management</span>
            <span class="nav-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>
        </a>

        <a href="{{ url('/admin/user-management') }}" class="nav-item {{ request()->is('*user-management*') ? 'active' : '' }}">
            <span class="nav-icon">
                <i class="fas fa-user-gear"></i>
            </span>
            <span class="nav-text">User Management</span>
            <span class="nav-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>
        </a>
    </div>

    <div class="nav-divider">
        <span class="divider-dot"></span>
    </div>

    <!-- Analytics Section -->
    <div class="nav-section">
        <div class="nav-label">
            <span>Analytics</span>
            <span class="nav-label-line"></span>
        </div>
        
        <a href="{{ url('/admin/report') }}" class="nav-item {{ request()->is('*report*') ? 'active' : '' }}">
            <span class="nav-icon">
                <i class="fas fa-chart-bar"></i>
            </span>
            <span class="nav-text">Queue Reports</span>
            <span class="nav-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>
        </a>

        <a href="{{ url('/admin/settings') }}" class="nav-item {{ request()->is('*settings*') ? 'active' : '' }}">
            <span class="nav-icon">
                <i class="fas fa-gears"></i>
            </span>
            <span class="nav-text">Settings</span>
            <span class="nav-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>
        </a>
    </div>

    <!-- User Profile & Logout -->
    <div class="user-section">
        <a href="#" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="logout-icon">
                <i class="fas fa-sign-out-alt"></i>
            </span>
            <span class="logout-text">Sign Out</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

<style>
    /* ============================================
       PREMIUM SIDEBAR - OCEAN BLUE THEME
       ============================================ */
    
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

    :root {
        --sidebar-width: 280px;
        --primary-dark: #0c1220;
        --primary-mid: #0f1a2e;
        --primary-light: #142a42;
        --accent-1: #0ea5e9;
        --accent-2: #3b82f6;
        --accent-3: #06b6d4;
        --accent-gradient: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 50%, #06b6d4 100%);
        --glass-bg: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.06);
        --text-primary: #ffffff;
        --text-secondary: rgba(255, 255, 255, 0.7);
        --text-muted: rgba(255, 255, 255, 0.35);
        --glow-color: rgba(14, 165, 233, 0.3);
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        height: 100dvh;
        background: linear-gradient(180deg, #0c1220 0%, #0f1a2e 50%, #142a42 100%);
        color: var(--text-primary);
        display: flex;
        flex-direction: column;
        padding: 0;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
        overflow-x: hidden;
        border-right: 1px solid var(--glass-border);
        z-index: 1002;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(20px);
    }

    /* ===== CLOSE BUTTON (Sirf mobile par dikhega) ===== */
    .sidebar-close-btn {
        display: none;
        position: absolute;
        top: 14px;
        right: 14px;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        font-size: 18px;
        cursor: pointer;
        z-index: 10;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .sidebar-close-btn:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    /* Animated Background Glows */
    .sidebar-bg-glow {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at 30% 20%, rgba(14, 165, 233, 0.08) 0%, transparent 60%);
        pointer-events: none;
        animation: glow-float 20s ease-in-out infinite;
    }

    .sidebar-bg-glow-2 {
        position: absolute;
        bottom: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at 70% 80%, rgba(6, 182, 212, 0.08) 0%, transparent 60%);
        pointer-events: none;
        animation: glow-float 25s ease-in-out infinite reverse;
    }

    @keyframes glow-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(10%, -10%) scale(1.1); }
        66% { transform: translate(-10%, 10%) scale(0.9); }
    }

    /* Custom Scrollbar */
    .sidebar::-webkit-scrollbar { width: 3px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }
    .sidebar::-webkit-scrollbar-thumb {
        background: var(--accent-1);
        border-radius: 10px;
    }

    /* ===== BRAND SECTION ===== */
    .brand-section {
        padding: 32px 24px 20px;
        border-bottom: 1px solid var(--glass-border);
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .brand-logo-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }

    .logo-container {
        position: relative;
        width: 110px;
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-logo {
        width: 90px;
        height: 90px;
        object-fit: contain;
        position: relative;
        z-index: 2;
        filter: drop-shadow(0 0 40px rgba(14, 165, 233, 0.3));
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        animation: float-logo 6s ease-in-out infinite;
    }

    .brand-logo:hover {
        transform: scale(1.05) rotate(-5deg);
        filter: drop-shadow(0 0 60px rgba(14, 165, 233, 0.5));
    }

    @keyframes float-logo {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .logo-ring-pulse {
        position: absolute;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        border: 2px solid rgba(14, 165, 233, 0.1);
        animation: ring-pulse 3s ease-in-out infinite;
    }

    .logo-ring-pulse::before {
        content: '';
        position: absolute;
        top: -4px; left: -4px; right: -4px; bottom: -4px;
        border-radius: 50%;
        border: 2px solid transparent;
        border-top-color: var(--accent-1);
        border-right-color: var(--accent-2);
        animation: ring-spin 4s linear infinite;
    }

    @keyframes ring-pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.5; }
    }

    @keyframes ring-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .brand-text { display: flex; flex-direction: column; align-items: center; }

    .brand-title {
        font-size: 28px;
        font-weight: 900;
        letter-spacing: -0.5px;
        margin: 0;
        line-height: 1.1;
        background: linear-gradient(135deg, #ffffff 0%, #93c5fd 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 0 40px rgba(14, 165, 233, 0.1);
    }

    .brand-title span {
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .brand-subtitle {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 4px;
        color: var(--text-secondary);
        margin: 4px 0 0 0;
        font-weight: 400;
        opacity: 0.6;
    }

    .brand-status {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 6px 18px;
        background: var(--glass-bg);
        border-radius: 20px;
        border: 1px solid var(--glass-border);
        margin-top: 14px;
        backdrop-filter: blur(10px);
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #34d399;
        animation: pulse-dot 2s infinite;
        box-shadow: 0 0 15px rgba(52, 211, 153, 0.3);
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.5); }
    }

    .status-text {
        font-size: 11px;
        color: var(--text-secondary);
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    /* ===== NAVIGATION ===== */
    .nav-section {
        padding: 16px 12px 8px;
        position: relative;
        z-index: 1;
    }

    .nav-label {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        padding: 0 12px 12px;
        font-weight: 600;
    }

    .nav-label-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, var(--glass-border), transparent);
    }

    .nav-item {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        text-decoration: none;
        color: var(--text-secondary);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin: 2px 0;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        position: relative;
        gap: 12px;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .nav-item .nav-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--glass-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.4s ease;
        font-size: 16px;
        border: 1px solid var(--glass-border);
        position: relative;
        overflow: hidden;
    }

    .nav-item .nav-icon::after {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--accent-gradient);
        opacity: 0;
        transition: all 0.4s ease;
        border-radius: 10px;
    }

    .nav-item .nav-icon i {
        position: relative;
        z-index: 1;
        transition: all 0.4s ease;
    }

    .nav-item .nav-text {
        flex: 1;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.85);
        letter-spacing: 0.3px;
        transition: all 0.4s ease;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .nav-item .nav-arrow {
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.4s ease;
        color: var(--text-muted);
        font-size: 11px;
    }

    .nav-item:hover {
        color: #ffffff;
        background: var(--glass-bg);
        transform: translateX(6px);
        border-color: var(--glass-border);
    }

    .nav-item:hover .nav-text { color: #ffffff; }

    .nav-item:hover .nav-icon {
        background: transparent;
        border-color: var(--accent-1);
        transform: scale(1.05);
    }

    .nav-item:hover .nav-icon::after { opacity: 0.1; }
    .nav-item:hover .nav-icon i { color: var(--accent-1); }
    .nav-item:hover .nav-arrow { opacity: 1; transform: translateX(0); }

    /* ===== ACTIVE STATE ===== */
    .nav-item.active {
        color: #ffffff;
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(59, 130, 246, 0.1) 100%);
        border-color: rgba(14, 165, 233, 0.2);
        box-shadow: 0 4px 30px rgba(14, 165, 233, 0.1);
    }

    .nav-item.active .nav-text {
        color: #ffffff;
        font-weight: 700;
        text-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
    }

    .nav-item.active::before {
        content: '';
        position: absolute;
        left: -1px;
        top: 20%;
        height: 60%;
        width: 3px;
        background: var(--accent-gradient);
        border-radius: 0 4px 4px 0;
        box-shadow: 0 0 20px rgba(14, 165, 233, 0.4);
    }

    .nav-item.active .nav-icon {
        background: var(--accent-gradient);
        border-color: transparent;
        box-shadow: 0 4px 20px rgba(14, 165, 233, 0.3);
    }

    .nav-item.active .nav-icon i { color: white; }
    .nav-item.active .nav-arrow {
        opacity: 1;
        transform: translateX(0);
        color: var(--accent-1);
    }

    /* ===== DIVIDER ===== */
    .nav-divider {
        position: relative;
        padding: 8px 20px;
        display: flex;
        justify-content: center;
    }

    .nav-divider::before {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
    }

    .divider-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--accent-1);
        box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    /* ===== USER SECTION ===== */
    .user-section {
        margin-top: auto;
        padding: 16px 16px 20px;
        border-top: 1px solid var(--glass-border);
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 1;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 12px;
        text-decoration: none;
        color: var(--text-secondary);
        transition: all 0.4s ease;
        border: 1px solid var(--glass-border);
        background: var(--glass-bg);
        cursor: pointer;
        width: 100%;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 500;
    }

    .logout-btn .logout-text {
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.08);
        border-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(239, 68, 68, 0.05);
    }

    .logout-btn:hover .logout-text { color: #ef4444; }

    .logout-btn .logout-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(239, 68, 68, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.4s ease;
        flex-shrink: 0;
    }

    .logout-btn:hover .logout-icon { background: rgba(239, 68, 68, 0.12); }

    /* ===== ANIMATIONS ===== */
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .nav-item {
        animation: slideIn 0.5s ease forwards;
        opacity: 0;
    }

    .nav-item:nth-child(1) { animation-delay: 0.05s; }
    .nav-item:nth-child(2) { animation-delay: 0.1s; }
    .nav-item:nth-child(3) { animation-delay: 0.15s; }
    .nav-item:nth-child(4) { animation-delay: 0.2s; }
    .nav-item:nth-child(5) { animation-delay: 0.25s; }
    .nav-item:nth-child(6) { animation-delay: 0.3s; }

    /* ============================================ */
    /* ✅ RESPONSIVE - TABLET (max 992px)           */
    /* ============================================ */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
            box-shadow: 4px 0 40px rgba(0, 0, 0, 0.5);
        }
        .sidebar.active {
            transform: translateX(0);
        }
        .sidebar-close-btn {
            display: flex;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - MOBILE (max 576px)           */
    /* ============================================ */
    @media (max-width: 576px) {
        .sidebar {
            width: 260px;
        }
        
        .brand-section {
            padding: 24px 18px 16px;
        }

        .brand-title { font-size: 22px; }
        
        .logo-container {
            width: 80px;
            height: 80px;
        }
        
        .brand-logo {
            width: 64px;
            height: 64px;
        }

        .logo-ring-pulse {
            width: 80px;
            height: 80px;
        }
        
        .brand-subtitle {
            font-size: 9px;
            letter-spacing: 3px;
        }

        .brand-status {
            padding: 5px 14px;
            font-size: 10px;
        }
        
        .nav-section {
            padding: 12px 10px 6px;
        }

        .nav-item {
            padding: 9px 12px;
            font-size: 13px;
            gap: 10px;
        }
        
        .nav-item .nav-icon {
            width: 34px;
            height: 34px;
            font-size: 14px;
        }

        .nav-item .nav-arrow {
            opacity: 1; /* Mobile par arrow visible rahe */
            transform: translateX(0);
        }

        .user-section {
            padding: 12px 12px 16px;
        }

        .logout-btn {
            padding: 9px 12px;
            font-size: 13px;
        }

        .logout-btn .logout-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - SMALL MOBILE (max 380px)     */
    /* ============================================ */
    @media (max-width: 380px) {
        .sidebar {
            width: 240px;
        }

        .brand-title { font-size: 20px; }

        .logo-container {
            width: 70px;
            height: 70px;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
        }

        .logo-ring-pulse {
            width: 70px;
            height: 70px;
        }

        .nav-item {
            padding: 8px 10px;
            font-size: 12px;
        }

        .nav-item .nav-icon {
            width: 32px;
            height: 32px;
            font-size: 13px;
        }
    }
</style>

<script>
    // ============================================
    // ✅ SIDEBAR MOBILE BEHAVIOR
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('mainSidebar');
        if (!sidebar) return;

        // Close sidebar on nav item click (mobile)
        const navItems = sidebar.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    // Small delay taake click register ho jaye
                    setTimeout(() => {
                        sidebar.classList.remove('active');
                        const overlay = document.querySelector('.sidebar-overlay');
                        if (overlay) overlay.classList.remove('active');
                        document.body.style.overflow = '';
                    }, 150);
                }
            });
        });

        // Escape key se sidebar band karo
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                const overlay = document.querySelector('.sidebar-overlay');
                if (overlay) overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Body scroll lock jab sidebar khule
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    if (sidebar.classList.contains('active')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                }
            });
        });

        observer.observe(sidebar, { attributes: true });
    });
</script>