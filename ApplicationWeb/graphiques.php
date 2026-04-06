<?php
# 1. CONFIGURATION CONNEXION BDD
$host = 'localhost';
$dbname = 'dechets';

$configs = [
    ['port' => '8889', 'user' => 'root', 'pass' => 'root'], 
    ['port' => '3306', 'user' => 'root', 'pass' => ''],     
    ['port' => '3306', 'user' => 'root', 'pass' => 'root'], 
    ['port' => '3308', 'user' => 'root', 'pass' => '']      
];

$pdo = null;
$error_db = null;

foreach ($configs as $config) {
    try {
        $dsn = "mysql:host=$host;port={$config['port']};dbname=$dbname;charset=utf8";
        $pdo = new PDO($dsn, $config['user'], $config['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $error_db = null; 
        break;
    } catch (PDOException $e) {
        $error_db = $e->getMessage();
    }
}

# 2. RÉCUPÉRATION DES DONNÉES
try {
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
    <title>Analyses Graphiques | ÉcoScan</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="hero text-center">
    <div class="container">
        <h1>Analyses & Visualisations</h1>
        <p>Explorez les tendances nationales ou croisez les données des 7 000 communes.</p>
    </div>
</div>

<div class="container nav-mode text-center">
    <div class="btn-group btn-group-toggle" role="group">
        <button type="button" id="btnFixe" class="btn btn-toggle btn-success shadow-sm" onclick="switchMode('fixed')">
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
                    <img src="" id="mainGraph" class="fade-in img-fluid rounded shadow" alt="Graphique d'analyse">
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
        <div class="row g-4 mb-5 bg-light p-4 rounded-4 text-start">
            <div class="col-md-4">
                <label class="fw-bold mb-2">1. Filtrer par régions :</label>
                <div class="region-checklist shadow-sm bg-white p-3 border rounded" style="max-height: 200px; overflow-y: auto;">
                    <?php foreach($allRegions as $reg): ?>
                        <div class="form-check">
                            <input class="form-check-input region-checkbox" type="checkbox" value="<?= htmlspecialchars($reg) ?>" id="check-<?= md5($reg) ?>">
                            <label class="form-check-label small" for="check-<?= md5($reg) ?>"><?= htmlspecialchars($reg) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-2 small">
                    <a href="javascript:void(0)" class="text-success me-2" onclick="toggleAll(true)">Tout cocher</a>
                    <a href="javascript:void(0)" class="text-muted" onclick="toggleAll(false)">Tout décocher</a>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="mb-3">
                    <label class="fw-bold mb-2">2. Type de rendu :</label>
                    <select id="chartType" class="form-select" onchange="toggleScatterUI()">
                        <option value="bar">Barres (Moyennes régionales)</option>
                        <option value="polarArea">Radar (Moyennes régionales)</option>
                        <option value="scatter">Nuage de points (Détail par commune)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label id="labelVarY" class="fw-bold mb-2">3. Variable Axe Y :</label>
                    <select id="variableSelectY" class="form-select">
                        <option value="taux_dechets_tries">Taux de tri réel (%)</option>
                        <option value="cout_total_habitant">Coût total par habitant (€)</option>
                        <option value="cout_dechets_tries">Coût déchets triés (€)</option>
                        <option value="revenu_moyen_imposable_habitant">Revenu moyen imposable (€)</option>
                        <option value="nb_habitant">Population Totale</option>
                        <option value="densite_population">Densité (Hab/km²)</option>
                        <option value="altitude">Altitude moyenne (m)</option> 
                    </select>
                </div>

                <div id="scatterXGroup" class="mb-3" style="display: none;">
                    <label class="fw-bold mb-2 text-success">4. Variable Axe X (Abscisse) :</label>
                    <select id="variableSelectX" class="form-select border-success">
                        <option value="taux_dechets_tries">Taux de tri réel (%)</option>
                        <option value="cout_total_habitant">Coût total par habitant (€)</option>
                        <option value="cout_dechets_tries">Coût déchets triés (€)</option>
                        <option value="revenu_moyen_imposable_habitant">Revenu moyen imposable (€)</option>
                        <option value="nb_habitant">Population Totale</option>
                        <option value="densite_population">Densité (Hab/km²)</option>
                        <option value="altitude">Altitude moyenne (m)</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button class="btn btn-success flex-grow-1 py-3 fw-bold rounded-pill shadow-sm" onclick="runAnalysis()">
                    <i class="bi bi-play-fill me-2"></i>Comparer
                </button>
                <button class="btn btn-outline-secondary py-3 rounded-pill" onclick="resetComparison()">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>

        <div class="chart-container fade-in" style="min-height: 450px; position: relative;">
            <div id="emptyChartMessage" class="text-center py-5">
                <i class="bi bi-graph-up-arrow fs-1 text-success opacity-25"></i>
                <h4 class="mt-3 text-muted">Prêt pour l'analyse</h4>
                <p class="text-secondary small">Sélectionnez vos paramètres et cliquez sur <b>Comparer</b>.</p>
            </div>
            <canvas id="dynamicChart" style="display: none;"></canvas>
        </div>
    </div>
</div>

<script>
const rawData = <?= $jsonStats ?>;
const palette = ['#2ecc71', '#3498db', '#9b59b6', '#f1c40f', '#e67e22', '#e74c3c', '#1abc9c', '#2c3e50', '#ff9ff3', '#feca57'];
let myChartInstance = null;

const regionalMapping = {
    "taux_dechets_tries": "taux_tri_reel",
    "cout_total_habitant": "cout_moyen",
    "cout_dechets_tries": "tri_moyen",
    "revenu_moyen_imposable_habitant": "revenu",
    "nb_habitant": "pop_totale",
    "densite_population": "densite",
    "altitude": "altitude_moyenne"
};

const descriptions = {
    "../visualisation/images/carte_tri_regions.png": "Ici une carte réalisée avec matplotlib représentant l’Italie ainsi que ses 20 régions délimitées. On obtient une représentation par région du taux de tri des déchets, avec un affichage de plus en plus vert foncé si ce taux est plus élevé. On remarque qu’une tendance se dégage, avec un pourcentage plus élevé au fur et à mesure que l’on se dirige au nord du pays, dans les régions plus riches. La carte est issue du site de l’Institut National de Statistique italien mettant à jour les frontières chaque année.",
    "../visualisation/images/dechets_plus_triés.png": "On compare la moyenne de chaque matériau : l'organique domine largement les scores. Comme c'est la matière la plus lourde, c’est elle qui fait monter les moyennes de recyclage. Le papier et le verre suivent, tandis que le plastique semble faible en poids alors qu'il prend beaucoup de place en volume. En conclusion, l’analyse montre que le tri est fortement lié à la densité et au poids des matériaux.",
    "../visualisation/images/taux_tri_altitude.png": "Le taux de tri diminue à mesure que l’altitude augmente. De 0 à 250 m, le taux moyen atteint 70% tandis qu’en montagne (+750m), il chute à 56%. Cette tendance s’explique par des facteurs logistiques : accessibilité complexe pour les camions de ramassage, infrastructures plus éloignées et pics de fréquentation saisonnière liés au tourisme de montagne.",
    "../visualisation/images/Box-Plot(taux-classe).png": "Ce graphique représente le taux de déchets collectés par commune. Les déchets organiques sont les plus collectés mais avec de fortes disparités. Le papier et le verre suivent avec une médiane autour de 10%. On note que certaines communes obtiennent des résultats extrêmes bien au-dessus de la moyenne, signalant des performances exceptionnelles localisées.",
    "../visualisation/images/comparaison_cout.png": "On compare les coûts totaux par habitant (€) selon deux systèmes de taxes : PAYT (Pay As You Throw) et Fixe. La taxe au poids (PAYT) permet de mieux maîtriser les coûts pour la majorité des villes. Néanmoins, quelques points extrêmes montrent que le coût dépend aussi d'autres variables structurelles de la commune.",
    "../visualisation/images/performanceVStaille.png": "Ce Bubble Chart montre que les communes utilisant la taxe au poids (PAYT) affichent généralement un meilleur taux de tri. On remarque également que les villes les plus peuplées ont tendance à avoir un taux de tri plus bas, illustrant la difficulté de gérer un système de tri performant à très grande échelle.",
    "../visualisation/images/comparaison_dechets_region.png": "Ce graphique compare les zones les plus productrices de déchets. On observe que le Lazio est la région produisant le plus de déchets totaux (portée par Rome). Cependant, des régions comme la Lombardie, la Toscane ou la Vénétie affichent une part beaucoup plus importante de déchets triés par rapport à leur production totale."
};

function toggleScatterUI() {
    const isScatter = document.getElementById('chartType').value === 'scatter';
    document.getElementById('scatterXGroup').style.display = isScatter ? 'block' : 'none';
    document.getElementById('labelVarY').innerText = isScatter ? "3. Variable Axe Y (Communes) :" : "3. Variable d'analyse :";
}

function switchMode(mode) {
    document.getElementById('sectionFixe').style.display = (mode === 'fixed') ? 'block' : 'none';
    document.getElementById('sectionDynamic').style.display = (mode === 'dynamic') ? 'block' : 'none';
    document.getElementById('btnFixe').className = (mode === 'fixed') ? 'btn btn-toggle btn-success shadow-sm' : 'btn btn-toggle btn-light';
    document.getElementById('btnDynamic').className = (mode === 'dynamic') ? 'btn btn-toggle btn-success shadow-sm' : 'btn btn-toggle btn-light';
}

function runAnalysis() {
    const type = document.getElementById('chartType').value;
    const regions = Array.from(document.querySelectorAll('.region-checkbox:checked')).map(cb => cb.value);
    if (regions.length === 0) { alert("Sélectionnez au moins une région !"); return; }

    document.getElementById('emptyChartMessage').style.display = 'none';
    document.getElementById('dynamicChart').style.display = 'block';

    if (type === 'scatter') { renderScatter(regions); } else { renderRegional(regions, type); }
}

function renderRegional(regions, type) {
    const filtered = rawData.filter(d => regions.includes(d.region));
    const selectKey = document.getElementById('variableSelectY').value;
    const jsonKey = regionalMapping[selectKey];
    const labelY = document.getElementById('variableSelectY').options[document.getElementById('variableSelectY').selectedIndex].text;

    if (myChartInstance) myChartInstance.destroy();
    myChartInstance = new Chart(document.getElementById('dynamicChart').getContext('2d'), {
        type: type,
        data: {
            labels: filtered.map(d => d.region),
            datasets: [{
                label: labelY,
                data: filtered.map(d => d[jsonKey]),
                backgroundColor: filtered.map((_, i) => palette[i % palette.length] + 'B3'),
                borderColor: filtered.map((_, i) => palette[i % palette.length]),
                borderWidth: 2
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

function renderScatter(regions) {
    const keyX = document.getElementById('variableSelectX').value;
    const keyY = document.getElementById('variableSelectY').value;

    fetch(`get_communes_data.php?x=${keyX}&y=${keyY}&regions=${encodeURIComponent(regions.join(','))}`)
        .then(res => res.json())
        .then(data => {
            if (myChartInstance) myChartInstance.destroy();

            const groups = {};
            data.forEach(pt => { if (!groups[pt.region]) groups[pt.region] = []; groups[pt.region].push(pt); });

            const datasets = Object.keys(groups).map((region, i) => ({
                label: region,
                data: groups[region],
                backgroundColor: palette[i % palette.length] + '66',
                borderColor: palette[i % palette.length],
                pointRadius: 2.5,
                hoverRadius: 6
            }));

            myChartInstance = new Chart(document.getElementById('dynamicChart').getContext('2d'), {
                type: 'scatter',
                data: { datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'logarithmic',
                            title: { display: true, text: document.getElementById('variableSelectX').options[document.getElementById('variableSelectX').selectedIndex].text },
                            ticks: {
                                autoSkip: true,
                                maxRotation: 0,
                                callback: function(value) {
                                    const remain = value / (Math.pow(10, Math.floor(Math.log10(value))));
                                    return (remain === 1 || remain === 2 || remain === 5) ? value : '';
                                }
                            }
                        },
                        y: {
                            type: 'logarithmic',
                            title: { display: true, text: document.getElementById('variableSelectY').options[document.getElementById('variableSelectY').selectedIndex].text },
                            ticks: {
                                autoSkip: true,
                                callback: function(value) {
                                    const remain = value / (Math.pow(10, Math.floor(Math.log10(value))));
                                    return (remain === 1 || remain === 2 || remain === 5) ? value : '';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: true },
                        tooltip: { callbacks: { label: (c) => `${c.raw.commune} | X: ${c.raw.x} | Y: ${c.raw.y}` } },
                        // --- AJOUT DU ZOOM ICI ---
                        zoom: {
                            pan: {
                                enabled: true,
                                mode: 'xy', // Permet de se déplacer horizontalement et verticalement
                                threshold: 10
                            },
                            zoom: {
                                wheel: {
                                    enabled: true, // Zoom avec la molette de la souris
                                },
                                pinch: {
                                    enabled: true // Zoom avec les doigts sur trackpad/mobile
                                },
                                mode: 'xy',
                            }
                        }
                    }
                }
            });
        });
}

function toggleAll(status) { document.querySelectorAll('.region-checkbox').forEach(cb => cb.checked = status); }

function resetComparison() {
    toggleAll(false);
    if (myChartInstance) myChartInstance.destroy();
    document.getElementById('dynamicChart').style.display = 'none';
    document.getElementById('emptyChartMessage').style.display = 'block';
}

function changerGraphique() {
    const select = document.getElementById('graphSelect');
    document.getElementById('displayArea').style.display = 'block';
    document.getElementById('placeholderText').style.display = 'none';
    document.getElementById('mainGraph').src = select.value;
    document.getElementById('graphTitle').innerText = select.options[select.selectedIndex].text;
    document.getElementById('analysisText').innerHTML = "<strong>Analyse :</strong> " + (descriptions[select.value] || "Analyse en attente...");
}
</script>
</body>
</html>