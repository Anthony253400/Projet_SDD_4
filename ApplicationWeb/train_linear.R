df <- read.csv("../data/public_data_waste_fee.csv", stringsAsFactors = FALSE)

colnames(df)[colnames(df) == "texile"] <- "textile"

features <- c("pop", "urb", "wage", "d_fee", "area", "region")
targets  <- c("organic", "paper", "glass", "wood", "metal", "plastic", "raee", "texile", "other")

linear_experts <- list()

for (waste in targets) {
  df_temp <- df[!is.na(df[[waste]]), ]
  
  if (nrow(df_temp) < 10) next
  
  for (f in c("pop", "wage", "area")) {
    df_temp[[f]][is.na(df_temp[[f]])] <- mean(df_temp[[f]], na.rm = TRUE)
  }
  
  formula_lm <- as.formula(paste(waste, "~", paste(features, collapse = " + ")))
  
  model_lm <- lm(formula_lm, data = df_temp)
  
  linear_experts[[waste]] <- list(model = model_lm)
  
  cat(paste0("Modèle créé pour : ", waste, "\n"))
}

saveRDS(linear_experts, "C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/model_linear.rds")

