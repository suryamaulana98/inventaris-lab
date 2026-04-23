<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventaris Laboratorium')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg: #f5f7fb;
            --text: #142136;
            --muted: #5f6b7a;
            --primary: #0f6fff;
            --accent: #19c5a4;
            --card: #ffffff;
            --shadow: 0 20px 45px rgba(20, 33, 54, 0.08);
            --radius: 18px;
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at 0% 0%, rgba(15, 111, 255, 0.14), transparent 42%),
                radial-gradient(circle at 100% 0%, rgba(25, 197, 164, 0.14), transparent 40%),
                var(--bg);
            color: var(--text);
        }

        .app-shell {
            min-height: 100vh;
            position: relative;
        }

        .main-nav {
            border-bottom: 1px solid rgba(20, 33, 54, 0.08);
            background: rgba(255, 255, 255, 0.84);
            backdrop-filter: blur(10px);
        }

        .brand-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: .2px;
        }

        .brand-chip .icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
        }

        .app-panel {
            background: var(--card);
            border: 1px solid rgba(20, 33, 54, 0.07);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .btn-glow {
            background: linear-gradient(135deg, var(--primary), #2357d9);
            color: #fff;
            border: none;
            box-shadow: 0 12px 26px rgba(15, 111, 255, 0.3);
        }

        .btn-glow:hover,
        .btn-glow:focus {
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-soft {
            border: 1px solid rgba(20, 33, 54, 0.12);
            background: #fff;
            color: var(--text);
        }

        .text-muted-soft {
            color: var(--muted) !important;
        }

        .badge-soft {
            border-radius: 999px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: .75rem;
        }

        .badge-soft-blue {
            background: rgba(15, 111, 255, .12);
            color: #0c4eb3;
        }

        .badge-soft-green {
            background: rgba(25, 197, 164, .12);
            color: #0f846d;
        }

        .badge-soft-orange {
            background: rgba(249, 164, 0, .16);
            color: #9d6300;
        }

        .badge-soft-red {
            background: rgba(228, 55, 90, .15);
            color: #b1103a;
        }

        .table-modern thead th {
            background: #f2f5fb;
            border: 0;
            font-size: .82rem;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: #607086;
        }

        .table-modern td,
        .table-modern th {
            vertical-align: middle;
            border-color: #edf1f7;
        }

        .section-title {
            font-weight: 700;
            letter-spacing: .1px;
        }

        .auth-wrap {
            min-height: calc(100vh - 76px);
            display: flex;
            align-items: center;
            padding: 28px 0;
        }

        .auth-hero {
            background: linear-gradient(150deg, #0d4bd1, #0f6fff 55%, #19c5a4);
            color: white;
            border-radius: 26px;
            padding: 34px;
            box-shadow: 0 24px 44px rgba(13, 75, 209, .35);
        }

        @media (max-width: 768px) {
            .auth-wrap {
                min-height: auto;
                padding: 16px 0 30px;
            }

            .auth-hero {
                padding: 22px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="app-shell">
        <nav class="navbar navbar-expand-lg main-nav sticky-top">
        <div class="container">
            <a class="navbar-brand brand-chip" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
                <span class="icon"><i class="bi bi-box-seam"></i></span>
                <span>Inventaris Lab</span>
            </a>
            <div class="ms-auto d-flex gap-2 align-items-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-soft btn-sm">Dashboard</a>
                    <a href="{{ route('items.index') }}" class="btn btn-soft btn-sm">Data Barang</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-soft btn-sm">Login</a>
                @endauth
            </div>
        </div>
    </nav>

        <main class="container py-4">
            @if (session('success'))
                <div class="alert alert-success border-0 app-panel mb-4">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 app-panel mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
