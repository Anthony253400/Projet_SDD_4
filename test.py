import tensorflow as tf
from tensorflow.keras import layers, models

# 1. Paramètres
IMG_SIZE = (224, 224)
BATCH_SIZE = 32

# Chargement du dossier Train
train_ds = tf.keras.utils.image_dataset_from_directory(
    'data/train',
    image_size=IMG_SIZE,
    batch_size=BATCH_SIZE)

# Chargement du dossier Validation
val_ds = tf.keras.utils.image_dataset_from_directory(
    'data/val',
    image_size=IMG_SIZE,
    batch_size=BATCH_SIZE)

# Optionnel : Charger le dossier Test pour l'évaluation finale
test_ds = tf.keras.utils.image_dataset_from_directory(
    'data/test',
    image_size=IMG_SIZE,
    batch_size=BATCH_SIZE)

# 3. Optimisation des performances (mémoire vive)
AUTOTUNE = tf.data.AUTOTUNE
train_ds = train_ds.cache().shuffle(1000).prefetch(buffer_size=AUTOTUNE)
val_ds = val_ds.cache().prefetch(buffer_size=AUTOTUNE)

# 4. Création du modèle avec Transfer Learning (MobileNetV2)
# On prend un modèle pré-entraîné sur ImageNet sans la dernière couche
base_model = tf.keras.applications.MobileNetV2(
    input_shape=(224, 224, 3), 
    include_top=False, 
    weights='imagenet'
)
base_model.trainable = False # On gèle les poids du modèle de base

model = models.Sequential([
    layers.Rescaling(1./255), # Normalisation des pixels [0,1]
    layers.RandomFlip("horizontal_and_vertical"), # Augmentation de données
    layers.RandomRotation(0.2),
    base_model,
    layers.GlobalAveragePooling2D(),
    layers.Dense(128, activation='relu'),
    layers.Dropout(0.2),
    layers.Dense(len(train_ds.class_names), activation='softmax') # Couche de sortie
])

# 5. Compilation et Entraînement
model.compile(optimizer='adam',
              loss='sparse_categorical_crossentropy',
              metrics=['accuracy'])

model.fit(train_ds, validation_data=val_ds, epochs=10)