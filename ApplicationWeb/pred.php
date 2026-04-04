<!DOCTYPE html>
<html lang="fr">
<head>
      <meta charset="UTF-8">
    <title> CNN </title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
  <header>
    <div class="logo-row">
      <div class="logo-icon">♻</div>
      <h1>Tri<span>AI</span></h1>
    </div>
    <p class="tagline">// reconnaissance des déchets par IA — modèle ensemble VGG + CNN</p>
  </header>

  <!-- Upload -->
  <div class="upload-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
    <div class="upload-icon">📷</div>
    <p class="upload-title">Déposer une image ou cliquer</p>
    <p class="upload-sub">JPG, PNG, WEBP — max 10 Mo</p>
    <input type="file" id="fileInput" accept="image/*">
  </div>

  <!-- Preview -->
  <div class="preview-wrap" id="previewWrap">
    <img id="preview" alt="Aperçu">
    <div class="preview-overlay">
      <button class="btn-icon" title="Changer l'image" onclick="resetAll()">✕</button>
    </div>
  </div>

  <!-- Analyse button -->
  <button id="analyseBtn" onclick="analyse()">
    <span id="btnSpinner" class="btn-spinner"></span>
    <span id="btnText">Analyser l'image</span>
  </button>

  <!-- Result -->
  <div class="result-card" id="resultCard">
    <div class="result-header">
      <div>
        <div class="result-label">Classe détectée</div>
        <div class="result-class" id="resultClass">—</div>
      </div>
      <div class="confidence-badge" id="confidenceBadge">—</div>
    </div>
    <div class="result-status" id="resultStatus"></div>

    <!-- Proba bars -->
    <div class="proba-section">
      <div class="proba-label">Distribution des probabilités</div>
      <div id="probaBars"></div>
    </div>

    <!-- Contester -->
    <div class="contest-section">
      <div class="contest-title">Résultat incorrect ?</div>
      <button class="contest-btn" id="contestBtn" onclick="toggleContest()">
        ⚑ Contester ce résultat
      </button>
      <div class="contest-form" id="contestForm">
        <label class="contest-form-label">Quelle est la bonne catégorie ?</label>
        <div class="class-grid" id="classGrid"></div>
        <button class="submit-contest" id="submitContest" onclick="submitContest()">
          Envoyer la correction
        </button>
      </div>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-bar" id="statsBar">
    <div class="stat">
      <span class="stat-val" id="statTotal">0</span>
      <span class="stat-key">Analyses</span>
    </div>
    <div class="stat">
      <span class="stat-val" id="statContests">0</span>
      <span class="stat-key">Contestées</span>
    </div>
    <div class="stat">
      <span class="stat-val" id="statRate">—</span>
      <span class="stat-key">Taux contestation</span>
    </div>
  </div>

  <footer>TriAI · Modèle ensemble 90% accuracy · Les contestations améliorent le modèle</footer>
</div>

<!-- Toast -->
<div class="toast" id="toast">
  <div class="toast-dot" id="toastDot"></div>
  <span id="toastMsg">Message</span>
</div>

</body>
</html>
