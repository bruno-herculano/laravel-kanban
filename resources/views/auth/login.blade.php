@extends('layouts.app')
@push('css')
    <style>
        .login-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .kanban-card {
            background: rgba(40, 40, 60, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .kanban-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
            padding: 25px 20px;
            border-bottom: none;
        }

        .card-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 30px;
        }

        .form-control {
            background: rgba(30, 30, 50, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(40, 40, 60, 0.9);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(29, 209, 161, 0.25);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-check-input {
            background-color: rgba(30, 30, 50, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-kanban {
            background: linear-gradient(135deg, var(--accent-color), #10ac84);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-kanban:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 172, 132, 0.4);
        }

        .forgot-password {
            color: var(--accent-color);
            text-decoration: none;
            transition: all 0.3s;
        }

        .forgot-password:hover {
            color: #10ac84;
            text-decoration: underline;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .login-footer a {
            color: var(--accent-color);
            text-decoration: none;
            transition: all 0.3s;
        }

        .login-footer a:hover {
            color: #10ac84;
            text-decoration: underline;
        }

        .brand-text {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .brand-text h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--accent-color), #10ac84);
            display: inline-block;
            color: rgba(255, 255, 255, 0.8);
        }

        .brand-text p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
        }

        .input-group-text {
            background: rgba(30, 30, 50, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            border-right: none;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: rgba(255, 255, 255, 0.5);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .divider::before {
            margin-right: 15px;
        }

        .divider::after {
            margin-left: 15px;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s;
        }

        .social-btn:hover {
            transform: translateY(-3px);
        }

        .btn-google {
            background: #DB4437;
        }

        .btn-facebook {
            background: #4267B2;
        }

        .btn-github {
            background: #333;
        }
    </style>

@endpush

@section('content')
    <div class="login-container">
        <div class="brand-text">
            <h1 class="text-white"><i class="fa-solid fa-chart-simple me-2"></i> Kanban-Laravel</h1>
            <p>Organize seu trabalho de forma eficiente</p>
        </div>

        <div class="kanban-card p-4">
            <div class="card-header">
                <h2><i class="fas fa-sign-in-alt me-2"></i> {{ __('Login') }}</h2>
            </div>

            <div class="card-body">
                <p>Login: teste@kanban.com</p>
                <p>Senha: password</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="seu@email.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password" placeholder="••••••">
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-success btn-kanban">
                            <i class="fas fa-sign-in-alt me-2"></i> {{ __('Login') }}
                        </button>
                    </div>

                    <div class="text-center mb-3">
                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
    </div>
                </form>
            </div>
        </div>

        <div class="login-footer">
            <p>Não tem uma conta? <a href="{{ route('register') }}">Crie uma agora</a></p>
            <p>&copy; {{ date('Y') }} Kanban-Laravel. Todos os direitos reservados.</p>
        </div>
    </div>
@endsection
