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
</head>
<body>

<div class="card">
    <h2>Envoyer une image</h2>
    <input type="file" id="imageInput" accept="image/*">
    <br>
    <button onclick="uploadImage()">Envoyer au serveur</button>
    <p id="status"></p>
</div>

<script>
    async function uploadImage() {
        const input = document.getElementById('imageInput');
        const status = document.getElementById('status');
        
        if (input.files.length === 0) {
            status.innerText = "Veuillez choisir un fichier.";
            return;
        }

        const formData = new FormData();
        formData.append('file', input.files[0]);

        status.innerText = "Envoi en cours...";

        try {
            const response = await fetch('http://127.0.0.1:8000/upload', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            status.style.color = "green";
            status.innerText = "Succès : " + result.message + " (" + result.filename + ")";
            console.log(result);
        } catch (error) {
            status.style.color = "red";
            status.innerText = "Erreur lors de l'envoi.";
            console.error(error);
        }
    }
</script>

</body>
</html>