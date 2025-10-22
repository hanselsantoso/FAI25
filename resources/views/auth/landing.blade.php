<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk atau Daftar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-Ml6g6fQYQf7W5e7/jH8Yk6QXx7W3nYVd8x5jv4D5Zz6k2G5l6K8j3M9e1a0b2c3d" crossorigin="anonymous">
    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-card: rgba(15, 23, 42, 0.92);
            --border-muted: rgba(148, 163, 184, 0.4);
            --text-muted: #94a3b8;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top, #1e293b, var(--bg-dark) 55%);
            color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        a {
            color: var(--accent);
        }

        .auth-card {
            background: var(--bg-card);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 18px;
            box-shadow: 0 30px 60px -35px rgba(15, 60, 160, 0.8);
            max-width: 540px;
            margin: 0 auto;
            padding: 2.5rem 2.25rem 2rem;
        }

        .auth-heading {
            font-weight: 700;
            font-size: 1.75rem;
        }

        .auth-subheading {
            margin-top: 0.35rem;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .auth-toggle {
            display: flex;
            gap: 0.75rem;
            background: rgba(30, 41, 59, 0.6);
            padding: 0.4rem;
            border-radius: 999px;
            margin: 1.75rem 0 1.5rem;
        }

        .auth-toggle button {
            flex: 1;
            border: none;
            border-radius: 999px;
            padding: 0.55rem 0.5rem;
            font-weight: 600;
            background: transparent;
            color: var(--text-muted);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .auth-toggle button.active {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 10px 25px -12px rgba(37, 99, 235, 0.9);
        }

        .auth-form {
            display: none;
            animation: fadeIn 0.25s ease;
        }

        .auth-form.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        label {
            font-weight: 600;
            margin-bottom: 0.35rem;
            display: block;
        }

        .form-control {
            background-color: rgba(15, 23, 42, 0.85);
            border: 1px solid var(--border-muted);
            color: #e2e8f0;
            border-radius: 10px;
            padding: 0.65rem 0.75rem;
        }

        .form-control:focus {
            border-color: var(--accent);
            background-color: rgba(15, 23, 42, 0.95);
            color: #f8fafc;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        }

        .form-check-label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            border-radius: 10px;
            padding: 0.65rem 0;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        .demo-accounts ul {
            padding-left: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .demo-accounts code {
            color: #facc15;
        }

        .text-muted, small {
            color: var(--text-muted) !important;
        }

        .alert {
            border-radius: 12px;
            border: 1px solid rgba(248, 250, 252, 0.1);
        }

        @media (max-width: 576px) {
            .auth-card {
                padding: 2rem 1.5rem 1.75rem;
            }
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="auth-card p-4">
                <div class="text-center mb-4">
                    <h1 class="h3 mb-1">Selamat datang kembali</h1>
                    <p class="text-muted mb-0">Gunakan kredensial demo atau daftar sebagai pelanggan baru untuk mencoba modul Laravel.</p>
                </div>
                <div class="auth-toggle" role="tablist">
                    <button type="button" class="auth-toggle-btn active" data-target="login-form">Masuk</button>
                    <button type="button" class="auth-toggle-btn" data-target="register-form">Daftar</button>
                </div>

                <div class="auth-forms">
                    <div id="login-form" class="auth-form active" role="tabpanel" aria-labelledby="login">
                        @if ($errors->login?->any())
                            <div class="alert alert-danger">
                                <strong>Gagal masuk.</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->login->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('login.perform') }}" novalidate>
                            @csrf
                            <div class="form-group">
                                <label for="login-email">Email</label>
                                <input type="email" id="login-email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="login-password">Password</label>
                                <input type="password" id="login-password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                        </form>

                        <div class="demo-accounts mt-4">
                            <p class="text-muted mb-2">Akun demo tersedia:</p>
                            <ul class="text-muted small mb-1">
                                <li>Admin &mdash; <code>admin@example.com</code></li>
                            </ul>
                            <p class="text-muted small mb-1">Password default: <code>password</code></p>
                            <p class="text-muted small mb-0">Warehouse manager dibuat oleh admin, pelanggan dapat mendaftar mandiri.</p>
                        </div>
                    </div>

                    <div id="register-form" class="auth-form" role="tabpanel" aria-labelledby="register">
                        @if ($errors->register?->any())
                            <div class="alert alert-danger">
                                <strong>Registrasi gagal.</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->register->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('register.perform') }}" novalidate>
                            @csrf
                            <div class="form-group">
                                <label for="register-name">Nama lengkap</label>
                                <input type="text" id="register-name" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="register-email">Email</label>
                                <input type="email" id="register-email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="register-password">Password</label>
                                <input type="password" id="register-password" name="password" class="form-control" required>
                                <small class="form-text text-muted">Minimal 8 karakter, kombinasikan huruf dan angka untuk keamanan.</small>
                            </div>
                            <div class="form-group">
                                <label for="register-password_confirmation">Konfirmasi Password</label>
                                <input type="password" id="register-password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Daftar &amp; Masuk</button>
                            <p class="text-muted small text-center mt-3 mb-0">Pendaftaran menghasilkan akun pelanggan dengan peran <strong>customer</strong>. Hubungi admin jika membutuhkan akses gudang.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    (function () {
        var toggleButtons = document.querySelectorAll('.auth-toggle-btn');
        var forms = document.querySelectorAll('.auth-form');
        var hash = window.location.hash;

        function activate(targetId) {
            forms.forEach(function (form) {
                form.classList.toggle('active', form.id === targetId);
            });

            toggleButtons.forEach(function (button) {
                button.classList.toggle('active', button.dataset.target === targetId);
            });
        }

        toggleButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                activate(button.dataset.target);
            });
        });

        var hasRegisterErrors = @json($errors->register?->any());
        var hasLoginErrors = @json($errors->login?->any());

        if (hash === '#register' || hasRegisterErrors) {
            activate('register-form');
        } else if (hasLoginErrors) {
            activate('login-form');
        }
    })();
</script>
</body>
</html>
