<?php
header("Content-Type: application/json");
$host = "localhost"; $user = "root"; $pass = "root"; $db = "dechets";
$conn = new mysqli($host, $user, $pass, $db);

$code = $_GET['code'] ?? '';

// 1. Moyenne nationale de tri (pour la comparaison)
$resMoy = $conn->query("SELECT AVG(taux_dechets_tries) as moyenne_nationale FROM municipalites");
$moyenneTable = $resMoy->fetch_assoc();
$moyenneGlobale = $moyenneTable['moyenne_nationale'];

$sql = "SELECT region, 
        AVG(taux_dechets_tries) as moyenne_region,
        SUM(quantite_totale_dechets_kg) as total_dechets,
        AVG(cout_total_habitant) as cout_moyen,
        SUM(nb_habitant) as total_pop, 
        AVG(niveau_revenus_habitants) as richesse, 
        AVG(altitude) as altitude,
        MAX(bord_de_mer) as bord_de_mer,
        AVG(geographie) as code_geo
        FROM municipalites
        WHERE region = ? 
        GROUP BY region";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    echo json_encode([
        "region" => $data['region'],
        "moyenne_region" => round($data['moyenne_region'], 2),
        "total_dechets" => $data['total_dechets'],
        "cout_moyen" => round($data['cout_moyen'], 2),
        "population" => $data['total_pop'],
        "richesse" => round($data['richesse'], 2),
        "altitude" => round($data['altitude'], 0),
        "bord_de_mer" => $data['bord_de_mer'],
        "code_geo" => round($data['code_geo'], 0), // Pour savoir si montagne/plaine
        "moyenne_nationale" => round($moyenneGlobale, 2)
    ]);
} else {
    echo json_encode(["error" => "Région non trouvée" . $code]);
}