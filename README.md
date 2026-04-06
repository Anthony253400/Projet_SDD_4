# Projet Sciences des Données : L'Intelligence Artificielle pour une Gestion Durable des Déchets

## Problématique
Comment l’intelligence artificielle peut-elle soutenir une gestion intelligente des déchets en combinant la prévision des flux communaux et l’assistance au tri pour les citoyens ?

L'objectif est de s'attaquer au problème des déchets sous deux angles :
1. **Pour les collectivités :** Anticiper les volumes pour optimiser la logistique.
2. **Pour les citoyens :** Lever le doute sur la consigne de tri via la reconnaissance d'image.

## Accès au Projet
Le projet est entièrement déployé en ligne grâce à une architecture distribuée :
* **Interface Web :** Hébergée sur [InfinityFree](https://tri-facile.rf.gd/) (PHP/MySQL).
* **Moteur d'IA (Back-end) :** API FastAPI hébergée sur Render.
* **Base de Données :** MySQL gérée à distance via l'interface InfinityFree.

## Fonctionnalités
### 1. Prédiction des Flux Communaux
Analyse prédictive basée sur des variables socio-économiques (population, zone géographique, revenus, altitude) :
* **Modèles utilisés :** Random Forest, Régression Logistique Multinomiale, Régression Linéaire Multiple.
* **Indicateurs :** Papier, Organique, Plastique, Verre, Bois, Métal, RAEE, Textile, Autre.

### 2. Reconnaissance de Déchets
Application web permettant de téléverser une photo d'un déchet pour obtenir :
* Sa catégorie (Plastique, Verre, Papier, etc.).
* La consigne de tri associée.
* **Boucle d'apprentissage :** Possibilité pour l'utilisateur de contester la réponse afin d'enrichir une base de données de feedback pour les futurs entraînements.

## Données utilisées

- *Garbage Classification Dataset* : Utilisé pour l'entraînement du modèle de Vision par Ordinateur. Il contient des images de papier, carton, plastique, métal, verre et déchets organiques.
(https://www.kaggle.com/datasets/hassnainzaidi/garbage-classification)

- *Municipal Waste Management Cost Prediction* : Utilisé pour la partie analyse prédictive des flux dechets à l'échelle d'une ville.
(https://www.kaggle.com/datasets/shashwatwork/municipal-waste-management-cost-prediction)

## Architecture du Projet
Le projet repose sur une architecture hybride permettant de faire communiquer R et Python :
* **Frontend :** PHP / HTML5 / CSS3 / JavaScript (Chart.js pour les graphiques dynamiques).
* **Backend IA :** FastAPI (Python 3.10) servant de pont entre l'interface et les modèles.
* **Calcul Statistique :** Scripts **R** (Plumber) pour les prédictions de flux.
* **Base de données :** MySQL pour le stockage des statistiques communales et des retours utilisateurs (Feedback).

## Résultats 

### Classification d'images (CNN)
* **Performance :** Accuracy de **XX%** sur le jeu de test.
* **Robustesse :** Le modèle distingue efficacement les matières principales (Organique, Verre, Métal) malgré des arrière-plans variés.

### Prédiction des flux (Modèles R)
* **Fiabilité :** Le modèle **Random Forest** a obtenu les meilleurs résultats avec une erreur moyenne (RMSE) de **Y** sur les prévisions de tonnage.
* **Analyse :** Les variables "Population" et "Revenu moyen" se sont révélées être les prédicteurs les plus influents sur la production de déchets.

### Impact Utilisateur
* Interface fluide permettant une réponse en moins de 3 secondes.
* Visualisation dynamique des statistiques régionales permettant de comparer les performances de tri entre différentes zones géographiques.
## Auteurs

- Croenne Victor (@vcroenne)
- Faizandier Ambre (@Ambre0108)
- Miranda Anthony (@Anthony253400)
- Valentin Nina (@Nina253)
