<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Bank Mini SMKN 1 Bantul</title>
    <meta name="description" content="Login ke Sistem Informasi Bank Mini SMKN 1 Bantul">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-logo">
                <div class="logo-icon">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Bantul">
                </div>
                <h1>Bank Mini SMKN 1 Bantul</h1>
                <p>Sistem Layanan Perbankan Mini Sekolah</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">✕ {{ session('error') }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="login-form">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text"
                           class="form-control"
                           id="username"
                           name="username"
                           value="{{ old('username') }}"
                           placeholder="Masukkan username"
                           required
                           autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password"
                           class="form-control"
                           id="password"
                           name="password"
                           placeholder="Masukkan password"
                           required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-login">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.alert').forEach(function(el) {
            setTimeout(function() { el.style.opacity = '0'; setTimeout(function() { el.remove(); }, 300); }, 5000);
        });
    </script>
</body>
</html>
