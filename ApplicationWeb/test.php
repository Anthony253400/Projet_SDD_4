
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tri Déchets | L3 MIASHS</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <style>
        .card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        #preview { width: 100%; max-height: 300px; object-fit: contain; margin-top: 15px; display: none; border-radius: 8px; }
        button { background: #6366f1; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; margin-top: 15px; width: 100%; font-weight: bold; }
        button:disabled { background: #a5a6f6; }
        #result { margin-top: 20px; padding: 10px; border-radius: 6px; background: #e0e7ff; display: none; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="card">
    <h2>Prédire l'image</h2>
    <input type="file" id="imageInput" accept="image/*">
    <img id="preview" alt="Aperçu">
    <button id="uploadBtn">Lancer la prédiction</button>
    <div id="result"></div>
</div>
</body>
</html>