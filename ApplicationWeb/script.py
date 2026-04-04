from fastapi import FastAPI, File, UploadFile
from fastapi.middleware.cors import CORSMiddleware
import numpy as np
from tensorflow.keras.models import load_model
from PIL import Image
import io

app = FastAPI()
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # ou ["http://localhost"] en prod
    allow_methods=["*"],
    allow_headers=["*"],
)

model = load_model("model/cnn2.keras")

@app.get("/")
def home():
    return {"message": "API Keras OK"}

@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    contents = await file.read()
    image = Image.open(io.BytesIO(contents)).convert("RGB").resize((256, 256))  # ✅ 256

    x = np.array(image, dtype=np.float32)  # ✅ pas de /255
    x = np.expand_dims(x, axis=0)

    prediction = model.predict(x)
    return {"prediction": prediction.tolist()}