import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns


df = pd.read_csv('data/public_data_waste_fee.csv')
print(df)

plt.figure(figsize=(10, 6))
#On utilise une échelle log pour la population car les écarts sont énormes
sns.scatterplot(data=df, x='pop', y='sor', size='msw', hue='fee', alpha=0.5, sizes=(20, 400))
plt.xscale('log')
plt.title('Les petites communes trient-elles mieux que les grandes ?')
plt.xlabel('Population (échelle log)')
plt.ylabel('Taux de tri (sor %)')
plt.grid(True, which="both", ls="-", alpha=0.2)
plt.show()
print("r")