document.addEventListener("DOMContentLoaded", () => {
    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('preview');
    const uploadBtn = document.getElementById('uploadBtn');
    const resultDiv = document.getElementById('result');

    let currentFile = null;

    // Configuration
    const SEUIL_CONFIANCE = 0.70; 
    const API_URL = "http://127.0.0.1:8000";

    const infosPoubelles = {
        "Carton": { poubelle: "jaune ou bac à carton", action: "le recycler", couleur: "#ffda1a", texte: "#000" },
        "Verre": { poubelle: "bac à verre (verte)", action: "le recycler", couleur: "#28a745", texte: "#fff" },
        "Métal": { poubelle: "jaune", action: "le recycler", couleur: "#ffda1a", texte: "#000" },
        "Papier": { poubelle: "bac bleu", action: "le recycler", couleur: "#007bff", texte: "#fff" },
        "Plastique": { poubelle: "jaune", action: "le recycler", couleur: "#ffda1a", texte: "#000" },
        "Ordure Ménagère": { poubelle: "grise/noire", action: "l'éliminer", couleur: "#495057", texte: "#fff" }
    };

    // Gestion de la sélection de l'image
    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            currentFile = file;
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            resultDiv.style.display = 'none'; 
        }
    });

    // Envoi pour prédiction
    uploadBtn.addEventListener('click', async () => {
        if (!currentFile) {
            alert("Veuillez sélectionner une image.");
            return;
        }

        // UI : État de chargement
        resultDiv.style.display = "block";
        resultDiv.style.background = "transparent"; 
        resultDiv.style.color = "#000";
        resultDiv.innerHTML = "<em>Analyse de l'image en cours...</em>";
        uploadBtn.disabled = true;

        const formData = new FormData();
        formData.append("file", currentFile);

        try {
            const response = await fetch(`${API_URL}/predict`, {
                method: "POST",
                body: formData
            });

            if (!response.ok) throw new Error("Le serveur ne répond pas");

            const data = await response.json();
            const probabilites = data.prediction[0];
            const nomsDesClasses = Object.keys(infosPoubelles);

            // Calcul du meilleur résultat
            let indexMax = probabilites.indexOf(Math.max(...probabilites));
            let scoreMax = probabilites[indexMax];
            let classePredite = nomsDesClasses[indexMax];

            let mainResultHTML = "";
            let bgColor = "#dc3545"; 
            let textColor = "#fff";
            let message = "";

            if (scoreMax < SEUIL_CONFIANCE) {
                message = `<strong>Désolé, je ne suis pas sûr de moi.</strong><br>
                           Veuillez reprendre une photo plus nette ou mieux éclairée.`;
            } else {
                const info = infosPoubelles[classePredite];
                bgColor = info.couleur;
                textColor = info.texte;
                message = `
                    Cet objet semble être du <strong>${classePredite.toLowerCase()}</strong>.<br>
                    Jetez-le dans la <strong>poubelle ${info.poubelle}</strong>.
                    <br><small style="opacity:0.8">Confiance : ${(scoreMax * 100).toFixed(1)}%</small>
                `;
            }

            mainResultHTML = `
                <div style="background: ${bgColor}; color: ${textColor}; padding: 20px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    ${message}
                </div>
            `;

            // Préparation du bloc de feedback (contestation)
            const classesFiltrees = nomsDesClasses.filter(c => c !== classePredite);
            
            const feedbackHTML = `
                <div id="feedbackBox" style="padding: 15px; background: #f8f9fa; color: #333; border-radius: 12px; font-size: 0.9em; border: 1px solid #ddd;">
                    <p style="margin: 0 0 10px 0;"><strong>Une erreur ?</strong> Aidez l'IA à s'améliorer :</p>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <select id="correctClass" style="padding: 8px; border-radius: 6px; border: 1px solid #ccc; flex-grow: 1;">
                            ${classesFiltrees.map(c => `<option value="${c}">${c}</option>`).join('')}
                        </select>
                        <button onclick="envoyerCorrection()" style="background: #333; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                            Envoyer
                        </button>
                    </div>
                </div>
            `;

            resultDiv.innerHTML = mainResultHTML + feedbackHTML;

        } catch (error) {
            resultDiv.innerHTML = `<div style="background: #dc3545; color: #fff; padding: 15px; border-radius: 12px;"><strong>Erreur :</strong> ${error.message}</div>`;
        } finally {
            uploadBtn.disabled = false;
        }
    });

    /**
     * Fonction globale pour envoyer la correction (Feedback)
     */
    window.envoyerCorrection = async function() {
        const correctClass = document.getElementById("correctClass").value;
        const feedbackBox = document.getElementById("feedbackBox");

        if (!currentFile) return;

        // Préparation des données pour le backend (Doit correspondre aux arguments du main.py)
        const formData = new FormData();
        formData.append("label", correctClass);
        formData.append("file", currentFile);

        feedbackBox.innerHTML = "<em>Enregistrement de votre retour...</em>";

        try {
            const response = await fetch(`${API_URL}/feedback`, {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.status === "success") {
                feedbackBox.style.background = "#d4edda";
                feedbackBox.style.borderColor = "#c3e6cb";
                feedbackBox.style.color = "#155724";
                feedbackBox.innerHTML = "<strong>Merci !</strong> Votre correction a été enregistrée en base de données.";
            } else {
                throw new Error(result.message || "Erreur lors de l'enregistrement");
            }
        } catch (error) {
            console.error("Erreur feedback:", error);
            feedbackBox.style.background = "#f8d7da";
            feedbackBox.style.color = "#721c24";
            feedbackBox.innerHTML = "<strong>Échec :</strong> Impossible d'envoyer le feedback.";
        }
    };
});