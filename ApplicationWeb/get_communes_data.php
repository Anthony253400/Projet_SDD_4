<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'dechets';
$configs = [
    ['port' => '8889', 'user' => 'root', 'pass' => 'root'], 
    ['port' => '3306', 'user' => 'root', 'pass' => ''],     
    ['port' => '3306', 'user' => 'root', 'pass' => 'root'], 
    ['port' => '3308', 'user' => 'root', 'pass' => '']      
];

$pdo = null;
foreach ($configs as $config) {
    try {
        $dsn = "mysql:host=$host;port={$config['port']};dbname=$dbname;charset=utf8";
        $pdo = new PDO($dsn, $config['user'], $config['pass']);
        break;
    } catch (PDOException $e) {}
}

if (!$pdo) { die(json_encode(['error' => 'Connexion échouée'])); }

$x = $_GET['x'] ?? 'altitude';
$y = $_GET['y'] ?? 'taux_dechets_tries';
$regionsStr = $_GET['regions'] ?? '';

// Liste autorisée basée STRICTEMENT sur ton image de base de données
$allowed = [
    'id_municipalite', 'region', 'province', 'nom', 
    'cout_total_habitant', 'cout_dechets_non_tries', 'cout_dechets_tries', 
    'superficie', 'nb_habitant', 'altitude', 'densite_population', 
    'taux_dechets_tries', 'revenu_moyen_imposable_habitant'
];

if (!in_array($x, $allowed) || !in_array($y, $allowed)) {
    die(json_encode(['error' => 'Variable non autorisée']));
}

$regions = explode(',', $regionsStr);
$placeholders = implode(',', array_fill(0, count($regions), '?'));

// On récupère "region" en plus pour la coloration
$sql = "SELECT nom, region, $x as valX, $y as valY FROM municipalites WHERE region IN ($placeholders)";
$stmt = $pdo->prepare($sql);
$stmt->execute($regions);

$points = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $points[] = [
        'x' => (float)$row['valX'],
        'y' => (float)$row['valY'],
        'commune' => $row['nom'],
        'region' => $row['region']
    ];
}
echo json_encode($points);