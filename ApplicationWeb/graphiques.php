<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyses Graphiques | L3 MIASHS</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* On garde exactement ton style de base */
        body { background-color: #f8f9fa; }
        .hero { 
            background: linear-gradient(135deg, #2ecc71, #27ae60); 
            color: white; 
            padding: 40px 0; 
        }
        .hero a { color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 500; }
        .hero a:hover { color: white; text-decoration: underline; }

        /* Style des cartes pour les graphiques */
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            height: 100%;
            transition: transform 0.2s;
        }
        .card:hover { transform: scale(1.02); }
        
        .graph-img {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #eee;
            margin-bottom: 15px;
        }

        .section-title {
            color: #27ae60;
            font-weight: bold;
            margin-top: 50px;
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mb-5">
    
    <h2 class="section-title"> Visualisation des Résultats & Analyses</h2>

    <div class="row g-4">
        
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="fw-bold">Répartition du tri par région en Italie</h5>
                <img src="../visualisation/images/carte_tri_regions.png" class="graph-img">
                <p class="text-muted small"> Voici une carte représentant la répartition du tri par région en Italie. Plus le vert est foncé, plus la région trie de déchets. Une tendance se dégage avec un taux plus élevé dans les régions du nord.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="fw-bold">Répartition de tri par matière</h5>
                <img src="../visualisation/images/dechets_plus_triés.png" class="graph-img">
                <p class="text-muted small">Ce graphique montre que les matières organiques dominent largement le tri en Italie,
                                             avec plus de 20 kg par habitant. Le papier et le verre suivent, tandis que le bois, le métal
                                              et les déchets électroniques (RAEE) ferment la marche avec des volumes bien plus faibles.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="fw-bold">Précision du Modèle</h5>
                <img src="../visualisation/images/taux_tri_altitude.png" class="graph-img">
                
                <div class="text-muted small">
                    <p>Le taux de tri chute de 70 % en plaine à 56 % en montagne. 
                    Cet écart s'explique par trois freins logistiques majeurs en altitude :</p>

                    <ul>
                        <li><strong>Accessibilité :</strong> Collecte difficile pour les camions.</li>
                        <li><strong>Infrastructures :</strong> Éloignement des centres de traitement.</li>
                        <li><strong>Saisonnalité :</strong> Pics de déchets liés au tourisme de montagne.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="fw-bold">Déchets les plus triés</h5>
                <img src="../visualisation/images/dechets_plus_triés.png" class="graph-img">
                <p class="text-muted small">Identification des matériaux présentant les meilleurs taux de réussite au tri.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="fw-bold">Déchets les plus triés</h5>
                <img src="../visualisation/images/dechets_plus_triés.png" class="graph-img">
                <p class="text-muted small">Identification des matériaux présentant les meilleurs taux de réussite au tri.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="fw-bold">Déchets les plus triés</h5>
                <img src="../visualisation/images/dechets_plus_triés.png" class="graph-img">
                <p class="text-muted small">Identification des matériaux présentant les meilleurs taux de réussite au tri.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="fw-bold">Déchets les plus triés</h5>
                <img src="../visualisation/images/dechets_plus_triés.png" class="graph-img">
                <p class="text-muted small">Identification des matériaux présentant les meilleurs taux de réussite au tri.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="fw-bold">Déchets les plus triés</h5>
                <img src="../visualisation/images/dechets_plus_triés.png" class="graph-img">
                <p class="text-muted small">Identification des matériaux présentant les meilleurs taux de réussite au tri.</p>
            </div>
        </div>

    </div>
</div>

<footer class="text-center text-muted py-4">
    <small>Projet L3 MIASHS - 2026</small>
</footer>

</body>
</html>