<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tri Déchets | L3 MIASHS</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>
<main class="content-wrapper">
    <div class="card_CNN">
        <h2>Prédire l'image</h2>
        <div class="title-separator mx-auto mb-4"></div>
        
        <div class="upload-container">
            <input type="file" id="imageInput" accept="image/*" hidden>
            <label for="imageInput" class="upload-label">
                <i class="fa-solid fa-cloud-arrow-up fa-2x mb-2"></i>
                <span>Choisir une photo</span>
                <small id="fileNameDisplay" class="text-muted d-block mt-1">Aucun fichier sélectionné</small>
            </label>
        </div>

        <img id="preview" alt="Aperçu">
        <button id="uploadBtn">Lancer la prédiction</button>
        <div id="result"></div>
    </div>
</main>
</body>
</html>