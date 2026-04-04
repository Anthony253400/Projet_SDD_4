<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tri Déchets | L3 MIASHS</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .hero { background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; padding: 40px 0; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="hero text-center">
    <h1>♻️ Projet Tri des Déchets</h1>
    <p>Modèles IA & Analyse des Déchets</p>
    <a href="index.html">Accueil</a>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-5">
            <div class="card p-4">
                <h3>⚙️ Paramètres de la ville</h3>
                <form id="formPrediction">
                    <div class="mb-3">
                        <label class="form-label">Nombre d'habitants</label>
                        <input type="number" min="1" step="1" class="form-control" id="pop" placeholder="ex: 1000" required>
                        <div class="form-text">Entrez un nombre entier positif.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Indice d'urbanisation</label>
                        <select class="form-select" id="urb" required>
                            <option value="" disabled selected>Niveau d'urbanisation...</option>
                            <option value="1">1 ⭢ Faiblement urbain</option>
                            <option value="2">2 ⭢ Moyennement urbain</option>
                            <option value="3">3 ⭢ Fortement urbain</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Revenu moyen imposable par habitant</label>
                        <input type="number" min="0" step="0.01" class="form-control" id="wage" placeholder="ex: 25000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Redevance incitative</label>
                        <select class="form-select" id="d_fee" required>
                            <option value="" disabled selected>La commune applique-t-elle la redevance ?</option>
                            <option value="0">Non (0)</option>
                            <option value="1">Oui (1)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Superficie (km²)</label>
                        <input type="number" min="0.1" step="0.01" class="form-control" id="area" placeholder="ex: 50.5" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Région</label>
                        <select class="form-select" id="region" required style="border: 1px solid #ced4da;">
                            <option value="" disabled selected>Choisissez une région...</option>
                            <option value="Toscane">Toscane</option>
                            <option value="Piemont">Piedmont</option>
                            <option value="Vallee d'Aoste">Vallée d'Aoste</option>
                            <option value="Lombardie">Lombardie</option>
                            <option value="Trentin-Haut-Adige">Trentin-Haut-Adige</option>
                            <option value="Venetie">Vénétie</option>
                            <option value="Frioul-Venetie Julienne">Frioul-Vénétie Julienne</option>
                            <option value="Ligurie">Ligurie</option>
                            <option value="Emilie-Romagne">Émilie-Romagne</option>
                            <option value="Ombrie">Ombrie</option>
                            <option value="Marches">Marche</option>
                            <option value="Latium">Latium</option>
                            <option value="Abruzzes">Abruzzes</option>
                            <option value="Molise">Molise</option>
                            <option value="Campanie">Campanie</option>
                            <option value="Pouilles">Pouilles</option>
                            <option value="Basilicate">Basilicate</option>
                            <option value="Calabre">Calabre</option>
                            <option value="Sicile">Sicile</option>
                            <option value="Sardaigne">Sardaigne</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Choix du modèle d'IA</label>
                        <select class="form-select" id="modelSelect" style="border: 2px solid #2ecc71;">
                            <option value="random_forest" selected>🌳 Random Forest (Plus précis)</option>
                            <option value="multinomial">📊 Régression Logistique Multinomiale</option>
                            <option value="linear">📈 Régression Linéaire Multiple</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Lancer la prédiction</button>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card p-4 text-center">
                <h3>📊 Mix de déchets prédit</h3>
                
                <div id="resultatsContainer" style="display: none;">
                    <div class="row mt-3">
                        <div class="col-6"><strong>Organique :</strong> <span id="res_org">--</span>%</div>
                        <div class="col-6"><strong>Papier :</strong> <span id="res_pap">--</span>%</div>
                        <div class="col-6"><strong>Plastique :</strong> <span id="res_pla">--</span>%</div>
                        <div class="col-6"><strong>Verre :</strong> <span id="res_ver">--</span>%</div>
                    </div>
                </div>
                
                <div id="loader" style="display:none;" class="spinner-border text-success mx-auto mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#formPrediction").on("submit", function(e) {
        e.preventDefault(); // Empêche le rechargement de la page

        // Afficher le loader et cacher les anciens résultats
        $("#loader").show();
        $("#resultatsContainer").hide();

        // 2. Préparation des données au format JSON pour FastAPI
        const donneesVille = {
            pop: parseFloat($("#pop").val()),
            urb: parseInt($("#urb").val()),
            wage: parseFloat($("#wage").val()),
            d_fee: parseInt($("#d_fee").val()),
            area: parseFloat($("#area").val()),
            region: $("#region").val(),
            model_type: $("#modelSelect").val()
        };

        // 3. Appel à ton serveur FastAPI (Python)
        $.ajax({
            url: "http://127.0.0.1:8001/predict",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(donneesVille), // On transforme l'objet en texte JSON
            success: function(reponse) {
                console.log("Réponse reçue du serveur :", reponse);
                $("#loader").hide();
                $("#resultatsContainer").fadeIn();

                // 4. Mise à jour des résultats dans la page
                $("#res_org").text(reponse.organic || "0");
                $("#res_pap").text(reponse.paper || "0");
                $("#res_pla").text(reponse.plastic || "0");
                $("#res_ver").text(reponse.glass || "0");
                
                console.log("Affichage mis à jour !");
            },
            error: function(xhr) {
                $("#loader").hide();
                alert("Erreur : Le serveur IA n'est pas lancé ou les données sont incorrectes.");
                console.log(xhr.responseText);
            }
        });
        
    });
});
</script>
</body>
</html>