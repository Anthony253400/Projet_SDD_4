import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import r2_score, mean_squared_error

# 1. Configuration et Chargement
# ---------------------------------------------------------
df = pd.read_csv('data/public_data_waste_fee.csv')
df.rename(columns={'texile': 'textile'}, inplace=True)

features = ["pop", "pden", "urb", "gdp", "wage", "roads", "alt", "d_fee", "tc", "area"]
targets = ["organic", "paper", "glass", "wood", "metal", "plastic", "raee", "textile", "other"]

# Nettoyage : Conversion en numérique
for col in features + targets:
    if col in df.columns:
        df[col] = pd.to_numeric(df[col], errors='coerce')

# --- ÉTAPE D'IMPUTATION : REMPLACER LES NAN PAR LA MOYENNE ---
# On ne le fait que sur les features pour ne pas inventer des résultats de déchets
for col in features:
    df[col] = df[col].fillna(df[col].mean())

print(f"\n{'DÉCHET':<12} | {'LIGNES':<6} | {'R²':<5} | {'NRMSE (%)':<10} | {'VARIABLE MAITRESSE'}")
print("-" * 65)

# 2. Boucle avec Optimisation
# ---------------------------------------------------------
for cat in targets:
    # On ne dropna() que sur la cible (car on a déjà rempli les features)
    df_temp = df[features + [cat]].dropna(subset=[cat])
    
    if len(df_temp) < 25: continue

    X = df_temp[features]
    y = df_temp[cat]
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    model = RandomForestRegressor(
        n_estimators=500,
        max_depth=10,            
        min_samples_split=10,     
        min_samples_leaf=5,       
        max_features=0.5,
        random_state=42,
        n_jobs=-1
    )
    model.fit(X_train, y_train)
    
    # Évaluation
    y_pred = model.predict(X_test)
    r2 = r2_score(y_test, y_pred)
    rmse_abs = np.sqrt(mean_squared_error(y_test, y_pred))
    nrmse = (rmse_abs / y_test.mean() * 100) if y_test.mean() != 0 else 0
    
    # Feature Importance
    importances = model.feature_importances_
    best_feature = features[np.argmax(importances)]
    score_best = np.max(importances) * 100
    
    # On affiche le nombre de lignes pour vérifier qu'on en a regagné
    print(f"[{cat:<10}] | {len(df_temp):<6} | {r2:5.2f} | ±{nrmse:6.1f}% | {best_feature} ({score_best:.1f}%)")