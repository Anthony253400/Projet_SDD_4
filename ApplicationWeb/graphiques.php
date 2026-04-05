<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyses Graphiques | L3 MIASHS</title>
    
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body { background-color: #f8f9fa; }

        /* --- Bandeau Hero optimisé (plus compact) --- */
        .hero { 
            background: linear-gradient(135deg, #2ecc71, #27ae60); 
            color: white; 
            padding: 80px 0; /* On remet un peu de hauteur pour laisser respirer le titre */
            margin-bottom: 0; 
        }

        .hero h1 {
            font-size: 3rem; 
            font-weight: 800;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* --- Effet de relief sur la carte d'analyse --- */
        .explorer-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            
            /* On change le margin-top négatif par une valeur plus douce ou 0 */
            margin-top: -40px; 
            
            position: relative;
            z-index: 10;
        }

        .custom-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            display: block;
        }

        .form-select-lg {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .form-select-lg:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 0.25rem rgba(39, 174, 96, 0.1);
        }

        #mainGraph {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            background: white;
        }

        #graphTitle {
            color: #2c3e50;
            font-weight: 700;
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
        }

        #graphTitle::after {
            content: "";
            display: block;
            width: 60px;
            height: 4px;
            background: #27ae60;
            margin: 10px auto 0;
            border-radius: 10px;
        }

        /* Style pour la description d'analyse */
        .analysis-box {
            max-width: 900px;
            margin: 35px auto 0;
            padding: 25px;
            background-color: #f9fdfb;
            border-left: 5px solid #27ae60;
            border-radius: 0 15px 15px 0;
            text-align: left;
            color: #4a5568;
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .display-wrapper {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="hero text-center">
    <div class="container">
        <h1>Visualisation de nos Graphiques</h1>
        <p>Sélectionnez une thématique pour explorer nos analyses détaillées</p>
    </div>
</div>

<div class="container mb-5">
    <div class="explorer-card p-4 p-md-5">
        
        <div class="row justify-content-center mb-5">
            <div class="col-lg-9 text-center">
                <label for="graphSelect" class="custom-label">Quelle analyse souhaitez-vous consulter ?</label>
                <select class="form-select form-select-lg shadow-sm" id="graphSelect" onchange="changerGraphique()">
                    <option value="" selected disabled>-- Sélectionnez un graphique --</option>
                    <option value="../visualisation/images/carte_tri_regions.png">🇮🇹 Répartition du tri par région (Italie)</option>
                    <option value="../visualisation/images/dechets_plus_triés.png">♻️ Répartition du tri par matière</option>
                    <option value="../visualisation/images/taux_tri_altitude.png">⛰️ Impact de l'altitude sur le tri</option>
                    <option value="../visualisation/images/Box-Plot(taux-classe).png">🗑️ Taux de collecte des déchets (Box-Plot)</option>
                    <option value="../visualisation/images/comparaison_cout.png">💰 Coût totaux par habitant en fonction de la taxe</option>
                    <option value="../visualisation/images/performanceVStaille.png">📊 Petite commune = meilleur tri ?</option>
                    <option value="../visualisation/images/comparaison_dechets_region.png">🚮 Déchets triés / non triés par région</option>
                </select>
            </div>
        </div>

        <div class="display-wrapper border-top pt-4">
            <div id="displayArea" class="text-center w-100" style="display: none;">
                <h3 id="graphTitle" class="fade-in"></h3>
                <div class="mt-2">
                    <img src="" id="mainGraph" class="img-fluid fade-in" alt="Graphique d'analyse">
                </div>
                <div id="analysisText" class="analysis-box fade-in"></div>
            </div>
            
            <div id="placeholderText" class="text-center text-muted py-5">
                <i class="bi bi-bar-chart-steps" style="font-size: 4rem; color: #e9ecef;"></i>
                <p class="mt-3 fs-5">Choisissez une analyse dans le menu ci-dessus pour afficher les résultats et l'interprétation.</p>
            </div>
        </div>

    </div>
</div>

<script>
// --- TES DESCRIPTIONS ---
const descriptions = {
    "../visualisation/images/carte_tri_regions.png": "Ici une carte réalisée avec matplotlib représentant l’Italie ainsi que ses 20 régions délimitées. On obtient une représentation par région du taux de tri des déchets, avec un affichage de plus en plus vert foncé si ce taux est plus élevé. On remarque qu’une tendance se dégage, avec un pourcentage plus élevé au fur et à mesure que l’on se dirige au nord du pays, dans les régions plus riches. La carte est issue du site de l’Institut National de Statistique italien mettant à jour les frontières chaque année.",
    
    "../visualisation/images/dechets_plus_triés.png": "On compare la moyenne de chaque matériau : l'organique domine largement les scores. Comme c'est la matière la plus lourde, c’est elle qui fait monter les moyennes de recyclage. Le papier et le verre suivent, tandis que le plastique semble faible en poids alors qu'il prend beaucoup de place en volume. En conclusion, l’analyse montre que le tri est fortement lié à la densité et au poids des matériaux.",
    
    "../visualisation/images/taux_tri_altitude.png": "Le taux de tri diminue à mesure que l’altitude augmente. De 0 à 250 m, le taux moyen atteint 70% tandis qu’en montagne (+750m), il chute à 56%. Cette tendance s’explique par des facteurs logistiques : accessibilité complexe pour les camions de ramassage, infrastructures plus éloignées et pics de fréquentation saisonnière liés au tourisme de montagne.",
    
    "../visualisation/images/Box-Plot(taux-classe).png": "Ce graphique représente le taux de déchets collectés par commune. Les déchets organiques sont les plus collectés mais avec de fortes disparités. Le papier et le verre suivent avec une médiane autour de 10%. On note que certaines communes obtiennent des résultats extrêmes bien au-dessus de la moyenne, signalant des performances exceptionnelles localisées.",
    
    "../visualisation/images/comparaison_cout.png": "On compare les coûts totaux par habitant (€) selon deux systèmes de taxes : PAYT (Pay As You Throw) et Fixe. La taxe au poids (PAYT) permet de mieux maîtriser les coûts pour la majorité des villes. Néanmoins, quelques points extrêmes montrent que le coût dépend aussi d'autres variables structurelles de la commune.",
    
    "../visualisation/images/performanceVStaille.png": "Ce Bubble Chart montre que les communes utilisant la taxe au poids (PAYT) affichent généralement un meilleur taux de tri. On remarque également que les villes les plus peuplées ont tendance à avoir un taux de tri plus bas, illustrant la difficulté de gérer un système de tri performant à très grande échelle.",
    
    "../visualisation/images/comparaison_dechets_region.png": "Ce graphique compare les zones les plus productrices de déchets. On observe que le Lazio est la région produisant le plus de déchets totaux (portée par Rome). Cependant, des régions comme la Lombardie, la Toscane ou la Vénétie affichent une part beaucoup plus importante de déchets triés par rapport à leur production totale."
};

function changerGraphique() {
    const select = document.getElementById('graphSelect');
    const displayArea = document.getElementById('displayArea');
    const placeholder = document.getElementById('placeholderText');
    const mainImg = document.getElementById('mainGraph');
    const titleImg = document.getElementById('graphTitle');
    const analysisText = document.getElementById('analysisText');

    if (select.value !== "") {
        const imagePath = select.value;
        
        // On retire les classes d'animation
        [mainImg, titleImg, analysisText].forEach(el => el.classList.remove('fade-in'));
        
        void mainImg.offsetWidth; // Reflow pour relancer l'animation

        // Mise à jour de l'image et du titre (nettoyage de l'emoji)
        mainImg.src = imagePath;
        const fullText = select.options[select.selectedIndex].text;
        titleImg.innerText = fullText.substring(fullText.indexOf(' ') + 1);

        // Mise à jour de la description
        analysisText.innerHTML = "<strong>Analyse :</strong> " + (descriptions[imagePath] || "Analyse en attente de rédaction.");

        // Affichage
        displayArea.style.display = "block";
        placeholder.style.display = "none";
        
        // Relance de l'animation
        [mainImg, titleImg, analysisText].forEach(el => el.classList.add('fade-in'));
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>