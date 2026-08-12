<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in · Inventory</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --bg: #070b16;
            --panel: rgba(12, 18, 36, 0.72);
            --line: rgba(255, 255, 255, 0.1);
            --text: #f5f7ff;
            --muted: #9aa6c3;
            --accent: #6d7cff;
            --accent-2: #22d3ee;
            --danger: #fb7185;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        .scene {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            position: relative;
        }

        .orb,
        .grid,
        .glow {
            position: absolute;
            pointer-events: none;
        }

        .glow {
            inset: 0;
            background:
                radial-gradient(800px 500px at 15% 20%, rgba(109, 124, 255, 0.28), transparent 55%),
                radial-gradient(700px 420px at 85% 80%, rgba(34, 211, 238, 0.16), transparent 50%);
            animation: pulseGlow 8s ease-in-out infinite;
        }

        .orb {
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.7;
        }

        .orb-a {
            width: 280px;
            height: 280px;
            left: 8%;
            top: 18%;
            background: #6d7cff;
            animation: float 10s ease-in-out infinite;
        }

        .orb-b {
            width: 220px;
            height: 220px;
            right: 12%;
            bottom: 10%;
            background: #22d3ee;
            animation: float 12s ease-in-out infinite reverse;
        }

        .grid {
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(circle at center, black 30%, transparent 80%);
        }

        .brand-side {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            overflow: hidden;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--text);
            text-decoration: none;
            font-weight: 800;
            letter-spacing: -0.04em;
            font-size: 22px;
            animation: fadeUp 0.8s ease both;
        }

        .logo-mark {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            box-shadow: 0 10px 30px rgba(109, 124, 255, 0.35);
        }

        .hero {
            max-width: 520px;
            animation: fadeUp 0.9s 0.1s ease both;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #c7d2fe;
            background: rgba(109, 124, 255, 0.12);
            border: 1px solid rgba(109, 124, 255, 0.25);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 22px;
        }

        h1 {
            font-size: clamp(36px, 5vw, 64px);
            line-height: 0.95;
            letter-spacing: -0.05em;
            margin-bottom: 18px;
        }

        h1 span {
            background: linear-gradient(90deg, #fff, #a5b4fc 45%, #67e8f9);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero p {
            color: var(--muted);
            font-size: 16px;
            line-height: 1.7;
            max-width: 440px;
        }

        .stats {
            display: flex;
            gap: 28px;
            margin-top: 40px;
        }

        .stat strong {
            display: block;
            font-size: 22px;
            margin-bottom: 4px;
        }

        .stat span {
            color: var(--muted);
            font-size: 13px;
        }

        .cubes {
            position: absolute;
            right: 6%;
            bottom: 12%;
            width: 280px;
            height: 220px;
        }

        .cube {
            position: absolute;
            width: 84px;
            height: 84px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: linear-gradient(145deg, rgba(109, 124, 255, 0.28), rgba(34, 211, 238, 0.08));
            border-radius: 18px;
            backdrop-filter: blur(8px);
            animation: drift 7s ease-in-out infinite;
        }

        .cube:nth-child(1) { left: 20px; top: 30px; animation-delay: 0s; }
        .cube:nth-child(2) { left: 110px; top: 0; animation-delay: 0.6s; }
        .cube:nth-child(3) { left: 90px; top: 100px; animation-delay: 1.1s; }

        .form-side {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }

        .card {
            width: 100%;
            max-width: 440px;
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: 36px 32px;
            backdrop-filter: blur(22px);
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
            animation: fadeUp 0.85s 0.15s ease both;
        }

        .card h2 {
            font-size: 28px;
            letter-spacing: -0.04em;
            margin-bottom: 8px;
        }

        .card .sub {
            color: var(--muted);
            margin-bottom: 28px;
            font-size: 14px;
        }

        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #c5cde3;
            margin-bottom: 8px;
        }

        .control {
            position: relative;
        }

        .control i.lead {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b97b8;
            font-size: 14px;
        }

        .control input {
            width: 100%;
            height: 52px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text);
            border-radius: 14px;
            padding: 0 44px 0 42px;
            font-size: 15px;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s, transform 0.25s;
        }

        .control input::placeholder {
            color: #7d89a8;
        }

        .control input:focus {
            border-color: rgba(109, 124, 255, 0.8);
            background: rgba(109, 124, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(109, 124, 255, 0.16);
            transform: translateY(-1px);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b97b8;
            cursor: pointer;
            background: none;
            border: 0;
            font-size: 15px;
        }

        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 8px 0 22px;
            color: var(--muted);
            font-size: 13px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember input {
            accent-color: var(--accent);
        }

        .btn {
            width: 100%;
            height: 52px;
            border: 0;
            border-radius: 14px;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.01em;
            cursor: pointer;
            background: linear-gradient(135deg, #6d7cff, #4f46e5 55%, #22d3ee);
            background-size: 180% 180%;
            box-shadow: 0 12px 30px rgba(109, 124, 255, 0.35);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            animation: gradientMove 4s ease infinite;
        }

        .btn::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 20%, rgba(255, 255, 255, 0.28), transparent 80%);
            transform: translateX(-120%);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(109, 124, 255, 0.45);
        }

        .btn:hover::after {
            animation: shine 0.8s ease;
        }

        .error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 7px;
            animation: fadeUp 0.35s ease;
        }

        .control input.is-invalid {
            border-color: rgba(251, 113, 133, 0.7);
        }

        .footer-link {
            text-align: center;
            margin-top: 22px;
            color: var(--muted);
            font-size: 14px;
        }

        .footer-link a {
            color: #c7d2fe;
            font-weight: 700;
            text-decoration: none;
        }

        .footer-link a:hover {
            color: #fff;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-24px) scale(1.05); }
        }

        @keyframes drift {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-16px) rotate(8deg); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes shine {
            to { transform: translateX(120%); }
        }

        @media (max-width: 980px) {
            .scene {
                grid-template-columns: 1fr;
            }

            .brand-side {
                min-height: 280px;
                padding: 28px 24px 12px;
            }

            .cubes,
            .stats {
                display: none;
            }

            .form-side {
                padding: 16px 16px 36px;
            }

            h1 {
                font-size: 36px;
            }
        }
    </style>
