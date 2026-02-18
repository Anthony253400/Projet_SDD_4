import pandas as pd

df = pd.read_csv('data/public_data_waste_fee.csv')

id_vars = ['istat'] 


value_vars = ['organic', 'paper', 'glass', 'wood', 'metal', 'plastic', 'raee', 'texile', 'other']

df_consommation = df.melt(id_vars=id_vars, 
                          value_vars=value_vars, 
                          var_name='nom_materiau', 
                          value_name='taux')

mapping_materiaux = {
    'organic': 1, 'paper': 2, 'glass': 3, 'wood': 4,
    'metal': 5, 'plastic': 6, 'raee': 7, 'texile': 8, 'other': 9
}
df_consommation['id_materiau'] = df_consommation['nom_materiau'].map(mapping_materiaux)

df_final = df_consommation[['istat', 'id_materiau', 'taux']]

df_final.to_csv('consommation_pret_a_importer.csv', index=False)

print("Fichier prêt ! Tu peux maintenant l'importer dans la table 'consommation'.")