<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sneat</title>
    <!-- Add your CSS files here -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/demo.css') }}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendor/css/core.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendor/css/pages/page-auth.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendor/css/pages/page-account-settings.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendor/css/pages/page-icons.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendor/css/pages/page-mics.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendor/css/theme-default.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendor/fonts/boxicons.css')}}">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .authentication-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: #f4f4f4;
        }

        .authentication-inner {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .app-brand {
            margin-bottom: 20px;
            text-align: center;
        }

        .app-brand-text {
            font-size: 24px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="authentication-wrapper">
        <div class="authentication-inner">
            <!-- Login -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img src="{{ asset('backend/assets/img/logo.png') }}" alt="Sneat Logo" width="50">
                            </span>
                            <span class="app-brand-text demo text-body fw-bolder">Sneat</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Welcome to Sneat! 👋</h4>
                    <p class="mb-4">Please sign in to your account and start the adventure</p>

                    <!-- Authentication Form -->
                    <form method="POST" action="{{ route('login') }}" id="formAuthentication" class="mb-3">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-merge">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password" placeholder="············">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-eye-slash toggle-password"></i>
                                    </span>
                                </div>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary d-grid w-100">Sign in</button>
                        </div>
                    </form>

                    <p class="text-center">
                        <span>New on our platform?</span>
                        <a href="{{ route('register') }}">
                            <span>Create an account</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
    <!-- Include JavaScript files here if needed -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Toggle password visibility
        $('.toggle-password').click(function () {
            $(this).toggleClass('fa-eye fa-eye-slash');
            let input = $(this).closest('.input-group').find('input');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });
    </script>
</body>

</html>
