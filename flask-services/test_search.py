from recom import search_products

def main():
    print("=== TEST SEARCH PRODUCTS (CBF + Typo Correction) ===")
    print("Ketik 'exit' atau 'quit' untuk keluar.\n")

    while True:
        query = input("Masukkan query: ").strip()

        if query.lower() in ("exit", "quit"):
            print("Keluar dari testing.")
            break

        if not query:
            continue

        result = search_products(query)

        print(f"\nQuery asli   : {result['query']}")
        if result.get("corrected_from"):
            print(f"Dikoreksi dari: {result['corrected_from']}")

        if not result["results"]:
            print("Tidak ada hasil ditemukan.\n")
            continue

        print(f"Ditemukan {len(result['results'])} hasil:\n")
        for i, item in enumerate(result["results"], start=1):
            print(f"{i}. {item['nama_produk']} "
                  f"(Kategori: {item['kategori']}, "
                  f"Skor: {item['cosine_similarity']})")
        print()


if __name__ == "__main__":
    main()