library(nnet)
library(caret)

# Changer de répertoire de travail ! : Session --> Set Working Directory --> To source file location

set.seed(123)

df <- read.csv("../../Data/public_data_waste_fee.csv")
dechets_colonnes <- c("organic", "paper", "glass", "wood", "metal", "plastic", "raee", "texile", "other")
df[dechets_colonnes][is.na(df[dechets_colonnes])] <- 0

# On enlève les régions qui n'apparaissent qu'une seule fois
counts <- table(df$region)
regions_valides <- names(counts[counts >= 5])
df <- df[df$region %in% regions_valides, ]
df$region <- factor(df$region)

# réduction organic
df$vrai_label <- dechets_colonnes[max.col(df[, dechets_colonnes])]
df_equilibre <- df[df$vrai_label != "organic", ] 
df_organic_reduit <- df[df$vrai_label == "organic", ][1:200, ] 
df_final <- rbind(df_equilibre, df_organic_reduit)
df_final$region <- factor(df_final$region)

# Validation Croisée
k <- 5
# createFolds --> répartition des régions équitablement
folds_indices <- createFolds(df_final$region, k = k, list = TRUE)

erreurs_totale <- data.frame()

reels_totaux <- data.frame()

for(i in 1:k){
  test_indices <- folds_indices[[i]]
  train_cv <- df_final[-test_indices, ]
  test_cv  <- df_final[test_indices, ]
  
  Y_train_cv <- as.matrix(train_cv[, dechets_colonnes])
  
  model_cv <- multinom(Y_train_cv ~ pop + gdp + wage + alt + pden + d_fee + roads + urb + region + area, 
                       data = train_cv, trace = FALSE, maxit = 200)
  
  preds_probs <- predict(model_cv, newdata = test_cv, type = "probs")
  
  reels_probs <- as.matrix(test_cv[, dechets_colonnes])
  reels_probs <- reels_probs / rowSums(reels_probs) # On normalise pour que la somme = 1
  
  erreur_pli <- abs(reels_probs - preds_probs)
  erreurs_totale <- rbind(erreurs_totale, as.data.frame(erreur_pli))
  reels_totaux <- rbind(reels_totaux, as.data.frame(reels_probs))
}
mae_par_classe <- colMeans(abs(erreurs_totale), na.rm = TRUE) * 100
rmse_par_classe <- sqrt(colMeans(erreurs_totale^2, na.rm = TRUE)) * 100

# NRMSE = RMSE divisé / moyenne de la classe réelle
moyennes_reelles <- colMeans(reels_totaux, na.rm = TRUE) * 100
nrmse_par_classe <- (rmse_par_classe / moyennes_reelles) * 100

rapport_complet <- data.frame(
  Classe = dechets_colonnes,
  MAE = round(mae_par_classe, 2),
  RMSE = round(rmse_par_classe, 2),
  NRMSE_pct = round(nrmse_par_classe, 2)
)

print(rapport_complet)

rmse_global <- sqrt(mean(as.matrix(erreurs_totale)^2, na.rm = TRUE)) * 100

nrmse_global <- (rmse_global / mean(as.matrix(reels_totaux) * 100)) * 100

cat("RMSE Global :", round(rmse_global, 2), "%")
cat("NRMSE Global :", round(nrmse_global, 2), "% (Erreur relative à la moyenne)")

# Intervalle de confiance à 95%
erreurs_propres <- na.omit(as.matrix(erreurs_totale)) #suppression lignes vides
n <- nrow(erreurs_propres)
mae_par_ville <- rowMeans(erreurs_propres) * 100
se <- sd(mae_par_ville, na.rm = TRUE) / sqrt(n) 
ic_95 <- qt(0.975, df = n-1) * se

mae_moyen <- mean(mae_par_ville, na.rm = TRUE)

cat("MAE Global :", round(mae_moyen, 2), "%")
cat("Intervalle de confiance à 95% : [", 
    round(mae_moyen - ic_95, 2), "% ,", 
    round(mae_moyen + ic_95, 2), "% ]\n")

# Exemple de la première ligne
exemple_mix <- predict(model_cv, newdata = df_final[1,], type = "probs")
print(round(exemple_mix * 100, 2))

saveRDS(model_cv, "model_multinom.rds")