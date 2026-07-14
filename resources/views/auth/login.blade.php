<x-guest-layout>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .login-wrap {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0F6E5C;
            font-family: 'Figtree', sans-serif;
            padding: 24px;
            box-sizing: border-box;
            overflow-y: auto;
            z-index: 0;
        }
        .login-card {
            width: 100%;
            max-width: 460px;
            background: #fff;
            border-radius: 0;
            box-shadow: 0 30px 60px rgba(30, 39, 73, 0.25);
            overflow: hidden;
            padding: 46px 50px 42px;
        }

        /* Logo */
        .logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 22px;
        }
        .logo-text {
            font-size: 1.9rem;
            font-weight: 800;
            color: #1E2749;
            letter-spacing: -0.5px;
        }

        .login-divider {
            border: none;
            border-top: 1px solid #E7E9F2;
            margin: 0 0 26px;
        }

        .field-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6B7194;
            margin-bottom: 8px;
            display: block;
        }

        .field-input,
        .field-select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #E2E4EF;
            border-radius: 0;
            font-size: 0.95rem;
            margin-bottom: 20px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
            color: #1E2749;
            appearance: none;
            -webkit-appearance: none;
        }
        .field-input:focus,
        .field-select:focus {
            outline: none;
            border-color: #4756c9;
            box-shadow: 0 0 0 3px rgba(71, 86, 201, 0.12);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 0.85rem;
            color: #6B7194;
        }
        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #0F6E5C;
        }

        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
            font-size: 0.82rem;
        }
        .login-footer a { color: #6B7194; text-decoration: none; }
        .login-footer a:hover { color: #1E2749; }

        .login-btn {
            width: 100%;
            padding: 13px;
            background: #fff;
            color: #1E2749;
            border: 2px solid #1E2749;
            border-radius: 0;
            font-weight: 700;
            font-size: 0.98rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .login-btn:hover {
            background: #1E2749;
            color: #fff;
        }
        .login-btn svg { width: 16px; height: 16px; }

        .status-msg {
            background: #E7F8EE;
            color: #2F9E4E;
            padding: 10px 14px;
            border-radius: 0;
            font-size: 0.85rem;
            margin-bottom: 18px;
            text-align: center;
        }
        .error-msg {
            color: #E0525F;
            font-size: 0.8rem;
            margin: -14px 0 14px;
        }
    </style>

    <div class="login-wrap">
        <div class="login-card">

            <!-- Logo -->
            <div class="logo-row">
                <span class="logo-text">ITM HUB</span>
            </div>

            <hr class="login-divider">

            @session('status')
                <div class="status-msg">{{ $value }}</div>
            @endsession

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label class="field-label" for="email">Email</label>
                <input id="email" class="field-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror

                <label class="field-label" for="password">Password</label>
                <input id="password" class="field-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember_me">
                    <label for="remember_me">Remember me</label>
                </div>

                <button type="submit" class="login-btn">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 17a2 2 0 100-4 2 2 0 000 4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 11V7a6 6 0 1112 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="4" y="11" width="16" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Login
                </button>

                
            </form>

        </div>
    </div>
</x-guest-layout>