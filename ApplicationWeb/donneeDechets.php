<?php
header("Content-Type: application/json");
$host = "localhost"; $user = "root"; $pass = "root"; $db = "dechets";
$conn = new mysqli($host, $user, $pass, $db);

$code = $_GET['code'] ?? '';

// 1. Moyenne nationale de tri (pour la comparaison)
$resMoy = $conn->query("SELECT AVG(taux_dechets_tries) as moyenne_nationale FROM municipalite");
$moyenneTable = $resMoy->fetch_assoc();
$moyenneGlobale = $moyenneTable['moyenne_nationale'];

// 2. Infos de la région cliquée
// On fait un AVG car une région contient plusieurs municipalités dans ta base
$sql = "SELECT region, 
        AVG(taux_dechets_tries) as moyenne_region,
        SUM(quantite_totale_dechets_kg) as total_dechets,
        AVG(cout_total_habitant) as cout_moyen,
        AVG(revenu_moyen_imposable_habitant) as richesse,
        AVG(altitude) as altitude,
        MAX(bord_de_mer) as littoral
        FROM municipalite 
        WHERE region = ? 
        GROUP BY region";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    $data['moyenne_nationale'] = round($moyenneGlobale, 2);
    $data['moyenne_region'] = round($data['moyenne_region'], 2);
    // On ajoute un petit texte de comparaison
    $diff = $data['moyenne_region'] - $data['moyenne_nationale'];
    $data['comparaison'] = ($diff > 0) ? "supérieur de ".abs($diff)."%" : "inférieur de ".abs($diff)."%";
    
    echo json_encode($data);
} else {
    echo json_encode(["error" => "Région non trouvée"]);
}