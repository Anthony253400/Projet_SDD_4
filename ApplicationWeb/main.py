from fastapi import FastAPI
from pydantic import BaseModel
import rpy2.robjects as robjects
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

# Autorise ton HTML à parler à ton Python
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

# On charge la liste R une seule fois au démarrage du serveur pour gagner du temps
robjects.r('experts <- readRDS("experts_waste_rf.rds")')

class PredictionInput(BaseModel):
    pop: float
    pden: float
    urb: int
    gdp: float
    wage: float
    roads: float
    alt: float
    d_fee: int
    area: float
    region: str

@app.post("/predict_all")
def predict_all_wastes(data: PredictionInput):
    # Préparation du data.frame R avec les saisies de l'utilisateur
    # Note : on transforme urb et region en facteurs car ton modèle a été entraîné comme ça
    r_code_data = f"""
    new_vile <- data.frame(
        pop = {data.pop}, pden = {data.pden}, urb = as.factor({data.urb}),
        gdp = {data.gdp}, wage = {data.wage}, roads = {data.roads},
        alt = {data.alt}, d_fee = {data.d_fee}, area = {data.area},
        region = as.factor("{data.region}")
    )
    """
    robjects.r(r_code_data)
    
    # On crée un dictionnaire pour stocker les résultats
    results = {}
    
    # On boucle sur les déchets principaux pour demander une prédiction à chaque expert
    categories = ["organic", "paper", "glass", "plastic"]
    
    for cat in categories:
        # On demande à R : "Prends l'expert 'cat', et prédis pour 'new_vile'"
        pred = robjects.r(f"predict(experts[['{cat}']]$model, new_vile)")
        results[cat] = round(float(pred[0]), 2)
        
    return results