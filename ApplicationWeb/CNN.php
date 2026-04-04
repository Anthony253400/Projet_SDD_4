<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tri Déchets | L3 MIASHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .hero { background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; padding: 40px 0; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="hero text-center">
    <h1>♻️ Projet Tri des Déchets</h1>
    <p>Modèles IA & Analyse des Déchets</p>
    <a href="index.html">Accueil</a>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card p-4">
                <h3>📊 Prédictions</h3>
                <p class="text-muted">Basé sur notre régression multinomiale.</p>
                <h4 class="mt-4 text-center">Résultat : <span id="resultatTri">--</span>%</h4>
            </div>
        </div>
    </div>
</div>

</body>
</html>