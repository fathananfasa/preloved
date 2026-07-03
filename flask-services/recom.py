import re
import logging
import pandas as pd

from sqlalchemy import create_engine, text
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import linear_kernel
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
from Sastrawi.StopWordRemover.StopWordRemoverFactory import StopWordRemoverFactory

# ---------------------------------------------------------------------------
# Logging
# ---------------------------------------------------------------------------
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s",
)
logger = logging.getLogger(__name__)

# ---------------------------------------------------------------------------
# Database configuration
# ---------------------------------------------------------------------------
DB_URL = "mysql+pymysql://root:fathan2502.@127.0.0.1:3306/laravel13"
# ---------------------------------------------------------------------------
# Content weighting  (nama_produk : deskripsi : kategori = 3 : 2 : 1)
# ---------------------------------------------------------------------------
WEIGHT_NAME     = 3
WEIGHT_DESC     = 2
WEIGHT_CATEGORY = 1

# ---------------------------------------------------------------------------
# Sastrawi — inisialisasi sekali, pakai berkali-kali
# ---------------------------------------------------------------------------
_stemmer          = StemmerFactory().create_stemmer()
_stopword_remover = StopWordRemoverFactory().create_stop_word_remover()

# ---------------------------------------------------------------------------
# State global — dibangun saat modul pertama kali di-import
# ---------------------------------------------------------------------------
_engine       = create_engine(DB_URL)
_df           = None
_vectorizer   = None
_tfidf_matrix = None


# ===========================================================================
# 1. AMBIL DATA DARI DATABASE
# ===========================================================================
def _fetch_products() -> pd.DataFrame:
    query = text("""
        SELECT
            p.id,
            p.name        AS nama_produk,
            p.description AS deskripsi,
            c.name        AS kategori
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.status = 'available'
    """)
    with _engine.connect() as conn:
        df = pd.read_sql(query, conn)
    logger.info("Berhasil mengambil %d produk dari database.", len(df))
    return df

#============================================================================
#SYNONYMS
#============================================================================
_SYNONYMS = {
    # ── Pakaian: supertype → subtype ──────────────────────────────────────────
    "baju":      ["kaos", "kemeja", "hoodie", "sweater", "blouse", "atasan"],
    "pakaian":   ["kaos", "kemeja", "hoodie", "jaket", "sweater", "dress", "rok"],
    "atasan":    ["kaos", "kemeja", "blouse", "hoodie", "sweater"],
    # ── Pakaian: spesifik → related (satu arah) ───────────────────────────────
    "hoodie":    ["sweater", "jaket"],      # hoodie → sweater OK, sweater ↛ hoodie
    "sweater":   ["rajut", "hangat"],
    "cardigan":  ["rajut", "sweater"],
    "jaket":     ["outer", "bomber", "coat"],
    "blouse":    ["atasan", "satin"],
    "dress":     ["gaun", "terusan"],
    "rok":       ["skirt"],
    "celana":    ["jeans", "chino", "cargo", "jogger"],
    "kaos":      ["oversize", "polos"],
    "hijab":     ["scarf", "voal", "pashmina"],
    # ── Alas Kaki ─────────────────────────────────────────────────────────────
    "sepatu":    ["sneakers", "boots", "sandal"],
    "sandal":    ["slip on"],
    "sneakers":  ["running shoes"],
    # ── Tas ───────────────────────────────────────────────────────────────────
    "tas":       ["ransel", "selempang", "dompet", "tote bag"],
    "ransel":    ["carrier", "daypack"],
    # ── Elektronik ────────────────────────────────────────────────────────────
    "hp":        ["android", "iphone"],     # one-way: "android" bukan synonym hp
    "laptop":    ["ultrabook", "chromebook"],
    "kamera":    ["mirrorless", "dslr"],
    "headphone": ["earphone"],
    "earbuds":   ["wireless earphone"],
    "speaker":   ["bluetooth speaker", "portable speaker"],
    "tv":        ["smart tv", "layar"],
    "smartwatch": ["jam tangan pintar", "wearable"],
    "charger":   ["cas", "adaptor"],
    "router":    ["wifi", "modem"],
    "printer":   ["inkjet", "laserjet"],
    # ── Rumah Tangga ──────────────────────────────────────────────────────────
    "dapur":     ["masak", "kitchen"],
    "panci":     ["wajan", "penggorengan"],
    "furnitur":  ["meja", "kursi", "lemari", "rak"],
    "meja":      ["desk", "furniture"],
    "kursi":     ["sofa", "furniture"],
    "lemari":    ["rak", "storage"],
    "lampu":     ["led", "lighting"],
    "sprei":     ["bed cover", "bedsheet"],
    "kipas":     ["kipas angin", "fan"],
    # ── Olahraga & Hobi ───────────────────────────────────────────────────────
    "olahraga":  ["fitness", "gym", "running", "sepeda", "renang"],
    "fitness":   ["gym", "dumbbell", "barbel"],
    "camping":   ["tenda", "hiking", "outdoor"],
    "outdoor":   ["camping", "tenda", "hiking"],
    "gitar":     ["akustik", "elektrik", "ukulele"],
    "pancing":   ["joran", "mancing"],
    # ── Style → produk yang biasanya punya label ini ──────────────────────────
    # (one-way: "casual" expand ke produk, produk tidak expand ke "casual")
    "casual":    ["kaos", "jeans", "sneakers", "sandal"],
    "formal":    ["kemeja", "celana bahan", "blazer"],
    "santai":    ["kaos", "jogger", "hoodie"],
    "hangat":    ["hoodie", "sweater", "jaket"],
    "oversize":  ["kaos", "hoodie"],
    "korean":    ["aesthetic", "korea"],
    "vintage":   ["retro", "klasik"],
    # ── Gender → produk (satu arah — cardigan tidak otomatis tarik "wanita") ──
    "wanita":    ["dress", "rok", "blouse", "hijab", "cardigan"],
    "pria":      ["kemeja", "chino", "polo"],
    # ── Material → produk ─────────────────────────────────────────────────────
    "katun":     ["kaos", "kemeja", "sprei"],
    "rajut":     ["sweater", "cardigan"],
    "denim":     ["jeans", "jaket denim"],
}



