<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÉcoScan | L'IA au service de l'écologie</title>
    
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<header class="accueil">
    <div class="container">
        <h1 class="fw-bold">L'IA au service de l'écologie</h1>
        <p class="lead">Analysez et prédisez la gestion des déchets en Italie</p>
    </div>
</header>

<div class="container stats-container">
    <div class="row g-4 justify-content-center text-center">
        <div class="col-md-3">
            <div class="card shadow border-0 p-3 rounded-4">
                <div class="card-body">
                    <h2 class="fw-bold text-success display-5">20</h2>
                    <p class="text-muted mb-0 fw-bold">Régions Italiennes</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-0 p-3 rounded-4">
                <div class="card-body">
                    <h2 class="fw-bold text-success display-5">+7 000</h2>
                    <p class="text-muted mb-0 fw-bold">Communes analysées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow border-0 p-3 rounded-4">
                <div class="card-body">
                    <h2 class="fw-bold text-success display-5">3</h2>
                    <p class="text-muted mb-0 fw-bold">Modèles d'IA</p>
                </div>
            </div>
        </div>
    </div>
</div>

<main class="container my-5 py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-6">Comment utiliser la plateforme ?</h2>
        <div class="title-separator mx-auto" style="width: 60px; height: 4px; background: #27ae60; margin-top: 15px; border-radius: 10px;"></div>
    </div>

    <div class="row g-5 text-center">
    
    <div class="col-md-3">
        <div class="mb-4 icon-wrapper">
            <i class="fa-solid fa-camera-retro fa-3x text-success"></i>
        </div>
        <h4 class="fw-bold">1. Scannez</h4>
        <p class="text-muted small">Uploadez la photo d'un objet. Notre <strong>modèle de reconnaissance</strong> identifiera sa classe pour vous aider à mieux trier.</p>
        <a href="pred.php" class="btn btn-outline-success rounded-pill px-4 mt-2 fw-bold">Scanner un objet</a>
    </div>

    <div class="col-md-3 border-start">
        <div class="mb-4 icon-wrapper">
            <i class="fa-solid fa-brain fa-3x text-success"></i>
        </div>
        <h4 class="fw-bold">2. Simulez</h4>
        <p class="text-muted small">Remplissez les critères d'une ville.<br> Notre <strong>IA</strong> prédira la répartition (en %) des classes de déchets.</p>
        <a href="prediction.php" class="btn btn-outline-success rounded-pill px-4 mt-2 fw-bold">Prédire le mix</a>
    </div>
    
    <div class="col-md-3 border-start">
        <div class="mb-4 icon-wrapper">
            <i class="fa-solid fa-map-location-dot fa-3x text-success"></i>
        </div>
        <h4 class="fw-bold">3. Explorez</h4>
        <p class="text-muted small">Naviguez sur la <strong>carte interactive</strong>.<br> Cliquez sur une région pour découvrir sa fiche détaillée.</p>
        <a href="carte.php" class="btn btn-outline-success rounded-pill px-4 mt-2 fw-bold">Voir la carte</a>
    </div>

    <div class="col-md-3 border-start">
        <div class="mb-4 icon-wrapper">
            <i class="fa-solid fa-chart-pie fa-3x text-success"></i>
        </div>
        <h4 class="fw-bold">4. Analysez</h4>
        <p class="text-muted small"><strong>Analysez</strong> l'influence démographique ou <strong>générez</strong> vos propres comparatifs régionaux sur-mesure.</p>
        <a href="graphiques.php" class="btn btn-outline-success rounded-pill px-4 mt-2 fw-bold">Accéder aux graphiques</a>
    </div>

</div>
</main>

<footer class="bg-light py-4 border-top mt-auto shadow-sm">
    <div class="container text-center">
        <p class="text-muted mb-0 small fw-bold">Projet SDD4 - L3 MIASHS - Université de Montpellier Paul Valéry</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>