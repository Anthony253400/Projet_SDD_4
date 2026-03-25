library(nnet)
set.seed(123)

# Changer de répartoire de travail : Session --> Set Working Directory --> To source file location

df <- read.csv("../../Data/public_data_waste_fee.csv")
dechets_colonnes <- c("organic", "paper", "glass", "wood", "metal", "plastic", "raee", "texile", "other")

df[dechets_colonnes][is.na(df[dechets_colonnes])] <- 0

index <- sample(1:nrow(df), size = 0.8 * nrow(df))
train_set <- df[index, ]
test_set  <- df[-index, ]

Y_train <- as.matrix(train_set[, dechets_colonnes])

modele_dechets <- multinom(Y_train ~ gdp + pden + alt + pop + urb, 
                           data = train_set, 
                           maxit = 500)

summary(modele_dechets)

#Validation croisée
k <- 5  

df$vrai_label <- dechets_colonnes[max.col(df[, dechets_colonnes])]
df_equilibre <- df[df$vrai_label != "organic", ] 
df_organic_reduit <- df[df$vrai_label == "organic", ][1:200, ] # On limite l'organique à 200 lignes
df_final <- rbind(df_equilibre, df_organic_reduit)

folds <- cut(seq(1, nrow(df_final)), breaks = k, labels = FALSE)
folds <- sample(folds)

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
