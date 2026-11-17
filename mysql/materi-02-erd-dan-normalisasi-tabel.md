# Modul 02: Perancangan Basis Data — ERD & Normalisasi Tabel

## 1. Prasyarat, Jembatan Konsep, & Peta Alur

### Prasyarat Awal
- Anda sudah memahami apa itu **basis data relasional (RDBMS)**, tabel, kolom, baris, serta cara membuat database di MySQL (**Modul 01**).

### Jembatan Konsep: Mengapa Kita Perlu Merancang Sebelum Mengetik SQL?
Bayangkan seorang arsitek membangun gedung tanpa menggambar denah terlebih dahulu: tembok bisa roboh atau ruangan menjadi sempit karena salah ukur. Hal yang sama terjadi pada basis data. Jika kita langsung menumpuk semua data transaksi, barang, pelanggan, dan kategori ke dalam **satu tabel besar** seperti di lembar kerja Excel, sistem akan mengalami tiga masalah serius yang disebut **Anomali Data** (*Insert, Update, dan Delete Anomaly*).

Untuk membangun basis data yang rapi, tangguh, dan bebas duplikasi, kita memadukan dua teknik saling melengkapi:
1. **Normalisasi Tabel (`UNF → 1NF → 2NF → 3NF`):** Teknik bedah "dari bawah ke atas" (*bottom-up*) untuk mengurai formulir atau nota mentah yang berantakan menjadi tabel-tabel bersih bebas duplikasi.
2. **ERD (*Entity Relationship Diagram*):** Peta visual "dari atas ke bawah" (*top-down*) yang menggambarkan entitas apa saja yang terlibat serta bagaimana mereka saling terhubung (`1:1`, `1:N`, `M:N`).

```text
[Dokumen / Nota Transaksi Nyata]
        │
        ▼  (Langkah 1: Normalisasi bertahap UNF → 1NF → 2NF → 3NF)
[Tabel-Tabel Bersih Bebas Anomali]
        │
        ▼  (Langkah 2: Pemetaan Diagram ERD & Kardinalitas 1:1 / 1:N / M:N)
[Peta Relasi PK & FK yang Utuh]
        │
        ▼  (Langkah 3: Kamus Data Final)
[Siap Diterjemahkan ke DDL CREATE TABLE & FOREIGN KEY di Modul 03–04]
```

### Tujuan Pembelajaran
Setelah menyelesaikan Modul 02, Anda diharapkan mampu:
1. Mengidentifikasi tiga **Anomali Data** (*Insert*, *Update*, *Delete*) akibat desain satu tabel yang menumpuk.
2. Melakukan **Normalisasi bertahap** (`UNF → 1NF → 2NF → 3NF`) pada studi kasus dokumen transaksi nyata hingga menghasilkan tabel-tabel bersih beserta contoh datanya.
3. Membaca dan menggambar **Diagram ERD** lengkap dengan penentuan **Primary Key (PK)**, **Foreign Key (FK)**, serta kardinalitas **`1:1`**, **`1:N`**, dan pemecahan **`M:N`**.
4. Menyusun **Kamus Data** terstruktur sebagai jembatan langsung menuju pembuatan tabel SQL pada Modul 03.

---

## 2. Kamus Konsep ERD & Aturan Normalisasi

### A. Komponen & Notasi Diagram ERD (*Entity Relationship Diagram*)

| Istilah / Simbol | Arti Sederhana | Contoh pada Sistem Toko |
| :--- | :--- | :--- |
| **Entitas (*Entity*)** | Objek atau hal nyata yang datanya perlu disimpan sebagai satu tabel tersendiri. | `kategori`, `produk`, `pelanggan`, `pesanan` |
| **Atribut (*Attribute*)** | Ciri atau kolom data yang melekat pada sebuah entitas. | `nama_produk`, `harga`, `no_hp`, `tgl_pesan` |
| **`PK` (*Primary Key*)** | Satu kolom (atau gabungan kolom) yang nilainya **unik** dan **tidak boleh kosong** untuk membedakan tiap baris. | `id_produk` pada tabel `produk` |
| **`FK` (*Foreign Key*)** | Kolom "jembatan" yang menyimpan nilai `PK` milik tabel lain agar kedua tabel saling terhubung. | `id_kategori` di dalam tabel `produk` |
| **`Composite Key`** | `PK` gabungan yang dibentuk oleh dua kolom sekaligus karena satu kolom saja belum cukup unik. | Gabungan `(id_pesanan, id_produk)` sebelum diberi ID tersendiri |

