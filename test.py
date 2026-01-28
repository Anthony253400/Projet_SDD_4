import kagglehub

# Download latest version
path = kagglehub.dataset_download("hassnainzaidi/garbage-classification")

print("Path to dataset files:", path)