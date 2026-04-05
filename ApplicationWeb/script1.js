const regionMapping = {
    "Piemont": "Piemont",
    "Lombardie": "Lombardie",
    "Trentin_Haut_Adige": "Trentin-Haut-Adige",
    "Venetie": "Venetie",
    "Frioul_Venetie_Julienne": "Frioul-Venezia Julienne",
    "Ligurie": "Ligurie",
    "Emilie_Romagne": "Emilie-Romagne",
    "Toscane": "Toscane",
    "Ombrie": "Ombrie",
    "Marches": "Marches",
    "Latium": "Latium",
    "Abruzzes": "Abruzzes",
    "Molise": "Molise",
    "Campanie": "Campania",
    "Pouilles": "Pouilles",
    "Basilicate": "Basilicate",
    "Calabre": "Calabre",
    "Sicile": "Sicile",
    "Sardaigne": "Sardaigne",
    "Aoste": "Vallee d’Aoste"
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
            $("#geographie").text(zone);

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

            $("#decharge").text(data.decharge + " %");

            let texteRedevance = (data.redevance == 1) ? "Activée (Paiement au poids) ⚖️" : "Forfaitaire (Fixe) 💸";
            $("#redevance").text(texteRedevance);

            
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

    

    $("#categorySelect").change(function() {
        let choix = $(this).val();

        if (choix === "none") {
            $("path").attr("fill", "#D4D4D4"); 
            $("#legend").html(""); // On vide la légende
            return;
        }

        $.get("data_carte.php", function(toutesLesRegions) {
            let valeurs = toutesLesRegions.map(r => parseFloat(r[choix]));
            let max = Math.max(...valeurs);
            let min = Math.min(...valeurs);

            toutesLesRegions.forEach(reg => {
                let nomBaseNettoyé = reg.region.toLowerCase().trim();
                let idSVG = Object.keys(regionMapping).find(key => {
                    return regionMapping[key].toLowerCase().trim() === nomBaseNettoyé;
                });

                if (idSVG) {
                    let score = parseFloat(reg[choix]);
                    let intensite = (max === min) ? 0.5 : (score - min) / (max - min);
                    let couleur = `rgba(39, 174, 96, ${0.1 + (intensite * 0.9)})`;
                    $("#" + idSVG).attr("fill", couleur).css("fill", couleur);
                }
            });

            if (choix !== "none") {
    // 1. On récupère le nom de l'option (ex: Taux de tri)
                var nomCategorie = $("#categorySelect option:selected").text();

    // 2. On met à jour les chiffres Min et Max
                $("#min-val").text(Math.round(min).toLocaleString());
                $("#max-val").text(Math.round(max).toLocaleString());

    // 3. On crée la phrase à l'ancienne avec des +
                var contenuHTML = "Plus le vert est foncé, plus le/la <strong>" + nomCategorie + "</strong> est élevé(e)." + 
                                "<br>" + 
                                "<span style='color: #157347; font-weight: bold; display: inline-block; mt-2;'>" +
                                "➔ Cliquez sur une région pour plus d'infos." +
                                "</span>";

                $("#phrase-explication").html(contenuHTML);
                $("#legend-container").show();
            } else {
                $("#legend-container").hide();
            }
        }); 
    });
});
