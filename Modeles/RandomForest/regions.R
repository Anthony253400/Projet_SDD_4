
if(!require(randomForest)) install.packages("randomForest")
library(randomForest)

df <- read.csv("data/public_data_waste_fee.csv", stringsAsFactors = FALSE)


colnames(df)[colnames(df) == "texile"] <- "textile"


features <- c("pop", "pden", "alt", "sea", "wage", "finance", 
              "gdp", "d_fee", "sor", "msw", "urb", "region")

targets <- c("organic", "paper", "glass", "wood", "metal", 
             "plastic", "raee", "textile", "other")


df$region <- as.factor(df$region)
df$urb <- as.factor(df$urb)

experts_results <- list()

cat("--- Entraînement et Test (Métriques R² et RMSE) ---\n")

# 4. Boucle d'entraînement
for (cat_name in targets) {
  
  if (!(cat_name %in% colnames(df))) {
    cat(sprintf("Saut de [%s] : colonne introuvable.\n", cat_name))
    next
  }
  
  df_temp <- df[complete.cases(df[, c(features, cat_name)]), ]
  
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
  
  # calcul r2
  r2 <- 1 - sum((actuals - preds)^2) / sum((actuals - mean(actuals))^2)
  
  # calcul rmse
  rmse <- sqrt(mean((actuals - preds)^2))
  
  experts_results[[cat_name]] <- list(model = expert_model, score_r2 = r2, score_rmse = rmse)
  
  
  cat(sprintf("Expert [%-8s] | R²: %.2f | RMSE: %.2f%%\n", cat_name, r2, rmse))
}





