import pandas as pd
import matplotlib.pyplot as plt
import os

# 1. Chargement des données
try:
    df = pd.read_csv('data/public_data_waste_fee.csv') 
    
    # Nettoyage des données (on s'assure que l'alt et le taux sont exploitables)
    df['alt'] = pd.to_numeric(df['alt'], errors='coerce')
    df['sor'] = pd.to_numeric(df['sor'], errors='coerce')
    df = df.dropna(subset=['alt', 'sor'])

    # 2. Création des classes d'alt (Discrétisation)
    # On définit les coupures : 0-250, 250-750, et 750 jusqu'au maximum
    bins = [0, 250, 750, df['alt'].max()]
    labels = ['0-250m', '250-750m', '+750m']
    df['classe_alt'] = pd.cut(df['alt'], bins=bins, labels=labels)

    # 3. Calcul de la moyenne de tri par classe
    analyse = df.groupby('classe_alt')['sor'].mean()

    print("--- Taux de tri moyen par tranche d'alt ---")
    print(analyse)

    # 4. Création du graphique
    plt.figure(figsize=(10, 6))
    analyse.plot(kind='bar', color=['#74b9ff', '#55efc4', '#fab1a0'], edgecolor='black')

    plt.title('Influence de l\'altitude sur le taux de tri', fontsize=14, fontweight='bold')
    plt.xlabel('Tranches d\'altitude', fontsize=12)
    plt.ylabel('Taux de tri moyen', fontsize=12)
    plt.xticks(rotation=0) # Labels horizontaux pour plus de lisibilité
    plt.grid(axis='y', linestyle='--', alpha=0.6)

    # 5. Sauvegarde et affichage
    plt.tight_layout()
    plt.savefig('analyse_alt.png')
    
    print("\n✅ Analyse terminée ! Image 'analyse_alt.png' créée.")
    
    # Force l'ouverture sur ton Mac pour voir le résultat
    os.system('open analyse_alt.png')
    plt.show()

except FileNotFoundError:
    print("Erreur : Le fichier 'municipalite.csv' est introuvable dans ce dossier.")
except Exception as e:
    print(f"Une erreur est survenue : {e}")