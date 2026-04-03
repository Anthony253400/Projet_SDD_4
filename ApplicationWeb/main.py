from fastapi import FastAPI
from pydantic import BaseModel
import rpy2.robjects as robjects
from fastapi.middleware.cors import CORSMiddleware
import os

os.environ['R_HOME'] = r'C:\Program Files\R\R-4.4.2' 

import rpy2.robjects as robjects 

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

robjects.r('experts_rf <- readRDS("experts_waste_rf.rds")')
robjects.r('model_logistique <- readRDS("model_multinom.rds")')

class DataVille(BaseModel):
    pop: float
    gdp: float
    wage: float
    alt: float
    pden: float
    roads: float
    urb: int
    area: float
    region: str
    model_type: str 

@app.post("/predict")
def predict_waste(data: DataVille):
    r_code_data = f"""
    input_df <- data.frame(
        pop = {data.pop}, gdp = {data.gdp}, wage = {data.wage}, 
        alt = {data.alt}, pden = {data.pden}, roads = {data.roads}, 
        urb = as.factor({data.urb}), area = {data.area}, 
        region = as.factor("{data.region}")
    )
    """
    robjects.r(r_code_data)

    results = {}

    if data.model_type == "random_forest":
        categories = ["organic", "paper", "glass", "plastic"] 
        for cat in categories:
            res = robjects.r(f"predict(experts_rf[['{cat}']]$model, input_df)")
            results[cat] = round(float(res[0]), 2)

    else:
        res_probs = robjects.r("predict(model_logistique, newdata = input_df, type = 'probs')")
        
        categories = ["organic", "paper", "glass", "plastic", "wood", "metal", "raee", "texile", "other"]
        for i, cat in enumerate(categories):
            results[cat] = round(float(res_probs[i]) * 100, 2)

    return results