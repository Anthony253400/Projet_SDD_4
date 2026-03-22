import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.ensemble import HistGradientBoostingRegressor
from sklearn.metrics import r2_score, mean_squared_error
import matplotlib.pyplot as plt
import seaborn as sns

df = pd.read_csv("data/public_data_waste_fee.csv")

features = ['pop', 'msw', 'sor', 'tc', 'wage', 'area', 'isle', 'sea', 'urb', 'gdp', 'finance']
waste_types = ["organic", "paper", "glass", "plastic", "metal", "wood", "texile"]

# Boucle par déchet
for waste in waste_types:

    # 1. Masque pour isoler les données valides pour CE déchet
    mask = df[waste].notna()
    X_current = df.loc[mask, features]
    y_current = df.loc[mask, waste]

    # 2. Vérification du volume de données
    if len(y_current) < 10:
        print(f"Pas assez de données pour {waste}, passage au suivant.")
        continue

    # 3. Séparation 80/20
    X_train, X_test, y_train, y_test = train_test_split(
        X_current, y_current, test_size=0.2, random_state=42
    )

    # 4. Entraînement
    model = HistGradientBoostingRegressor(random_state=42)
    model.fit(X_train, y_train)

    # 5. Évaluation
    y_pred = model.predict(X_test)
    r2 = r2_score(y_test, y_pred)
    rmse = np.sqrt(mean_squared_error(y_test, y_pred))

    print(f"--- RÉSULTATS : {waste.upper()} ---")
    print(f"Lignes utilisées : {len(y_current)}")
    print(f"R² Score : {r2:.4f}")
    print(f"RMSE : {rmse:.4f}")

    # 6. AFFICHAGE DES PRÉDICTIONS (Tableau comparatif)
    # On crée un petit tableau pour comparer les 10 premières valeurs de test
    comparison_df = pd.DataFrame({
        'Valeur Réelle': y_test.values,
        'Prédiction Modèle': y_pred,
        'Erreur': y_test.values - y_pred
    })

    print("\nAperçu des 10 premières prédictions :")
    print(comparison_df.head(10).to_string(index=False))
    print("\n" + "="*40 + "\n")

    # --- VISUALISATION ---
    plt.figure(figsize=(10, 6))
    sns.scatterplot(x=y_test, y=y_pred, alpha=0.7, color='teal')

    lims = [y_test.min(), y_test.max()]
    plt.plot(lims, lims, color='red', linestyle='--', lw=2, label='Prédiction parfaite')

    plt.title(f"Performance du modèle : {waste.upper()}\n(R² = {r2:.3f} | RMSE = {rmse:.2f})")
    plt.xlabel("Valeurs réelles mesurées")
    plt.ylabel("Valeurs prédites par le modèle")
    plt.legend()
    plt.grid(True, linestyle='--', alpha=0.6)

    plt.tight_layout()
    plt.show()