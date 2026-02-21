# .\.venv\Scripts\Activate.ps1
import tensorflow as tf
from tensorflow.keras import layers, models

import os

data_dir = "data/train" 


img_height, img_width = 512, 384
batch_size = 32

# Entraînement et Validation (depuis le dossier 'train')
train_ds = tf.keras.utils.image_dataset_from_directory(
    'data/train',
    validation_split=0.2,
    subset="training",
    seed=42,
    image_size=(img_height, img_width),
    batch_size=batch_size
)

val_ds = tf.keras.utils.image_dataset_from_directory(
    'data/train',
    validation_split=0.2,
    subset="validation",
    seed=42,
    image_size=(img_height, img_width),
    batch_size=batch_size
)

# Test (depuis ton dossier 'test' s'il existe au même niveau)
# test_ds = tf.keras.utils.image_dataset_from_directory(
#     'test',
#     image_size=(img_height, img_width),
#     batch_size=batch_size
# )

class_names = train_ds.class_names
num_classes = len(class_names) # Dynamique : sera 6 dans ton cas

# --- 2. ARCHITECTURE DU MODÈLE ---
model = tf.keras.Sequential([
    layers.Rescaling(1./255, input_shape=(img_height, img_width, 3)),
    layers.Conv2D(32, 3, activation='relu'), # On commence petit (32) et on augmente
    layers.MaxPooling2D(),
    layers.Conv2D(64, 3, activation='relu'),
    layers.MaxPooling2D(),
    layers.Conv2D(128, 3, activation='relu'),
    layers.MaxPooling2D(),
    layers.Flatten(),
    layers.Dense(128, activation='relu'),
    layers.Dense(num_classes, activation='softmax') # Softmax pour multi-classe
])

# --- 3. COMPILATION ---
model.compile(
    optimizer='adam',
    loss='sparse_categorical_crossentropy', # Plus simple si labels sont des entiers
    metrics=['accuracy']
)

# --- 4. ENTRAÎNEMENT ---
model.fit(
    train_ds,
    validation_data=val_ds,
    epochs=10 # 2 c'est trop peu pour voir un résultat
)