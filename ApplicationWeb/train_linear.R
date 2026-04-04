df <- read.csv("../data/public_data_waste_fee.csv", stringsAsFactors = FALSE)

# On renomme "texile" en "textile" comme tu l'as fait pour les autres
colnames(df)[colnames(df) == "texile"] <- "textile"

# 2. Liste des variables (On garde les 6 variables que tu utilises sur ton site)
features <- c("pop", "urb", "wage", "d_fee", "area", "region")
targets  <- c("organic", "paper", "glass", "plastic") # Les types principaux

# Liste pour stocker nos "experts" linéaires
linear_experts <- list()

cat("\n--- Entraînement RÉGRESSION LINÉAIRE (R) ---\n")

for (waste in targets) {
  # Suppression des lignes où le déchet est manquant
  df_temp <- df[!is.na(df[[waste]]), ]
  
  if (nrow(df_temp) < 10) next
  
  # --- IMPUTATION PAR LA MOYENNE (Comme ton SimpleImputer) ---
  # Pour chaque colonne numérique dans features, on remplace les NA par la moyenne
  for (f in c("pop", "wage", "area")) {
    df_temp[[f]][is.na(df_temp[[f]])] <- mean(df_temp[[f]], na.rm = TRUE)
  }
  
  # --- ENTRAÎNEMENT ---
  # Construction de la formule : waste ~ pop + urb + wage + d_fee + area + region
  formula_lm <- as.formula(paste(waste, "~", paste(features, collapse = " + ")))
  
  model_lm <- lm(formula_lm, data = df_temp)
  
  # On stocke le modèle dans notre liste
  linear_experts[[waste]] <- list(model = model_lm)
  
  cat(paste0("Modèle créé pour : ", waste, "\n"))
}

# --- SAUVEGARDE ---
saveRDS(linear_experts, "C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/model_linear.rds")
cat("\nFichier 'model_linear.rds' généré avec succès !")

