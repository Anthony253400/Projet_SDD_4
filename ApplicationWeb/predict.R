library(randomForest)
library(nnet)
library(jsonlite)

args <- commandArgs(trailingOnly = TRUE)
if(length(args) < 7) stop("Erreur : Pas assez d'arguments")

pop        <- args[1]
urb        <- args[2]
wage       <- args[3]
d_fee      <- args[4]
area       <- args[5]
region     <- args[6]
model_type <- args[7]

input_df <- data.frame(
  pop = as.numeric(pop),
  urb = as.numeric(urb), 
  wage = as.numeric(wage),
  d_fee = as.numeric(d_fee),
  area = as.numeric(area),
  region = as.character(region),
  stringsAsFactors = FALSE
)

options(warn=-1)

get_p <- function(model_obj, df, is_linear = FALSE) {
  if (is.null(model_obj)) return(0)
  val <- as.numeric(predict(model_obj, df))
  if (is_linear) val <- max(0, val)
  return(round(val, 2))
}

# Random Forest
if (model_type == "random_forest") {
    model_list <- readRDS("C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/modeles/experts_waste_rf.rds")
    
    ref_model <- model_list[[1]]$model
    input_df$urb <- factor(input_df$urb, levels = ref_model$forest$xlevels$urb)
    input_df$region <- factor(input_df$region, levels = ref_model$forest$xlevels$region)

    res <- list(
      paper   = get_p(model_list[['paper']]$model,   input_df),
      organic = get_p(model_list[['organic']]$model, input_df),
      plastic = get_p(model_list[['plastic']]$model, input_df),
      glass   = get_p(model_list[['glass']]$model,   input_df),
      wood    = get_p(model_list[['wood']]$model,    input_df),
      metal   = get_p(model_list[['metal']]$model,   input_df),
      raee    = get_p(model_list[['raee']]$model,    input_df),
      texile  = get_p(model_list[['texile']]$model,  input_df),
      other   = get_p(model_list[['other']]$model,   input_df)
    )
    cat(toJSON(res, auto_unbox = TRUE))

# Régression logistique multionomiale
} else if (model_type == "multinomial") {
    model_m <- readRDS("C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/modeles/model_multinom.rds")
    
    input_df$urb <- as.numeric(urb) 
    input_df$region <- as.factor(region)
    
    res_m <- predict(model_m, input_df, type='probs')
    
    cols <- c("organic", "paper", "plastic", "glass", "wood", "metal", "raee", "texile", "other")
    res <- list()
    for(c in cols) {
      val <- if(c %in% names(res_m)) as.numeric(res_m[c]) else 0
      res[[c]] <- round(val * 100, 2)
    }
    cat(toJSON(res, auto_unbox = TRUE))

# Régression linéaire multiple
} else if (model_type == "linear") {
    linear_list <- readRDS("C:/MAMP/htdocs/Projet_SDD_4/ApplicationWeb/modeles/model_linear.rds")
    
    input_df$urb <- as.numeric(urb)
    input_df$region <- as.factor(region)
    
    res <- list(
      paper   = get_p(linear_list[['paper']]$model,   input_df, TRUE),
      organic = get_p(linear_list[['organic']]$model, input_df, TRUE),
      plastic = get_p(linear_list[['plastic']]$model, input_df, TRUE),
      glass   = get_p(linear_list[['glass']]$model,   input_df, TRUE),
      wood    = get_p(linear_list[['wood']]$model,    input_df, TRUE),
      metal   = get_p(linear_list[['metal']]$model,   input_df, TRUE),
      raee    = get_p(linear_list[['raee']]$model,    input_df, TRUE),
      texile  = get_p(linear_list[['texile']]$model,  input_df, TRUE),
      other   = get_p(linear_list[['other']]$model,   input_df, TRUE)
    )
    cat(toJSON(res, auto_unbox = TRUE))
}