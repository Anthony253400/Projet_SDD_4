async function uploadImage() {
    const fileInput = document.querySelector("#imageInput");

    if (!fileInput.files[0]) {
        document.getElementById("status").textContent = "Veuillez sélectionner une image.";
        return;
    }

    const formData = new FormData();
    formData.append("file", fileInput.files[0]);

    document.getElementById("status").textContent = "Envoi en cours...";

    try {
        const response = await fetch("http://127.0.0.1:8000/predict", {
            method: "POST",
            body: formData
        });

        const result = await response.json();
        console.log(result);
        //document.getElementById("status").textContent = "Résultat : " + JSON.stringify(result.prediction);
        const probabilites = result.prediction[0];

        const nomsDesClasses = ["Carton", "Verre", "Métal", "Papier", "Plastique", "Ordure Ménager"];

        let indexMax = 0;
        let probaMax = probabilites[0];
        
        for (let i = 1; i < probabilites.length; i++) {
            if (probabilites[i] > probaMax) {
                probaMax = probabilites[i];
                indexMax = i;
            }
        }

        const pourcentage = (probaMax * 100).toFixed(2);
        const classePredite = nomsDesClasses[indexMax];

        document.getElementById("status").innerHTML = 
            `Résultat : <strong>${classePredite}</strong> (Confiance : ${pourcentage}%)`;

    } catch (error) {
        document.getElementById("status").textContent = "Erreur : " + error.message;
    }
}
