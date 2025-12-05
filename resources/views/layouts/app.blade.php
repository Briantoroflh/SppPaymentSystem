<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css"
        rel="stylesheet" />
    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"></script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out forwards;
        }

        .animate-slide-out {
            animation: slideOut 0.3s ease-in-out forwards;
        }
    </style>
</head>

<body class="bg-base-200 font-sans">
    <div class="flex min-h-screen">
        <!-- SIDEBAR -->
        @include('layouts.sidebar')
        <!-- END SIDEBAR -->

        <!-- Main Content -->
        <main class="flex-1 md:ml-72 bg-base-100">
            @yield('section')
        </main>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
    @stack('additional-js')
    @stack('additional-css')

    <script>
        // Theme Management
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'system';
            setTheme(savedTheme);
        }

        function setTheme(theme) {
            let appliedTheme = theme;

            if (theme === 'system') {
                appliedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            // Set HTML attribute for DaisyUI
            document.documentElement.setAttribute('data-theme', appliedTheme);

            // Save preference
            localStorage.setItem('theme', theme);

            // Update icon
            updateThemeIcon(appliedTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (theme === 'dark') {
                icon.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />';
            } else {
                icon.innerHTML = '<circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>';
            }
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (localStorage.getItem('theme') === 'system') {
                setTheme('system');
            }
        });

        // Mobile Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            initTheme();

            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggleMobile');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function() {
                    const isVisible = sidebar.getAttribute('data-visible') === 'true';
                    sidebar.setAttribute('data-visible', !isVisible);

                    if (!isVisible) {
                        sidebar.style.transform = 'translateX(0)';
                    } else {
                        sidebar.style.transform = 'translateX(-100%)';
                    }
                });
            }
        });
    </script>
</body>

</html>