import re
import logging
import pandas as pd

from sqlalchemy import create_engine, text
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import linear_kernel
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
from Sastrawi.StopWordRemover.StopWordRemoverFactory import StopWordRemoverFactory
from rapidfuzz import process, fuzz

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
DB_URL = "mysql+pymysql://xxxx:xxxx@localhost:3306/xxxxx"
# ---------------------------------------------------------------------------
# Content weighting  (nama_produk : deskripsi : kategori = 3 : 2 : 1)
# ---------------------------------------------------------------------------
WEIGHT_NAME     = 3
WEIGHT_DESC     = 1
WEIGHT_CATEGORY = 2
MIN_SCORE_THRESHOLD = 0.15

# ---------------------------------------------------------------------------
# Typo correction settings
# ---------------------------------------------------------------------------
TYPO_SIMILARITY_THRESHOLD = 80   # skala 0-100, makin tinggi makin ketat
MIN_WORD_LENGTH_FOR_FUZZY = 3    # kata < 3 huruf skip (biar "hp" ga ke-koreksi aneh)

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
_raw_vocab    = set()   # kamus kata mentah (belum di-stem) buat fuzzy matching



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
    # Fashion
    "sepatu": [
        "sneakers",
        "sneaker",
        "pantofel",
        "boots",
        "boot",
        "loafers",
        "oxford",
    ],

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

    # --- Frasa majemuk (compound) — key HARUS tanpa spasi/separator, ---
    # --- sesuai hasil _protect_compounds(). JANGAN pakai underscore: ---
    # --- Sastrawi stem() mengubah underscore jadi spasi lagi, jadi ------
    # --- proteksinya kebongkar kalau masih ada underscore. -------------
    "kaoskaki": [
        "sock",
        "socks",
        "stocking",
    ],

    "celanadalam": [
        "underwear",
        "cd",
        "dalaman",
    ],

    "bajudalam": [
        "singlet",
        "kaosdalam",
        "undershirt",
    ],

    "sarungtangan": [
        "gloves",
        "glove",
    ],

    "ikatpinggang": [
        "belt",
        "sabuk",
    ],

    "kacamatahitam": [
        "sunglasses",
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

    # --- Frasa majemuk (compound) — key pakai SPASI di sini, karena -----
    # --- lookup-nya terjadi SEBELUM _preprocess (lihat search_products). -
    "kaos kaki": "Fashion",
    "celana dalam": "Fashion",
    "baju dalam": "Fashion",
    "sarung tangan": "Fashion",
    "ikat pinggang": "Fashion",
    "kacamata hitam": "Fashion",

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
# 2c. COMPOUND PHRASE PROTECTION
# ===========================================================================
# Frasa yang kalau dipecah kata-per-kata bakal nyasar ke produk lain.
# Contoh: "kaos kaki" dipecah jadi "kaos" + "kaki" -> nyangkut ke produk
# kaos (t-shirt) yang sama sekali beda karena share token "kaos".
# "celana dalam" malah lebih parah: kata "dalam" itu stopword Indonesia
# yang di-strip sama _stopword_remover, jadi query-nya efektif jadi
# "celana" doang -> nyangkut ke semua produk celana (jeans, cargo, dll).
#
# Solusinya: sebelum tokenisasi TF-IDF, gabungkan frasa ini jadi SATU
# token TANPA spasi/separator (mis. "kaos kaki" -> "kaoskaki") baik di
# query maupun di corpus produk. PENTING: jangan pakai underscore —
# Sastrawi punya kebiasaan mengubah underscore balik jadi spasi lagi
# pas stemming, jadi proteksinya kebongkar lagi di tahap akhir. Tanpa
# separator sama sekali itu yang selamat lolos utuh sampai akhir.
# Karena digabung tanpa spasi, token ini otomatis kebal juga dari
# stopword remover (soalnya "dalam" udah bukan kata yang berdiri
# sendiri lagi begitu masuk sini).
#
# Tambahin frasa baru di sini kalau nemu kasus serupa lagi.
_COMPOUND_TERMS = [
    "kaos kaki",
    "celana dalam",
    "baju dalam",
    "sarung tangan",
    "ikat pinggang",
    "kacamata hitam",
]

# gabungkan juga semua key multi-kata dari QUERY_CATEGORY_MAP secara
# otomatis, biar ga perlu didaftar dua kali
_COMPOUND_TERMS = sorted(
    set(_COMPOUND_TERMS) | {k for k in QUERY_CATEGORY_MAP if " " in k},
    key=len,
    reverse=True,   # frasa terpanjang duluan, biar ga ke-overlap parsial
)

_COMPOUND_PATTERNS = [
    (re.compile(r"\b" + re.escape(phrase) + r"\b"), phrase.replace(" ", ""))
    for phrase in _COMPOUND_TERMS
]


def _protect_compounds(text: str) -> str:
    """Gabungkan frasa majemuk yang dikenal jadi satu token tanpa spasi."""
    for pattern, joined in _COMPOUND_PATTERNS:
        text = pattern.sub(joined, text)
    return text


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
    text = _protect_compounds(text)
    text = _apply_synonyms(text)
    text = _stopword_remover.remove(text)
    text = _stemmer.stem(text)
    return text


# ===========================================================================
# 2b. TYPO CORRECTION (fuzzy matching)
# ===========================================================================
def _build_raw_vocab(df: pd.DataFrame) -> set:
    """
    Bangun kamus kata mentah (belum di-stem) dari:
    - isi nama_produk, deskripsi, kategori di database
    - key & value di _SYNONYMS
    - key di QUERY_CATEGORY_MAP
    Dipakai sebagai referensi buat fuzzy matching, BUKAN buat TF-IDF.
    """
    vocab = set()

    for column in ("nama_produk", "deskripsi", "kategori"):
        for value in df[column].astype(str):
            cleaned = _clean(value)
            vocab.update(cleaned.split())

    for key, synonyms in _SYNONYMS.items():
        vocab.update(key.replace("_", " ").split())
        for syn in synonyms:
            vocab.update(syn.replace("_", " ").split())

    for key in QUERY_CATEGORY_MAP.keys():
        vocab.update(key.split())

    # buang kata terlalu pendek biar ga bikin fuzzy matching ngaco
    vocab = {w for w in vocab if len(w) >= MIN_WORD_LENGTH_FOR_FUZZY}

    logger.info("Vocabulary untuk typo correction: %d kata unik.", len(vocab))
    return vocab


def _correct_typos(query: str) -> str:
    """
    Cek tiap kata di query. Kalau kata tsb tidak ada persis di vocabulary,
    cari kata paling mirip (fuzzy). Kalau similarity-nya >= threshold, ganti.
    Kalau tidak ada yang cukup mirip, biarkan kata aslinya (mungkin memang
    bukan typo, atau kata yang belum dikenal sistem).
    """
    if not _raw_vocab:
        return query

    words = query.lower().split()
    corrected_words = []

    for word in words:
        if word in _raw_vocab or len(word) < MIN_WORD_LENGTH_FOR_FUZZY:
            corrected_words.append(word)
            continue

        match = process.extractOne(word, _raw_vocab, scorer=fuzz.ratio)

        if match and match[1] >= TYPO_SIMILARITY_THRESHOLD:
            corrected_word, score, _ = match
            logger.info(
                "Koreksi typo: '%s' -> '%s' (similarity=%.1f)",
                word, corrected_word, score
            )
            corrected_words.append(corrected_word)
        else:
            corrected_words.append(word)

    return " ".join(corrected_words)


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
    global _df, _vectorizer, _tfidf_matrix, _raw_vocab
    _df                        = _fetch_products()
    corpus                     = _build_corpus(_df)
    _vectorizer, _tfidf_matrix = _build_tfidf(corpus)
    _raw_vocab                 = _build_raw_vocab(_df)
    logger.info("Index selesai dibangun.")


def reload_index() -> None:
    """Dipanggil oleh app.py via endpoint /reload."""
    logger.info("Memulai reload index...")
    _build_index()
    logger.info("Reload index selesai.")


# ===========================================================================
# 5. COSINE SIMILARITY — PUBLIC API
# ===========================================================================
def search_products(query: str, top_n: int = 10, min_score: float = MIN_SCORE_THRESHOLD) -> dict:
    print(f"!!! VERSI BARU JALAN, min_score = {min_score} !!!")  # sementara

    original_query = query
    query = _correct_typos(query)

    if query != original_query.lower():
        logger.info("Query dikoreksi: '%s' -> '%s'", original_query, query)

    query_processed = _preprocess(query)
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

        if score < min_score:
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
        "corrected_from": original_query if query != original_query.lower() else None,
        "results": items,
    }

# ===========================================================================
# Bangun index otomatis saat modul di-import pertama kali
# ===========================================================================
_build_index()