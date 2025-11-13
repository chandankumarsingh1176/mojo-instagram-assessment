<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Login - Mojo Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { max-width: 400px; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
    <div class="login-card bg-white">
        <div class="text-center mb-4">
            <h2 class="text-primary">Welcome to Mojo Instagram Tool</h2>
            <p class="text-muted">Login with Instagram to view profile, feeds, and reply to comments.</p>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <a href="{{ route('auth.facebook') }}" class="btn btn-primary w-100 mb-3">
            <i class="fab fa-instagram me-2"></i> Login with Instagram
        </a>
        <div class="text-center">
            <small class="text-muted">Powered by Laravel & Instagram Graph API</small>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/your-fa-kit.js" crossorigin="anonymous"></script> <!-- Add FontAwesome for icons -->
</body>
</html>
