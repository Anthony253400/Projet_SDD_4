import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns

df = pd.read_csv('data/public_data_waste_fee.csv')

df['type_taxe'] = df['d_fee'].map({0: 'Fixe (Standard)', 1: 'Incitative (PAYT)'})

plt.figure(figsize=(10, 7))
sns.set_theme(style="ticks")

sns.boxplot(
    data=df, 
    x='type_taxe', 
    y='tc', 
    palette='Set2',
    width=0.5,
    showmeans=True,
    meanprops={"marker":"o", "markerfacecolor":"white", "markeredgecolor":"black", "markersize":"8"}
)

plt.title('Comparaison des coûts : Taxe Fixe vs Incitative', fontsize=16)
plt.xlabel('Système de Tarification', fontsize=12)
plt.ylabel('Coût total par habitant (€)', fontsize=12)

plt.tight_layout()
plt.show()