</head>

<body>
    <div class="glow"></div>
    <div class="grid"></div>
    <div class="orb orb-a"></div>
    <div class="orb orb-b"></div>

    <div class="scene">
        <section class="brand-side">
            <a href="{{ url('/') }}" class="logo">
                <span class="logo-mark">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M3 7.5L12 3l9 4.5v9L12 21l-9-4.5v-9z" stroke="white" stroke-width="1.8"/>
                        <path d="M12 12l9-4.5M12 12v9M12 12L3 7.5" stroke="white" stroke-width="1.8"/>
                    </svg>
                </span>
                Inventory
            </a>

            <div class="hero">
                <div class="eyebrow">Smart stock control</div>
                <h1>Welcome back to <span>your warehouse.</span></h1>
                <p>Sign in with your email or username and keep products, purchases, and invoices moving in one place.</p>
                <div class="stats">
                    <div class="stat"><strong>Live</strong><span>Stock visibility</span></div>
                    <div class="stat"><strong>Fast</strong><span>Invoice flow</span></div>
                    <div class="stat"><strong>Secure</strong><span>Role based access</span></div>
                </div>
            </div>

            <div class="cubes" aria-hidden="true">
                <div class="cube"></div>
                <div class="cube"></div>
                <div class="cube"></div>
            </div>
        </section>

        <section class="form-side">
            <div class="card">
                <h2>Sign in</h2>
                <p class="sub">Use your email or username to continue.</p>

                <form method="POST" action="{{ route('login') }}" id="formAuthentication">
                    @csrf

                    <div class="field">
                        <label for="login">Email or Username</label>
                        <div class="control">
                            <i class="fa-solid fa-user lead"></i>
                            <input id="login" type="text"
                                class="@error('login') is-invalid @enderror"
                                name="login"
                                value="{{ old('login') }}"
                                required autofocus
                                autocomplete="username"
                                placeholder="admin@gmail.com or superadmin">
                        </div>
                        @error('login')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="control">
                            <i class="fa-solid fa-lock lead"></i>
                            <input id="password" type="password"
                                class="@error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password">
                            <button type="button" class="toggle-password" aria-label="Show password">
                                <i class="fa-solid fa-eye-slash"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row-between">
                        <label class="remember">
                            <input type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn">Sign in</button>
                </form>

                <p class="footer-link">
                    New on our platform?
                    <a href="{{ route('register') }}">Create an account</a>
                </p>
            </div>
        </section>
    </div>

    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.classList.toggle('fa-eye', show);
            icon.classList.toggle('fa-eye-slash', !show);
        });
    </script>
</body>

</html>
