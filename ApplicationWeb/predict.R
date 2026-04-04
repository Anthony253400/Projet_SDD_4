# 1. Chargement des bibliothèques
library(randomForest)
library(nnet)

# 2. Récupération des arguments
args <- commandArgs(trailingOnly = TRUE)
if(length(args) < 7) stop("Erreur : Pas assez d'arguments")

pop        <- args[1]
urb        <- args[2]
wage       <- args[3]
d_fee      <- args[4]
area       <- args[5]
region     <- args[6]
model_type <- args[7]

# 3. Création du DataFrame de base (Neutre)
input_df <- data.frame(
  pop = as.numeric(pop),
  urb = as.numeric(urb), 
  wage = as.numeric(wage),
  d_fee = as.numeric(d_fee),
  area = as.numeric(area),
  region = as.character(region),
  stringsAsFactors = FALSE
)

# 4. PRÉDICTION
options(warn=-1)

# --- CAS 1 : RANDOM FOREST ---
if (model_type == "random_forest") {
    model_list <- readRDS("C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/experts_waste_rf.rds")
    
    # Synchronisation des types spécifique au RF
    # On utilise le premier expert (paper) pour récupérer la structure des facteurs
    ref_model <- model_list[['paper']]$model
    input_df$urb <- factor(input_df$urb, levels = ref_model$forest$xlevels$urb)
    input_df$region <- factor(input_df$region, levels = ref_model$forest$xlevels$region)

    p_pap <- round(as.numeric(predict(model_list[['paper']]$model,   input_df)), 2)
    p_org <- round(as.numeric(predict(model_list[['organic']]$model, input_df)), 2)
    p_pla <- round(as.numeric(predict(model_list[['plastic']]$model, input_df)), 2)
    p_gla <- round(as.numeric(predict(model_list[['glass']]$model,   input_df)), 2)
    
    cat(paste0('{"paper":', p_pap, ', "organic":', p_org, ', "plastic":', p_pla, ', "glass":', p_gla, '}'))

# --- CAS 2 : MULTINOMIAL ---
} else if (model_type == "multinomial") {
    model_m <- readRDS("C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/model_multinom.rds")
    
    input_df$urb <- as.numeric(urb) 
    input_df$region <- as.factor(region)
    
    res_m <- predict(model_m, input_df, type='probs')
    
    p_org <- round(as.numeric(res_m["organic"]) * 100, 2)
    p_pap <- round(as.numeric(res_m["paper"]) * 100, 2)
    p_pla <- round(as.numeric(res_m["plastic"]) * 100, 2)
    p_gla <- round(as.numeric(res_m["glass"]) * 100, 2)

    cat(paste0('{"organic":', p_org, ', "paper":', p_pap, ', "plastic":', p_pla, ', "glass":', p_gla, '}'))

# --- CAS 3 : RÉGRESSION LINÉAIRE (NOUVEAU) ---
} else if (model_type == "linear") {
    # Chargement de la liste des experts linéaires
    linear_list <- readRDS("C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/model_linear.rds")
    
    # Types requis
    input_df$urb <- as.numeric(urb)
    input_df$region <- as.factor(region)
    
    # On récupère les prédictions pour chaque déchet
    # On utilise max(0, ...) car la régression linéaire peut donner des chiffres négatifs
    p_pap <- round(max(0, as.numeric(predict(linear_list[['paper']]$model,   input_df))), 2)
    p_org <- round(max(0, as.numeric(predict(linear_list[['organic']]$model, input_df))), 2)
    p_pla <- round(max(0, as.numeric(predict(linear_list[['plastic']]$model, input_df))), 2)
    p_gla <- round(max(0, as.numeric(predict(linear_list[['glass']]$model,   input_df))), 2)

    cat(paste0('{"paper":', p_pap, ', "organic":', p_org, ', "plastic":', p_pla, ', "glass":', p_gla, '}'))
}