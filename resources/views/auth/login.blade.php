<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sedjati Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            color: #f8f5f0;
            background:
                radial-gradient(circle at 1px 1px, rgba(255,255,255,0.12) 1px, transparent 0) 0 0 / 16px 16px,
                linear-gradient(135deg, #201b19 0%, #2d211a 50%, #452a16 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(320px, 1.05fr) minmax(420px, 0.95fr);
        }

        .login-showcase {
            padding: 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .login-card {
            margin: 28px;
            border-radius: 28px;
            background: #f8f5f1;
            color: #2e241c;
            padding: 40px;
            box-shadow: 0 28px 60px rgba(0, 0, 0, 0.28);
            align-self: center;
            width: min(100%, 520px);
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #5a361f 0%, #8a5c36 100%);
            color: #fff;
            box-shadow: 0 18px 30px rgba(90, 54, 31, 0.26);
        }

        .hero-panel {
            max-width: 540px;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255,255,255,0.14);
            background: rgba(255,255,255,0.05);
            color: #f0e5dc;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 0.78rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .hero-title {
            font-size: clamp(2.4rem, 4vw, 4rem);
            font-weight: 700;
            line-height: 1.05;
            margin: 22px 0 14px;
        }

        .hero-copy {
            max-width: 500px;
            color: rgba(255,255,255,0.72);
            font-size: 1rem;
            line-height: 1.75;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .feature-card {
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 18px;
            padding: 16px;
            background: rgba(255,255,255,0.05);
        }

        .feature-card .stat {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .feature-card small {
            color: rgba(255,255,255,0.62);
        }

        .soft-input {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid #e8ddd2;
            background: #fcfaf7;
        }

        .role-hint {
            border: 1px solid #ece4dc;
            border-radius: 18px;
            background: #fcfaf8;
            padding: 16px;
        }

        @media (max-width: 992px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-showcase {
                padding: 28px 24px 0;
            }

            .login-card {
                margin: 0 24px 28px;
                width: auto;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <section class="login-showcase">
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="brand-mark"><i class="bi bi-cup-hot"></i></div>
                    <div>
                        <div class="fw-semibold">Sedjati Coffee</div>
                        <small class="text-white-50">Ruang Kerja Admin & Kasir</small>
                    </div>
                </div>

                <div class="hero-panel mt-5">
                    <div class="hero-kicker"><i class="bi bi-shield-lock"></i> Akses Aman</div>
                    <h1 class="hero-title">Kelola operasional cafe dari satu dashboard modern.</h1>
                    <p class="hero-copy">
                        Masuk sebagai admin atau kasir untuk memantau menu, transaksi, meja aktif, dan alur pembayaran
                        Sedjati Coffee secara lebih cepat dan rapi.
                    </p>
                </div>
            </div>

            <div class="feature-grid">
                <div class="feature-card">
                    <div class="stat">Pesanan Aktif</div>
                    <small>Pantau transaksi dan status pembayaran dalam satu panel kerja.</small>
                </div>
                <div class="feature-card">
                    <div class="stat">Inventaris Menu</div>
                    <small>Kelola stok kopi dan side dish dengan tampilan yang lebih visual.</small>
                </div>
                <div class="feature-card">
                    <div class="stat">Laporan Harian</div>
                    <small>Ringkas penjualan harian dan ekspor data laporan secara cepat.</small>
                </div>
            </div>
        </section>

        <aside class="login-card">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="brand-mark"><i class="bi bi-box-arrow-in-right"></i></div>
                <div>
                    <div class="fw-semibold">Masuk ke Sistem</div>
                    <small class="text-muted">Gunakan akun admin atau kasir yang tersedia.</small>
                </div>
            </div>

            <h1 class="h3 mb-2">Login Sedjati Coffee</h1>
            <p class="text-muted mb-4">Silakan login untuk mengakses dashboard Sedjati Coffee.</p>

            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control soft-input" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control soft-input" required>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-dark w-100 rounded-4 py-3">Login Sekarang</button>
            </form>

            <div class="role-hint mt-4">
                <div class="fw-semibold mb-2">Akun Demo</div>
                <div class="small text-muted mb-1">Admin: admin@sedjaticoffee.test / password</div>
                <div class="small text-muted">Kasir: kasir@sedjaticoffee.test / password</div>
            </div>
        </aside>
    </div>
</body>
</html>
