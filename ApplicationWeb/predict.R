# 1. Chargement des bibliothèques
library(randomForest)
library(nnet)

# 2. Récupération des arguments envoyés par Python
args <- commandArgs(trailingOnly = TRUE)

# Sécurité : on vérifie qu'on a bien nos 7 arguments
if(length(args) < 7) stop("Erreur : Pas assez d'arguments envoyés")

pop        <- args[1]
urb        <- args[2]
wage       <- args[3]
d_fee      <- args[4]
area       <- args[5]
region     <- args[6]
model_type <- args[7]

# 3. Chargement du modèle pour analyse de structure
# Remarque : on le charge AVANT de créer le dataframe pour copier ses types
model_list <- readRDS("C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/experts_waste_rf.rds")
trained_model <- model_list[['paper']]$model

# 4. Création du DataFrame initial (tout en brut)
input_df <- data.frame(
  pop = as.numeric(pop),
  urb = as.numeric(urb), 
  wage = as.numeric(wage),
  d_fee = as.numeric(d_fee),
  area = as.numeric(area),
  region = as.character(region),
  stringsAsFactors = FALSE
)

# 5. SYNCHRONISATION FORCÉE DES TYPES (Pour éviter l'erreur "Type mismatch")
target_names <- names(trained_model$forest$xlevels) 

# Conversion de URB (Facteur ou Numérique selon le modèle)
if ("urb" %in% target_names) {
  input_df$urb <- factor(input_df$urb, levels = trained_model$forest$xlevels$urb)
} else {
  input_df$urb <- as.numeric(input_df$urb)
}

# Conversion de REGION
if ("region" %in% target_names) {
  input_df$region <- factor(input_df$region, levels = trained_model$forest$xlevels$region)
}

# Sécurité pour les autres colonnes numériques
numeric_cols <- c("pop", "wage", "d_fee", "area")
for (col in numeric_cols) {
  input_df[[col]] <- as.numeric(input_df[[col]])
}

# 6. PRÉDICTION
options(warn=-1)

if (model_type == "random_forest") {
  # Prédiction Random Forest
  res <- predict(trained_model, input_df)
  cat(paste0('{"paper":', round(as.numeric(res[1]), 2), ', "organic":0, "plastic":0, "glass":0}'))

} else {
  # Prédiction Multinomial (si besoin)
  model_m <- readRDS("C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/model_multinom.rds")
  res_m <- predict(model_m, input_df, type='probs')
  cat(paste0('{"organic":', round(as.numeric(res_m[1])*100, 2), ', "paper":', round(as.numeric(res_m[2])*100, 2), ', "plastic":0, "glass":0}'))
}