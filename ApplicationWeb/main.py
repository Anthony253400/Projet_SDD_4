import os
import uuid
import uvicorn
import random
import mysql.connector
from fastapi import FastAPI, UploadFile, File, Form
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",      
        password="root",
        database="dechets",
        port=8889
    )

FEEDBACK_DIR = "dataset_feedback"
if not os.path.exists(FEEDBACK_DIR):
    os.makedirs(FEEDBACK_DIR)


@app.post("/feedback")
async def save_feedback(label: str = Form(...), file: UploadFile = File(...)):
    try:
        # 1. Création du dossier
        class_path = os.path.join(FEEDBACK_DIR, label)
        os.makedirs(class_path, exist_ok=True)
        
        # 2. Génération du nom de fichier unique
        file_extension = os.path.splitext(file.filename)[1] or ".jpg"
        unique_filename = f"{uuid.uuid4()}{file_extension}"
        file_path = os.path.join(class_path, unique_filename)
        
        # 3. Écriture du fichier (utilisation de chunks pour plus de sécurité)
        with open(file_path, "wb") as f:
            content = await file.read()
            f.write(content)

        # 4. Insertion BDD
        conn = get_db_connection()
        cursor = conn.cursor()
        
        sql = "INSERT INTO feedbacks (image_path, label_correct) VALUES (%s, %s)"
        cursor.execute(sql, (file_path, label))
        
        conn.commit()
        cursor.close()
        conn.close()
            
        return {"status": "success", "message": "Feedback enregistré"}
    
    except Exception as e:
        print(f"Erreur détaillée : {str(e)}") # Pour voir l'erreur dans votre console Python
        return {"status": "error", "message": str(e)}

if __name__ == "__main__":
    uvicorn.run(app, host="127.0.0.1", port=8000)