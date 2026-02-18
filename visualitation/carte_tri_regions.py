import geopandas as gpd
import pandas as pd
import matplotlib.pyplot as plt

# --- 1. CHARGEMENT DES FICHIERS ---
path_map = "../Limiti01012025_g/Reg01012025_g/Reg01012025_g_WGS84.shp"
italie_regions = gpd.read_file(path_map)
df_villes = pd.read_csv('data/public_data_waste_fee.csv') 

# --- 2. LE DICTIONNAIRE DE CORRESPONDANCE ---
# Note : L'ISTAT utilise des noms bilingues officiels (ex: "Valle d'Aosta/Vallée d'Aoste")
# J'ai ajouté les correspondances pour les régions bilingues et celles qui posaient problème.
mapping = {
    "Valle d'Aosta": "Valle_d'Aosta",
    "Trentino-Alto Adige": "Trentino_Alto_Adige",
    "Friuli-Venezia Giulia": "Friuli_Venezia_Giulia",
    "Emilia-Romagna": "Emilia_Romagna",
    "Piemonte": "piemonte"
}

# On inverse pour transformer le CSV vers le format ISTAT
inv_mapping = {v: k for k, v in mapping.items()}

# --- 3. TRAITEMENT DES DONNÉES ---
# Nettoyage des noms dans le CSV
df_villes['region_clean'] = df_villes['region'].replace(inv_mapping)

# Calcul de la moyenne du tri (colonne 'sor')
stats_regions = df_villes.groupby('region_clean')['sor'].mean().reset_index()

# --- 4. FUSION (MERGE) ---
carte_finale = italie_regions.merge(stats_regions, left_on='DEN_REG', right_on='region_clean', how='left')

# --- 5. VISUALISATION ---
fig, ax = plt.subplots(figsize=(12, 14))

# Création de la carte
# La légende est gérée ici. Plus c'est foncé (Green), plus la valeur de 'sor' est haute.
carte_finale.plot(
    column='sor', 
    ax=ax, 
    legend=True, 
    cmap='YlGn',            # Palette : Yellow (bas) -> Green (haut)
    edgecolor='black', 
    linewidth=0.4,
    missing_kwds={'color': 'lightgrey', 'label': 'Données manquantes'}, 
    legend_kwds={
        'label': "Taux moyen de tri des déchets", 
        'orientation': "vertical", 
        'shrink': 0.8,
        'pad': 0.02
    }
)

# --- 6. RÉGLAGES ESTHÉTIQUES ---
plt.title(" Pourcentage du tri des déchets par région en Italie", fontsize=18, fontweight='bold', pad=20)

# On ajoute un petit texte explicatif sur la carte
ax.text(0.5, -0.05, "Plus le vert est foncé, plus le taux de tri est élevé", 
        transform=ax.transAxes, ha="center", fontsize=10, style='italic')

ax.set_axis_off()

plt.show()