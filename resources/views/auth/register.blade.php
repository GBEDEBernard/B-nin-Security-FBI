<!doctype html>
<html lang="fr">
<!-- 
  ========================================
  PAGE D'INSCRIPTION - BENIN SECURITY
  ========================================
  Cette page permet aux nouveaux utilisateurs
  de créer un compte sur la plateforme.
-->

<head>
  <!-- Métadonnées de la page -->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>@yield('title', 'Bénin Security - Inscription')</title>

  <!-- Viewport pour le responsive design -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <!-- Couleur du thème pour mobile -->
  <meta name="theme-color" content="#1a1a2e" />

  <!-- Préchargement des polices Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <!-- Polices : Outfit pour les titres, Plus Jakarta Sans pour le corps -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet" />

  <!-- Icônes Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />

  <!-- Framework Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- 
    ========================================
    STYLES CSS PERSONNALISÉS
    ========================================
  -->
  <style>
    /* --- VARIABLES CSS --- */
    :root {
      /* Couleur principale : teal/vert */
      --primary-color: #00d4aa;
      --primary-dark: #00b894;

      /* Couleur de fond sombre */
      --dark-bg: #0f0f1a;

      /* Style glassmorphism (effet verre) */
      --card-bg: rgba(255, 255, 255, 0.08);
      --card-border: rgba(255, 255, 255, 0.12);

      /* Couleurs du texte */
      --text-primary: #ffffff;
      --text-secondary: rgba(255, 255, 255, 0.7);

      /* Style des champs de saisie */
      --input-bg: rgba(255, 255, 255, 0.05);
      --input-border: rgba(255, 255, 255, 0.15);

      /* Couleur d'erreur */
      --error-color: #ff6b6b;
    }

    /* --- RESET ET BASE --- */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* --- CORPS DE LA PAGE --- */
    body {
      /* Police principale */
      font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
      /* Fond sombre avec dégradé */
      background: var(--dark-bg);
      /* Centrage vertical et horizontal */
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow-x: hidden;
      position: relative;
      padding: 20px 15px;
    }

    /* --- FOND ANIMÉ --- */
    .auth-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);
    }

    /* Cercles de couleur en arrière-plan */
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

    /* --- FORMES FLOTTANTES --- */
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

    /* Forme 1 : cercle teal en haut à droite */
    .shape-1 {
      width: 250px;
      height: 250px;
      background: rgba(0, 212, 170, 0.15);
      top: -50px;
      right: -50px;
    }

    /* Forme 2 : cercle violet en bas à gauche */
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

    /* --- CONTENEUR PRINCIPAL --- */
    .auth-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 420px;
    }

    /* --- SECTION LOGO --- */
    .logo-section {
      text-align: center;
      margin-bottom: 16px;
    }

    /* Logo avec effet de gradient et animation */
    .logo-wrapper {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 56px;
      height: 56px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border-radius: 14px;
      margin-bottom: 10px;
      box-shadow: 0 12px 25px rgba(0, 212, 170, 0.3);
      animation: logoPulse 3s ease-in-out infinite;
      position: relative;
      overflow: hidden;
    }

    /* Effet de shimmer (reflet) sur le logo */
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

    /* Animation de pulsation du logo */
    @keyframes logoPulse {

      0%,
      100% {
        box-shadow: 0 12px 25px rgba(0, 212, 170, 0.3);
      }

      50% {
        box-shadow: 0 12px 25px rgba(0, 212, 170, 0.3), 0 0 0 8px rgba(0, 212, 170, 0);
      }
    }

    .logo-wrapper i {
      font-size: 26px;
      color: white;
    }

    /* Nom de la marque */
    .brand-name {
      font-family: 'Outfit', sans-serif;
      font-size: 22px;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 3px;
    }

    /* Slogan / tagline */
    .brand-tagline {
      font-size: 11px;
      color: var(--text-secondary);
    }

    /* --- CARTE D'AUTHENTIFICATION --- */
    .auth-card {
      background: var(--card-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid var(--card-border);
      border-radius: 18px;
      padding: 20px;
      box-shadow: 0 18px 35px rgba(0, 0, 0, 0.4);
    }

    /* En-tête de la carte */
    .auth-card-header {
      text-align: center;
      margin-bottom: 18px;
    }

    .auth-card-title {
      font-family: 'Outfit', sans-serif;
      font-size: 18px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 3px;
    }

    .auth-card-subtitle {
      font-size: 12px;
      color: var(--text-secondary);
    }

    /* --- CHAMPS DE FORMULAIRE --- */
    .form-group {
      margin-bottom: 12px;
      position: relative;
    }

    /* Étiquette du champ */
    .form-label {
      display: block;
      font-size: 11px;
      font-weight: 500;
      color: var(--text-secondary);
      margin-bottom: 5px;
    }

    /* Conteneur pour centrer les icônes */
    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    /* Style des champs de saisie */
    .form-control {
      width: 100%;
      padding: 9px 11px;
      padding-left: 34px;
      padding-right: 34px;
      font-size: 13px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: var(--text-primary);
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 9px;
      outline: none;
      transition: all 0.3s ease;
      height: 38px;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.3);
    }

    /* Style au focus */
    .form-control:focus {
      background: rgba(255, 255, 255, 0.08);
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.1);
    }

    /* Style erreur */
    .form-control.is-invalid {
      border-color: var(--error-color);
    }

    /* --- ICÔNES DANS LES CHAMPS --- */
    .input-icon {
      position: absolute;
      left: 10px;
      color: var(--text-secondary);
      font-size: 13px;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 14px;
      height: 14px;
      z-index: 1;
    }

    .form-control:focus~.input-icon {
      color: var(--primary-color);
    }

    /* --- BOUTON VOIR/MASQUER MOT DE PASSE --- */
    .password-toggle {
      position: absolute;
      right: 10px;
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      font-size: 13px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 18px;
      height: 18px;
      padding: 0;
    }

    .password-toggle:hover {
      color: var(--primary-color);
    }

    /* --- INDICATEUR DE FORCE DU MOT DE PASSE --- */
    .password-strength {
      margin-top: 6px;
    }

    /* Barre de progression */
    .strength-bar {
      height: 3px;
      background: var(--input-border);
      border-radius: 2px;
      overflow: hidden;
      margin-bottom: 4px;
    }

    /* Remplissage de la barre */
    .strength-fill {
      height: 100%;
      width: 0%;
      transition: all 0.3s ease;
      border-radius: 2px;
    }

    /* Couleurs selon la force */
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
      background: #00d4aa;
    }

    /* Texte de la force */
    .strength-text {
      font-size: 10px;
      color: var(--text-secondary);
    }

    /* --- CASE À COCHER CONDITIONS --- */
    .form-check-wrapper {
      display: flex;
      align-items: flex-start;
      margin-bottom: 14px;
      width: 100%;
    }

    .form-check-wrapper .form-check {
      display: flex;
      align-items: flex-start;
      padding-left: 0;
      margin: 0;
    }

    .form-check-input {
      width: 13px;
      height: 13px;
      margin: 0;
      margin-right: 5px;
      margin-top: 2px;
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
      font-size: 10px;
      color: var(--text-secondary);
      cursor: pointer;
      line-height: 1.4;
      text-align: right;
    }

    .form-check-label a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
      margin-left: 2px;
    }

    .form-check-label a:hover {
      text-decoration: underline;
    }

    /* --- BOUTON D'INSCRIPTION --- */
    .btn-submit {
      width: 100%;
      padding: 11px 18px;
      font-size: 13px;
      font-weight: 600;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #0f0f1a;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border: none;
      border-radius: 9px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(0, 212, 170, 0.35);
    }

    .btn-submit:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }

    /* --- PIED DE PAGE --- */
    .auth-footer {
      text-align: center;
      margin-top: 16px;
    }

    .auth-footer p {
      font-size: 12px;
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

    /* --- MESSAGES D'ALERTE --- */
    .alert-custom {
      padding: 10px 12px;
      border-radius: 8px;
      margin-bottom: 14px;
      font-size: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
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
      font-size: 10px;
      color: var(--error-color);
      margin-top: 3px;
    }

    /* --- INDICATEUR DE CHARGEMENT --- */
    .spinner {
      display: inline-block;
      width: 14px;
      height: 14px;
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
        padding: 14px 12px;
      }

      .logo-wrapper {
        width: 44px;
        height: 44px;
      }

      .logo-wrapper i {
        font-size: 20px;
      }

      .brand-name {
        font-size: 16px;
      }

      .brand-tagline {
        font-size: 10px;
      }

      .auth-card-title {
        font-size: 15px;
      }

      .auth-card-subtitle {
        font-size: 11px;
      }

      .form-label {
        font-size: 10px;
      }

      .form-control {
        padding: 8px 10px;
        padding-left: 30px;
        padding-right: 30px;
        font-size: 12px;
        height: 36px;
      }

      .input-icon {
        font-size: 11px;
        left: 8px;
      }

      .password-toggle {
        right: 8px;
        font-size: 11px;
        width: 16px;
        height: 16px;
      }

      .form-check-wrapper {
        margin-bottom: 12px;
      }

      .form-check-label {
        font-size: 9px;
      }

      .form-check-label a {
        font-size: 9px;
      }

      .btn-submit {
        padding: 10px 14px;
        font-size: 12px;
        height: 36px;
      }

      .auth-footer {
        margin-top: 14px;
      }

      .auth-footer p {
        font-size: 11px;
      }

      .alert-custom {
        padding: 8px 10px;
        font-size: 11px;
      }
    }

    @media (max-width: 360px) {
      body {
        padding: 8px 6px;
      }

      .auth-card {
        padding: 12px 10px;
      }

      .brand-name {
        font-size: 14px;
      }

      .auth-card-title {
        font-size: 14px;
      }

      .form-control {
        font-size: 11px;
        height: 34px;
        padding-left: 28px;
        padding-right: 28px;
      }

      .btn-submit {
        font-size: 11px;
        height: 34px;
      }
    }

    /* --- MODE CLAIR (SUPPORT) --- */
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

