<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tri Déchets | L3 MIASHS</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<header class="accueil">
    <div class="container">
        <h1>L'IA au service de l'écologie</h1>
        <p>Analysez la gestion des déchets en Italie...</p>
        <div class="accueil-btns">
            <a href="carte.php" class="btn-main">Explorer la Carte</a>
            <a href="prediction.php" class="btn-outline">Prédire le Mix</a>
        </div>
    </div>
</header>

<div class="container stats-container">
    <div class="row g-4 justify-content-center text-center">
        <div class="col-md-3">
            <div class="card shadow border-0 p-3">
                <div class="card-body">
                    <h2 class="fw-bold text-success">20</h2>
                    <p class="text-muted mb-0">Régions Italiennes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow border-0 p-3">
                <div class="card-body">
                    <h2 class="fw-bold text-success">+7 000</h2>
                    <p class="text-muted mb-0">Communes analysées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow border-0 p-3">
                <div class="card-body">
                    <h2 class="fw-bold text-success">3</h2>
                    <p class="text-muted mb-0">Modèles d'IA</p>
                </div>
            </div>
        </div>
    </div>
</div>

<main class="container my-5 py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Comment utiliser la plateforme ?</h2>
        <div class="title-separator mx-auto"></div>
    </div>

    <div class="row g-5 text-center">
        <div class="col-md-4">
            <div class="mb-4 icon-wrapper">
                <i class="fa-solid fa-map-location-dot fa-4x text-success"></i>
            </div>
            <h4 class="fw-bold">1. Visualisez</h4>
            <p class="text-muted">Utilisez la carte interactive pour observer les disparités régionales. Identifiez les zones géographiques les plus performantes en matière de recyclage.</p>
        </div>

        <div class="col-md-4">
            <div class="mb-4 icon-wrapper">
                <i class="fa-solid fa-chart-line fa-4x text-success"></i>
            </div>
            <h4 class="fw-bold">2. Analysez</h4>
            <p class="text-muted">Croisez les données : est-ce l'altitude, la richesse par habitant ou l'urbanisation qui influence le plus le tri des déchets organiques ?</p>
        </div>

        <div class="col-md-4">
            <div class="mb-4 icon-wrapper">
                <i class="fa-solid fa-brain fa-4x text-success"></i>
            </div>
            <h4 class="fw-bold">3. Prédisez</h4>
            <p class="text-muted">Soumettez les caractéristiques d'une ville fictive à nos algorithmes pour obtenir une estimation précise du mix de déchets.</p>
        </div>
    </div>
</main>

<footer class="bg-light py-4 border-top mt-auto">
    <div class="container text-center">
        <p class="text-muted mb-0 small">Projet SDD4 - L3 MIASHS - Université de Montpellier Paul Valéry</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>