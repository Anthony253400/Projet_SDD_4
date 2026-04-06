<?php
$host = 'localhost';
$port = '8889';
$dbname = 'dechets';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $queryRegions = $pdo->query("SELECT DISTINCT region FROM municipalites ORDER BY region ASC");
    $allRegions = $queryRegions->fetchAll(PDO::FETCH_COLUMN);

    $queryStats = $pdo->query("SELECT region, 
                               AVG(cout_total_habitant) as cout_moyen, 
                               AVG(cout_dechets_tries) as tri_moyen, 
                               SUM(nb_habitant) as pop_totale,
                               AVG(taux_dechets_tries) as taux_tri_reel,
                               AVG(revenu_moyen_imposable_habitant) as revenu,
                               AVG(densite_population) as densite,
                               AVG(altitude) as altitude_moyenne 
                               FROM municipalites 
                               GROUP BY region");
    $statsData = $queryStats->fetchAll(PDO::FETCH_ASSOC);
    $jsonStats = json_encode($statsData);

} catch (PDOException $e) {
    $error_db = $e->getMessage();
    $allRegions = [];
    $jsonStats = "[]";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyses Graphiques | L3 MIASHS</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="hero text-center">
    <div class="container">
        <h1>Analyses & Visualisations</h1>
        <p>Explorez nos résultats ou générez votre propre comparaison</p>
        <?php if(isset($error_db)) echo "<p class='badge bg-danger'>Erreur SQL : $error_db</p>"; ?>
    </div>
</div>

<div class="container nav-mode text-center">
    <div class="btn-group btn-group-toggle" role="group">
        <button type="button" id="btnFixe" class="btn btn-toggle btn-success shadow-sm" onclick="switchMode('fixe')">
            <i class="bi bi-images me-2"></i>Analyses Prédéfinies
        </button>
        <button type="button" id="btnDynamic" class="btn btn-toggle btn-light" onclick="switchMode('dynamic')">
            <i class="bi bi-sliders me-2"></i>Comparateur Interactif
        </button>
    </div>
</div>

<div id="sectionFixe" class="container mb-5">
    <div class="explorer-card p-4 p-md-5 text-center">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-9 text-center">
                <label for="graphSelect" class="custom-label fw-bold mb-3 d-block text-secondary small">Quelle analyse souhaitez-vous consulter ?</label>
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
                <h3 id="graphTitle" class="fade-in fw-bold mb-4" style="color: #2c3e50;"></h3>
                <div class="mt-2">
                    <img src="" id="mainGraph" class="fade-in" alt="Graphique d'analyse">
                </div>
                <div id="analysisText" class="analysis-box fade-in"></div>
            </div>
            
            <div id="placeholderText" class="text-center text-muted py-5">
                <i class="bi bi-bar-chart-steps" style="font-size: 4rem; color: #e9ecef;"></i>
                <p class="mt-3 fs-5">Choisissez une analyse dans le menu ci-dessus pour afficher le graphique correspondant.</p>
            </div>
        </div>
    </div>
</div>

<div id="sectionDynamic" class="container mb-5" style="display: none;">
    <div class="explorer-card p-4 p-md-5">
        <div class="text-center mb-5">
            <h3 class="fw-bold" style="color: #2c3e50;">Comparateur de Régions Dynamique</h3>
            <p class="text-muted">Préparez votre sélection et cliquez sur comparer pour lancer l'analyse.</p>
        </div>

        <div class="row g-4 mb-5 bg-light p-4 rounded-4 text-start">
            <div class="col-md-5">
                <label class="fw-bold mb-2">Choisir les régions :</label>
                <div class="region-checklist shadow-sm">
                    <?php foreach($allRegions as $reg): ?>
                        <div class="form-check">
                            <input class="form-check-input region-checkbox" type="checkbox" value="<?= htmlspecialchars($reg) ?>" id="check-<?= md5($reg) ?>">
                            <label class="form-check-label small" for="check-<?= md5($reg) ?>">
                                <?= htmlspecialchars($reg) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-2">
                    <button class="btn btn-link btn-sm text-success text-decoration-none p-0" onclick="toggleAll(true)">Tout cocher</button>
                    <span class="text-muted mx-2">|</span>
                    <button class="btn btn-link btn-sm text-muted text-decoration-none p-0" onclick="toggleAll(false)">Tout décocher</button>
                </div>
            </div>
            <div class="col-md-4">
                <label class="fw-bold mb-2">Variable d'analyse :</label>
                <select id="variableSelect" class="form-select mb-3">
                    <option value="taux_tri_reel">Taux de tri réel (%)</option>
                    <option value="cout_moyen">Coût total par habitant (€)</option>
                    <option value="tri_moyen">Coût déchets triés (€)</option>
                    <option value="revenu">Revenu moyen imposable (€)</option>
                    <option value="pop_totale">Population Totale</option>
                    <option value="densite">Densité (Hab/km²)</option>
                    <option value="altitude_moyenne">Altitude moyenne (m)</option> </select>
                <label class="fw-bold mb-2">Type de graphique :</label>
                <select id="chartType" class="form-select">
                    <option value="bar">Graphique en barres</option>
                    <option value="polarArea">Radar Polaire</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow-sm" onclick="updateChart()">
                    <i class="bi bi-play-fill me-2"></i>Comparer
                </button>
            </div>
        </div>

        <div class="chart-container fade-in">
            <div id="emptyChartMessage" class="text-center py-5">
                <i class="bi bi-cursor-fill fs-1 text-success opacity-25"></i>
                <h4 class="mt-3 text-muted">Prêt pour l'analyse</h4>
                <p class="text-secondary small">Cochez les régions, choisissez vos variables puis cliquez sur <b>Comparer</b>.</p>
            </div>
            <canvas id="dynamicChart" style="display: none;"></canvas>
        </div>
    </div>
</div>

<script>
const rawData = <?= $jsonStats ?>;
const palette = ['#2ecc71', '#3498db', '#9b59b6', '#f1c40f', '#e67e22', '#e74c3c', '#1abc9c', '#2c3e50', '#ff9ff3', '#feca57'];

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
    document.getElementById('displayArea').style.display = 'block';
    document.getElementById('placeholderText').style.display = 'none';
    document.getElementById('mainGraph').src = select.value;
    document.getElementById('graphTitle').innerText = select.options[select.selectedIndex].text;
    document.getElementById('analysisText').innerHTML = "<strong>Analyse :</strong> " + (descriptions[select.value] || "Analyse en attente de rédaction.");
}

