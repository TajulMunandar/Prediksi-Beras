import requests
import pandas as pd
from sklearn.ensemble import RandomForestRegressor
from sklearn.preprocessing import LabelEncoder
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_squared_error, r2_score, mean_absolute_error
import joblib
import numpy as np

# ===========================
# STEP 1: GET DATA FROM LARAVEL
# ===========================
# Ganti dengan URL API Laravel yang sesuai
response = requests.get("http://localhost:8000/api/data-beras")

print(response)
# Pastikan request berhasil
if response.status_code == 200:
    data = response.json()
else:
    print("Gagal mengambil data.")
    exit()

raw_data = response.json()

# ===========================
# STEP 2: CONVERT TO DATAFRAME
# ===========================
# Convert data JSON ke pandas DataFrame
data = []
for item in raw_data:
    data.append(
        {
            "Tahun": item["tahun"],
            "Bulan": item["bulan"],
            "beras_id": item["beras"]["id"],
            "Nama_Beras": item["beras"]["nama_beras"],
            "Hari_Besar": item["hari_besar"],
            "Curah_Hujan": item["curah_hujan"],
            "Suhu": item["suhu"],
            "Kelembaban": item["kelembaban"],
            "Harga": item["harga"],
        }
    )

df = pd.DataFrame(data)

bulan_mapping = {
    1: 1,
    2: 2,
    3: 3,
    4: 4,
    5: 5,
    6: 6,
    7: 7,
    8: 8,
    9: 9,
    10: 10,
    11: 11,
    12: 12,
}
df["Bulan"] = df["Bulan"].map(bulan_mapping)

# Encode nama beras
le = LabelEncoder()
df["Nama_Beras_Encoded"] = le.fit_transform(df["Nama_Beras"])

# Add interaction feature for month and year
df["Bulan_Tahun"] = df["Bulan"] * 100 + df["Tahun"]

# ===========================
# STEP 4: SPLIT DATA
# ===========================
X = df[
    [
        "Tahun",
        "Bulan",
        "Nama_Beras_Encoded",
        "Hari_Besar",
        "Curah_Hujan",
        "Suhu",
        "Kelembaban",
        "Bulan_Tahun",
    ]
]
y = df["Harga"]

X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)

# ===========================
# STEP 5: TRAINING MODEL
# ===========================
model = RandomForestRegressor(n_estimators=100, random_state=42)
model.fit(X_train, y_train)

# ===========================
# STEP 6: EVALUATE
# ===========================
y_pred = model.predict(X_test)

mse = mean_squared_error(y_test, y_pred)
rmse = np.sqrt(mse)
mae = mean_absolute_error(y_test, y_pred)
r2 = r2_score(y_test, y_pred)

# Tampilkan hasil evaluasi
print(f"MAE: {mae}")
print(f"RMSE: {rmse}")
print(f"MSE: {mse}")
print(f"R2 Score: {r2}")

# ===========================
# STEP 7: SAVE THE MODEL
# ===========================
joblib.dump(model, "model_random_forest_beras.pkl")
joblib.dump(le, "label_encoder.pkl")
print("Model saved successfully.")
# ======================
# STEP 7: PREDIKSI PER BULAN
# ======================
features = [
    "Tahun",
    "Bulan",
    "Nama_Beras_Encoded",
    "Hari_Besar",
    "Curah_Hujan",
    "Suhu",
    "Kelembaban",
    "Bulan_Tahun",
]

# Gunakan df yang sudah di-preprocessing
prediksi_bulan_tahun_beras = (
    df.groupby(["Tahun", "Bulan", "Nama_Beras"])
    .apply(lambda group: model.predict(group[features]).mean())
    .reset_index(name="Prediksi_Harga")
)

harga_aktual = (
    df.groupby(["Tahun", "Bulan", "Nama_Beras"])["Harga"]
    .mean()
    .reset_index(name="Harga_Aktual")
)

hasil_akhir = pd.merge(
    prediksi_bulan_tahun_beras, harga_aktual, on=["Tahun", "Bulan", "Nama_Beras"]
)

# ======================
# PRINT PREDIKSI PER BULAN DAN TAHUN
# ======================
print("\n=== PREDIKSI PER BULAN ===")
if not hasil_akhir.empty:
    for index, row in hasil_akhir.iterrows():
        tahun_bulan = f"{row['Tahun']}-{row['Bulan']:02d}"
        nama_beras = row["Nama_Beras"]
        prediksi_harga = int(row["Prediksi_Harga"])
        harga_aktual = int(row["Harga_Aktual"])
        print(
            f"Bulan {tahun_bulan}, Beras '{nama_beras}': "
            f"Prediksi = Rp {prediksi_harga}, Aktual = Rp {harga_aktual}"
        )
else:
    print("Tidak ada data untuk ditampilkan.")

# ======================
# STEP 8: KIRIM DATA EVALUASI KE LARAVEL
# ======================

evaluasi_payload = {
    "mae": mae,
    "mse": mse,
    "rmse": rmse,
    "r2_score": r2,
    "persentase_error": mae / y.mean() * 100,
    "akurasi": 100 - (mae / y.mean() * 100),
}

response_eval = requests.post(
    "http://localhost:8000/api/evaluasi-model", json=evaluasi_payload
)

if response_eval.status_code == 201:
    print("Evaluasi model berhasil disimpan.")
else:
    print("Gagal menyimpan evaluasi model:", response_eval.text)


df_id_mapping = df[["Tahun", "Bulan", "Nama_Beras", "beras_id"]].drop_duplicates()
hasil_akhir = pd.merge(hasil_akhir, df_id_mapping, on=["Tahun", "Bulan", "Nama_Beras"])

# Buat list of dicts
bulk_prediksi_data = [
    {
        "beras_id": row["beras_id"],
        "bulan": int(row["Bulan"]),
        "tahun": int(row["Tahun"]),
        "harga_prediksi": float(row["Prediksi_Harga"]),
        "harga_aktual": float(row["Harga_Aktual"]),
    }
    for _, row in hasil_akhir.iterrows()
]

# Kirim dalam sekali POST
response = requests.post(
    "http://localhost:8000/api/prediksi-bulanan", json=bulk_prediksi_data
)

if response.status_code == 201:
    print("✅ Semua prediksi berhasil dikirim .")
else:
    print("❌ Gagal mengirim batch prediksi:", response.text)
