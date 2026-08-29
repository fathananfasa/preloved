import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

from sklearn.metrics.pairwise import linear_kernel

import recom


# ============================================================
# KONFIGURASI
# ============================================================

QUERY = "sepatu"
TOP_N = 10


# ============================================================
# 1. AMBIL DATA DARI DATABASE
# ============================================================

def tampilkan_data_database():

    print("\n")
    print("=" * 80)
    print("TAHAP 1 - DATA PRODUK DARI DATABASE")
    print("=" * 80)

    df = recom._df

    print(f"\nJumlah produk: {len(df)}")
    print("\nData produk:")

    print(
        df[
            ["id", "nama_produk", "deskripsi", "kategori"]
        ].to_string(index=False)
    )

    # Visualisasi jumlah produk berdasarkan kategori
    kategori_count = df["kategori"].value_counts()

    plt.figure(figsize=(10, 5))
    kategori_count.plot(kind="bar")

    plt.title("Jumlah Produk Berdasarkan Kategori")
    plt.xlabel("Kategori")
    plt.ylabel("Jumlah Produk")
    plt.xticks(rotation=30)
    plt.tight_layout()
    plt.show()


# ============================================================
# 2. PREPROCESSING
# ============================================================

def tampilkan_preprocessing():

    print("\n")
    print("=" * 80)
    print("TAHAP 2 - PREPROCESSING")
    print("=" * 80)

    df = recom._df

    data = []

    for _, row in df.iterrows():

        nama_asli = str(row["nama_produk"])
        deskripsi_asli = str(row["deskripsi"])
        kategori_asli = str(row["kategori"])

        nama_bersih = recom._clean(nama_asli)
        nama_compound = recom._protect_compounds(nama_bersih)
        nama_synonym = recom._apply_synonyms(nama_compound)
        nama_stopword = recom._stopword_remover.remove(nama_synonym)
        nama_stem = recom._stemmer.stem(nama_stopword)

        data.append({
            "Nama Asli": nama_asli,
            "Setelah Cleaning": nama_bersih,
            "Setelah Compound": nama_compound,
            "Setelah Synonym": nama_synonym,
            "Setelah Stopword": nama_stopword,
            "Setelah Stemming": nama_stem
        })

    hasil = pd.DataFrame(data)

    print("\nContoh hasil preprocessing:\n")

    print(
        hasil.head(10).to_string(index=False)
    )

    return hasil


# ============================================================
# 3. CONTENT WEIGHTING
# ============================================================

def tampilkan_content_weighting():

    print("\n")
    print("=" * 80)
    print("TAHAP 3 - CONTENT WEIGHTING")
    print("=" * 80)

    df = recom._df

    print(
        f"\nBobot yang digunakan:"
        f"\nNama Produk = {recom.WEIGHT_NAME}"
        f"\nDeskripsi   = {recom.WEIGHT_DESC}"
        f"\nKategori    = {recom.WEIGHT_CATEGORY}"
    )

    print("\nRumus:")
    print(
        "Dokumen = (Nama × 3) + (Deskripsi × 1) + (Kategori × 2)"
    )

    data = []

    for _, row in df.iterrows():

        nama = recom._preprocess(str(row["nama_produk"]))
        deskripsi = recom._preprocess(str(row["deskripsi"]))
        kategori = recom._preprocess(str(row["kategori"]))

        weighted_nama = (nama + " ") * recom.WEIGHT_NAME
        weighted_deskripsi = (deskripsi + " ") * recom.WEIGHT_DESC
        weighted_kategori = (kategori + " ") * recom.WEIGHT_CATEGORY

        weighted_document = (
            weighted_nama
            + weighted_deskripsi
            + weighted_kategori
        ).strip()

        data.append({
            "Produk": row["nama_produk"],
            "Nama": nama,
            "Nama × 3": weighted_nama.strip(),
            "Deskripsi": deskripsi,
            "Deskripsi × 1": weighted_deskripsi.strip(),
            "Kategori": kategori,
            "Kategori × 2": weighted_kategori.strip(),
            "Weighted Document": weighted_document
        })

    hasil = pd.DataFrame(data)

    print("\nContoh hasil content weighting:\n")

    for i, row in hasil.head(5).iterrows():

        print(f"\nProduk : {row['Produk']}")
        print(f"Nama   : {row['Nama']}")
        print(f"Nama × 3:")
        print(f"  {row['Nama × 3']}")

        print(f"Deskripsi:")
        print(f"  {row['Deskripsi']}")

        print(f"Deskripsi × 1:")
        print(f"  {row['Deskripsi × 1']}")

        print(f"Kategori:")
        print(f"  {row['Kategori']}")

        print(f"Kategori × 2:")
        print(f"  {row['Kategori × 2']}")

        print("\nWeighted Document:")
        print(f"  {row['Weighted Document']}")

    return hasil


# ============================================================
# 4. TF-IDF
# ============================================================

