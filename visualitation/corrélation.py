import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt

df = pd.read_csv('data/public_data_waste_fee.csv')

data_corr = df[['pop', 'msw', 'sor', 'tc', 'wage', 'cres', 'csor', 'area', 'isle', 'sea', 'wden', 'urb', 'msw_so','msw_un', 'gdp', 'finance']]

matrix = data_corr.corr()

plt.figure(figsize=(10, 8))
sns.heatmap(matrix, annot=True, cmap='coolwarm', fmt=".2f", linewidths=0.5)

plt.title('Matrice de Corrélation')
plt.show()
