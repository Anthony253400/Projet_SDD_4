import subprocess
import json
import joblib  # pip install scikit-learn joblib 
import numpy as np
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

# --- CHARGEMENT DU MODÈLE PYTHON (Régression Linéaire) ---
# Assure-toi que le fichier .joblib est bien dans le même dossier
try:
    model_lin_python = joblib.load("model_linear.joblib")
    print("✅ Modèle linéaire Python chargé avec succès.")
except:
    model_lin_python = None
    print("⚠️ Attention : model_linear.joblib introuvable.")

class DataVille(BaseModel):
    pop: float; urb: int; wage: float; d_fee: int; 
    area: float; region: str; model_type: str

@app.post("/predict")
def predict(data: DataVille):
    print(f"Modèle demandé : {data.model_type}")

    # --- CAS 1 : RÉGRESSION LINÉAIRE (Exécuté par Python) ---
    if data.model_type == "linear" and model_lin_python is not None:
        try:
            # On prépare les données (Attention à l'ordre des colonnes utilisé lors de l'entraînement)
            # Ici : pop, urb, wage, d_fee, area
            entrees = np.array([[data.pop, data.urb, data.wage, data.d_fee, data.area]])
            prediction = model_lin_python.predict(entrees)[0]
            
            return {
                "paper": round(max(0, float(prediction)), 2),
                "organic": 0, "plastic": 0, "glass": 0,
                "note": "Calculé directement par Python"
            }
        except Exception as e:
            return {"error": "Erreur calcul Python", "details": str(e)}

    # --- CAS 2 : RF & MULTINOMIAL (Exécuté par R) ---
    else:
        script_path = r"C:\MAMP\htdocs\Projet_SDD_4\ApplicationWeb\predict.R"
        r_exe = r"C:\Program Files\R\R-4.4.2\bin\Rscript.exe"

        cmd = [
            r_exe, script_path,
            str(data.pop), str(data.urb),
            str(data.wage), str(data.d_fee),
            str(data.area), data.region, data.model_type
        ]

        print(f"Exécution de la commande R : {' '.join(cmd)}")

        try:
            process = subprocess.run(cmd, capture_output=True, text=True, encoding='utf-8')
            
            print(f"Code de sortie R : {process.returncode}")
            
            if process.returncode == 0:
                output = process.stdout.strip().split('\n')[-1]
                return json.loads(output)
            else:
                return {"error": "R a planté", "details": process.stderr}
                
        except Exception as e:
            print(f"ERREUR PYTHON LORS DE L'APPEL R : {str(e)}")
            return {"error": str(e)}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="127.0.0.1", port=8001)