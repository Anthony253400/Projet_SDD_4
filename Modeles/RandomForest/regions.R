if(!require(randomForest)) install.packages("randomForest")
library(randomForest)

# 1. Chargement et Nettoyage
df <- read.csv("data/public_data_waste_fee.csv", stringsAsFactors = FALSE)
colnames(df)[colnames(df) == "texile"] <- "textile"

# Liste des variables
features <- c("pop", "pden", "urb", "gdp", "wage", "roads", 
                "alt", "d_fee", "tc", "area", "region")

targets <- c("organic", "paper", "glass", "wood", "metal", 
             "plastic", "raee", "textile", "other")

# Préparation des facteurs
df$region <- as.factor(df$region)
df$urb    <- as.factor(df$urb)

# Conversion numérique pour les cibles (au cas où)
for (col in targets) {
  df[[col]] <- as.numeric(df[[col]])
}

experts_results <- list()

cat("\n--- Entraînement Random Forest (AVEC MAE & RÉGIONS) ---\n")
# Mise à jour de l'en-tête pour inclure MAE
cat(sprintf("%-10s | %-6s | %-5s | %-10s | %-10s\n", "DÉCHET", "LIGNES", "R²", "MAE", "NRMSE (%)"))
cat(paste0(rep("-", 60), collapse = ""), "\n")

# 4. Boucle d'entraînement
for (cat_name in targets) {
  
  if (!(cat_name %in% colnames(df))) next
  
  # Suppression des lignes incomplètes
  df_temp <- df[complete.cases(df[, c(features, cat_name)]), ]
  
  if(nrow(df_temp) < 25) next
  
  set.seed(42) 
  sample_idx <- sample(seq_len(nrow(df_temp)), size = floor(0.8 * nrow(df_temp)))
  train_data <- df_temp[sample_idx, ]
  test_data  <- df_temp[-sample_idx, ]
  
  formula_rf <- as.formula(paste(cat_name, "~", paste(features, collapse = " + ")))
  
  expert_model <- randomForest(formula_rf, 
                               data = train_data, 
                               ntree = 200, 
                               nodesize = 10, 
                               importance = TRUE)
  
  preds <- predict(expert_model, test_data)
  actuals <- test_data[[cat_name]]
  
  # --- CALCUL DES MÉTRIQUES ---
  # R²
  r2 <- 1 - sum((actuals - preds)^2) / sum((actuals - mean(actuals))^2)
  
  # MAE (Mean Absolute Error)
  mae <- mean(abs(actuals - preds))
  
  # RMSE Absolu (pour le calcul du NRMSE)
  rmse_abs <- sqrt(mean((actuals - preds)^2))
  
  # NRMSE (Erreur relative par rapport à la moyenne)
  nrmse <- (rmse_abs / mean(actuals)) * 100
  
  experts_results[[cat_name]] <- list(model = expert_model, score_r2 = r2, score_mae = mae, score_nrmse = nrmse)
  
  # Affichage harmonisé (ajout de la colonne MAE)
  cat(sprintf("[%-8s] | %-6d | %-5.2f | %-10.2f | ±%-8.2f%%\n", 
              cat_name, nrow(df_temp), r2, mae, nrmse))
}