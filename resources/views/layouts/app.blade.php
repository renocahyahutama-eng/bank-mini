<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Teller Bank Mini') — Bank Mini Sekolah</title>
    <meta name="description" content="Sistem Informasi E-Teller Bank Mini Sekolah">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <div class="app-layout">
        {{-- Sidebar Toggle (Mobile) --}}
        <button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')" id="sidebar-toggle">☰</button>

        {{-- Sidebar --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">🏦</div>
                <div class="brand-text">
                    <h2>E-Teller</h2>
                    <span>Bank Mini Sekolah</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                @yield('sidebar')
            </nav>

            <div class="sidebar-user">
                <div class="user-avatar">
                    {{ strtoupper(substr($__currentUserName ?? 'U', 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ $__currentUserName ?? 'User' }}</div>
                    <div class="user-role">{{ $__currentUserRole ?? 'Guest' }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary" title="Logout" id="btn-logout">⏻</button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="main-content">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success" id="alert-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error" id="alert-error">✕ {{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info" id="alert-info">ℹ {{ session('info') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning" id="alert-warning">⚠ {{ session('warning') }}</div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-error" id="alert-validation">
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="margin: 0.5rem 0 0 1rem; list-style: disc;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Auto-dismiss alerts
        document.querySelectorAll('.alert').forEach(function(el) {
            setTimeout(function() { el.style.opacity = '0'; setTimeout(function() { el.remove(); }, 300); }, 5000);
        });
    </script>

    @yield('scripts')
</body>
</html>
