import os
import shutil
import random

random.seed(25)

source_dir = "data/train"
base_output_dir = "data/data_split"

train_dir = os.path.join(base_output_dir, "train")
val_dir = os.path.join(base_output_dir, "val")
test_dir = os.path.join(base_output_dir, "test")


train_ratio = 0.7
val_ratio = 0.15
test_ratio = 0.15

for folder in [train_dir, val_dir, test_dir]:
    os.makedirs(folder, exist_ok=True)

for class_name in os.listdir(source_dir):
    class_path = os.path.join(source_dir, class_name)
    
    if not os.path.isdir(class_path):
        continue

    images = os.listdir(class_path)
    random.shuffle(images)

    total = len(images)
    train_split = int(total * train_ratio)
    val_split = int(total * val_ratio)

    train_images = images[:train_split]
    val_images = images[train_split:train_split + val_split]
    test_images = images[train_split + val_split:]

    for folder in [train_dir, val_dir, test_dir]:
        os.makedirs(os.path.join(folder, class_name), exist_ok=True)

    for img in train_images:
        shutil.copy(os.path.join(class_path, img),
                    os.path.join(train_dir, class_name, img))

    for img in val_images:
        shutil.copy(os.path.join(class_path, img),
                    os.path.join(val_dir, class_name, img))

    for img in test_images:
        shutil.copy(os.path.join(class_path, img),
                    os.path.join(test_dir, class_name, img))

def count_images(folder):
    total = 0
    class_counts = {}

    for class_name in os.listdir(folder):
        class_path = os.path.join(folder, class_name)
        
        if not os.path.isdir(class_path):
            continue

        num_images = len([
            f for f in os.listdir(class_path)
            if os.path.isfile(os.path.join(class_path, f))
        ])

        class_counts[class_name] = num_images
        total += num_images

    return total, class_counts


for split in ["train", "val", "test"]:
    folder_path = os.path.join("data/data_split", split)
    total, class_counts = count_images(folder_path)

    print(f"\n📁 {split.upper()}")
    print(f"Total images: {total}")
    
    for cls, count in class_counts.items():
        print(f"  {cls}: {count}")

print("Split fini")