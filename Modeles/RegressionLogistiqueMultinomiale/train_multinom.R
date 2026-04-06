if(!require(nnet)) install.packages("nnet")
library(nnet)

df <- read.csv("../data/public_data_waste_fee.csv", stringsAsFactors = FALSE)

features <- c("pop", "urb", "wage", "d_fee", "area", "region")
df$region <- as.factor(df$region)
df$urb    <- as.numeric(df$urb)

targets <- c("organic", "paper", "glass", "wood", "metal", "plastic", "raee", "texile", "other")
df$dominant_waste <- targets[max.col(df[, targets], ties.method = "first")]
df$dominant_waste <- as.factor(df$dominant_waste)

model_multinom <- multinom(dominant_waste ~ pop + urb + wage + d_fee + area + region, 
                           data = df, MaxNWts = 2000)

saveRDS(model_multinom, "C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/model_multinom.rds")
