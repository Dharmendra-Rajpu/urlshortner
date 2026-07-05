<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('frontend/css/style.css')}}" rel="stylesheet">
</head>
<body>


<div class="login-shell">
  <div class="login-card">
    <div class="text-center mb-4">
      <a href="javascript:void(0)" class="brand-mark fs-4">Login</a>
    </div>

    <div class="card">
      <div class="card-body p-4">
        <form method="POST" action="{{ route('login.submit') }}">
    @csrf

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">

        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label>Password</label>

        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3 form-check">
        <input class="form-check-input"  type="checkbox" name="remember" id="remember" >
        <label class="form-check-label">
            Remember Me
        </label>

    </div>
    <button class="btn btn-brand w-100">
        Login
    </button>

</form>
      </div>
    </div>

  </div>
</div>

</body>
</html>
