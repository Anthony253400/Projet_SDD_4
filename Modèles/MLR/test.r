accuracies <- c()
all_preds <- c()
all_vrais_labels <- c()

for(i in 1:k){
  test_indices <- which(folds == i, arr.ind = TRUE)
  train_cv <- df_final[-test_indices, ]
  test_cv  <- df_final[test_indices, ]
  
  Y_train_cv <- as.matrix(train_cv[, dechets_colonnes])
  model_cv <- multinom(Y_train_cv ~ log(pop) + gdp + wage + alt + urb, 
                       data = train_cv, trace = FALSE, maxit = 200)
  
  preds_cv <- predict(model_cv, newdata = test_cv)
  vrai_label_cv <- dechets_colonnes[max.col(test_cv[, dechets_colonnes])]
  
  all_preds <- c(all_preds, as.character(preds_cv))
  all_vrais_labels <- c(all_vrais_labels, as.character(vrai_label_cv))
  
  acc <- mean(as.character(preds_cv) == as.character(vrai_label_cv), na.rm = TRUE)
  accuracies <- c(accuracies, acc)
  
  print(paste("Pli", i, "- Précision :", round(acc * 100, 2), "%"))
}

# Matrice de confusion
preds_factor <- factor(all_preds, levels = dechets_colonnes)
vrai_factor  <- factor(all_vrais_labels, levels = dechets_colonnes)

table_carree <- table(Prédit = preds_factor, Réel = vrai_factor)

# Calcul des métriques
diag_vector <- diag(table_carree)
row_sums    <- rowSums(table_carree)
col_sums    <- colSums(table_carree)

precision <- ifelse(row_sums > 0, diag_vector / row_sums, 0)
rappel    <- ifelse(col_sums > 0, diag_vector / col_sums, 0)
f1_score  <- ifelse((precision + rappel) > 0, 2 * (precision * rappel) / (precision + rappel), 0)


rapport_classes <- data.frame(
  Classe = dechets_colonnes,
  Precision = round(precision * 100, 2),
  Rappel = round(rappel * 100, 2),
  F1_Score = round(f1_score * 100, 2)
)

print(rapport_classes)
