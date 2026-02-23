<!doctype html>
<html lang="fr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>@yield('title', 'Bénin Security - Inscription')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#1a1a2e" />
  <meta name="description" content="Plateforme de gestion de sécurité Benin Security" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #00d4aa;
      --primary-dark: #00b894;
      --secondary-color: #6c5ce7;
      --dark-bg: #0f0f1a;
      --card-bg: rgba(255, 255, 255, 0.08);
      --card-border: rgba(255, 255, 255, 0.12);
      --text-primary: #ffffff;
      --text-secondary: rgba(255, 255, 255, 0.7);
      --input-bg: rgba(255, 255, 255, 0.05);
      --input-border: rgba(255, 255, 255, 0.15);
      --error-color: #ff6b6b;
      --success-color: #00d4aa;
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
      overflow-x: hidden;
      position: relative;
      padding: 30px 0;
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
      background: 
        radial-gradient(circle at 20% 80%, rgba(0, 212, 170, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(108, 92, 231, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(0, 212, 170, 0.1) 0%, transparent 30%);
      animation: bgFloat 20s ease-in-out infinite;
    }

    @keyframes bgFloat {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      33% { transform: translate(30px, -30px) rotate(5deg); }
      66% { transform: translate(-20px, 20px) rotate(-5deg); }
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
      filter: blur(60px);
      opacity: 0.5;
      animation: float 15s ease-in-out infinite;
    }

    .shape-1 {
      width: 400px;
      height: 400px;
      background: rgba(0, 212, 170, 0.2);
      top: -100px;
      right: -100px;
    }

    .shape-2 {
      width: 300px;
      height: 300px;
      background: rgba(108, 92, 231, 0.2);
      bottom: -50px;
      left: -50px;
      animation-delay: -5s;
    }

    .shape-3 {
      width: 200px;
      height: 200px;
      background: rgba(0, 212, 170, 0.15);
      top: 50%;
      left: 50%;
      animation-delay: -10s;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-30px) scale(1.05); }
    }

    .auth-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 520px;
      padding: 20px;
    }

    .logo-section {
      text-align: center;
      margin-bottom: 25px;
    }

    .logo-wrapper {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border-radius: 22px;
      margin-bottom: 18px;
      box-shadow: 0 20px 40px rgba(0, 212, 170, 0.3), 0 0 0 0 rgba(0, 212, 170, 0.4);
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
      0% { transform: translateX(-100%) rotate(45deg); }
      100% { transform: translateX(100%) rotate(45deg); }
    }

    @keyframes logoPulse {
      0%, 100% { box-shadow: 0 20px 40px rgba(0, 212, 170, 0.3), 0 0 0 0 rgba(0, 212, 170, 0.4); }
      50% { box-shadow: 0 20px 40px rgba(0, 212, 170, 0.3), 0 0 0 15px rgba(0, 212, 170, 0); }
    }

    .logo-wrapper i {
      font-size: 36px;
      color: white;
    }

    .brand-name {
      font-family: 'Outfit', sans-serif;
      font-size: 28px;
      font-weight: 700;
      color: var(--text-primary);
      letter-spacing: -0.5px;
      margin-bottom: 6px;
    }

    .brand-tagline {
      font-size: 13px;
      color: var(--text-secondary);
      font-weight: 400;
    }

    .auth-card {
      background: var(--card-bg);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid var(--card-border);
      border-radius: 24px;
      padding: 32px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
      position: relative;
    }

    .auth-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    }

    .auth-card-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .auth-card-title {
      font-family: 'Outfit', sans-serif;
      font-size: 24px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 6px;
    }

    .auth-card-subtitle {
      font-size: 13px;
      color: var(--text-secondary);
    }

    .form-group {
      margin-bottom: 16px;
      position: relative;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: var(--text-secondary);
      margin-bottom: 6px;
    }

    .form-control {
      width: 100%;
      padding: 12px 14px;
      padding-left: 42px;
      font-size: 14px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: var(--text-primary);
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 12px;
      outline: none;
      transition: all 0.3s ease;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.3);
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.08);
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(0, 212, 170, 0.1), 0 4px 12px rgba(0, 0, 0, 0.2);
      transform: translateY(-1px);
    }

    .form-control.is-invalid {
      border-color: var(--error-color);
      box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.1);
    }

    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      font-size: 16px;
      transition: all 0.3s ease;
      z-index: 10;
    }

    .form-control:focus ~ .input-icon {
      color: var(--primary-color);
    }

    .password-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      padding: 4px;
      font-size: 16px;
      transition: all 0.3s ease;
      z-index: 10;
    }

    .password-toggle:hover {
      color: var(--primary-color);
    }

    .password-strength {
      margin-top: 8px;
    }

    .strength-bar {
      height: 4px;
      background: var(--input-border);
      border-radius: 2px;
      overflow: hidden;
      margin-bottom: 6px;
    }

    .strength-fill {
      height: 100%;
      width: 0%;
      transition: all 0.3s ease;
      border-radius: 2px;
    }

    .strength-fill.weak {
      width: 33%;
      background: var(--error-color);
    }

    .strength-fill.medium {
      width: 66%;
      background: #ffc107;
    }

    .strength-fill.strong {
      width: 100%;
      background: var(--success-color);
    }

    .strength-text {
      font-size: 11px;
      color: var(--text-secondary);
    }

    .form-check-wrapper {
      display: flex;
      align-items: flex-start;
      margin-bottom: 20px;
      gap: 10px;
    }

    .form-check {
      display: flex;
      align-items: flex-start;
      padding-left: 0;
      margin: 0;
    }

    .form-check-input {
      width: 18px;
      height: 18px;
      margin: 0;
      margin-top: 2px;
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 4px;
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }

    .form-check-input:checked {
      background: var(--primary-color);
      border-color: var(--primary-color);
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='white' d='M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e");
      background-size: 11px;
      background-position: center;
      background-repeat: no-repeat;
    }

    .form-check-label {
      font-size: 13px;
      color: var(--text-secondary);
      cursor: pointer;
      user-select: none;
      line-height: 1.4;
    }

    .form-check-label a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
    }

    .form-check-label a:hover {
      text-decoration: underline;
    }

    .btn-submit {
      width: 100%;
      padding: 14px 24px;
      font-size: 15px;
      font-weight: 600;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #0f0f1a;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-submit::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s ease;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(0, 212, 170, 0.4), 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-submit:hover::before {
      left: 100%;
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .btn-submit:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }

    .auth-footer {
      text-align: center;
      margin-top: 24px;
    }

    .auth-footer p {
      font-size: 14px;
      color: var(--text-secondary);
      margin: 0;
    }

    .auth-footer a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .auth-footer a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .alert-custom {
      padding: 14px 18px;
      border-radius: 12px;
      margin-bottom: 20px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 12px;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-danger-custom {
      background: rgba(255, 107, 107, 0.15);
      border: 1px solid rgba(255, 107, 107, 0.3);
      color: var(--error-color);
    }

    .alert-success-custom {
      background: rgba(0, 212, 170, 0.15);
      border: 1px solid rgba(0, 212, 170, 0.3);
      color: var(--success-color);
    }

    .invalid-feedback-custom {
      display: block;
      font-size: 12px;
      color: var(--error-color);
      margin-top: 6px;
      padding-left: 4px;
    }

    .spinner {
      display: inline-block;
      width: 18px;
      height: 18px;
      border: 2px solid rgba(15, 15, 26, 0.3);
      border-radius: 50%;
      border-top-color: #0f0f1a;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .form-row {
      display: flex;
      gap: 12px;
    }

    .form-row .form-group {
      flex: 1;
    }

    @media (max-width: 576px) {
      .auth-container {
        padding: 15px;
      }

      .auth-card {
        padding: 24px 20px;
      }

      .logo-wrapper {
        width: 70px;
        height: 70px;
      }

      .logo-wrapper i {
        font-size: 30px;
      }

      .brand-name {
        font-size: 24px;
      }

      .auth-card-title {
        font-size: 20px;
      }

      .form-row {
        flex-direction: column;
        gap: 0;
      }
    }

    @media (prefers-color-scheme: light) {
      :root {
        --dark-bg: #f0f2f5;
        --card-bg: rgba(255, 255, 255, 0.85);
        --card-border: rgba(0, 0, 0, 0.08);
        --text-primary: #1a1a2e;
        --text-secondary: #6b7280;
        --input-bg: rgba(0, 0, 0, 0.03);
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
    <div class="shape shape-3"></div>
  </div>

  <div class="auth-container">
    <div class="logo-section">
      <div class="logo-wrapper">
        <i class="bi bi-shield-check"></i>
      </div>
      <h1 class="brand-name">Bénin Security</h1>
      <p class="brand-tagline">Gestion de Sécurité Intelligente</p>
    </div>

    <div class="auth-card">
      <div class="auth-card-header">
        <h2 class="auth-card-title">Créer un compte</h2>
        <p class="auth-card-subtitle">Rejoignez notre plateforme de sécurité</p>
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

      <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <div class="form-group">
          <label for="name" class="form-label">Nom complet</label>
          <input
            type="text"
            class="form-control @error('name') is-invalid @enderror"
            id="name"
            name="name"
            value="{{ old('name') }}"
            placeholder="Votre nom complet"
            required
            autofocus>
          <i class="bi bi-person-fill input-icon"></i>
          @error('name')
            <span class="invalid-feedback-custom">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="email" class="form-label">Adresse email</label>
          <input
            type="email"
            class="form-control @error('email') is-invalid @enderror"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="exemple@email.com"
            required>
          <i class="bi bi-envelope-fill input-icon"></i>
          @error('email')
            <span class="invalid-feedback-custom">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="phone" class="form-label">Téléphone (optionnel)</label>
          <input
            type="tel"
            class="form-control"
            id="phone"
            name="phone"
            value="{{ old('phone') }}"
            placeholder="+229 XX XX XX XX">
          <i class="bi bi-phone-fill input-icon"></i>
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Mot de passe</label>
          <input
            type="password"
            class="form-control @error('password') is-invalid @enderror"
            id="password"
            name="password"
            placeholder="••••••••"
            required
            oninput="checkPasswordStrength()">
          <i class="bi bi-lock-fill input-icon"></i>
          <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
            <i class="bi bi-eye-fill" id="toggleIcon1"></i>
          </button>
          <div class="password-strength">
            <div class="strength-bar">
              <div class="strength-fill" id="strengthFill"></div>
            </div>
            <span class="strength-text" id="strengthText"></span>
          </div>
          @error('password')
            <span class="invalid-feedback-custom">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
          <input
            type="password"
            class="form-control"
            id="password_confirmation"
            name="password_confirmation"
            placeholder="••••••••"
            required
            oninput="checkPasswordMatch()">
          <i class="bi bi-lock-fill input-icon"></i>
          <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
            <i class="bi bi-eye-fill" id="toggleIcon2"></i>
          </button>
          <span class="invalid-feedback-custom" id="matchFeedback" style="display: none;"></span>
        </div>

        <div class="form-check-wrapper">
          <div class="form-check">
            <input
              type="checkbox"
              class="form-check-input"
              id="terms"
              name="terms"
              required>
            <label class="form-check-label" for="terms">
              J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a>
            </label>
          </div>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
          <span id="btnText">
            <i class="bi bi-person-plus"></i>
            Créer mon compte
          </span>
        </button>
      </form>

      <div class="auth-footer">
        <p>Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    function togglePassword(inputId, iconId) {
      const passwordInput = document.getElementById(inputId);
      const toggleIcon = document.getElementById(iconId);
      
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

    function checkPasswordStrength() {
      const password = document.getElementById('password').value;
      const strengthFill = document.getElementById('strengthFill');
      const strengthText = document.getElementById('strengthText');
      
      let strength = 0;
      
      if (password.length >= 8) strength++;
      if (password.match(/[a-z]/)) strength++;
      if (password.match(/[A-Z]/)) strength++;
      if (password.match(/[0-9]/)) strength++;
      if (password.match(/[^a-zA-Z0-9]/)) strength++;
      
      strengthFill.className = 'strength-fill';
      
      if (password.length === 0) {
        strengthFill.style.width = '0%';
        strengthText.textContent = '';
      } else if (strength < 3) {
        strengthFill.classList.add('weak');
        strengthText.textContent = 'Mot de passe faible';
        strengthText.style.color = '#ff6b6b';
      } else if (strength < 5) {
        strengthFill.classList.add('medium');
        strengthText.textContent = 'Mot de passe moyen';
        strengthText.style.color = '#ffc107';
      } else {
        strengthFill.classList.add('strong');
        strengthText.textContent = 'Mot de passe fort';
        strengthText.style.color = '#00d4aa';
      }
    }

    function checkPasswordMatch() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('password_confirmation').value;
      const matchFeedback = document.getElementById('matchFeedback');
      
      if (confirmPassword.length > 0) {
        if (password === confirmPassword) {
          matchFeedback.style.display = 'block';
          matchFeedback.style.color = '#00d4aa';
          matchFeedback.textContent = 'Les mots de passe correspondent';
        } else {
          matchFeedback.style.display = 'block';
          matchFeedback.style.color = '#ff6b6b';
          matchFeedback.textContent = 'Les mots de passe ne correspondent pas';
        }
      } else {
        matchFeedback.style.display = 'none';
      }
    }

    document.getElementById('registerForm').addEventListener('submit', function(e) {
      const terms = document.getElementById('terms');
      const submitBtn = document.getElementById('submitBtn');
      const btnText = document.getElementById('btnText');
      
      if (!terms.checked) {
        e.preventDefault();
        alert('Veuillez accepter les conditions d\'utilisation');
        return;
      }
      
      submitBtn.disabled = true;
      btnText.innerHTML = '<span class="spinner"></span> Création en cours...';
    });
  </script>
</body>
</html>