### B. Memahami Tiga Jenis Kardinalitas Relasi
Kardinalitas menyatakan **berapa banyak baris** di satu tabel yang boleh berhubungan dengan baris di tabel lain:

| Kardinalitas | Cara Membaca & Contoh Nyata | Aturan Wajib Peletakan Kunci (`PK` / `FK`) |
| :--- | :--- | :--- |
| **`1 : 1`** (*One-to-One*) | Satu baris di A hanya berpasangan dengan **tepat satu** baris di B.<br>*Contoh:* 1 Pelanggan ↔ 1 Profil Akun Login. | `FK` dapat diletakkan di salah satu tabel (biasanya di tabel pelengkap yang opsional) dan diberi aturan unik. |
| **`1 : N`** (*One-to-Many*) | Satu baris di A dapat berhubungan dengan **banyak** baris di B, tetapi 1 baris di B hanya milik 1 baris di A.<br>*Contoh:* 1 `kategori` memiliki banyak `produk`; 1 `pelanggan` bisa membuat banyak `pesanan`. | **Aturan Emas:** `PK` dari tabel pihak **`1`** **SELALU diletakkan sebagai `FK` di tabel pihak `N`** (misal `id_kategori` ditaruh di tabel `produk`, bukan sebaliknya). |
| **`M : N`** (*Many-to-Many*) | Banyak baris di A dapat berhubungan dengan banyak baris di B.<br>*Contoh:* 1 `pesanan` memuat banyak `produk`, dan 1 `produk` bisa dibeli dalam banyak `pesanan`. | **Wajib Dipecah:** RDBMS tidak boleh menyimpan relasi `M:N` langsung dalam 2 tabel. Sisipkan **1 Tabel Penghubung (*Junction Table*)** di tengahnya (misal `detail_pesanan`) sehingga berubah menjadi dua relasi `1 : N`. |

**Notasi Visual Ringkas dalam Modul Ini:**
```text
[Tabel Sisi 1] 1 ──────────< N [Tabel Sisi Banyak]
```
Garis tunggal (`1`) menunjukkan sisi induk (*one*), sedangkan cabang kaki gagak (`< N`) menunjukkan sisi anak (*many*) tempat `Foreign Key` diletakkan.

### C. Empat Tahap Normalisasi Tabel (`UNF → 1NF → 2NF → 3NF`)

| Bentuk Normal | Syarat Utama | Pertanyaan Uji Cepat |
| :--- | :--- | :--- |
| **UNF** (*Unnormalized Form*) | Data mentah apa adanya dari struk/formulir; masih ada daftar berulang atau banyak nilai dalam 1 sel. | Apakah ada 1 kotak sel yang berisi daftar lebih dari satu barang/nilai? |
| **1NF** (*First Normal Form*) | Setiap sel hanya berisi **1 nilai tunggal (*atomic*)**, tidak ada grup kolom berulang (`barang_1`, `barang_2`), dan memiliki kunci unik. | Apakah setiap sel sudah tepat 1 nilai tunggal? |
| **2NF** (*Second Normal Form*) | Sudah memenuhi **1NF** dan bebas **Ketergantungan Parsial** (semua kolom bukan kunci wajib bergantung penuh pada keseluruhan `PK`, bukan separuhnya saja). | Jika `PK` gabungan, adakah kolom yang hanya ikut pada salah satu bagian `PK`? |
| **3NF** (*Third Normal Form*) | Sudah memenuhi **2NF** dan bebas **Ketergantungan Transitif** (kolom bukan kunci tidak boleh bergantung pada kolom bukan kunci lainnya). | Adakah kolom non-kunci yang sebenarnya menjelaskan kolom non-kunci lain (bukan `PK`)? |

