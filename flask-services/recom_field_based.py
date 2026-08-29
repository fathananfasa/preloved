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
DB_URL = "mysql+pymysql://root:fathan2502.@localhost:3306/laravel13"
# ---------------------------------------------------------------------------
# Content weighting  (nama_produk : deskripsi : kategori = 3 : 2 : 1)
# ---------------------------------------------------------------------------
WEIGHT_NAME     = 3
WEIGHT_DESC     = 1
WEIGHT_CATEGORY = 2

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
QUERY_EXPANSION = {
    "jaket": [
        "jacket",
        "hoodie",
        "parka",
        "windbreaker",
        "bomber",
    ],

    "hoodie": [
        "hooded sweatshirt",
        "sweater hoodie",
    ],
    
    "sepatu": [
        "sneakers",
        "sneaker",
        "pantofel",
        "boots",
        "boot",
        "loafers",
        "oxford",
    ],
    
    "celana": [
        "jeans",
        "chino",
        "cargo",
        "kulot",
        "jogger",
    ],
    
    "tas": [
        "sling bag",
        "tas selempang",
        "selempang",
        "backpack",
        "ransel",
        "tote bag",
        "clutch",
        "shoulder bag",
    ],
}

_SYNONYMS = {
    # Fashion

    "kaos": [
        "t-shirt",
        "tshirt",
        "tee",
    ],

    "hoodie": [
        "sweater hoodie",
        "hooded sweatshirt",
    ],

    "jaket": [
        "jacket",
        "hoodie",
    ],

    # Elektronik
    "hp": [
        "smartphone",
        "handphone",
        "ponsel",
    ],

    "earphone": [
        "earbuds",
        "airpods",
        "headset",
    ],

    "speaker": [
        "bluetooth speaker",
        "speaker bluetooth",
        "speaker portable",
    ],

    "laptop": [
        "notebook",
        "komputer jinjing",
    ],

    "kamera": [
        "camera",
        "dslr",
    ],

    "mouse": [
        "mouse gaming",
        "gaming mouse",
    ],

    # Rumah Tangga
    "blender": [
        "penghalus makanan",
    ],

    "kipas": [
        "kipas angin",
    ],

    "vacuum": [
        "vacuum cleaner",
        "penyedot debu",
    ],

    "rice cooker": [
        "penanak nasi",
    ],

    "setrika": [
        "setrika listrik",
    ],

    # Olahraga dan Hobi
    "sepak bola": [
        "bola sepak",
        "football",
    ],

    "badminton": [
        "bulu tangkis",
    ],

    "action figure": [
        "figure",
        "figurine",
    ],

    "rubik": [
        "rubik cube",
        "cube puzzle",
    ],

    "skateboard": [
        "papan skate",
    ],
}


QUERY_CATEGORY_MAP = {
    # Fashion
    "sepatu": "Fashion",
    "sneakers": "Fashion",
    "sneaker": "Fashion",
    "pantofel": "Fashion",
    "boots": "Fashion",
    "boot": "Fashion",
    "kaos": "Fashion",
    "baju": "Fashion",
    "hoodie": "Fashion",
    "jaket": "Fashion",
    "celana": "Fashion",
    "jeans": "Fashion",
    "tas": "Fashion",
    "topi": "Fashion",

    # Elektronik
    "hp": "Elektronik",
    "smartphone": "Elektronik",
    "handphone": "Elektronik",
    "ponsel": "Elektronik",
    "earphone": "Elektronik",
    "airpods": "Elektronik",
    "speaker": "Elektronik",
    "kamera": "Elektronik",
    "laptop": "Elektronik",
    "mouse": "Elektronik",

    # Olahraga dan Hobi
    "bola": "Olahraga dan Hobi",
    "sepak bola": "Olahraga dan Hobi",
    "badminton": "Olahraga dan Hobi",
    "raket": "Olahraga dan Hobi",
    "action figure": "Olahraga dan Hobi",
    "rubik": "Olahraga dan Hobi",
    "skateboard": "Olahraga dan Hobi",

    # Rumah Tangga
    "rak": "Rumah Tangga",
    "blender": "Rumah Tangga",
    "kipas": "Rumah Tangga",
    "vacuum": "Rumah Tangga",
    "rice cooker": "Rumah Tangga",
    "setrika": "Rumah Tangga",
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


def _preprocess_document(text):
    text = _clean(text)
    text = _stopword_remover.remove(text)
    text = _stemmer.stem(text)
    return text


def _preprocess_query(text):
    text = _clean(text)
    text = _stopword_remover.remove(text)
    text = _stemmer.stem(text)
    return text

# ===========================================================================
# 3. CONTENT WEIGHTING
# ===========================================================================
def _build_corpus(df: pd.DataFrame) -> pd.Series:
    def combine_row(row):
        nama = _preprocess_document(row["nama_produk"])
        deskripsi = _preprocess_document(str(row["deskripsi"]))
        kategori  = _preprocess_document(str(row["kategori"]))

        weighted = (
            (nama      + " ") * WEIGHT_NAME
            + (deskripsi + " ") * WEIGHT_DESC
            + (kategori  + " ") * WEIGHT_CATEGORY
        ).strip()
        return weighted

    corpus = df.apply(combine_row, axis=1)
    logger.info("Corpus siap — %d dokumen.", len(corpus))
    return corpus


def _expand_query(query):
    query = _clean(query)

    result = []

    for word in query.split():
        result.append(word)

        if word in QUERY_EXPANSION:
            result.extend(QUERY_EXPANSION[word])

    return " ".join(result)

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
    query_processed = _preprocess_query(_expand_query(query))
    query_vec = _vectorizer.transform([query_processed])

    scores = linear_kernel(query_vec, _tfidf_matrix).flatten()

    query_category = QUERY_CATEGORY_MAP.get(query.lower())

    if query_category:
        for idx in range(len(scores)):
            product_category = str(_df.iloc[idx]["kategori"])

            if product_category.lower() == query_category.lower():
                scores[idx] *= 1.2
            else:
                scores[idx] *= 0.5

    top_indices = scores.argsort()[::-1][:top_n]

    items = []

    for idx in top_indices:
        score = float(scores[idx])

        if score == 0:
            continue

        row = _df.iloc[idx]

        items.append({
            "id": int(row["id"]),
            "nama_produk": row["nama_produk"],
            "kategori": row["kategori"],
            "cosine_similarity": round(score, 4),
        })

        logger.info(
            "%s - cosine similarity = %.4f",
            row["nama_produk"],
            score
        )

    return {
        "success": True,
        "query": query,
        "results": items,
    }

# ===========================================================================
# Bangun index otomatis saat modul di-import pertama kali
# ===========================================================================
_build_index()
search_products('sepatu')