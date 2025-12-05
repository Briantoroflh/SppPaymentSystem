<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartSPP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-primary/10 via-accent/10 to-neutral/20 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-base-100 rounded-lg shadow-2xl p-8 border border-base-300">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary mb-2">SmartSPP</h1>
                <p class="text-base-content/70">Masuk ke akun Anda</p>
            </div>

            <!-- Toggle Switch -->
            <div class="flex items-center justify-center mb-8 bg-base-200 rounded-lg p-1">
                <input type="radio" name="login-type" id="adminRadio" value="admin" checked class="hidden" />
                <input type="radio" name="login-type" id="schoolRadio" value="school" class="hidden" />

                <label for="adminRadio" class="flex-1 py-3 px-4 text-center font-semibold cursor-pointer rounded-md transition-all duration-300 admin-label bg-primary text-white">
                    <i class="ri-shield-admin-line mr-2"></i>Super Admin
                </label>

                <label for="schoolRadio" class="flex-1 py-3 px-4 text-center font-semibold cursor-pointer rounded-md transition-all duration-300 school-label text-base-content/60 hover:text-base-content">
                    <i class="ri-building-line mr-2"></i>School Admin
                </label>
            </div>

            <!-- Forms Container -->
            <div class="forms-container">
                <!-- Admin Login Form -->
                <div id="adminForm" class="admin-form-wrapper transition-all duration-500">
                    <form id="loginFormAdmin" class="space-y-4">
                        <!-- Email Field -->
                        <div>
                            <label for="email-admin" class="block text-sm font-medium text-base-content mb-2">
                                Email
                            </label>
                            <input
                                type="email"
                                id="email-admin"
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full px-4 py-2 bg-base-200 border border-base-300 rounded-lg text-base-content placeholder-base-content/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition"
                                placeholder="Masukkan email Anda"
                                required>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password-admin" class="block text-sm font-medium text-base-content mb-2">
                                Password
                            </label>
                            <input
                                type="password"
                                id="password-admin"
                                name="password"
                                class="w-full px-4 py-2 bg-base-200 border border-base-300 rounded-lg text-base-content placeholder-base-content/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition"
                                placeholder="Masukkan password Anda"
                                required>
                        </div>

                        <!-- Login Button -->
                        <button
                            type="submit"
                            id="loginBtn"
                            class="w-full mt-6 px-4 py-2 bg-primary hover:bg-primary-focus text-white font-semibold rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                            Masuk
                        </button>
                    </form>
                </div>

                <!-- School Admin Login Form -->
                <div id="schoolForm" class="school-form-wrapper transition-all duration-500 opacity-0 hidden">
                    <form id="loginFormSchool" class="space-y-4">
                        <!-- Email Field -->
                        <div>
                            <label for="email-school" class="block text-sm font-medium text-base-content mb-2">
                                Email
                            </label>
                            <input
                                type="email"
                                id="email-school"
                                name="email"
                                class="w-full px-4 py-2 bg-base-200 border border-base-300 rounded-lg text-base-content placeholder-base-content/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition"
                                placeholder="Masukkan email Anda"
                                required>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password-school" class="block text-sm font-medium text-base-content mb-2">
                                Password
                            </label>
                            <input
                                type="password"
                                id="password-school"
                                name="password"
                                class="w-full px-4 py-2 bg-base-200 border border-base-300 rounded-lg text-base-content placeholder-base-content/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition"
                                placeholder="Masukkan password Anda"
                                required>
                        </div>

                        <!-- Login Button -->
                        <button
                            type="submit"
                            id="loginBtnSchool"
                            class="w-full mt-6 px-4 py-2 bg-primary hover:bg-primary-focus text-white font-semibold rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script text="text/javascript">
        $(document).ready(function() {
            // Toggle between Admin and School Admin forms
            $('input[name="login-type"]').on('change', function() {
                const isAdmin = $('#adminRadio').is(':checked');
                const adminForm = $('#adminForm');
                const schoolForm = $('#schoolForm');
                const adminLabel = $('.admin-label');
                const schoolLabel = $('.school-label');

                if (isAdmin) {
                    // Show admin form
                    adminForm.removeClass('opacity-0 hidden').addClass('opacity-100');
                    schoolForm.removeClass('opacity-100').addClass('opacity-0 hidden');

                    // Update labels
                    adminLabel.addClass('bg-primary text-white').removeClass('text-base-content/60');
                    schoolLabel.removeClass('bg-primary text-white').addClass('text-base-content/60 hover:text-base-content');
                } else {
                    // Show school admin form
                    schoolForm.removeClass('opacity-0 hidden').addClass('opacity-100');
                    adminForm.removeClass('opacity-100').addClass('opacity-0 hidden');

                    // Update labels
                    schoolLabel.addClass('bg-primary text-white').removeClass('text-base-content/60 hover:text-base-content');
                    adminLabel.removeClass('bg-primary text-white').addClass('text-base-content/60');
                }
            });

            // Admin Login Form
            $('#loginFormAdmin').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(this);
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('auth.login.admin') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#loginBtn').prop('disabled', true);
                        $('#loginBtn').html(`
                            <span class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sedang memproses...
                            </span>
                        `);
                    },
                    success: function(response) {
                        $('#loginBtn').html(`
                            <span class="flex items-center justify-center gap-2">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Login Berhasil!
                            </span>
                        `);
                        setTimeout(function() {
                            window.location.href = response.redirect || '/dashboard';
                        }, 1000);
                    },
                    error: function(xhr) {
                        let errorMessage = 'Login gagal. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert(errorMessage, 'error');
                        $('#loginBtn').prop('disabled', false);
                        $('#loginBtn').html('Masuk');
                    }
                });
            });

            // School Admin Login Form
            $('#loginFormSchool').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(this);
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('auth.login.school.admin') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#loginBtnSchool').prop('disabled', true);
                        $('#loginBtnSchool').html(`
                            <span class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sedang memproses...
                            </span>
                        `);
                    },
                    success: function(response) {
                        $('#loginBtnSchool').html(`
                            <span class="flex items-center justify-center gap-2">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Login Berhasil!
                            </span>
                        `);
                        setTimeout(function() {
                            window.location.href = response.redirect || '/dashboard';
                        }, 1000);
                    },
                    error: function(xhr) {
                        let errorMessage = 'Login gagal. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert(errorMessage, 'error');
                        $('#loginBtnSchool').prop('disabled', false);
                        $('#loginBtnSchool').html('Masuk');
                    }
                });
            });

            // Show Alert Function
            function showAlert(message, type = 'info') {
                let bgColor = 'bg-blue-500/20';
                let borderColor = 'border-blue-500';
                let textColor = 'text-blue-400';
                let iconColor = 'text-blue-400';
                let icon = 'ri-information-line';

                if (type === 'error') {
                    bgColor = 'bg-red-500/20';
                    borderColor = 'border-red-500';
                    textColor = 'text-red-400';
                    iconColor = 'text-red-400';
                    icon = 'ri-error-warning-line';
                } else if (type === 'success') {
                    bgColor = 'bg-green-500/20';
                    borderColor = 'border-green-500';
                    textColor = 'text-green-400';
                    iconColor = 'text-green-400';
                    icon = 'ri-check-circle-line';
                }

                const alertHTML = `
                    <div class="${bgColor} border ${borderColor} rounded-lg p-4 mb-6 flex items-start justify-between alert-item">
                        <div class="flex items-start gap-3">
                            <i class="${icon} ${iconColor} mt-0.5 flex-shrink-0 text-lg"></i>
                            <p class="${textColor} text-sm">${message}</p>
                        </div>
                        <button type="button" class="${textColor} hover:opacity-70 closeAlert">
                            <i class="ri-close-line text-lg"></i>
                        </button>
                    </div>
                `;

                // Insert at the beginning of the card
                $('.bg-base-100').prepend(alertHTML);

                // Close alert on button click
                $('.closeAlert').on('click', function() {
                    $(this).closest('.alert-item').fadeOut(300, function() {
                        $(this).remove();
                    });
                });

                // Auto-close alert after 5 seconds
                setTimeout(function() {
                    $('.alert-item').fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        });
    </script>
</body>

</html>