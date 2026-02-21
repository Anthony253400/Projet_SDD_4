import pandas as pd

df = pd.read_csv('data/public_data_waste_fee.csv')

import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression

# 1. Sélection des variables pertinentes (exemples)
features = ['pden', 'pop', 'wage', 'gdp', ]
target = 'plastic'

# Nettoyage rapide pour l'exemple
df_model = df[features + [target]].dropna()

X = df_model[features]
y = df_model[target]

# 2. Séparation Train/Test
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 3. Création et entraînement du modèle
model = LinearRegression()
model.fit(X_train, y_train)

# 4. Score (R²)
print(f"Précision du modèle (R²) : {model.score(X_test, y_test):.2f}")