---

## 3. Persiapan Skenario Studi Kasus

Dalam modul ini kita belum mengetik perintah SQL ke server; siapkan kertas catatan atau editor teks untuk melatih perancangan pada tiga skema berkelanjutan:

| Jalur | Dokumen Sumber Dunia Nyata | Target Akhir Modul 02 |
| :--- | :--- | :--- |
| **`toko_db`** *(Studi Kasus Utama)* | Struk / Nota Penjualan Toko Ritel Halal *Berkah Mart* | Memahami langkah utuh `UNF → 1NF → 2NF → 3NF`, ERD 5 entitas, & Kamus Data |
| **`akademik_db`** *(Latihan Terapan A)* | Kartu Rencana Studi (KRS) Mahasiswa | Merancang pemecahan relasi `M:N` Mahasiswa–Mata Kuliah + Prodi & Dosen |
| **`logistik_db`** *(Latihan Terapan B)* | Manifest & Riwayat Transit Pengiriman Paket | Merancang relasi `M:N` Paket–Gudang (*riwayat transit berurutan*) |

---

## 4. Studi Kasus Utuh — Merancang `toko_db` dari Struk Mentah Hingga 3NF & ERD

Bayangkan Toko *Berkah Mart* awalnya mencatat transaksi penjualan harian ke dalam **1 tabel besar** di lembar kerja seperti berikut:

### Tahap 0: Bentuk Tidak Ternormalisasi (`UNF`) — Tabel `nota_penjualan_mentah`

| id_pesanan | tgl_pesan | pelanggan | no_hp | barang_dibeli | kategori | ket_kategori |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `ORD-001` | `2026-11-01` | Budi Santoso | `0812111` | Madu Hutan (2 × 75.000), Kurma Ajwa (1 × 90.000) | Herbal, Pangan | Produk Alami, Makanan Sunnah |
| `ORD-002` | `2026-11-01` | Siti Aminah | `0813222` | Minyak Zaitun (1 × 65.000) | Herbal | Produk Alami |
| `ORD-003` | `2026-11-02` | Budi Santoso | `0812111` | Madu Hutan (1 × 75.000) | Herbal | Produk Alami |

**Mengapa Desain Satu Tabel Ini Bermasalah? (Pembuktian 3 Anomali Data):**
1. **Anomali Tambah (*Insert Anomaly*):** Jika toko ingin mendaftarkan produk baru *Sari Kurma* beserta harganya sebelum ada pembeli, data tidak bisa disimpan karena belum ada `id_pesanan` maupun `pelanggan`.
2. **Anomali Ubah (*Update Anomaly*):** Jika nomor HP Budi berubah menjadi `0812999`, kita harus mencari dan mengubahnya di `ORD-001`, `ORD-003`, dan ratusan transaksi Budi lainnya. Jika ada 1 baris terlewat, muncul dua nomor HP berbeda untuk orang yang sama.
3. **Anomali Hapus (*Delete Anomaly*):** Jika transaksi `ORD-002` milik Siti dibatalkan dan barisnya dihapus, maka seluruh catatan tentang produk *Minyak Zaitun* dan pelanggan *Siti Aminah* ikut hilang dari sistem.

---

### Tahap 1: Menuju Bentuk Normal Pertama (`1NF`)
**Aturan yang Diterapkan:** Pecah daftar berulang di kolom `barang_dibeli`, `kategori`, dan `ket_kategori` agar **setiap kotak sel hanya menyimpan 1 nilai tunggal (*atomic*)**, serta pisahkan jumlah dan harga menjadi kolom tersendiri.

**Tabel `nota_1nf`** *(Kunci Gabungan Sementara: `PK = (id_pesanan, id_produk)`)*

