@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Game Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: white;
        }

        .form-label {
            color: #e0e0e0;
        }

        .form-control, .form-check-input {
            background-color: rgba(255,255,255,0.1);
            border: none;
            color: white;
        }

        .form-control:focus {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }

        .btn-primary {
            background-color: #00c9ff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #00a4cc;
        }

        a {
            color: #aee3f9;
        }

        .invalid-feedback {
            color: #ffcccc;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="glass-card">
                <h3 class="text-center mb-4">Login to Game Store</h3>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autofocus>

                        @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required>

                        @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

{{-- Submit Button --}}
<div class="d-grid mb-3">
    <button type="submit" class="btn btn-primary">
        {{ __('Login') }}
    </button>
</div>

{{-- Forgot Password aa --}}
@if (Route::has('password.request'))
    <div class="text-center mb-2">
        <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
    </div>
@endif

{{-- Register Link --}}
@if (Route::has('register'))
    <div class="text-center">
        <span class="text-light">Don't have an account?</span>
        <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm mt-2">
            {{ __('Register') }}
        </a>
    </div>
@endif

                </form>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert Notification --}}
@if (session('status'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('status') }}",
            icon: "success",
            confirmButtonText: "OK"
        });
    </script>
@endif

</body>
</html>
