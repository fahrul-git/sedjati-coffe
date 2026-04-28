<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sedjati Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark-subtle">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h3 mb-3">Login Sedjati Coffee</h1>
                        <p class="text-muted">Masuk sebagai admin atau kasir untuk mengelola operasional cafe.</p>

                        <form action="{{ route('login.store') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label">Ingat saya</label>
                            </div>

                            <button type="submit" class="btn btn-dark w-100">Login</button>
                        </form>

                        <div class="mt-4 small text-muted">
                            Admin: admin@sedjaticoffee.test / password<br>
                            Kasir: kasir@sedjaticoffee.test / password
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