| id_pesanan | tgl_pesan | id_pel | nama_pel | no_hp | id_prod | nama_produk | harga_satuan | jumlah | id_kat | nama_kategori | ket_kategori |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :---: | :---: | :---: | :--- | :--- |
| `ORD-001` | `2026-11-01` | `P01` | Budi Santoso | `0812111` | `BRG-1` | Madu Hutan | 75.000 | 2 | `K01` | Herbal | Produk Alami |
| `ORD-001` | `2026-11-01` | `P01` | Budi Santoso | `0812111` | `BRG-2` | Kurma Ajwa | 90.000 | 1 | `K02` | Pangan | Makanan Sunnah |
| `ORD-002` | `2026-11-01` | `P02` | Siti Aminah | `0813222` | `BRG-3` | Minyak Zaitun | 65.000 | 1 | `K01` | Herbal | Produk Alami |
| `ORD-003` | `2026-11-02` | `P01` | Budi Santoso | `0812111` | `BRG-1` | Madu Hutan | 75.000 | 1 | `K01` | Herbal | Produk Alami |

**Evaluasi Hasil 1NF:**
Sekarang setiap sel sudah bersih bernilai tunggal, tetapi **pengulangan data masih terjadi**: nama Budi ditulis berulang untuk setiap barang pada `ORD-001`, dan harga *Madu Hutan* ditulis berulang setiap kali laku.

---

### Tahap 2: Menuju Bentuk Normal Kedua (`2NF`)
**Aturan yang Diterapkan:** Periksa setiap kolom bukan kunci terhadap kunci gabungan `(id_pesanan, id_produk)`. Pisahkan kolom yang hanya bergantung pada **sebagian** kunci saja (*Ketergantungan Parsial*):

1. **Hanya bergantung pada `id_pesanan`:** `tgl_pesan`, `id_pel`, `nama_pel`, `no_hp` → pindahkan ke tabel **`pesanan`**.
2. **Hanya bergantung pada `id_produk`:** `nama_produk`, `harga_katalog`, `id_kat`, `nama_kategori`, `ket_kategori` → pindahkan ke tabel **`produk`**.
3. **Bergantung penuh pada `(id_pesanan, id_produk)`:** `jumlah` dan `harga_saat_beli` → simpan di tabel penghubung **`detail_pesanan`**.

**Hasil Tabel Setelah 2NF:**

*1) Tabel `pesanan` (`PK: id_pesanan`)*

| id_pesanan | tgl_pesan | id_pel | nama_pel | no_hp |
| :--- | :--- | :--- | :--- | :--- |
| `ORD-001` | `2026-11-01` | `P01` | Budi Santoso | `0812111` |
| `ORD-002` | `2026-11-01` | `P02` | Siti Aminah | `0813222` |
| `ORD-003` | `2026-11-02` | `P01` | Budi Santoso | `0812111` |

*2) Tabel `produk` (`PK: id_produk`)*

| id_produk | nama_produk | harga | id_kat | nama_kategori | ket_kategori |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `BRG-1` | Madu Hutan | 75.000 | `K01` | Herbal | Produk Alami |
| `BRG-2` | Kurma Ajwa | 90.000 | `K02` | Pangan | Makanan Sunnah |
| `BRG-3` | Minyak Zaitun | 65.000 | `K01` | Herbal | Produk Alami |

*3) Tabel `detail_pesanan` (`PK: id_detail`, merujuk `id_pesanan` & `id_produk`)*

| id_detail | id_pesanan | id_produk | jumlah | harga_saat_beli |
| :---: | :--- | :--- | :---: | :---: |
| `1` | `ORD-001` | `BRG-1` | 2 | 75.000 |
| `2` | `ORD-001` | `BRG-2` | 1 | 90.000 |
| `3` | `ORD-002` | `BRG-3` | 1 | 65.000 |
| `4` | `ORD-003` | `BRG-1` | 1 | 75.000 |

> **Catatan Arsitektur Penting:** Mengapa kolom `harga_saat_beli` tetap disimpan di `detail_pesanan` meskipun `harga` sudah ada di `produk`? Karena harga katalog produk dapat naik bulan depan, sedangkan **nominal pada struk penjualan masa lalu tidak boleh ikut berubah**. Menyimpan nilai historis transaksi bukanlah duplikasi, melainkan kebutuhan integritas akuntansi.

