import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.impute import SimpleImputer
from sklearn.metrics import r2_score, mean_squared_error

df = pd.read_csv("data/public_data_waste_fee.csv")
df.rename(columns={'texile': 'textile'}, inplace=True)

features = ['pop', 'tc', 'wage', 'area', 'urb', 'gdp', 'roads','alt', 'pden', 'd_fee']
waste_types = ["organic", "paper", "glass", "wood", "metal", "plastic", "raee", "textile", "other"]

imputer = SimpleImputer(strategy='mean')

print(f"{'DÉCHET':<12} | {'R²':<5} | {'RMSE ABS':<10} | {'MOYENNE':<10} | {'ERREUR RELATIVE (NRMSE)':<20}")
print("-" * 85)

for waste in waste_types:
    if waste not in df.columns:
        continue

    mask = df[waste].notna()
    X_current = df.loc[mask, features]
    y_current = df.loc[mask, waste]

    if len(y_current) < 25:
        continue

    X_imputed = imputer.fit_transform(X_current)
    
    X_train, X_test, y_train, y_test = train_test_split(X_imputed, y_current, test_size=0.2, random_state=42)

    model = LinearRegression()
    model.fit(X_train, y_train)

    y_pred = model.predict(X_test)
    
    r2 = r2_score(y_test, y_pred)
    rmse_abs = np.sqrt(mean_squared_error(y_test, y_pred))
    moyenne_reelle = y_test.mean()
    
    nrmse = (rmse_abs / moyenne_reelle * 100) if moyenne_reelle != 0 else 0

    print(f"{waste:<12} | {r2:5.2f} | ±{rmse_abs:7.2f}% | {moyenne_reelle:9.2f}% | {nrmse:6.1f}% de la moyenne")