import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns

df = pd.read_csv('data/public_data_waste_fee.csv')

df_top = df.nlargest(20, 'msw')

df_plot = df_top.melt(id_vars='region', value_vars=['msw', 'msw_so', 'msw_un'], 
                      var_name='Type_Dechet', value_name='Tonnes')

# 3. Création du graphique
plt.figure(figsize=(15, 8))
sns.set_style("whitegrid")

# On trace les barres côte à côte avec 'hue'
plot = sns.barplot(data=df_plot, x='region', y='Tonnes', hue='Type_Dechet', palette=['skyblue', '#e74c3c', "#622a99"], errorbar=None)

# 4. Personnalisation
plt.title('Comparaison : Déchets Totaux, Déchets Triés et Déchets Non Triés', fontsize=16, pad=20)
plt.xlabel('Commune / Région', fontsize=12)
plt.ylabel('Quantité en Tonnes', fontsize=12)
plt.xticks(rotation=45)

plt.tight_layout()
plt.show()