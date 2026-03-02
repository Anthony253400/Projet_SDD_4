import pandas as pd
import statsmodels.api as sm

# 1. Sélection des variables explicatives (Features)
# On choisit des facteurs socio-économiques et structurels
X = df[['wage', 'pden', 'pop', 'alt', 'payt']] 
y = df['sor']  # La cible : le taux de tri (entre 0 et 1)

# 2. Ajout de la constante (indispensable pour la régression)
X = sm.add_constant(X)

# 3. Entraînement du modèle OLS (Ordinary Least Squares)
model = sm.OLS(y, X).fit()

# 4. Affichage du bilan
print(model.summary())