library(nnet)
library(caret)

# Changer de répertoire de travail ! : Session --> Set Working Directory --> To source file location

set.seed(123)

df <- read.csv("../../Data/public_data_waste_fee.csv")
dechets_colonnes <- c("organic", "paper", "glass", "wood", "metal", "plastic", "raee", "texile", "other")
df[dechets_colonnes][is.na(df[dechets_colonnes])] <- 0

# On enlève les provinces qui n'apparaissent qu'une seule fois
counts <- table(df$province)
provinces_valides <- names(counts[counts > 1])
df <- df[df$province %in% provinces_valides, ]
df$province <- factor(df$province)

# réduction organic --> garder lignes, sinon utilise df_final <- df
df$vrai_label <- dechets_colonnes[max.col(df[, dechets_colonnes])]
df_equilibre <- df[df$vrai_label != "organic", ] 
df_organic_reduit <- df[df$vrai_label == "organic", ][1:200, ] 
df_final <- rbind(df_equilibre, df_organic_reduit)
df_final$province <- factor(df_final$province)

# Validation Croisée
k <- 5
# createFolds --> répartition des provinces équitable
folds_indices <- createFolds(df_final$province, k = k, list = TRUE)

erreurs_totale <- data.frame()

for(i in 1:k){
  test_indices <- folds_indices[[i]]
  train_cv <- df_final[-test_indices, ]
  test_cv  <- df_final[test_indices, ]
  
  Y_train_cv <- as.matrix(train_cv[, dechets_colonnes])
  
  model_cv <- multinom(Y_train_cv ~ log(pop) + gdp + wage + alt + urb + province, 
                       data = train_cv, trace = FALSE, maxit = 200)
  
  # On prédit les probas 
  preds_probs <- predict(model_cv, newdata = test_cv, type = "probs")
  
  reels_probs <- as.matrix(test_cv[, dechets_colonnes])
  reels_probs <- reels_probs / rowSums(reels_probs) # On normalise pour que la somme = 1
  
  # Calcul erreur absolue
  erreur_pli <- abs(reels_probs - preds_probs)
  erreurs_totale <- rbind(erreurs_totale, as.data.frame(erreur_pli))
}

# Erreurs moyenne par catégories
# Plus le score est proche de 0, plus le modèle est précis sur le pourcentage
rapport_erreurs <- data.frame(
  Classe = dechets_colonnes,
  Erreur_Moyenne_Points_Pct = round(colMeans(erreurs_totale, na.rm = TRUE) * 100, 2)
)
print(rapport_erreurs)

# Exemple 
exemple_mix <- predict(model_cv, newdata = df_final[1,], type = "probs")
print(round(exemple_mix * 100, 2))