<!-- 
  ========================================
  CORPS DE LA PAGE HTML
  ========================================
-->

<body>

  <!-- Fond animé en arrière-plan -->
  <div class="auth-background"></div>

  <!-- Formes flottantes décoratives -->
  <div class="floating-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
  </div>

  <!-- Conteneur principal -->
  <div class="auth-container">

    <!-- Section Logo et Nom -->
    <div class="logo-section">
      <div class="logo-wrapper">
        <!-- Icône de bouclier (sécurité) -->
        <i class="bi bi-shield-check"></i>
      </div>
      <h1 class="brand-name">Bénin Security</h1>
      <p class="brand-tagline">Gestion de Sécurité Intelligente</p>
    </div>

    <!-- Carte principale d'authentification -->
    <div class="auth-card">
      <!-- En-tête -->
      <div class="auth-card-header">
        <h2 class="auth-card-title">Créer un compte</h2>
        <p class="auth-card-subtitle">Rejoignez notre plateforme</p>
      </div>

      <!-- Messages d'erreur Laravel -->
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

      <!-- Message de succès -->
      @if(session('success'))
      <div class="alert alert-success-custom">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
      </div>
      @endif

      <!-- Formulaire d'inscription -->
      <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Champ Nom complet -->
        <div class="form-group">
          <label for="name" class="form-label">Nom complet</label>
          <div class="input-wrapper">
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Votre nom" required autofocus>
            <!-- Icône personne -->
            <i class="bi bi-person-fill input-icon"></i>
          </div>
          @error('name')
          <span class="invalid-feedback-custom">{{ $message }}</span>
          @enderror
        </div>

        <!-- Champ Email -->
        <div class="form-group">
          <label for="email" class="form-label">Email</label>
          <div class="input-wrapper">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="exemple@email.com" required>
            <!-- Icône enveloppe -->
            <i class="bi bi-envelope-fill input-icon"></i>
          </div>
          @error('email')
          <span class="invalid-feedback-custom">{{ $message }}</span>
          @enderror
        </div>

        <!-- Champ Téléphone (optionnel) -->
        <div class="form-group">
          <label for="phone" class="form-label">Téléphone</label>
          <div class="input-wrapper">
            <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+229 XX XX XX XX">
            <!-- Icône téléphone -->
            <i class="bi bi-phone-fill input-icon"></i>
          </div>
        </div>

        <!-- Champ Mot de passe -->
        <div class="form-group">
          <label for="password" class="form-label">Mot de passe</label>
          <div class="input-wrapper">
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required oninput="checkPasswordStrength()">
            <!-- Icône cadenas -->
            <i class="bi bi-lock-fill input-icon"></i>
            <!-- Bouton afficher/masquer -->
            <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
              <i class="bi bi-eye-fill" id="toggleIcon1"></i>
            </button>
          </div>
          <!-- Indicateur de force du mot de passe -->
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

        <!-- Champ Confirmer le mot de passe -->
        <div class="form-group">
          <label for="password_confirmation" class="form-label">Confirmer</label>
          <div class="input-wrapper">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required oninput="checkPasswordMatch()">
            <!-- Icône cadenas -->
            <i class="bi bi-lock-fill input-icon"></i>
            <!-- Bouton afficher/masquer -->
            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
              <i class="bi bi-eye-fill" id="toggleIcon2"></i>
            </button>
          </div>
          <!-- Feedback de correspondance -->
          <span class="invalid-feedback-custom" id="matchFeedback" style="display: none;"></span>
        </div>

        <!-- Case à cocher Conditions d'utilisation -->
        <div class="form-check-wrapper">
          <div class="form-check" style="margin-left: 25px;margin-top: 5px;">
            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
            <label class="form-check-label" for="terms">J'accepte les <a href="#">conditions</a> et la <a href="#">politique</a></label>
          </div>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn-submit" id="submitBtn">
          <span id="btnText">
            <i class="bi bi-person-plus"></i>
            Créer mon compte
          </span>
        </button>
      </form>

      <!-- Lien vers la page de connexion -->
      <div class="auth-footer">
        <p>Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
      </div>
    </div>
  </div>

  <!-- Scripts JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Fonction pour afficher/masquer le mot de passe
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

    // Fonction pour vérifier la force du mot de passe
    function checkPasswordStrength() {
      const password = document.getElementById('password').value;
      const strengthFill = document.getElementById('strengthFill');
      const strengthText = document.getElementById('strengthText');

      let strength = 0;

      // Critères de force
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
        strengthText.textContent = 'Faible';
        strengthText.style.color = '#ff6b6b';
      } else if (strength < 5) {
        strengthFill.classList.add('medium');
        strengthText.textContent = 'Moyen';
        strengthText.style.color = '#ffc107';
      } else {
        strengthFill.classList.add('strong');
        strengthText.textContent = 'Fort';
        strengthText.style.color = '#00d4aa';
      }
    }

    // Fonction pour vérifier si les mots de passe correspondent
    function checkPasswordMatch() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('password_confirmation').value;
      const matchFeedback = document.getElementById('matchFeedback');

      if (confirmPassword.length > 0) {
        if (password === confirmPassword) {
          matchFeedback.style.display = 'block';
          matchFeedback.style.color = '#00d4aa';
          matchFeedback.textContent = '✓ Correspond';
        } else {
          matchFeedback.style.display = 'block';
          matchFeedback.style.color = '#ff6b6b';
          matchFeedback.textContent = '✗ Ne correspond pas';
        }
      } else {
        matchFeedback.style.display = 'none';
      }
    }

    // Gestion de la soumission du formulaire
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      const terms = document.getElementById('terms');
      const submitBtn = document.getElementById('submitBtn');
      const btnText = document.getElementById('btnText');

      // Vérifier si les conditions sont acceptées
      if (!terms.checked) {
        e.preventDefault();
        alert('Veuillez accepter les conditions');
        return;
      }

      // Désactiver le bouton et montrer le chargement
      submitBtn.disabled = true;
      btnText.innerHTML = '<span class="spinner"></span> Création...';
    });
  </script>
</body>

</html>