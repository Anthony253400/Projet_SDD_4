# 1. Chargement des données
# On suppose que le fichier CSV a été créé avec les données fournies
data <- read.csv("dechets.csv")

# 2. Création du nuage de points avec les fonctions de base de R
plot(data$pop, data$msw,
     main = "Quantité de déchets en fonction des habitants",
     xlab = "Nombre d'habitants (pop)",
     ylab = "Quantité totale de déchets (msw)",
     pch = 19,      # Type de point (plein)
     col = "blue")  # Couleur des points

# Optionnel : Ajouter une grille pour la lisibilité
grid()

# --- Alternative avec ggplot2 (plus moderne) ---
# library(ggplot2)
# ggplot(data, aes(x = pop, y = msw)) +
#   geom_point(size = 3, color = "darkgreen") +
#   labs(title = "Analyse des déchets par population",
#        x = "Nombre d'habitants",
#        y = "Déchets municipaux (msw)") +
#   theme_minimal()