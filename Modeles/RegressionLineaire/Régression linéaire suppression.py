import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.metrics import r2_score, mean_squared_error


df = pd.read_csv("data/public_data_waste_fee.csv")
df.rename(columns={'texile': 'textile'}, inplace=True) 


features = ["pop", "pden", "urb", "gdp", "wage", "roads", "alt", "d_fee", "tc", "area"]
waste_types = ["organic", "paper", "glass", "wood", "metal", "plastic", "raee", "textile", "other"]


print(f"{'DÉCHET':<12} | {'Lignes':<6} | {'R²':<6} | {'RMSE (%)':<10}")
print("-" * 45)

delta = []

for waste in waste_types:
    if waste not in df.columns:
        continue


    colone = features + [waste]
    temp_df = df[colone].dropna()


    delta.append(len(df) - len(temp_df))

    if len(temp_df) < 25:
        continue

    X_current = temp_df[features]
    y_current = temp_df[waste]


    X_train, X_test, y_train, y_test = train_test_split(X_current, y_current, test_size=0.2, random_state=42)


    model = LinearRegression()
    model.fit(X_train, y_train)

    y_pred = model.predict(X_test)


    r2 = r2_score(y_test, y_pred)
    rmse = np.sqrt(mean_squared_error(y_test, y_pred))
    mean_val = y_test.mean()
    rmse_pct = (rmse / mean_val) * 100 if mean_val != 0 else 0

    print(f"[{waste:<10}] | {len(temp_df):<6} | {r2:5.2f} | ±{rmse_pct:6.2f}%")