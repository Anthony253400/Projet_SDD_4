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

for col in features + targets:
    if col in df.columns:
        df[col] = pd.to_numeric(df[col], errors='coerce')

print(f"\n{'DÉCHET':<12} | {'R²':<5} | {'NRMSE (%)':<10} | {'VARIABLE MAITRESSE'}")
print("-" * 55)

# 2. Boucle avec Optimisation (Point 2 : Fine-tuning)
# ---------------------------------------------------------
for cat in targets:
    df_temp = df[features + [cat]].dropna()
    if len(df_temp) < 25: continue

    X = df_temp[features]
    y = df_temp[cat]
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    # --- LES NOUVEAUX RÉGLAGES D'OPTIMISATION ---
    model = RandomForestRegressor(
        n_estimators=500,
        max_depth=10,            # Limite la complexité (évite le "par cœur")
        min_samples_split=10,     # Une branche doit diviser au moins 10 communes
        min_samples_leaf=5,       # Une décision finale doit concerner 5 communes
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
    
    print(f"[{cat:<10}] | {r2:5.2f} | ±{nrmse:6.1f}% | {best_feature} ({score_best:.1f}%)")