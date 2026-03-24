import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import r2_score, mean_squared_error

# 1. Configuration et Chargement
# ---------------------------------------------------------
df = pd.read_csv('../public_data_waste_fee.csv')

# Correction du nom de la colonne (erreur de frappe historique)
df.rename(columns={'texile': 'textile'}, inplace=True)

# Liste complète des variables explicatives (Features)
features = ["pop", "pden", "urb", "gdp", "wage", "roads", "alt", "sea", "d_fee", "sor", "msw", "tc", "area"]
# Liste des catégories de déchets (Targets)
targets = ["organic", "paper", "glass", "wood", "metal", "plastic", "raee", "textile", "other"]

# Nettoyage : Conversion en numérique
for col in features + targets:
    if col in df.columns:
        df[col] = pd.to_numeric(df[col], errors='coerce')

modeles_experts = {}

print("--- ENTRAÎNEMENT DES EXPERTS OPTIMISÉS (RF BOOSTÉ) ---")

# 2. Boucle d'entraînement
# ---------------------------------------------------------
for cat in targets:
    if cat not in df.columns:
        continue
        
    # NETTOYAGE RIGOUREUX : On supprime si une feature OU la cible manque
    df_temp = df.dropna(subset=features + [cat])
    
    if len(df_temp) < 25: 
        print(f"⚠️ {cat:10} : Données insuffisantes ({len(df_temp)} lignes).")
        continue

    X = df_temp[features]
    y = df_temp[cat]

    # Split 80/20 avec la graine 42 pour la reproductibilité
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    # CONFIGURATION PERFORMANCE
    model = RandomForestRegressor(
        n_estimators=500,        # Plus d'arbres pour lisser l'erreur
        max_depth=None,          # On laisse l'arbre apprendre les détails complexes
        min_samples_leaf=2,      # Équilibre entre précision et généralisation
        max_features=0.5,        # On teste la moitié des variables à chaque nœud
        random_state=42,
        n_jobs=-1                # Utilise toute la puissance de ton processeur
    )
    model.fit(X_train, y_train)
    
    # Évaluation
    y_pred = model.predict(X_test)
    r2 = r2_score(y_test, y_pred)
    rmse = np.sqrt(mean_squared_error(y_test, y_pred))
    
    modeles_experts[cat] = {'model': model, 'r2': r2, 'rmse': rmse, 'y_test': y_test, 'y_pred': y_pred}
    
    print(f"Expert [{cat:10}] | Lignes: {len(df_temp):4} | R²: {r2:5.2f} | RMSE: ±{rmse:.2f}%")