**Evaluasi Hasil 2NF:**
Ketergantungan parsial sudah hilang, namun lihat baik-baik dua masalah yang tersisa (*Ketergantungan Transitif*):
- Di tabel `pesanan`, nama dan nomor HP *Budi Santoso* bergantung pada `id_pel`, bukan pada `id_pesanan`, sehingga tetap berulang setiap Budi belanja.
- Di tabel `produk`, `nama_kategori` dan `ket_kategori` bergantung pada `id_kat`, bukan pada `id_produk`, sehingga tulisan *Herbal / Produk Alami* tetap berulang di `BRG-1` dan `BRG-3`.

---

### Tahap 3: Menuju Bentuk Normal Ketiga (`3NF`)
**Aturan yang Diterapkan:** Keluarkan setiap atribut yang bergantung pada kolom non-kunci (*Ketergantungan Transitif*) menjadi entitas mandiri!
- `id_pesanan → id_pel → (nama_pel, no_hp)` ⇒ Lahir tabel baru **`pelanggan`**, sedangkan tabel `pesanan` cukup menyimpan **`id_pel` sebagai `FK`**.
- `id_produk → id_kat → (nama_kategori, ket_kategori)` ⇒ Lahir tabel baru **`kategori`**, sedangkan tabel `produk` cukup menyimpan **`id_kat` sebagai `FK`**.

**Hasil Akhir 5 Tabel Bersih (`3NF`):**

*1) `kategori` (`PK: id_kategori`)*

| id_kategori | nama_kategori | keterangan |
| :--- | :--- | :--- |
| `K01` | Herbal | Produk Alami |
| `K02` | Pangan | Makanan Sunnah |

*2) `produk` (`PK: id_produk`, `FK: id_kategori`)*

| id_produk | nama_produk | harga | id_kategori *(FK)* |
| :--- | :--- | :---: | :--- |
| `BRG-1` | Madu Hutan | 75.000 | `K01` |
| `BRG-2` | Kurma Ajwa | 90.000 | `K02` |
| `BRG-3` | Minyak Zaitun | 65.000 | `K01` |

*3) `pelanggan` (`PK: id_pelanggan`)*

| id_pelanggan | nama_pelanggan | no_hp |
| :--- | :--- | :--- |
| `P01` | Budi Santoso | `0812111` |
| `P02` | Siti Aminah | `0813222` |

*4) `pesanan` (`PK: id_pesanan`, `FK: id_pelanggan`)*

| id_pesanan | tgl_pesan | id_pelanggan *(FK)* |
| :--- | :--- | :--- |
| `ORD-001` | `2026-11-01` | `P01` |
| `ORD-002` | `2026-11-01` | `P02` |
| `ORD-003` | `2026-11-02` | `P01` |

*5) `detail_pesanan` (`PK: id_detail`, `FK: id_pesanan, id_produk`)* — sama seperti hasil 2NF di atas.

**Uji Bukti Teratasinya 3 Anomali:**
- Ingin menambah produk *Sari Kurma*? Cukup sisipkan 1 baris ke `produk` tanpa harus ada transaksi (*Insert Anomaly* hilang).
- Budi ganti nomor HP? Ubah **1 baris saja** di `pelanggan` (`P01`), seluruh transaksi Budi otomatis merujuk ke data terbaru (*Update Anomaly* hilang).
- `ORD-002` dihapus? Produk `Minyak Zaitun` dan pelanggan `Siti Aminah` tetap aman tersimpan di tabel induknya masing-masing (*Delete Anomaly* hilang).

---

### Tahap 4: Memetakan ke Diagram ERD & Kamus Data Final `toko_db`

