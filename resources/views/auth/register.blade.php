<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — MyNotepad</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --background: #f9fafb;
            --foreground: #09090b;
            --card: #ffffff;
            --card-foreground: #09090b;
            --popover: #ffffff;
            --popover-foreground: #09090b;
            --primary: #18181b;
            --primary-foreground: #fafafa;
            --secondary: #f4f4f5;
            --secondary-foreground: #18181b;
            --muted: #f4f4f5;
            --muted-foreground: #71717a;
            --accent: #f4f4f5;
            --accent-foreground: #18181b;
            --destructive: #ef4444;
            --destructive-foreground: #fafafa;
            --border: #e4e4e7;
            --input: #e4e4e7;
            --ring: #18181b;
            --radius: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --background: #09090b;
                --foreground: #fafafa;
                --card: #09090b;
                --card-foreground: #fafafa;
                --popover: #09090b;
                --popover-foreground: #fafafa;
                --primary: #fafafa;
                --primary-foreground: #18181b;
                --secondary: #27272a;
                --secondary-foreground: #fafafa;
                --muted: #27272a;
                --muted-foreground: #a1a1aa;
                --accent: #27272a;
                --accent-foreground: #fafafa;
                --destructive: #7f1d1d;
                --destructive-foreground: #fafafa;
                --border: #27272a;
                --input: #27272a;
                --ring: #d4d4d8;
            }
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            width: 100%;
            max-width: 28rem; /* 448px */
            padding: 1rem;
        }

        .card {
            background-color: var(--card);
            color: var(--card-foreground);
            border-radius: 0.75rem; /* xl */
            border: 1px solid var(--border);
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); /* shadow-sm */
            /* Tailwind shadow-lg for the container as requested */
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            border: none;
            display: flex;
            flex-direction: column;
        }

        .card-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 1.5rem 1rem;
        }

        .logo {
            width: 3rem;
            height: 3rem;
            margin-bottom: 0.5rem;
            color: var(--foreground);
        }

        .title {
            font-size: 1.5rem;
            line-height: 2rem;
            font-weight: 600;
            letter-spacing: -0.025em;
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .description {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            text-align: center;
        }

        .card-content {
            padding: 0 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .label {
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input {
            display: flex;
            height: 2.5rem;
            width: 100%;
            border-radius: var(--radius);
            border: 1px solid var(--input);
            background-color: transparent;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: var(--foreground);
            transition: border-color 0.15s, box-shadow 0.15s;
            font-family: inherit;
            outline: none;
        }

        .input::placeholder {
            color: var(--muted-foreground);
        }

        .input:focus-visible {
            border-color: var(--ring);
            box-shadow: 0 0 0 2px var(--background), 0 0 0 4px var(--ring);
        }

        .input:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
        
        .input.has-error {
            border-color: var(--destructive);
        }

        .error-message {
            color: var(--destructive);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            padding: 0 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            color: var(--muted-foreground);
            cursor: pointer;
            border-radius: 0 var(--radius) var(--radius) 0;
        }
        
        .password-toggle:hover {
            color: var(--foreground);
        }
        
        .password-toggle:focus-visible {
            outline: none;
            box-shadow: 0 0 0 2px var(--background), 0 0 0 4px var(--ring);
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            height: 2.5rem;
            padding: 0.5rem 1rem;
            background-color: var(--primary);
            color: var(--primary-foreground);
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            font-family: inherit;
            width: 100%;
        }

        .button:hover {
            background-color: rgb(24 24 27 / 0.9);
        }

        .button:focus-visible {
            outline: none;
            box-shadow: 0 0 0 2px var(--background), 0 0 0 4px var(--ring);
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-top: 1px solid var(--border);
        }

        .footer-text {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        .link {
            color: var(--primary);
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .checkbox {
            appearance: none;
            width: 1rem;
            height: 1rem;
            border: 1px solid var(--primary);
            border-radius: 0.125rem;
            outline: none;
            cursor: pointer;
            position: relative;
            background-color: transparent;
        }
        
        .checkbox:checked {
            background-color: var(--primary);
        }
        
        .checkbox:checked::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--primary-foreground);
            font-size: 0.6rem;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .checkbox:focus-visible {
            box-shadow: 0 0 0 2px var(--background), 0 0 0 4px var(--ring);
        }
        
        .checkbox-label {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <!-- SVG Logo from the original Shadcn component -->
                <svg class="logo" fill="currentColor" viewBox="0 0 40 48">
                    <clipPath id="a"><path d="m0 0h40v48h-40z" /></clipPath>
                    <g clipPath="url(#a)">
                        <path d="m25.0887 5.05386-3.933-1.05386-3.3145 12.3696-2.9923-11.16736-3.9331 1.05386 3.233 12.0655-8.05262-8.0526-2.87919 2.8792 8.83271 8.8328-10.99975-2.9474-1.05385625 3.933 12.01860625 3.2204c-.1376-.5935-.2104-1.2119-.2104-1.8473 0-4.4976 3.646-8.1436 8.1437-8.1436 4.4976 0 8.1436 3.646 8.1436 8.1436 0 .6313-.0719 1.2459-.2078 1.8359l10.9227 2.9267 1.0538-3.933-12.0664-3.2332 11.0005-2.9476-1.0539-3.933-12.0659 3.233 8.0526-8.0526-2.8792-2.87916-8.7102 8.71026z" />
                        <path d="m27.8723 26.2214c-.3372 1.4256-1.0491 2.7063-2.0259 3.7324l7.913 7.9131 2.8792-2.8792z" />
                        <path d="m25.7665 30.0366c-.9886 1.0097-2.2379 1.7632-3.6389 2.1515l2.8794 10.746 3.933-1.0539z" />
                        <path d="m21.9807 32.2274c-.65.1671-1.3313.2559-2.0334.2559-.7522 0-1.4806-.102-2.1721-.2929l-2.882 10.7558 3.933 1.0538z" />
                        <path d="m17.6361 32.1507c-1.3796-.4076-2.6067-1.1707-3.5751-2.1833l-7.9325 7.9325 2.87919 2.8792z" />
                        <path d="m13.9956 29.8973c-.9518-1.019-1.6451-2.2826-1.9751-3.6862l-10.95836 2.9363 1.05385 3.933z" />
                    </g>
                </svg>
                <h2 class="title">Create an account</h2>
                <p class="description">Welcome! Create an account to get started.</p>
            </div>
            
            <div class="card-content">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="label" for="name">Full name</label>
                            <input class="input {{ $errors->has('name') ? 'has-error' : '' }}" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')<div class="error-message">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="label" for="email">Email address</label>
                            <input class="input {{ $errors->has('email') ? 'has-error' : '' }}" id="email" type="email" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="error-message">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="label" for="password">Password</label>
                            <div class="input-wrapper">
                                <input class="input {{ $errors->has('password') ? 'has-error' : '' }}" id="password" type="password" name="password" style="padding-right: 2.5rem;" required>
                                <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                                    <i class="fa-regular fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            @error('password')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="label" for="password_confirmation">Confirm Password</label>
                            <div class="input-wrapper">
                                <input class="input" id="password_confirmation" type="password" name="password_confirmation" style="padding-right: 2.5rem;" required>
                                <button type="button" class="password-toggle" id="toggleConfirmPassword" aria-label="Toggle confirm password visibility">
                                    <i class="fa-regular fa-eye" id="confirmEyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="terms" class="checkbox" required>
                            <label for="terms" class="checkbox-label">
                                I agree to the <a href="#" class="link">Terms</a> and <a href="#" class="link">Conditions</a>
                            </label>
                        </div>

                        <button type="submit" class="button">Create free account</button>
                    </div>
                </form>
            </div>
            
            <div class="card-footer">
                <p class="footer-text">
                    Already have an account? <a href="{{ route('login') }}" class="link">Sign in</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Password toggle logic
        function setupPasswordToggle(inputId, buttonId, iconId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            const icon = document.getElementById(iconId);

            button.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        setupPasswordToggle('password', 'togglePassword', 'eyeIcon');
        setupPasswordToggle('password_confirmation', 'toggleConfirmPassword', 'confirmEyeIcon');
    </script>
</body>
</html>
