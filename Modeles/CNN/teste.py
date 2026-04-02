import os
classes = ["cardboard", "glass", "metal", "paper", "plastic", "trash"]

for c in classes:
    n = len(os.listdir(f"data/data_split/test/{c}"))  # adapte le chemin
    print(f"{c} : {n} images")