Sekarang perhatikan bahwa hasil akhir Normalisasi 3NF di atas **sama persis** dengan pemodelan ERD berikut:
- `kategori` **(1) ──< (N)** `produk`: Satu kategori menampung banyak produk (`id_kategori` menjadi `FK` di `produk`).
- `pelanggan` **(1) ──< (N)** `pesanan`: Satu pelanggan bisa membuat banyak pesanan (`id_pelanggan` menjadi `FK` di `pesanan`).
- `pesanan` **(M) ── (N)** `produk` dipecah melalui **`detail_pesanan`** menjadi:
  `pesanan` **(1) ──< (N)** `detail_pesanan` **(N) >── (1)** `produk`.

```text
+------------------+          +-------------------+          +--------------------+
|     kategori     |          |      produk       |          |   detail_pesanan   |
+------------------+ 1      N +-------------------+ 1      N +--------------------+
| PK id_kategori   |---------<| PK id_produk      |---------<| PK id_detail      |
|    nama_kategori |          | FK id_kategori    |          | FK id_pesanan     |
|    keterangan    |          |    kode_sku       |          | FK id_produk      |
+------------------+          |    nama_produk    |          |    jumlah          |
                              |    harga          |          |    harga_saat_beli |
                              |    stok           |          +--------------------+
                              +-------------------+                    ▲ N
                                                                       │
                              +-------------------+          +---------+----------+
                              |     pelanggan     | 1      N |      pesanan       |
                              +-------------------+---------<+--------------------+
                              | PK id_pelanggan   |          | PK id_pesanan      |
                              |    nama_pelanggan |          | FK id_pelanggan    |
                              |    no_hp          |          |    tgl_pesan       |
                              +-------------------+          +--------------------+
```

**Ringkasan Kamus Data Rancangan Final (Bekal DDL Modul 03–04):**

| Entitas (Tabel) | Primary Key (PK) | Foreign Key (FK) | Kolom Inti Lainnya |
| :--- | :--- | :--- | :--- |
| `kategori` | `id_kategori` | — | `nama_kategori`, `keterangan` |
| `produk` | `id_produk` | `id_kategori` → `kategori` | `kode_sku`, `nama_produk`, `harga`, `stok`, `tanggal_kadaluwarsa`, `dibuat_pada` |
| `pelanggan` | `id_pelanggan` | — | `nama_pelanggan`, `no_hp` |
| `pesanan` | `id_pesanan` | `id_pelanggan` → `pelanggan` | `tgl_pesan` |
| `detail_pesanan` | `id_detail` | `id_pesanan` → `pesanan`<br>`id_produk` → `produk` | `jumlah`, `harga_saat_beli` |

---

## 5. Verifikasi Desain & Kendala Umum Perancangan

### Daftar Periksa Kelayakan Rancangan (Uji sebelum Menulis DDL)
1. Apakah **setiap tabel** sudah memiliki tepat satu `Primary Key` yang stabil (bukan teks nama atau nomor HP yang mudah berubah)?
2. Apakah masih ada sel yang berisi **daftar banyak nilai** (misal `"Madu, Kurma"`) atau kolom berulang (`barang1`, `barang2`)? Jika ya, tabel belum lolos `1NF`.
3. Apakah semua **relasi `M:N`** sudah dipecah dengan menyisipkan tabel penghubung di antara keduanya?
4. Apakah **Foreign Key (`FK`)** selalu berada di tabel sisi **`N` (Banyak)**, bukan terbalik di tabel sisi `1`?

### Kendala Umum Perancangan & Solusinya

