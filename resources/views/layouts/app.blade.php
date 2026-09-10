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
        <button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')" id="sidebar-toggle">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>

        {{-- Sidebar --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"/></svg>
                </div>
                <div class="brand-text">
                    <h2>Bank Mini</h2>
                </div>
            </div>

            <nav class="sidebar-nav">
                @hasSection('sidebar')
                    @yield('sidebar')
                @else
                    @include('layouts.sidebar')
                @endif
            </nav>

            <div class="sidebar-user">
                <div class="user-avatar">
                    @if(Auth::guard('nasabah')->check())
                        {{ strtoupper(substr(Auth::guard('nasabah')->user()->nasabah->student_name ?? 'N', 0, 1)) }}
                    @elseif(auth()->check())
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    @else
                        U
                    @endif
                </div>
                <div class="user-info">
                    <div class="user-name">
                        @if(Auth::guard('nasabah')->check())
                            {{ Auth::guard('nasabah')->user()->nasabah->student_name ?? 'Nasabah' }}
                        @elseif(auth()->check())
                            {{ auth()->user()->name ?? 'User' }}
                        @else
                            Tamu
                        @endif
                    </div>
                    <div class="user-role">
                        @if(Auth::guard('nasabah')->check())
                            Siswa Nasabah
                        @elseif(auth()->check())
                            {{ auth()->user()->role ?? 'Pegawai' }}
                        @else
                            Guest
                        @endif
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary" title="Logout" id="btn-logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="main-content">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success" id="alert-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error" id="alert-error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info" id="alert-info">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    {{ session('info') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning" id="alert-warning">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    {{ session('warning') }}
                </div>
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
