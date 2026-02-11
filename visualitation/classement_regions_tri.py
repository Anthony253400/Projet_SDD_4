import pandas as pd
import matplotlib.pyplot as plt

# 1. Chargement des données
try:
    # On essaie avec la virgule, si ça rate essaie sep=';'
    df = pd.read_csv('municipalite.csv') 
    
    # 2. Nettoyage (enlève les lignes où le taux ou la région sont vides)
    df = df.dropna(subset=['taux_dechets_tries', 'region'])

    # 3. Groupement par région et calcul de la moyenne
    # On trie du plus grand au plus petit
    classement = df.groupby('region')['taux_dechets_tries'].mean().sort_values(ascending=False)

    print("--- Classement des régions (Meilleurs trieurs en haut) ---")
    print(classement)

    # 4. Création du graphique
    plt.figure(figsize=(12, 7))
    bars = plt.bar(classement.index, classement['taux_dechets_tries'], color='#2ecc71')

    # Ajouter les étiquettes de texte
    plt.title('Taux de triage des déchets par région', fontsize=14, fontweight='bold')
    plt.xlabel('Région', fontsize=12)
    plt.ylabel('Taux de triage moyen', fontsize=12)
    plt.xticks(rotation=45, ha='right')
    plt.grid(axis='y', linestyle='--', alpha=0.6)

    # 5. Sauvegarder l'image pour la voir
    plt.tight_layout()
    plt.savefig('graphique_triage.png')
    print("\n✅ Succès ! L'image 'graphique_triage.png' a été créée dans ton dossier.")
    
    plt.show()

except FileNotFoundError:
    print("Erreur : Le fichier 'municipalite.csv' est introuvable. Vérifie qu'il est bien dans le dossier Projet_SDD_4-1.")
except KeyError:
    print("Erreur : Vérifie l'orthographe des colonnes 'region' ou 'taux_dechets_tries' dans ton CSV.")
