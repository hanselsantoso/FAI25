<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Landing</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-Ml6g6fQYQf7W5e7/jH8Yk6QXx7W3nYVd8x5jv4D5Zz6k2G5l6K8j3M9e1a0b2c3d" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            color: #e2e8f0;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        .message-card {
            background: rgba(15, 23, 42, 0.9);
            padding: 2rem 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 30px 60px -40px rgba(15, 23, 42, 0.9);
            text-align: center;
        }
        a {
            color: #38bdf8;
        }
    </style>
</head>
<body>
    <div class="message-card">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <h1 class="h3 mb-3">Halo, {{ auth()->user()->name ?? 'Customer' }}!</h1>
        <p class="mb-3">Halaman pelanggan masih kosong sementara. Konten akan ditambahkan pada iterasi berikutnya.</p>
        <p class="small text-muted mb-4">Silakan kembali lagi nanti atau hubungi admin jika membutuhkan akses tambahan.</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</body>
</html>
