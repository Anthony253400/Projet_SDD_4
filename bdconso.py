import pandas as pd

# 1. Charger ton dataset original (modifie le nom du fichier)
df = pd.read_csv('data/public_data_waste_fee.csv')

# 2. Définir les colonnes qui ne bougent pas (les identifiants)
# On garde icipalite pour faire le lien avec l'autre table
id_vars = ['istat'] 

# 3. Définir les colonnes "matériaux" que l'on veut transformer en lignes
value_vars = ['organic', 'paper', 'glass', 'wood', 'metal', 'plastic', 'raee', 'texile', 'other']

# 4. Utiliser la fonction .melt()
# var_name : le nom de la nouvelle colonne qui contiendra le nom du matériau
# value_name : le nom de la nouvelle colonne qui contiendra le pourcentage (taux)
df_consommation = df.melt(id_vars=id_vars, 
                          value_vars=value_vars, 
                          var_name='nom_materiau', 
                          value_name='taux')

# 5. Remplacer les noms de matériaux par leurs IDs (ceux de ta table 'materiau')
# Il faut que l'ordre corresponde exactement à ce que tu as mis en SQL
mapping_materiaux = {
    'organic': 1, 'paper': 2, 'glass': 3, 'wood': 4,
    'metal': 5, 'plastic': 6, 'raee': 7, 'texile': 8, 'other': 9
}
df_consommation['id_materiau'] = df_consommation['nom_materiau'].map(mapping_materiaux)

# 6. Supprimer la colonne texte 'nom_materiau' pour ne garder que l'ID
df_final = df_consommation[['istat', 'id_materiau', 'taux']]

# 7. Sauvegarder pour l'importer dans phpMyAdmin
df_final.to_csv('consommation_pret_a_importer.csv', index=False)

print("Fichier prêt ! Tu peux maintenant l'importer dans la table 'consommation'.")