def tampilkan_tfidf():

    print("\n")
    print("=" * 80)
    print("TAHAP 4 - TF-IDF")
    print("=" * 80)

    vectorizer = recom._vectorizer
    matrix = recom._tfidf_matrix

    feature_names = vectorizer.get_feature_names_out()

    print(f"\nJumlah dokumen : {matrix.shape[0]}")
    print(f"Jumlah fitur   : {matrix.shape[1]}")

    print("\nBeberapa vocabulary hasil TF-IDF:")

    print(feature_names[:50])

    # Ambil 10 produk pertama
    jumlah_produk = min(10, matrix.shape[0])

    tfidf_array = matrix[:jumlah_produk].toarray()

    tfidf_df = pd.DataFrame(
        tfidf_array,
        columns=feature_names
    )

    # Ambil fitur dengan nilai terbesar
    print("\nNilai TF-IDF terbesar pada setiap produk:\n")

    for i in range(jumlah_produk):

        row = tfidf_array[i]

        top_indices = np.argsort(row)[::-1][:5]

        print(
            f"\nProduk: {recom._df.iloc[i]['nama_produk']}"
        )

        for idx in top_indices:

            if row[idx] > 0:

                print(
                    f"  {feature_names[idx]} = {row[idx]:.4f}"
                )

    # Visualisasi heatmap sederhana
    fitur_aktif = np.where(
        tfidf_array.sum(axis=0) > 0
    )[0]

    # Batasi fitur agar grafik tidak terlalu penuh
    fitur_aktif = fitur_aktif[:30]

    plt.figure(figsize=(14, 7))

    plt.imshow(
        tfidf_array[:, fitur_aktif],
        aspect="auto"
    )

    plt.colorbar(label="Nilai TF-IDF")

    plt.title("Visualisasi Matriks TF-IDF")
    plt.xlabel("Term")
    plt.ylabel("Produk")

    plt.xticks(
        range(len(fitur_aktif)),
        feature_names[fitur_aktif],
        rotation=90
    )

    plt.yticks(
        range(jumlah_produk),
        recom._df.iloc[:jumlah_produk]["nama_produk"]
    )

    plt.tight_layout()
    plt.show()

    return tfidf_df


# ============================================================
# 5. QUERY
# ============================================================

def tampilkan_query():

    print("\n")
    print("=" * 80)
    print("TAHAP 5 - QUERY PENCARIAN")
    print("=" * 80)

    original_query = QUERY

    print(f"\nQuery asli:")
    print(f"  {original_query}")

    corrected_query = recom._correct_typos(
        original_query
    )

    print(f"\nSetelah typo correction:")
    print(f"  {corrected_query}")

    processed_query = recom._preprocess(
        corrected_query
    )

    print(f"\nSetelah preprocessing:")
    print(f"  {processed_query}")

    return processed_query


# ============================================================
# 6. VEKTOR QUERY
# ============================================================

def tampilkan_query_vector(processed_query):

    print("\n")
    print("=" * 80)
    print("TAHAP 6 - VEKTOR QUERY TF-IDF")
    print("=" * 80)

    vectorizer = recom._vectorizer

    query_vector = vectorizer.transform(
        [processed_query]
    )

    feature_names = vectorizer.get_feature_names_out()

    vector_array = query_vector.toarray()[0]

    print("\nTerm yang memiliki nilai TF-IDF pada query:")

    indices = np.where(vector_array > 0)[0]

    if len(indices) == 0:

        print("Tidak ada term yang ditemukan dalam vocabulary.")

    else:

        for idx in indices:

            print(
                f"  {feature_names[idx]} = "
                f"{vector_array[idx]:.4f}"
            )

    return query_vector


# ============================================================
# 7. COSINE SIMILARITY
# ============================================================

def tampilkan_cosine_similarity(query_vector):

    print("\n")
    print("=" * 80)
    print("TAHAP 7 - COSINE SIMILARITY")
    print("=" * 80)

    scores = linear_kernel(
        query_vector,
        recom._tfidf_matrix
    ).flatten()

    hasil = recom._df[
        ["id", "nama_produk", "kategori"]
    ].copy()

    hasil["Cosine Similarity"] = scores

    hasil = hasil.sort_values(
        by="Cosine Similarity",
        ascending=False
    )

    print("\nHasil cosine similarity:\n")

    print(
        hasil.head(TOP_N).to_string(index=False)
    )

    # Visualisasi ranking
    top = hasil.head(TOP_N).sort_values(
        "Cosine Similarity"
    )

    plt.figure(figsize=(10, 6))

    plt.barh(
        top["nama_produk"],
        top["Cosine Similarity"]
    )

    plt.title(
        f"Cosine Similarity untuk Query: '{QUERY}'"
    )

    plt.xlabel("Cosine Similarity")
    plt.ylabel("Produk")

    plt.tight_layout()
    plt.show()

    return hasil


# ============================================================
# 8. SIMULASI LENGKAP
# ============================================================

def main():

    print("\n")
    print("#" * 80)
    print("# VISUALISASI CONTENT-BASED FILTERING")
    print("#" * 80)

    # Pastikan index sudah dibangun
    if recom._df is None:
        recom._build_index()

    # 1. Database
    tampilkan_data_database()

    # 2. Preprocessing
    tampilkan_preprocessing()

    # 3. Content weighting
    tampilkan_content_weighting()

    # 4. TF-IDF
    tampilkan_tfidf()

    # 5. Query
    processed_query = tampilkan_query()

    # 6. Query vector
    query_vector = tampilkan_query_vector(
        processed_query
    )

    # 7. Cosine similarity
    hasil = tampilkan_cosine_similarity(
        query_vector
    )

    print("\n")
    print("=" * 80)
    print("PROSES SELESAI")
    print("=" * 80)

    return hasil


if __name__ == "__main__":
    main()