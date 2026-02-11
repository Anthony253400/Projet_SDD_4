import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
import os

file_path = "data/public_data_waste_fee.csv"



df = pd.read_csv(file_path)

categories = ['organic', 'paper', 'glass', 'wood', 'metal', 'plastic', 'raee', 'texile', 'other']

for col in categories:
        df[col] = pd.to_numeric(df[col], errors='coerce')

df_long = df.melt(
        value_vars=categories, 
        var_name='Déchet', 
        value_name='Taux_pourcentage'
    )

plt.figure(figsize=(12, 7))
sns.set_theme(style="whitegrid")

sns.boxplot(
        x='Déchet', 
        y='Taux_pourcentage', 
        data=df_long, 
        palette="Set2",
        hue='Déchet',
        legend=False,
        width=0.6 # Ajuste la largeur des boîtes
    )

plt.title('Distribution des taux de collecte par catégorie (Boxplot)', fontsize=14)
plt.xlabel('Type de déchet', fontsize=11)
plt.ylabel('Taux (%)', fontsize=11)
plt.ylim(0, df_long['Taux_pourcentage'].max() * 1.1) # Ajuste l'échelle Y
    
plt.tight_layout()
plt.show()