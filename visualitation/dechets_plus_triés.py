import pandas as pd
import matplotlib.pyplot as plt

df = pd.read_csv('data/public_data_waste_fee.csv')

# On liste les colonnes des matières triées
materials = ['organic', 'paper', 'glass', 'wood', 'metal', 'plastic', 'raee']

# On calcule la moyenne pour chaque matière (en ignorant les cases vides)
mean_tri = df[materials].mean().sort_values(ascending=False)

# Création du graphique
plt.figure(figsize=(10, 6))
mean_tri.plot(kind='bar', color='orange')

plt.title('Quelles matières les Italiens trient-ils le plus ?', fontsize=14)
plt.ylabel('Moyenne triée (kg / habitant)')
plt.xlabel('Type de déchet')
plt.xticks(rotation=45)
plt.grid(axis='y', linestyle='--', alpha=0.7)
plt.show()