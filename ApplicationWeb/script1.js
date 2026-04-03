const regionMapping = {
    "Piemont": "piemonte",
    "Lombardie": "Lombardia",
    "Emilie_Romagne": "Emilia_Romagna",
    "Toscane": "Toscana",
    "Sicile": "Sicilia",
    "Pouilles": "Puglia",
    "Latium": "Lazio",
    "Trentin_Haut_Adige": "Trentino_Alto_Adige",
    "Calabre": "Calabria",
    "Campanie": "Campania",
    "Abruzzes": "Abruzzo",
    "Marches": "Marche",
    "Basilicate": "Basilicata",
    "Ombrie": "Umbria",
    "Ligurie": "Liguria",
    "Molise": "Molise",
    "Aoste": "Valle_d'Aosta",
    "Frioul_Venetie_Julienne": "Friuli_Venezia_Giulia",
    "Venetie": "Veneto",
    "Sardaigne": "Sardegna"
};

$(document).ready(function () {
    let lastClicked = null;

    $("path").attr("fill", "#D4D4D4").attr("stroke", "#FFFFFF").attr("stroke-width", "0.5");

    
    $(".region").click(function () {
        let svgId = $(this).attr("id"); // Récupère l'ID français (ex: "Sicile")
        console.log("ID cliqué : '" + svgId + "'");
        let dbName = regionMapping[svgId]; // Traduit en italien (ex: "Sicilia")

        if (!dbName) {
            console.warn("Aucune correspondance pour l'ID HTML :", svgId);
            return;
        }

        
        if (lastClicked) $(lastClicked).attr("fill", "#D4D4D4");
        $(this).attr("fill", "#27ae60");
        lastClicked = this;

        
        $("#details-region").fadeIn();

        
        $.get("donneeDechets.php?code=" + encodeURIComponent(dbName), function (data) {
            if (data.error) {
                $("#nom_region").text("Erreur");
                $("#comparaison_texte").text(data.error);
                return;
            }

            
            $("#nom_region").text(data.region); 
            $("#taux_tri").text(data.moyenne_region + " %");
            $("#total_dechets").text(new Intl.NumberFormat().format(data.total_dechets) + " kg");
            $("#cout_moyen").text(data.cout_moyen + " € / hab");
            $("#nb_habitants").text(new Intl.NumberFormat().format(data.population) + " hab.");
            $("#richesse").text(new Intl.NumberFormat().format(data.richesse) + " € / hab");
            $("#altitude").text(data.altitude + " m");
            let zone = (data.bord_de_mer == 1) ? "Littoral (Bord de mer) 🏖️" : "Terres / Montagnes ⛰️";
$           ("#geographie").text(zone);

            let typeGeo = "";
            if (data.bord_de_mer == 1) {
                typeGeo = "Littoral 🏖️";
            } else if (data.code_geo >= 3) {
                typeGeo = "Montagne ⛰️";
            } else if (data.code_geo == 2) {
                typeGeo = "Collines 🌄";
            } else {
                typeGeo = "Plaine / Terres 🌾";
            }
            $("#geographie").text(typeGeo);

            
            let diff = (data.moyenne_region - data.moyenne_nationale).toFixed(2);
            let message = "";
            let color = "";

            if (diff > 0) {
                message = "Cette région trie " + diff + "% de plus que la moyenne nationale (" + data.moyenne_nationale + "%).";
                color = "#27ae60"; 
            } else if (diff < 0) {
                message = "Cette région est à " + Math.abs(diff) + "% sous la moyenne nationale (" + data.moyenne_nationale + "%).";
                color = "#e74c3c"; 
            } else {
                message = "Cette région est pile dans la moyenne nationale.";
                color = "#f39c12";
            }

            $("#comparaison_texte").text(message).css("color", color);
        });
    });

    
    $("path").mouseover(function () { if (this !== lastClicked) $(this).attr("fill", "#bdc3c7"); });
    $("path").mouseout(function () { if (this !== lastClicked) $(this).attr("fill", "#D4D4D4"); });
});