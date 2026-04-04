if(!require(nnet)) install.packages("nnet")
library(nnet)

# 1. Chargement (même base que l'autre)
df <- read.csv("../../data/public_data_waste_fee.csv", stringsAsFactors = FALSE)

# 2. Préparation des variables
features <- c("pop", "urb", "wage", "d_fee", "area", "region")
df$region <- as.factor(df$region)
df$urb    <- as.numeric(df$urb) # La régression préfère le numérique ici

# 3. Création de la variable cible (Le "Vainqueur")
# La régression logistique multinomiale prédit souvent la CATEGORIE dominante
targets <- c("organic", "paper", "glass", "plastic")
df$dominant_waste <- targets[max.col(df[, targets], ties.method = "first")]
df$dominant_waste <- as.factor(df$dominant_waste)

# 4. Entraînement
model_multinom <- multinom(dominant_waste ~ pop + urb + wage + d_fee + area + region, 
                           data = df, MaxNWts = 2000)

# 5. Sauvegarde
saveRDS(model_multinom, "C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/model_multinom.rds")
cat("✅ Modèle Multinomial sauvegardé !")