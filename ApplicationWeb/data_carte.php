<?php
header("Content-Type: application/json");
$host = "localhost"; $user = "root"; $pass = "root"; $db = "dechets";
$conn = new mysqli($host, $user, $pass, $db);

// On calcule les stats par région sur la table municipalites (avec s)
$sql = "SELECT region, 
        AVG(taux_dechets_tries) as taux_tri,
        AVG(revenu_moyen_imposable_habitant) as richesse,
        SUM(nb_habitant) as population,
        SUM(quantite_totale_dechets_kg) as dechets
        FROM municipalites 
        GROUP BY region";

$result = $conn->query($sql);
$data = [];

while($row = $result->fetch_assoc()) {
    $data[] = [
        "region" => $row['region'],
        "taux_tri" => (float)$row['taux_tri'],
        "richesse" => (float)$row['richesse'],
        "population" => (float)$row['population'],
        "dechets" => (float)$row['dechets']
    ];
}

echo json_encode($data);