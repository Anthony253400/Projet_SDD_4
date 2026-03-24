import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.impute import SimpleImputer
from sklearn.metrics import r2_score, mean_squared_error

df = pd.read_csv("data/public_data_waste_fee.csv")

features = ['pop', 'msw', 'sor', 'tc', 'wage', 'area', 'isle', 'sea', 'urb', 'gdp', 'alt', 'roads', 'pden']
waste_types = ["organic", "paper", "glass", "wood", "metal", "plastic", "raee", "textile", "other"]

imputer = SimpleImputer(strategy='mean')

print(f"{'DÉCHET':<12} | {'Lignes':<6} | {'R²':<6} | {'RMSE (%)':<10}")
print("-" * 45)

for waste in waste_types:
    if waste not in df.columns:
        continue

    mask = df[waste].notna()
    X_current = df.loc[mask, features]
    y_current = df.loc[mask, waste]

    if len(y_current) < 10:
        continue

    X_imputed = imputer.fit_transform(X_current)
    X_train, X_test, y_train, y_test = train_test_split(X_imputed, y_current, test_size=0.2, random_state=42)

    model = LinearRegression()
    model.fit(X_train, y_train)

    y_pred = model.predict(X_test)
    r2 = r2_score(y_test, y_pred)
    rmse = np.sqrt(mean_squared_error(y_test, y_pred))
    
    mean_val = y_test.mean()
    rmse_pct = (rmse / mean_val) * 100 if mean_val != 0 else 0

    print(f"[{waste:<10}] | Lignes: {len(y_current):<4} | R²: {r2:<5.2f} | RMSE: ±{rmse_pct:.2f}%")