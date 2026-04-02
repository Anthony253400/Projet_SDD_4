<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Upload d'Image vers FastAPI</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 50px; background: #f4f4f9; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        input { margin-bottom: 1rem; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        #status { margin-top: 1rem; font-weight: bold; }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
</head>
<body>

<div class="card">
    <h2>Envoyer une image</h2>
    <input type="file" id="imageInput" accept="image/*">
    <br>
    <button onclick="uploadImage()">Envoyer au serveur</button>
    <p id="status"></p>
</div>



</body>
</html>