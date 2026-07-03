"""
evaluate.py — Evaluasi Precision@K dan Recall@K untuk CBF
==========================================================
Cara pakai:
  1. Isi GROUND_TRUTH di bawah sesuai data produk lo
  2. Jalankan: python evaluate.py
  3. Hasil precision@k dan recall@k per query + rata-rata
"""

import sys
from recom import search_products

# ─── GROUND TRUTH ─────────────────────────────────────────────────────────────
# Format: "query" -> set of product IDs yang dianggap RELEVAN
# Isi ini manual sesuai produk di database lo!
#
# Contoh: kalau query "kamera", produk relevan adalah id 12 dan 7
# Lo bisa cek id produk dari tabel products di DB

GROUND_TRUTH: dict[str, set[int]] = {
    "kamera":    {23},           # ← ganti dengan id produk kamera di DB lo
    "jaket":     {6},
    "sepatu":    {1, 2, 3, 4},
    "laptop":    {14, 15},
    "tas":       {10},
    # tambah query lain sesuai kebutuhan skripsi lo...
}

# ─── Konstanta ────────────────────────────────────────────────────────────────
K_VALUES = [3, 5, 10]   # hitung precision@3, @5, @10 sekaligus


# ─── Metric Functions ─────────────────────────────────────────────────────────
def precision_at_k(retrieved_ids: list[int], relevant_ids: set[int], k: int) -> float:
    """
    Precision@K = |retrieved[:K] ∩ relevant| / K

    Berapa banyak dari K hasil teratas yang benar-benar relevan.
    """
    top_k = retrieved_ids[:k]
    if not top_k:
        return 0.0
    hits = sum(1 for pid in top_k if pid in relevant_ids)
    return hits / k


def recall_at_k(retrieved_ids: list[int], relevant_ids: set[int], k: int) -> float:
    """
    Recall@K = |retrieved[:K] ∩ relevant| / |relevant|

    Berapa banyak produk relevan yang berhasil ditemukan dari K hasil.
    """
    top_k = retrieved_ids[:k]
    if not relevant_ids:
        return 0.0
    hits = sum(1 for pid in top_k if pid in relevant_ids)
    return hits / len(relevant_ids)


def average_precision(retrieved_ids: list[int], relevant_ids: set[int]) -> float:
    """
    Average Precision (AP) — mempertimbangkan urutan ranking.
    Digunakan untuk hitung MAP (Mean Average Precision).
    """
    if not relevant_ids:
        return 0.0
    hits      = 0
    score_sum = 0.0
    for rank, pid in enumerate(retrieved_ids, start=1):
        if pid in relevant_ids:
            hits      += 1
            score_sum += hits / rank
    return score_sum / len(relevant_ids)


# ─── Evaluasi ─────────────────────────────────────────────────────────────────
def evaluate(ground_truth: dict[str, set[int]], k_values: list[int]) -> None:
    max_k = max(k_values)

    # Akumulator per K
    sum_precision = {k: 0.0 for k in k_values}
    sum_recall    = {k: 0.0 for k in k_values}
    sum_ap        = 0.0
    n_queries     = len(ground_truth)

    print("=" * 70)
    print(f"  EVALUASI CBF — Precision@K & Recall@K")
    print("=" * 70)

    for query, relevant_ids in ground_truth.items():
        # Ambil top max_k hasil dari engine
        results      = search_products(query, top_n=max_k, min_score=0.0)
        retrieved_ids = [r["id"] for r in results]

        ap = average_precision(retrieved_ids, relevant_ids)
        sum_ap += ap

        print(f"\nQuery : '{query}'")
        print(f"  Relevan (ground truth) : {sorted(relevant_ids)}")
        print(f"  Retrieved              : {retrieved_ids[:max_k]}")

        for k in k_values:
            p = precision_at_k(retrieved_ids, relevant_ids, k)
            r = recall_at_k(retrieved_ids, relevant_ids, k)
            sum_precision[k] += p
            sum_recall[k]    += r
            print(f"  Precision@{k:<3} = {p:.4f}  |  Recall@{k:<3} = {r:.4f}")

        print(f"  AP (avg precision) = {ap:.4f}")

    # ── Rata-rata keseluruhan ──────────────────────────────────────────────────
    print("\n" + "=" * 70)
    print("  RATA-RATA KESELURUHAN")
    print("=" * 70)
    for k in k_values:
        mp = sum_precision[k] / n_queries
        mr = sum_recall[k]    / n_queries
        print(f"  Mean Precision@{k:<3} = {mp:.4f}  |  Mean Recall@{k:<3} = {mr:.4f}")

    map_score = sum_ap / n_queries
    print(f"  MAP (Mean Avg Precision) = {map_score:.4f}")
    print("=" * 70)

    # ── Tabel ringkas untuk copas ke laporan ──────────────────────────────────
    print("\n  TABEL RINGKAS (copas ke skripsi)")
    print(f"  {'Query':<15}", end="")
    for k in k_values:
        print(f"  P@{k}   R@{k} ", end="")
    print()
    print("  " + "-" * (15 + len(k_values) * 16))

    for query, relevant_ids in ground_truth.items():
        results       = search_products(query, top_n=max_k, min_score=0.0)
        retrieved_ids = [r["id"] for r in results]
        print(f"  {query:<15}", end="")
        for k in k_values:
            p = precision_at_k(retrieved_ids, relevant_ids, k)
            r = recall_at_k(retrieved_ids, relevant_ids, k)
            print(f"  {p:.3f}  {r:.3f} ", end="")
        print()

    print("  " + "-" * (15 + len(k_values) * 16))
    print(f"  {'Rata-rata':<15}", end="")
    for k in k_values:
        mp = sum_precision[k] / n_queries
        mr = sum_recall[k]    / n_queries
        print(f"  {mp:.3f}  {mr:.3f} ", end="")
    print()


# ─── Main ─────────────────────────────────────────────────────────────────────
if __name__ == "__main__":
    if not GROUND_TRUTH:
        print("ERROR: GROUND_TRUTH kosong. Isi dulu sebelum evaluasi.")
        sys.exit(1)

    evaluate(GROUND_TRUTH, K_VALUES)