# Projet Sciences des Données : L'Intelligence Artificielle pour une Gestion Durable des Déchets

## Problématique
Comment l’intelligence artificielle peut-elle soutenir une gestion intelligente des déchets en combinant la prévision des flux communaux et l’assistance au tri pour les citoyens ?

L'objectif est de s'attaquer au problème des déchets sous deux angles :
1. **Pour les collectivités :** Anticiper les volumes pour optimiser la logistique.
2. **Pour les citoyens :** Lever le doute sur la consigne de tri via la reconnaissance d'image.

## Fonctionnalité
### 1. Prédiction des Flux Communaux
Analyse prédictive basée sur des variables socio-économiques (population, zone géographique, revenus, altitude) pour estimer le flux de déchets par catégorie :
* **Modèles utilisés :** Random Forest, Régression Logistique Multinomiale, Régression Linéaire Multiple.
* **Indicateurs :** Papier, Organique, Plastique, Verre, Bois, Métal, RAEE, Textile, Autre.

### 2. Reconnaissance de Déchets
Application web permettant de téléverser une photo d'un déchet pour obtenir instantanément :
* Sa catégorie (Plastique, Verre, Papier, etc.).
* La consigne de tri associée.
* Contester la réponse afin d'entraîner le modèle.

## Données utilisées

- *Garbage Classification Dataset* : Utilisé pour l'entraînement du modèle de Vision par Ordinateur. Il contient des images de papier, carton, plastique, métal, verre et déchets organiques.
(https://www.kaggle.com/datasets/hassnainzaidi/garbage-classification)

- *Municipal Waste Management Cost Prediction* : Utilisé pour la partie analyse prédictive des flux dechets à l'échelle d'une ville.
(https://www.kaggle.com/datasets/shashwatwork/municipal-waste-management-cost-prediction)

## Architecture du Projet

Le projet repose sur une architecture hybride permettant de faire communiquer R et Python :

* **Interface :** PHP / HTML5 / CSS3 / JavaScript (jQuery).
* **Serveur d'Intelligence Artificielle :** FastAPI (Python 3.10) pour servir les modèles.
* **Modèles :** Scripts **R**(Random Forest, Régression Linéaire Multiple et Régression Logistique Multinomiale) et **Python**(CNN).
* **Base de données :** MySQL (MAMP).

## Installation
 ...

## Résultats 
Classification d'images : Accuracy de  ... % sur le jeu de test.

Prédiction des flux : Erreur Quadratique Moyenne (RMSE) de Y sur les prévisions de tonnage. ...

## Auteurs

- Croenne Victor (@vcroenne)
- Faizandier Ambre (@Ambre0108)
- Miranda Anthony (@Anthony253400)
- Valentin Nina (@Nina253)