| Kesalahan Rancangan yang Sering Terjadi | Dampak pada Basis Data | Solusi Tepat |
| :--- | :--- | :--- |
| Meletakkan `id_produk` di tabel `kategori` untuk relasi Kategori–Produk | 1 kategori hanya bisa memuat 1 produk saja; relasi `1:N` gagal total. | Pindahkan `id_kategori` sebagai `FK` ke dalam tabel `produk` (pihak **`N`**). |
| Menghubungkan `pesanan` dan `produk` langsung tanpa `detail_pesanan` | Tidak dapat mencatat lebih dari 1 jenis barang per nota atau menyimpan `jumlah` beli per barang. | Pecah relasi `M:N` menggunakan tabel penghubung `detail_pesanan`. |
| Memakai `nama_pelanggan` atau `email` sebagai `PRIMARY KEY` | Saat pelanggan mengubah nama/email, seluruh tabel transaksi harus ikut diubah. | Gunakan ID angka buatan (`id_pelanggan`) sebagai `PK` yang permanen. |
| Menaruh kolom `nama_kategori` langsung di tabel `produk` | Jika nama kategori diubah, puluhan baris produk harus diperbarui satu per satu (*Update Anomaly*). | Pisahkan ke tabel `kategori` (`3NF`), lalu hubungkan memakai `id_kategori`. |

---

## 6. Latihan Soal

*Perhatikan tabel-tabel data acuan kasus berikut sebelum mengerjakan latihan soal:*

#### Tabel: kulakan_supplier (Data Mentah Kasus Toko)

| id_nota | tgl_masuk | nama_supplier | kota_supplier | daftar_barang |
| :---: | :---: | :--- | :--- | :--- |
| N-01 | 2026-11-01 | CV Berkah Alam | Solo | Madu Hutan (10 dus), Habbatussauda (5 dus) |
| N-02 | 2026-11-02 | PT Barokah Herbal | Yogyakarta | Minyak Zaitun (20 botol) |
| N-03 | 2026-11-03 | CV Berkah Alam | Solo | Sari Kurma (15 botol) |

#### Tabel: krs_mentah (Data Mentah Kasus Akademik)

| nim | nama_mhs | kode_prodi | nama_prodi | semester | daftar_matkul_diambil |
| :---: | :--- | :---: | :--- | :---: | :--- |
| 2401001 | Ahmad Fauzi | IF | Informatika | 2026-1 | IF101 - Basis Data (3 SKS, Dr. Hasan); IF102 - Algoritma (3 SKS, Bu Rina) |
| 2401002 | Zahra Aulia | IF | Informatika | 2026-1 | IF101 - Basis Data (3 SKS, Dr. Hasan) |

#### Tabel: manifest_transit (Data Mentah Kasus Logistik)

| id_manifest | no_resi | kota_asal | kota_tujuan | daftar_gudang_singgah |
| :---: | :---: | :--- | :--- | :--- |
| M-101 | RESI-901 | Jakarta | Surabaya | G01 - Hub Cengkareng; G02 - Hub Semarang |
| M-102 | RESI-902 | Bandung | Malang | G03 - Hub Pasteur; G02 - Hub Semarang; G04 - Hub Surabaya |

---

#### Daftar Tugas Praktik
1. Tulis analisis identifikasi *Insert Anomaly* pada tabel `kulakan_supplier` bila toko ingin mencatat data supplier baru sebelum ada transaksi pasokan barang.
2. Tulis analisis identifikasi *Update Anomaly* pada tabel `kulakan_supplier` bila supplier `CV Berkah Alam` pindah alamat kota.
3. Tulis analisis identifikasi *Delete Anomaly* pada tabel `kulakan_supplier` bila transaksi nota `N-02` dibatalkan dan dihapus.
4. Tulis rancangan kardinalitas relasi (`1:1`, `1:N`, atau `M:N`) dan tentukan letak kolom `Foreign Key` untuk menghubungkan entitas `pesanan` dengan entitas baru `metode_pembayaran`.
5. Tulis evaluasi ketergantungan transitif (*3NF*) pada rancangan tabel `gudang_cabang (id_gudang, nama_gudang, kode_pos, nama_kota, nama_provinsi)` dan berikan dekomposisi perbaikannya.
6. Tulis hasil dekomposisi tabel bentuk normal pertama (**1NF**) dari tabel `krs_mentah` sehingga setiap sel bernilai tunggal (*atomic*).
7. Tulis hasil dekomposisi tabel bentuk normal kedua (**2NF**) dan ketiga (**3NF**) untuk skema akademik kampus (`akademik_db`) beserta penentuan `Primary Key` dan `Foreign Key` pada setiap tabel.
8. Tulis rancangan diagram relasi dan kamus data untuk memecah relasi banyak-ke-banyak (*Many-to-Many*) antara tabel `paket_kiriman` dan `gudang` pada `logistik_db` menggunakan tabel penghubung `riwayat_transit`.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Gunakan petunjuk ringkas berikut untuk mencocokkan hasil rancangan latihan soal Anda secara mandiri:*

