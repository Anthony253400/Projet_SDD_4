import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import r2_score, mean_squared_error

# 1. Chargement et Configuration
df = pd.read_csv("data/public_data_waste_fee.csv")
df.rename(columns={'texile': 'textile'}, inplace=True)

features = ['pop', 'tc', 'wage', 'area', 'isle', 'sea', 'urb', 'gdp', 'alt', 'roads', 'pden']
waste_types = ["organic", "paper", "glass", "wood", "metal", "plastic", "raee", "textile", "other"]

# Pour comparer les impacts, on doit normaliser les données
scaler = StandardScaler()

print(f"{'DÉCHET':<12} | {'Lignes':<6} | {'R²':<5} | {'RMSE (%)':<9} | {'TOP IMPACTS (%)'}")
print("-" * 110)

for waste in waste_types:
    if waste not in df.columns:
        continue
    
    # PROCESSUS DE NETTOYAGE : Suppression stricte par ligne (ton processus)
    colonne_travail = features + [waste]
    temp_df = df[colonne_travail].dropna()

    if len(temp_df) < 25:
        continue

    X = temp_df[features]
    y = temp_df[waste]

    # Mise à l'échelle pour que les coefficients deviennent des % d'impact comparables
    X_scaled = scaler.fit_transform(X)

    X_train, X_test, y_train, y_test = train_test_split(X_scaled, y, test_size=0.2, random_state=42)

    # Modèle Linéaire
    model = LinearRegression()
    model.fit(X_train, y_train)

    # Métriques
    y_pred = model.predict(X_test)
    r2 = r2_score(y_test, y_pred)
    rmse_abs = np.sqrt(mean_squared_error(y_test, y_pred))
    
    mean_val = y_test.mean()
    rmse_pct = (rmse_abs / mean_val) * 100 if mean_val != 0 else 0

    # CALCUL DES POURCENTAGES D'IMPACT
    # On prend la valeur absolue des coefficients pour mesurer la force de l'influence
    abs_coefs = np.abs(model.coef_)
    total_coefs = np.sum(abs_coefs)
    
    # On crée une liste de tuples (Nom, Pourcentage) triée
    impacts = []
    for i in range(len(features)):
        pct = (abs_coefs[i] / total_coefs) * 100
        impacts.append((features[i], pct))
    
    impacts = sorted(impacts, key=lambda x: x[1], reverse=True)
    
    # Formatage du Top 2 pour l'affichage
    top_str = f"{impacts[0][0]}: {impacts[0][1]:.1f}% | {impacts[1][0]}: {impacts[1][1]:.1f}%"

    print(f"{waste:<12} | {len(temp_df):<6} | {r2:5.2f} | ±{rmse_pct:6.2f}% | {top_str}")