<!doctype html>
<html lang="fr">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>@yield('title', 'Bénin Security - Connexion')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="theme-color" content="#1a1a2e" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #00d4aa;
      --primary-dark: #00b894;
      --dark-bg: #0f0f1a;
      --card-bg: rgba(255, 255, 255, 0.08);
      --card-border: rgba(255, 255, 255, 0.12);
      --text-primary: #ffffff;
      --text-secondary: rgba(255, 255, 255, 0.7);
      --input-bg: rgba(255, 255, 255, 0.05);
      --input-border: rgba(255, 255, 255, 0.15);
      --error-color: #ff6b6b;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
      background: var(--dark-bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
      padding: 15px;
    }

    .auth-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);
    }

    .auth-background::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at 20% 80%, rgba(0, 212, 170, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(108, 92, 231, 0.15) 0%, transparent 50%);
      animation: bgFloat 20s ease-in-out infinite;
    }

    @keyframes bgFloat {

      0%,
      100% {
        transform: translate(0, 0);
      }

      50% {
        transform: translate(20px, -20px);
      }
    }

    .floating-shapes {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
      overflow: hidden;
    }

    .shape {
      position: absolute;
      border-radius: 50%;
      filter: blur(50px);
      opacity: 0.4;
      animation: float 12s ease-in-out infinite;
    }

    .shape-1 {
      width: 250px;
      height: 250px;
      background: rgba(0, 212, 170, 0.15);
      top: -50px;
      right: -50px;
    }

    .shape-2 {
      width: 200px;
      height: 200px;
      background: rgba(108, 92, 231, 0.15);
      bottom: -30px;
      left: -30px;
      animation-delay: -4s;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-20px);
      }
    }

    .auth-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 400px;
    }

    .logo-section {
      text-align: center;
      margin-bottom: 20px;
    }

    .logo-wrapper {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border-radius: 16px;
      margin-bottom: 12px;
      box-shadow: 0 15px 30px rgba(0, 212, 170, 0.3);
      animation: logoPulse 3s ease-in-out infinite;
      position: relative;
      overflow: hidden;
    }

    .logo-wrapper::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent 40%, rgba(255, 255, 255, 0.2) 50%, transparent 60%);
      animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
      0% {
        transform: translateX(-100%) rotate(45deg);
      }

      100% {
        transform: translateX(100%) rotate(45deg);
      }
    }

    @keyframes logoPulse {

      0%,
      100% {
        box-shadow: 0 15px 30px rgba(0, 212, 170, 0.3);
      }

      50% {
        box-shadow: 0 15px 30px rgba(0, 212, 170, 0.3), 0 0 0 8px rgba(0, 212, 170, 0);
      }
    }

    .logo-wrapper i {
      font-size: 28px;
      color: white;
    }

    .brand-name {
      font-family: 'Outfit', sans-serif;
      font-size: 24px;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 4px;
    }

    .brand-tagline {
      font-size: 12px;
      color: var(--text-secondary);
    }

    .auth-card {
      background: var(--card-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid var(--card-border);
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .auth-card-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .auth-card-title {
      font-family: 'Outfit', sans-serif;
      font-size: 20px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 4px;
    }

    .auth-card-subtitle {
      font-size: 13px;
      color: var(--text-secondary);
    }

    .form-group {
      margin-bottom: 14px;
      position: relative;
    }

    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 500;
      color: var(--text-secondary);
      margin-bottom: 6px;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .form-control {
      width: 100%;
      padding: 10px 12px;
      padding-left: 36px;
      padding-right: 36px;
      font-size: 14px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: var(--text-primary);
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 10px;
      outline: none;
      transition: all 0.3s ease;
      height: 42px;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.3);
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.08);
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.1);
    }

    .form-control.is-invalid {
      border-color: var(--error-color);
    }

    .input-icon {
      position: absolute;
      left: 12px;
      color: var(--text-secondary);
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 16px;
      height: 16px;
      z-index: 1;
    }

    .form-control:focus~.input-icon {
      color: var(--primary-color);
    }

    .password-toggle {
      position: absolute;
      right: 10px;
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 20px;
      height: 20px;
      padding: 0;
    }

    .password-toggle:hover {
      color: var(--primary-color);
    }

    .form-options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
      width: 100%;
    }

    .remember-wrapper {
      display: flex;
      align-items: center;
    }

    .form-check {
      display: flex;
      align-items: center;
      padding-left: 0;
      margin: 0;
    }

    .form-check-input {
      width: 14px;
      height: 14px;
      margin: 0;
      margin-right: 6px;
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 3px;
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      flex-shrink: 0;
    }

    .form-check-input:checked {
      background: var(--primary-color);
      border-color: var(--primary-color);
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='white' d='M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e");
      background-size: 8px;
      background-position: center;
      background-repeat: no-repeat;
    }

    .form-check-label {
      font-size: 11px;
      color: var(--text-secondary);
      cursor: pointer;
    }

    .forgot-link {
      font-size: 11px;
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
    }

    .forgot-link:hover {
      text-decoration: underline;
    }

    .btn-submit {
      width: 100%;
      padding: 12px 20px;
      font-size: 14px;
      font-weight: 600;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #0f0f1a;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 42px;
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 8px 20px rgba(0, 212, 170, 0.35);
    }

    .social-divider {
      display: flex;
      align-items: center;
      margin: 18px 0;
    }

    .social-divider::before,
    .social-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--input-border);
    }

    .social-divider span {
      padding: 0 12px;
      font-size: 11px;
      color: var(--text-secondary);
    }

    .social-buttons {
      display: flex;
      gap: 10px;
    }

    .btn-social {
      flex: 1;
      padding: 10px;
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 10px;
      color: var(--text-primary);
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 40px;
    }

    .btn-social:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: var(--primary-color);
    }

    .auth-footer {
      text-align: center;
      margin-top: 18px;
    }

    .auth-footer p {
      font-size: 13px;
      color: var(--text-secondary);
      margin: 0;
    }

    .auth-footer a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
    }

    .auth-footer a:hover {
      text-decoration: underline;
    }

    .alert-custom {
      padding: 12px 14px;
      border-radius: 10px;
      margin-bottom: 16px;
      font-size: 13px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-danger-custom {
      background: rgba(255, 107, 107, 0.15);
      border: 1px solid rgba(255, 107, 107, 0.3);
      color: var(--error-color);
    }

    .alert-success-custom {
      background: rgba(0, 212, 170, 0.15);
      border: 1px solid rgba(0, 212, 170, 0.3);
      color: #00d4aa;
    }

    .invalid-feedback-custom {
      display: block;
      font-size: 11px;
      color: var(--error-color);
      margin-top: 4px;
    }

    .spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid rgba(15, 15, 26, 0.3);
      border-radius: 50%;
      border-top-color: #0f0f1a;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    @media (max-width: 480px) {
      .auth-container {
        padding: 8px;
        max-width: 100%;
      }

      .auth-card {
        padding: 16px 14px;
      }

      .logo-wrapper {
        width: 48px;
        height: 48px;
      }

      .logo-wrapper i {
        font-size: 22px;
      }

      .brand-name {
        font-size: 18px;
      }

      .brand-tagline {
        font-size: 10px;
      }

      .auth-card-title {
        font-size: 16px;
      }

      .auth-card-subtitle {
        font-size: 11px;
      }

      .form-label {
        font-size: 11px;
      }

      .form-control {
        padding: 10px 10px;
        padding-left: 32px;
        padding-right: 32px;
        font-size: 13px;
        height: 40px;
      }

      .input-icon {
        font-size: 12px;
        left: 10px;
      }

      .password-toggle {
        right: 8px;
        font-size: 12px;
      }

      .form-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }

      .remember-wrapper {
        margin-left: 0 !important;
      }

      .form-check-label,
      .forgot-link {
        font-size: 11px;
      }

      .btn-submit {
        padding: 12px 16px;
        font-size: 13px;
        height: 40px;
      }

      .social-divider {
        margin: 14px 0;
      }

      .social-divider span {
        font-size: 10px;
      }

      .btn-social {
        padding: 8px;
        height: 36px;
        font-size: 14px;
      }

      .auth-footer p {
        font-size: 12px;
      }

      .alert-custom {
        padding: 10px 12px;
        font-size: 12px;
      }
    }

    @media (max-width: 360px) {
      body {
        padding: 10px 8px;
      }

      .auth-card {
        padding: 14px 12px;
      }

      .brand-name {
        font-size: 16px;
      }

      .auth-card-title {
        font-size: 15px;
      }

      .form-control {
        font-size: 12px;
        height: 38px;
        padding-left: 30px;
        padding-right: 30px;
      }

      .btn-submit {
        font-size: 12px;
        height: 38px;
      }
    }

    @media (prefers-color-scheme: light) {
      :root {
        --dark-bg: #f0f2f5;
        --card-bg: rgba(255, 255, 255, 0.9);
        --card-border: rgba(0, 0, 0, 0.08);
        --text-primary: #1a1a2e;
        --text-secondary: #6b7280;
        --input-bg: rgba(0, 0, 0, 0.04);
        --input-border: rgba(0, 0, 0, 0.1);
      }
    }
  </style>