### Indikator Keberhasilan Rancangan (Checklist Hasil Soal 1–8)
- **No. 1–3:** Teridentifikasi jelas kegagalan simpan supplier baru tanpa nota (*Insert*), keharusan menyunting banyak baris saat kota berubah (*Update*), serta hilangnya data master supplier saat nota dihapus (*Delete*).
- **No. 4:** Kardinalitas relasi adalah **`1 : N`** (`1` metode pembayaran untuk banyak pesanan), sehingga `id_metode` (`FK`) wajib berada di tabel `pesanan`.
- **No. 5:** Teridentifikasi ketergantungan transitif (`kode_pos → kota, provinsi`), perbaikannya adalah memisahkan referensi wilayah ke tabel master tersendiri.
- **No. 6:** Kolom `daftar_matkul_diambil` dipecah menjadi baris-baris atomik individual berulang.
- **No. 7:** Terbentuk minimal 5 tabel bersih: `program_studi`, `mahasiswa`, `dosen`, `mata_kuliah`, dan `krs_detail` (`3NF`).
- **No. 8:** Terbentuk tabel penghubung `riwayat_transit` dengan kolom `id_transit (PK)`, `id_paket (FK)`, `id_gudang (FK)`, `waktu_tiba`, dan `status_transit`.

### Checklist Kesiapan Desain
- [ ] Seluruh entitas memiliki tepat satu `Primary Key` yang unik dan stabil.
- [ ] Tidak ada relasi `M:N` yang dibiarkan tanpa tabel penghubung (*junction table*).
- [ ] Seluruh kolom `Foreign Key` diletakkan di tabel sisi banyak (`N`).

> [!NOTE]
> **Akses Kunci Jawaban Lengkap:**  
> Kunci jawaban rancangan dekomposisi tabel, kamus data lengkap, dan pembahasan teoritis analitis untuk setiap butir soal dihimpun secara terpusat pada dokumen pegangan instruktur: [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md#modul-02-perancangan-basis-data-erd--normalisasi-tabel).

---

## 8. Ringkasan Konsep & Refleksi Pemahaman

### Ringkasan Cepat Modul 02

| Konsep | Inti Praktis yang Harus Diingat |
| :--- | :--- |
| **`1NF`** | 1 sel = 1 nilai tunggal (*atomic*), tanpa daftar dipisah koma atau kolom berulang. |
| **`2NF`** | Bebas ketergantungan parsial: semua atribut non-kunci bergantung penuh pada seluruh `PK`. |
| **`3NF`** | Bebas ketergantungan transitif: kolom non-kunci hanya bergantung langsung pada `PK`. |
| **Relasi `1 : N`** | `PK` dari tabel sisi `1` diletakkan sebagai `FK` di tabel sisi `N`. |
| **Relasi `M : N`** | Wajib dipecah dengan **1 Tabel Penghubung** (*Junction Table*) berisi `FK` dari kedua tabel induk. |

### Refleksi Pemahaman
1. Mengapa cara normalisasi *bottom-up* (`UNF → 1NF → 2NF → 3NF`) dan pemetaan *top-down* (`ERD`) pada akhirnya selalu menghasilkan susunan tabel yang sama?
2. Setelah Diagram ERD dan **Kamus Data** untuk `toko_db`, `akademik_db`, dan `logistik_db` tersusun rapi pada modul ini, langkah apa yang kita lakukan berikutnya untuk mewujudkan entitas-entitas tersebut di MySQL? *(Kita akan menerjemahkan Kamus Data tersebut ke perintah SQL pada **Modul 03: Tipe Data Inti & DDL Pembuatan Tabel**).*
