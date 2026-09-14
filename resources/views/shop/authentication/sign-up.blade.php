<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – ShopZone</title>
    <link rel="icon" href="https://img.icons8.com/fluency/48/shopping-bag.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="auth-page">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-logo">{{ __(APPLICATION_NAME) }}</div>
                    <p class="text-center text-muted mb-4" style="font-size:.88rem">Welcome back! Sign in to your
                        account.</p>

                    <div id="alertBox" class="d-none"></div>

                    <form id="loginForm" method="POST" action="{{ route('shopping.authenticate_customer') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="ri-mail-line text-muted"></i></span>
                                <input type="email" class="form-control border-start-0" id="loginEmail" name="email"
                                    required autocomplete="email">
                            </div>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0">Password</label>
                                <a href="#" class="text-primary" style="font-size:.8rem" id="forgotPwdLink">Forgot
                                    password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="ri-lock-line text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 border-end-0"
                                    id="loginPassword" name="password" required autocomplete="current-password">
                                <button class="input-group-text bg-light border-start-0 toggle-pw" type="button"
                                    data-target="loginPassword">
                                    <i class="ri-eye-line text-muted"></i>
                                </button>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <label class="d-flex align-items-center gap-2 small" style="cursor:pointer">
                                <input type="checkbox" id="rememberMe" class="form-check-input mt-0"> Remember me
                            </label>
                        </div>
                        <button type="submit" class="btn-primary-custom w-100 justify-content-center" id="loginBtn">
                            <i class="ri-login-box-line me-2"></i>Sign In
                        </button>
                    </form>

                    <div class="text-center mt-3" style="font-size:.88rem">
                        Don't have an account? <a href="{{ route('shopping.sign_up') }}" class="text-primary fw-700">Create Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
</body>

</html>