def _apply_synonyms(text: str) -> str:
    words = text.split()
    result = []
    for word in words:
        result.append(word)  # kata asli tetap disertakan
        if word in _SYNONYMS:
            result.extend(_SYNONYMS[word])  # tambahkan semua synonym
    return " ".join(result)

# ===========================================================================
# 2. PREPROCESSING
# ===========================================================================
def _clean(text: str) -> str:
    text = text.lower()
    text = re.sub(r"[^a-z\s]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()
    return text


def _preprocess(text: str) -> str:
    text = _clean(text)
    text = _stopword_remover.remove(text)
    text = _stemmer.stem(text)
    text = _apply_synonyms(text)
    return text


# ===========================================================================
# 3. CONTENT WEIGHTING
# ===========================================================================
def _build_corpus(df: pd.DataFrame) -> pd.Series:
    def combine_row(row):
        nama      = _preprocess(str(row["nama_produk"]))
        deskripsi = _preprocess(str(row["deskripsi"]))
        kategori  = _preprocess(str(row["kategori"]))

        weighted = (
            (nama      + " ") * WEIGHT_NAME
            + (deskripsi + " ") * WEIGHT_DESC
            + (kategori  + " ") * WEIGHT_CATEGORY
        ).strip()
        return weighted

    corpus = df.apply(combine_row, axis=1)
    logger.info("Corpus siap — %d dokumen.", len(corpus))
    return corpus


# ===========================================================================
# 4. TF-IDF
# ===========================================================================
def _build_tfidf(corpus: pd.Series):
    vectorizer   = TfidfVectorizer()
    tfidf_matrix = vectorizer.fit_transform(corpus)
    logger.info("TF-IDF matrix shape: %s.", tfidf_matrix.shape)
    return vectorizer, tfidf_matrix


# ===========================================================================
# INISIALISASI & RELOAD INDEX
# ===========================================================================
def _build_index() -> None:
    """Bangun (atau rebuild) seluruh index dari database."""
    global _df, _vectorizer, _tfidf_matrix
    _df                      = _fetch_products()
    corpus                   = _build_corpus(_df)
    _vectorizer, _tfidf_matrix = _build_tfidf(corpus)
    logger.info("Index selesai dibangun.")


def reload_index() -> None:
    """Dipanggil oleh app.py via endpoint /reload."""
    logger.info("Memulai reload index...")
    _build_index()
    logger.info("Reload index selesai.")


# ===========================================================================
# 5. COSINE SIMILARITY — PUBLIC API
# ===========================================================================
def search_products(query: str, top_n: int = 10) -> dict:
    """
    Dipanggil oleh app.py via endpoint /search.
    Mengembalikan dict siap jsonify.
    """
    query_processed = _preprocess(query)
    query_vec       = _vectorizer.transform([query_processed])

    # linear_kernel == cosine similarity untuk vektor unit-norm TF-IDF (0.0–1.0)
    scores      = linear_kernel(query_vec, _tfidf_matrix).flatten()
    top_indices = scores.argsort()[::-1][:top_n]

    items = []
    for idx in top_indices:
        score = float(scores[idx])
        if score == 0:
            continue
        row = _df.iloc[idx]
        items.append({
            "id"                : int(row["id"]),
            "nama_produk"       : row["nama_produk"],
            "kategori"          : row["kategori"],
            "cosine_similarity" : round(score, 4),
        })

        # Log ke terminal sesuai ketentuan
        logger.info("%s - cosine similarity = %.4f", row["nama_produk"], score)

    return {
        "success" : True,
        "query"   : query,
        "results" : items,
    }


# ===========================================================================
# Bangun index otomatis saat modul di-import pertama kali
# ===========================================================================
_build_index()