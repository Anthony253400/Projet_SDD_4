import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from sklearn.ensemble import RandomForestRegressor
from sklearn.preprocessing import LabelEncoder

# 1. Chargement
df = pd.read_csv('data/public_data_waste_fee.csv')

# 2. Nettoyage de la cible
df = df.dropna(subset=['raee'])

# Définition des colonnes
target = 'metal'
# On ne supprime QUE les autres matériaux pour éviter la corrélation directe
autres_materiaux = ['plastic', 'organic', 'glass', 'wood', 'paper', 'raee', 'texile', 'other', 'msw_so', 'msw_un', 'msw', 'sor' ]

X = df.drop(columns=[c for c in autres_materiaux + [target] if c in df.columns])
y = df[target]

# 3. TRAITEMENT SPÉCIAL POUR GARDER "LES NON LIÉS"
# On transforme les colonnes de texte (region, province, name, geo) en codes numériques
le = LabelEncoder()
for col in X.columns:
    if X[col].dtype == 'object':
        # On transforme le texte en nombres (ex: "Nord" -> 1, "Sud" -> 2)
        X[col] = le.fit_transform(X[col].astype(str))

# Remplissage des valeurs manquantes par la moyenne
X = X.fillna(X.mean(numeric_only=True))

# 4. Entraînement
rf = RandomForestRegressor(n_estimators=100, random_state=42)
rf.fit(X, y)

# 5. Extraction et Affichage
importances = rf.feature_importances_
importance_table = pd.DataFrame({
    'Variable': X.columns,
    'Importance': importances
}).sort_values(by='Importance', ascending=False)

print("\n" + "="*45)
print("  POIDS DE TOUTES LES VARIABLES (INCLUANT GEO/NOM)")
print("="*45)
for index, row in importance_table.iterrows():
    print(f"{row['Variable']:20} : {row['Importance']*100:>6.2f}%")
print("="*45)

# 6. Graphique
plt.figure(figsize=(12, 10))
# On affiche le top 20 pour que ce soit lisible
top_20 = importance_table.head(20).sort_values(by='Importance', ascending=True)
plt.barh(top_20['Variable'], top_20['Importance'], color='darkorange')
plt.title('Importance des variables (Top 20 incluant données géographiques)')
plt.xlabel('Poids dans la prédiction (%)')
plt.tight_layout()
plt.show()




importances = rf.fit(X, y).feature_importances_
features = X.columns

# 2. Créer un DataFrame pour manipuler les résultats facilement
importance_table = pd.DataFrame({
    'Variable': features,
    'Importance_Relative': importances
})

# 3. Trier par importance décroissante
importance_table = importance_table.sort_values(by='Importance_Relative', ascending=False)

# 4. Affichage propre dans la console
print("\n" + "="*40)
print("  POIDS DES VARIABLES (RANDOM FOREST)")
print("="*40)

# On affiche en format pourcentage pour plus de clarté
for index, row in importance_table.iterrows():
    print(f"{row['Variable']:15} : {row['Importance_Relative']*100:>6.2f}%")

print("="*40)