</head>

<body>

  <div class="auth-background"></div>
  <div class="floating-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
  </div>

  <div class="auth-container">
    <div class="logo-section">
      <div class="logo-wrapper"><i class="bi bi-shield-check"></i></div>
      <h1 class="brand-name">Bénin Security</h1>
      <p class="brand-tagline">Gestion de Sécurité Intelligente</p>
    </div>

    <div class="auth-card">
      <div class="auth-card-header">
        <h2 class="auth-card-title">Bon retour !</h2>
        <p class="auth-card-subtitle">Connectez-vous à votre espace</p>
      </div>

      @if($errors->any())
      <div class="alert alert-danger-custom">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
          @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
          @endforeach
        </div>
      </div>
      @endif

      @if(session('success'))
      <div class="alert alert-success-custom">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <div class="form-group">
          <label for="email" class="form-label">Adresse email</label>
          <div class="input-wrapper">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="exemple@email.com" required autofocus autocomplete="email">
            <i class="bi bi-envelope-fill input-icon"></i>
          </div>
          @error('email') <span class="invalid-feedback-custom">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Mot de passe</label>
          <div class="input-wrapper">
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
            <i class="bi bi-lock-fill input-icon"></i>
            <button type="button" class="password-toggle" onclick="togglePassword()">
              <i class="bi bi-eye-fill" id="toggleIcon"></i>
            </button>
          </div>
          @error('password') <span class="invalid-feedback-custom">{{ $message }}</span> @enderror
        </div>

        <div class="form-options">
          <div class="remember-wrapper" style="margin-left: 20px;">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="remember" name="remember">
              <label class="form-check-label" for="remember">Se souvenir</label>
            </div>
          </div>
          <a href="#" class="forgot-link">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
          <span id="btnText"><i class="bi bi-box-arrow-in-right"></i> Se connecter</span>
        </button>

        <div class="social-divider"><span>ou</span></div>

        <div class="social-buttons">
          <button type="button" class="btn-social" title="Google"><i class="bi bi-google"></i></button>
          <button type="button" class="btn-social" title="Facebook"><i class="bi bi-facebook"></i></button>
          <button type="button" class="btn-social" title="LinkedIn"><i class="bi bi-linkedin"></i></button>
        </div>
      </form>

      <div class="auth-footer">
        <p>Pas de compte ? <a href="{{ route('register') }}">Créer un compte</a></p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye-fill');
        toggleIcon.classList.add('bi-eye-slash-fill');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash-fill');
        toggleIcon.classList.add('bi-eye-fill');
      }
    }

    document.getElementById('loginForm').addEventListener('submit', function() {
      const btnText = document.getElementById('btnText');
      btnText.innerHTML = '<span class="spinner"></span> Connexion...';
    });
  </script>
</body>

</html>