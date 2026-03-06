library(nnet)
set.seed(123)

# Quelle variable on utlise pour la prediciton ?
# Comment bien repartir les données d'entrainement et de test ?
# Comment bien évaluer le model ?


df <- read.csv("data/public_data_waste_fee.csv")
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

