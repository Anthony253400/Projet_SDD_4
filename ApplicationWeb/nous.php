<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Qui sommes-nous | Tri Déchets</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<header class="page-header text-center text-black">
    <div class="container">
        <h1 class="fw-bold">Notre Équipe</h1>
        <p class="lead">Les étudiants derrière le projet Tri Déchets Italie - L3 MIASHS</p>
        <div class="title-separator mx-auto"></div>
    </div>
</header>

<main class="container my-5">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card team-card shadow-sm border-0 text-center p-3">
                <div class="team-img-placeholder mx-auto mb-3">
                    <i class="fa-solid fa-user fa-4x text-muted"></i>
                </div>
                <h5 class="fw-bold">Ambre Faizandier</h5>
                <p class="text-success small fw-bold">Étudiante</p>
                <p class="text-muted small">ambre.faizandier@etu.univ-montp3.fr</p>
                <a class="text-muted small" href="https://github.com/Ambre0108">Github</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card team-card shadow-sm border-0 text-center p-3">
                <div class="team-img-placeholder mx-auto mb-3">
                    <i class="fa-solid fa-user fa-4x text-muted"></i>
                </div>
                <h5 class="fw-bold">Anthony Miranda</h5>
                <p class="text-success small fw-bold">Étudiant</p>
                <p class="text-muted small">anthony.miranda@etu.univ-montp3.fr.</p>
                <a class="text-muted small" href="https://github.com/Anthony253400">Github</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card team-card shadow-sm border-0 text-center p-3">
                <div class="team-img-placeholder mx-auto mb-3">
                    <i class="fa-solid fa-user fa-4x text-muted"></i>
                </div>
                <h5 class="fw-bold">Nina Valentin</h5>
                <p class="text-success small fw-bold">Étudiante</p>
                <p class="text-muted small">nina.valentin@etu.univ-montp3.fr</p>
                <a class="text-muted small" href="https://github.com/Nina253">Github</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card team-card shadow-sm border-0 text-center p-3">
                <div class="team-img-placeholder mx-auto mb-3">
                    <i class="fa-solid fa-user fa-4x text-muted"></i>
                </div>
                <h5 class="fw-bold">Victor Croenne</h5>
                <p class="text-success small fw-bold">Étudiant</p>
                <p class="text-muted small">victor.croenne@etu.univ-montp3.fr</p>
                <a class="text-muted small" href="https://github.com/vcroenne">Github</a>
            </div>
        </div>
    </div>

    <section class="about-section mt-5 pt-5 text-center">
    <div class="container">
        <h3 class="fw-bold mb-4">À propos du projet</h3>
        
        <p class="text-muted mx-auto about-text">
            Ce projet a été réalisé dans le cadre de l'UE <strong>Science des Données 4</strong> du parcours L3 MIASHS à l'Université de Montpellier Paul Valéry. 
            Notre mission est double : prédire les flux de déchets communaux via l'IA et accompagner les citoyens dans leur geste de tri grâce à la reconnaissance d'images.
        </p>

        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <h5 class="text-secondary fw-bold mb-3">Nos sources de données (Kaggle)</h5>
                <div class="list-group list-group-horizontal-md justify-content-center">
                    <a href="https://www.kaggle.com/datasets/shashwatwork/municipal-waste-management-cost-prediction" 
                       class="list-group-item list-group-item-action border-success text-success d-flex align-items-center justify-content-center" target="_blank">
                        <i class="fa-solid fa-database me-2"></i> Waste Management Cost
                    </a>
                    <a href="https://www.kaggle.com/datasets/hassnainzaidi/garbage-classification" 
                       class="list-group-item list-group-item-action border-success text-success d-flex align-items-center justify-content-center" target="_blank">
                        <i class="fa-solid fa-images me-2"></i> Garbage Classification
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <p class="mb-2 text-muted">Retrouvez l'intégralité du code source et de la documentation :</p>
            <a href="https://github.com/Anthony253400/Projet_SDD_4" class="btn btn-dark btn-github shadow-sm" target="_blank">
                <i class="fa-brands fa-github me-2"></i> Voir sur GitHub
            </a>
        </div>
    </div>
    </section>
</main>

<footer class="bg-light py-4 border-top">
    <div class="container text-center">
        <p class="text-muted mb-0 small">Projet SDD4 - L3 MIASHS - 2026</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>