function switchMode(mode) {
    document.getElementById('sectionFixe').style.display = (mode === 'fixe') ? 'block' : 'none';
    document.getElementById('sectionDynamic').style.display = (mode === 'dynamic') ? 'block' : 'none';
    document.getElementById('btnFixe').className = (mode === 'fixe') ? 'btn btn-toggle btn-success shadow-sm' : 'btn btn-toggle btn-light';
    document.getElementById('btnDynamic').className = (mode === 'dynamic') ? 'btn btn-toggle btn-success shadow-sm' : 'btn btn-toggle btn-light';
    if(mode === 'dynamic') initChart();
}

function toggleAll(status) {
    document.querySelectorAll('.region-checkbox').forEach(cb => cb.checked = status);
}

let myChartInstance = null;
function initChart() {
    if (myChartInstance) return;
    document.querySelectorAll('.region-checkbox').forEach(cb => cb.checked = false);
}

function updateChart() {
    const canvas = document.getElementById('dynamicChart');
    const message = document.getElementById('emptyChartMessage');
    const checkedRegions = Array.from(document.querySelectorAll('.region-checkbox:checked')).map(cb => cb.value);
    
    if (checkedRegions.length === 0) {
        alert("Veuillez sélectionner au moins une région.");
        if (myChartInstance) { myChartInstance.destroy(); myChartInstance = null; }
        canvas.style.display = 'none'; message.style.display = 'block'; return;
    }

    message.style.display = 'none'; canvas.style.display = 'block';
    const filteredData = rawData.filter(item => checkedRegions.includes(item.region));
    const variable = document.getElementById('variableSelect').value;
    const varLabel = document.getElementById('variableSelect').options[document.getElementById('variableSelect').selectedIndex].text;
    const type = document.getElementById('chartType').value;

    if (myChartInstance) myChartInstance.destroy();
    myChartInstance = new Chart(canvas.getContext('2d'), {
        type: type,
        data: {
            labels: filteredData.map(d => d.region),
            datasets: [{
                label: varLabel,
                data: filteredData.map(d => d[variable]),
                backgroundColor: filteredData.map((_, i) => palette[i % palette.length] + 'B3'),
                borderColor: filteredData.map((_, i) => palette[i % palette.length]),
                borderWidth: 2, fill: true
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: {
                legend: { display: type !== 'bar' }
            },
            scales: (type === 'bar') ? { y: { beginAtZero: true } } : {}
        }
    });
}
</script>
</body>
</html>