import subprocess
import json
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

class DataVille(BaseModel):
    pop: float; urb: int; wage: float; d_fee: int; 
    area: float; region: str; model_type: str

@app.post("/predict")
def predict(data: DataVille):
    print(f"Modèle demandé : {data.model_type}")
    
    script_path = r"C:\MAMP\htdocs\Projet_SDD_4\ApplicationWeb\predict.R"
    r_exe = r"C:\Program Files\R\R-4.4.2\bin\Rscript.exe"

    cmd = [
        r_exe, script_path,
        str(data.pop), str(data.urb),
        str(data.wage), str(data.d_fee),
        str(data.area), data.region, data.model_type
    ]

    print(f"Exécution de la commande : {' '.join(cmd)}")

    try:
        # On exécute et on CAPTURE TOUT
        process = subprocess.run(cmd, capture_output=True, text=True, encoding='utf-8')
        
        print(f"Code de sortie R : {process.returncode}")
        print(f"Sortie standard (STDOUT) : {process.stdout}")
        print(f"Sortie d'erreur (STDERR) : {process.stderr}")

        if process.returncode == 0:
            # On nettoie la sortie pour ne garder que le JSON
            output = process.stdout.strip().split('\n')[-1]
            return json.loads(output)
        else:
            return {"error": "R a planté", "details": process.stderr}
            
    except Exception as e:
        print(f"ERREUR PYTHON : {str(e)}")
        return {"error": str(e)}

if __name__ == "__main__":
    import uvicorn
    # On change 8000 par 8001
    uvicorn.run(app, host="127.0.0.1", port=8001)