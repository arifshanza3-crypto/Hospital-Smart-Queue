<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Queue Admin')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @stack('styles')

    <style>
        /* ============================================ */
        /* GLOBAL                                       */
        /* ============================================ */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* ============================================ */
        /* MAIN CONTENT AREA                            */
        /* ============================================ */
        .main-content {
            margin-left: 280px;        /* Sidebar ki width */
            margin-top: 80px;          /* Navbar ki height */
            padding: 20px;
            min-height: calc(100vh - 80px);
            transition: margin-left 0.3s ease;
        }

        /* ============================================ */
        /* ALERT MESSAGES                               */
        /* ============================================ */
        .alert-box {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert-box.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-box.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-box i {
            font-size: 16px;
            flex-shrink: 0;
        }

        /* ============================================ */
        /* SIDEBAR OVERLAY (Mobile par)                 */
        /* ============================================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;              /* ✅ 997 se 1001 kiya (navbar 999 ke upar) */
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* ============================================ */
        /* ✅ RESPONSIVE - TABLET (max 992px)           */
        /* ============================================ */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0 !important;
                margin-top: 62px !important;
                padding: 15px;
                min-height: calc(100vh - 62px);
            }
        }

        /* ============================================ */
        /* ✅ RESPONSIVE - MOBILE (max 576px)           */
        /* ============================================ */
        @media (max-width: 576px) {
            .main-content {
                margin-left: 0 !important;
                margin-top: 58px !important;
                padding: 12px;
                min-height: calc(100vh - 58px);
            }

            .alert-box {
                padding: 11px 14px;
                font-size: 13px;
                border-radius: 8px;
                margin-bottom: 15px;
            }

            .alert-box i {
                font-size: 14px;
            }
        }

        /* ============================================ */
        /* ✅ RESPONSIVE - SMALL MOBILE (max 380px)     */
        /* ============================================ */
        @media (max-width: 380px) {
            .main-content {
                margin-top: 56px !important;
                padding: 10px;
                min-height: calc(100vh - 56px);
            }

            .alert-box {
                padding: 10px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    @include('Layout.side')
    @include('Layout.admin_nav')

    {{-- Sidebar Overlay (Mobile par sidebar khulne par dark background) --}}
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert-box success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/notification.js') }}"></script>
    @stack('scripts')
</body>
</html>
