<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sedjati Coffee' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg: #1f2024;
            --shell: #f8f5f1;
            --surface: #ffffff;
            --surface-soft: #fbf8f4;
            --line: #ece5dc;
            --text: #2f241c;
            --muted: #8d7e71;
            --brand: #5f3b22;
            --brand-2: #8b5e3c;
            --good: #1e8f5d;
            --warn: #f2a23a;
            --danger: #d85c4e;
            --shadow: 0 18px 45px rgba(31, 18, 8, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--text);
            background:
                radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.10) 1px, transparent 0) 0 0 / 14px 14px,
                var(--bg);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .admin-shell {
            max-width: 1680px;
            min-height: calc(100vh - 24px);
            margin: 12px auto;
            padding: 0 12px;
        }

        .workspace {
            display: grid;
            grid-template-columns: 255px minmax(0, 1fr);
            gap: 14px;
            align-items: start;
        }

        .sidebar,
        .workspace-panel {
            background: var(--shell);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            box-shadow: var(--shadow);
        }

        .sidebar {
            padding: 16px 14px;
            position: sticky;
            top: 12px;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--brand) 0%, #352012 100%);
            color: #fff;
            font-size: 0.95rem;
            box-shadow: 0 10px 20px rgba(95, 59, 34, 0.25);
        }

        .brand-meta small,
        .muted-copy {
            color: var(--muted);
        }

        .sidebar-nav {
            margin-top: 20px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 12px 12px;
            border-radius: 12px;
            color: #4c4036;
            font-size: 0.95rem;
            margin-bottom: 6px;
            transition: 0.18s ease;
        }

        .sidebar-link i {
            width: 18px;
            font-size: 0.95rem;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #5d3418;
            background: #efe8e1;
        }

        .sidebar-link.active {
            font-weight: 600;
        }

        .sidebar-footer-btn {
            margin-top: 22px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3f2616 0%, #5e3c24 100%);
            color: #fff;
            border: 0;
            width: 100%;
            padding: 12px 14px;
            text-align: left;
            font-weight: 600;
        }

        .workspace-panel {
            padding: 14px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 12px 16px;
            margin-bottom: 14px;
        }

        .topbar-search {
            position: relative;
            flex: 1 1 420px;
            max-width: 460px;
        }

        .topbar-search input {
            border-radius: 999px;
            border: 1px solid #eee5dc;
            background: #faf8f5;
            padding-left: 38px;
            height: 40px;
            color: var(--text);
        }

        .topbar-search i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.9rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-chip {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            border: 1px solid var(--line);
            color: #6d5c4f;
            background: #fff;
        }

        .avatar-chip {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 700;
            background: linear-gradient(135deg, #4f2913 0%, #138d90 100%);
        }

        .page-grid {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 18px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 18px;
        }

        .page-eyebrow {
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 700;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 4px 0;
        }

        .page-subtitle {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .action-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-brand {
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, #4a2d18 0%, #6f472b 100%);
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 600;
        }

        .btn-light-soft {
            border: 1px solid var(--line);
            background: #fff;
            color: #54453a;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 600;
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .metric-card {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 16px;
            background: var(--surface-soft);
            min-height: 116px;
        }

        .metric-card.featured {
            color: #fff;
            background: linear-gradient(135deg, #5c3b23 0%, #7b5132 100%);
            border-color: transparent;
        }

        .metric-card .metric-icon {
            position: absolute;
            right: 12px;
            bottom: 8px;
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.16);
        }

        .metric-label {
            color: var(--muted);
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .metric-card.featured .metric-label,
        .metric-card.featured .metric-foot {
            color: rgba(255, 255, 255, 0.72);
        }

        .metric-value {
            margin: 8px 0 4px;
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .metric-foot {
            font-size: 0.84rem;
            color: var(--muted);
        }

        .dashboard-panels {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(300px, 0.85fr);
            gap: 14px;
            margin-bottom: 16px;
        }

        .panel-card {
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 16px;
            background: var(--surface);
        }

        .panel-heading {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 14px;
        }

        .panel-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
        }

        .panel-subtitle {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: 0.84rem;
        }

        .micro-select {
            border-radius: 10px;
            border: 1px solid var(--line);
            padding: 6px 10px;
            font-size: 0.82rem;
            color: #6b5a4d;
            background: #fff;
        }

        .bar-chart {
            height: 210px;
            display: flex;
            align-items: end;
            gap: 10px;
            padding-top: 10px;
        }

        .bar-wrap {
            flex: 1;
            text-align: center;
        }

        .bar-stack {
            height: 170px;
            display: flex;
            align-items: end;
            justify-content: center;
            gap: 6px;
        }

        .bar {
            width: 24px;
            border-radius: 10px 10px 4px 4px;
            background: #e9e2da;
        }

        .bar.accent {
            background: #4d361f;
        }

        .bar.soft {
            background: #c79871;
        }

        .bar-label {
            margin-top: 8px;
            font-size: 0.74rem;
            color: var(--muted);
            text-transform: uppercase;
        }

        .trend-list {
            display: grid;
            gap: 10px;
        }

        .trend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--surface-soft);
        }

        .trend-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, #3c2619 0%, #da8f5e 100%);
            font-size: 1rem;
        }

        .section-table {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--surface);
            overflow: hidden;
        }

        .section-table-header {
            padding: 16px 18px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .table-clean {
            margin: 0;
        }

        .table-clean thead th {
            color: var(--muted);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            padding: 12px 18px;
            border-bottom: 1px solid var(--line);
            background: #fcfaf7;
        }

        .table-clean tbody td {
            padding: 14px 18px;
            vertical-align: middle;
            border-top: 1px solid #f1ebe4;
            font-size: 0.95rem;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .status-success {
            color: #4b7b5d;
            background: #e8f3ea;
        }

        .status-warning {
            color: #9a6f22;
            background: #fdf0da;
        }

        .status-danger {
            color: #ad4b48;
            background: #fde6e4;
        }

        .menu-toolbar,
        .orders-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .menu-card {
            border: 1px solid var(--line);
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            position: relative;
        }

        .menu-visual {
            height: 176px;
            display: flex;
            align-items: end;
            justify-content: start;
            padding: 12px;
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: linear-gradient(145deg, #24170f 0%, #765034 100%);
        }

        .menu-card:nth-child(2) .menu-visual { background: linear-gradient(145deg, #7a5439 0%, #d6b08c 100%); }
        .menu-card:nth-child(3) .menu-visual { background: linear-gradient(145deg, #1f1512 0%, #8a4f1f 100%); }
        .menu-card:nth-child(4) .menu-visual { background: linear-gradient(145deg, #6b6b6b 0%, #b7b7b7 100%); }
        .menu-card:nth-child(5) .menu-visual { background: linear-gradient(145deg, #2b1f1b 0%, #9d6b3d 100%); }

        .menu-card-body {
            padding: 14px;
        }

        .badge-flag {
            display: inline-flex;
            margin-bottom: 10px;
            border-radius: 999px;
            font-size: 0.68rem;
            padding: 4px 9px;
            font-weight: 700;
            color: #fff;
            background: #22160f;
        }

        .stock-note {
            font-size: 0.74rem;
            font-weight: 700;
            margin-top: 8px;
        }

        .stock-good { color: #2d8c5b; }
        .stock-low { color: #d07d2d; }
        .stock-empty { color: #c65850; }

        .ghost-card {
            display: grid;
            place-items: center;
            min-height: 320px;
            border: 2px dashed #e8ddd2;
            color: var(--muted);
            border-radius: 18px;
            background: #fcfaf8;
            text-align: center;
            padding: 20px;
        }

        .customer-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #efe7de;
            color: #7a5a44;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .action-icons {
            display: inline-flex;
            gap: 8px;
            color: #8d7e71;
        }

        .action-icons i {
            cursor: default;
        }

        @media (max-width: 1200px) {
            .metric-grid,
            .menu-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-panels {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .workspace {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .metric-grid,
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .topbar-search {
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="admin-shell">
        <div class="workspace">
            <aside class="sidebar">
                <div class="d-flex align-items-center gap-3">
                    <div class="brand-mark"><i class="bi bi-cup-hot"></i></div>
                    <div class="brand-meta">
                        <div class="fw-bold">Admin Portal</div>
                        <small>Main Branch</small>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                    <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="bi bi-cup-straw"></i> Menu
                    </a>
                    <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i> Orders
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-people"></i> Customers
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </nav>

                <a href="{{ route('orders.create') }}" class="sidebar-footer-btn d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i> New Order
                </a>
            </aside>

            <section class="workspace-panel">
                <div class="topbar">
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <div>
                            <div class="fw-semibold">{{ $topbarTitle ?? 'Sedjati Coffee' }}</div>
                            <small class="muted-copy">{{ $topbarSubtitle ?? 'Coffee operations dashboard' }}</small>
                        </div>
                        <div class="topbar-search">
                            <i class="bi bi-search"></i>
                            <input type="search" class="form-control" placeholder="{{ $topbarSearchPlaceholder ?? 'Search orders, menu, customers...' }}">
                        </div>
                    </div>
                    <div class="topbar-actions">
                        <span class="icon-chip"><i class="bi bi-bell"></i></span>
                        <span class="icon-chip"><i class="bi bi-cart3"></i></span>
                        <div class="text-end d-none d-md-block">
                            <div class="fw-semibold small">{{ auth()->user()->name ?? 'Admin User' }}</div>
                            <small class="muted-copy text-uppercase">{{ auth()->user()->role ?? 'Manager' }}</small>
                        </div>
                        <div class="avatar-chip">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </section>
        </div>
    </div>
</body>
</html>
