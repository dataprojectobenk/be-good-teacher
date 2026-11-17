# MATEMATIKA SMA (JURUSAN IPA & PESANTREN)
## Kelas 11 - Semester Ganjil
### Pertemuan 9: Transformasi Geometri (Translasi dan Refleksi)

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Selesaikan 2 soal pemanasan pengingat sistem koordinat Kartesius berikut:

1. Tentukan koordinat titik baru jika titik $P(2, -5)$ digeser sejauh $3$ satuan ke kanan dan $2$ satuan ke atas!
2. Jika titik $A(4, 7)$ dicerminkan terhadap sumbu-$X$, manakah nilai koordinat yang berubah tanda? Tuliskan koordinat bayangannya!

*(Kunci Cek Mandiri: No. 1 = $(5, -3)$; No. 2 = Nilai $y$ berubah tanda menjadi negatif, bayangan $A'(4, -7)$)*

---

#### B. Pengantar & Konsep Dasar

Dalam ilmu ukur bidang (geometri analitik), **transformasi geometri** adalah aturan pemindahan posisi setiap titik pada bidang datar ke posisi yang baru.
- Titik mula-mula dinamakan **titik asal**: dinotasikan dengan $A(x, y)$.
- Titik hasil pemindahan dinamakan **titik bayangan**: dinotasikan dengan $A'(x', y')$.

Allah SWT menciptakan alam semesta ini dengan keteraturan ukuran, proporsi, dan keseimbangan yang sempurna:

> *"Dan segala sesuatu pada sisi-Nya ada ukuran dan ketentuannya."* (QS. Ar-Ra'd: 8)

Dua bentuk perpindahan posisi yang paling mendasar adalah:
1. **Translasi (Pergeseran):** Memindahkan posisi suatu benda dengan jarak dan arah tertentu tanpa mengubah bentuk, ukuran, maupun orientasi hadap benda tersebut.
   - *Analogi nyata:* Gerakan barisan shaf jamaah sholat yang maju bersamaan 1 meter ke depan. Posisi berpindah, namun jarak antarjamaah dan orientasi hadap tetap sama.
2. **Refleksi (Pencerminan):** Memindahkan setiap titik pada bangun geometri dengan sifat cermin datar. Jarak titik asal ke cermin selalu sama dengan jarak titik bayangan ke cermin, dengan arah yang saling berhadapan simetris.

Kedua jenis transformasi ini termasuk ke dalam **transformasi isometrik**, yaitu transformasi yang **mempertahankan jarak dan bentuk asli**. Bangun hasil bayangannya selalu **kongruen** (sama dan sebangun) dengan bangun aslinya.

---

### 2. Bentuk Umum & Rumus Baku

Misalkan titik mula-mula adalah $A(x, y)$ dan bayangannya adalah $A'(x', y')$.

#### A. Rumus Baku Translasi (Pergeseran)

Translasi ditentukan oleh komponen geser $T = \begin{pmatrix} a \\ b \end{pmatrix}$, dengan:
- $a$ = pergeseran mendatar (positif ke kanan, negatif ke kiri)
- $b$ = pergeseran tegak (positif ke atas, negatif ke bawah)

**1. Pemetaan Titik:**

$$
x' = x + a
$$

$$
y' = y + b
$$

Atau dalam bentuk matriks:

$$
\begin{pmatrix} x' \\ y' \end{pmatrix} = \begin{pmatrix} x \\ y \end{pmatrix} + \begin{pmatrix} a \\ b \end{pmatrix}
$$

**2. Pemetaan Persamaan Garis / Kurva:**  
Untuk mencari bayangan garis $f(x, y) = 0$, ubah rumus menjadi bentuk variabel asal:

$$
x = x' - a
$$

$$
y = y' - b
$$

Lalu substitusikan $x$ dan $y$ tersebut ke dalam persamaan garis semula.

---

#### B. Rumus Baku Refleksi (Pencerminan)

Tabel berikut memuat 7 jenis cermin dasar yang sering digunakan. Cukup perhatikan **aturan perubahan koordinat** untuk menghitung bayangan secara cepat:

| No. | Jenis Cermin | Hubungan Titik Asal $\to$ Bayangan | Aturan Praktis Ingatan |
| :---: | :--- | :---: | :--- |
| 1 | **Sumbu-$X$** | $(x, y) \to (x, -y)$ | Nilai $x$ tetap, nilai $y$ ganti tanda |
| 2 | **Sumbu-$Y$** | $(x, y) \to (-x, y)$ | Nilai $y$ tetap, nilai $x$ ganti tanda |
| 3 | **Titik Asal $O(0, 0)$** | $(x, y) \to (-x, -y)$ | Kedua koordinat berganti tanda |
| 4 | **Garis $y = x$** | $(x, y) \to (y, x)$ | Tukar posisi $x$ dan $y$ |
| 5 | **Garis $y = -x$** | $(x, y) \to (-y, -x)$ | Tukar posisi dan dua-duanya balik tanda |
| 6 | **Garis Tegak $x = h$** | $(x, y) \to (2h - x, \ y)$ | Nilai $x' = 2h - x$, nilai $y$ tetap |
| 7 | **Garis Datar $y = k$** | $(x, y) \to (x, \ 2k - y)$ | Nilai $x$ tetap, nilai $y' = 2k - y$ |

---

### 3. Sifat-Sifat & Aturan Penting

1. **Mempertahankan Ukuran (Sifat Isometri):**  
   Panjang ruas garis, besar sudut, keliling, dan luas bidang setelah ditranslasikan atau direfleksikan **tidak mengalami perubahan**.
2. **Sifat Jarak Cermin:**  
   Pada refleksi, garis yang menghubungkan titik asal dengan titik bayangannya selalu tegak lurus dengan cermin, dan cermin berada tepat di tengah-tengah antara titik asal dan bayangan.
3. **Pengulangan Dua Kali Cermin yang Sama:**  
   Jika sebuah titik dicerminkan terhadap cermin yang sama sebanyak dua kali, maka titik bayangan akhir akan **kembali ke posisi semula**:

$$
(A')' = A
$$

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

Proses penyelesaian soal dituliskan secara **menurun baris per baris** agar mudah dipahami alur perhitungannya.

---

#### Contoh 1 (Tingkat Dasar: Translasi Titik dan Garis Lurus)

**Soal A:**  
Tentukan koordinat bayangan dari titik $A(3, -2)$ setelah digeser oleh translasi $T = \begin{pmatrix} -1 \\ 4 \end{pmatrix}$!

**Penyelesaian:**
- **Diketahui:**  
  Titik asal: $x = 3$ dan $y = -2$  
  Komponen pergeseran: $a = -1$ dan $b = 4$
- **Ditanyakan:** Koordinat bayangan $A'(x', y')$.
- **Langkah Penyelesaian (Menurun):**

Menghitung koordinat $x'$:
$$
\begin{aligned}
x' &= x + a \\
   &= 3 + (-1) \\
   &= 3 - 1 \\
   &= 2
\end{aligned}
$$

Menghitung koordinat $y'$:
$$
\begin{aligned}
y' &= y + b \\
   &= -2 + 4 \\
   &= 2
\end{aligned}
$$

- **Kesimpulan:**  
  Koordinat bayangan titik $A$ adalah $A'(2, 2)$ ($\checkmark\ \text{Benar}$).

---

**Soal B:**  
Tentukan persamaan bayangan dari garis lurus $2x + y - 5 = 0$ oleh translasi $T = \begin{pmatrix} 3 \\ -2 \end{pmatrix}$!

**Penyelesaian:**
- **Diketahui:**  
  Persamaan garis asal: $2x + y - 5 = 0$  
  Vektor translasi: $a = 3$ dan $b = -2$
- **Ditanyakan:** Persamaan garis bayangan.
- **Langkah Penyelesaian (Menurun):**

*Langkah 1: Nyatakan $x$ dan $y$ dalam bentuk $x'$ dan $y'$:*
$$
\begin{aligned}
x' = x + 3 &\implies x = x' - 3 \\
y' = y - 2 &\implies y = y' + 2
\end{aligned}
$$

*Langkah 2: Substitusikan ke persamaan garis semula:*
$$
\begin{aligned}
2x + y - 5 &= 0 \\
2(x' - 3) + (y' + 2) - 5 &= 0 \\
2x' - 6 + y' + 2 - 5 &= 0 \\
2x' + y' - 6 + 2 - 5 &= 0 \\
2x' + y' - 9 &= 0
\end{aligned}
$$

*Langkah 3: Tuliskan kembali tanpa tanda aksen:*
$$
2x + y - 9 = 0
$$

- **Kesimpulan:**  
  Persamaan garis bayangannya adalah $2x + y - 9 = 0$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Penguatan Konsep: Refleksi Satu Titik ke Berbagai Cermin)

Untuk memahami pola dasar pencerminan dengan mudah, perhatikan titik $P(4, -3)$ yang dicerminkan terhadap beberapa cermin berikut.

**Soal:**  
Tentukan koordinat bayangan titik $P(4, -3)$ jika dicerminkan terhadap:
1. Sumbu-$X$
2. Sumbu-$Y$
3. Garis $y = x$
4. Garis tegak $x = 1$

**Penyelesaian:**
- **Diketahui:** Titik asal $P(x, y) = P(4, -3)$, artinya $x = 4$ dan $y = -3$.

**1. Pencerminan terhadap Sumbu-$X$:**  
Aturan: Nilai $x$ tetap, nilai $y$ dikalikan $-1$.
$$
\begin{aligned}
x' &= x = 4 \\
y' &= -y = -(-3) = 3
\end{aligned}
$$
Bayangan: $P_1'(4, 3)$ ($\checkmark\ \text{Benar}$)

**2. Pencerminan terhadap Sumbu-$Y$:**  
Aturan: Nilai $x$ dikalikan $-1$, nilai $y$ tetap.
$$
\begin{aligned}
x' &= -x = -(4) = -4 \\
y' &= y = -3
\end{aligned}
$$
Bayangan: $P_2'(-4, -3)$ ($\checkmark\ \text{Benar}$)

**3. Pencerminan terhadap Garis $y = x$:**  
Aturan: Tukar posisi antara $x$ dan $y$.
$$
\begin{aligned}
x' &= y = -3 \\
y' &= x = 4
\end{aligned}
$$
Bayangan: $P_3'(-3, 4)$ ($\checkmark\ \text{Benar}$)

**4. Pencerminan terhadap Garis Tegak $x = 1$:**  
Aturan: Gunakan $x' = 2h - x$ dengan $h = 1$, sedangkan nilai $y$ tetap.
$$
\begin{aligned}
x' &= 2h - x \\
   &= 2(1) - 4 \\
   &= 2 - 4 \\
   &= -2
\end{aligned}
$$
$$
y' = y = -3
$$
Bayangan: $P_4'(-2, -3)$ ($\checkmark\ \text{Benar}$)

---

#### Contoh 3 (Tingkat Menengah: Refleksi Garis Lurus)

**Soal:**  
Tentukan persamaan bayangan dari garis $3x - 2y + 6 = 0$ jika dicerminkan terhadap sumbu-$X$!

**Penyelesaian:**
- **Diketahui:** Garis $3x - 2y + 6 = 0$ dicerminkan terhadap sumbu-$X$.
- **Ditanyakan:** Persamaan garis bayangan.
- **Langkah Penyelesaian (Menurun):**

*Langkah 1: Tuliskan hubungan koordinat pencerminan sumbu-$X$:*
$$
\begin{aligned}
x' = x &\implies x = x' \\
y' = -y &\implies y = -y'
\end{aligned}
$$

*Langkah 2: Substitusikan ke persamaan garis awal:*
$$
\begin{aligned}
3x - 2y + 6 &= 0 \\
3(x') - 2(-y') + 6 &= 0 \\
3x' + 2y' + 6 &= 0
\end{aligned}
$$

*Langkah 3: Tuliskan persamaan akhir tanpa tanda aksen:*
$$
3x + 2y + 6 = 0
$$

- **Kesimpulan:**  
  Persamaan garis bayangannya adalah $3x + 2y + 6 = 0$ ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual Santri & Kehidupan Nyata

#### Penataan Shaf Sholat Aula Pesantren

Pengurus takmir mushola santri sedang mengatur posisi karpet sajadah shaf sholat di aula asrama. Pada denah lantai (dalam satuan meter), ujung kiri sajadah berada di titik $A(1, 2)$ dan ujung kanan sajadah berada di titik $B(6, 2)$.

Untuk merapikan shaf jamaah, karpet tersebut digeser:
- $3\text{ meter}$ maju ke arah kiblat (searah sumbu-$Y$ positif)
- $2\text{ meter}$ ke arah kanan (searah sumbu-$X$ positif)

1. Tentukan vektor translasi $T$ pergeseran karpet!
2. Tentukan koordinat baru ujung kiri ($A'$) dan ujung kanan ($B'$) karpet!
3. Buktikan bahwa panjang karpet tidak mengalami perubahan!

**Langkah Pemecahan (Menurun):**

**1. Menentukan Vektor Translasi $T$:**
- Pergeseran mendatar ke kanan $2\text{ meter} \implies a = 2$.
- Pergeseran tegak maju ke kiblat $3\text{ meter} \implies b = 3$.

$$
T = \begin{pmatrix} 2 \\ 3 \end{pmatrix}
$$

**2. Menghitung Posisi Ujung Karpet yang Baru:**

Untuk ujung kiri karpet $A(1, 2)$:
$$
\begin{aligned}
x_{A}' &= x_A + a \\
       &= 1 + 2 \\
       &= 3
\end{aligned}
$$
$$
\begin{aligned}
y_{A}' &= y_A + b \\
       &= 2 + 3 \\
       &= 5
\end{aligned}
$$
Diperoleh koordinat baru: $A'(3, 5)$.

Untuk ujung kanan karpet $B(6, 2)$:
$$
\begin{aligned}
x_{B}' &= x_B + a \\
       &= 6 + 2 \\
       &= 8
\end{aligned}
$$
$$
\begin{aligned}
y_{B}' &= y_B + b \\
       &= 2 + 3 \\
       &= 5
\end{aligned}
$$
Diperoleh koordinat baru: $B'(8, 5)$.

**3. Pembuktian Panjang Karpet Tetap Sama:**
- Panjang awal karpet:
$$
\text{Panjang } AB = 6 - 1 = 5\text{ meter}
$$
- Panjang karpet setelah digeser:
$$
\text{Panjang } A'B' = 8 - 3 = 5\text{ meter}
$$

Terbukti panjang karpet tetap sama ($5\text{ meter}$), karena translasi bersifat mempertahankan jarak/ukuran objek asli (isometris).

---

### 6. Latihan Soal Kelas (Penguatan Konsep Bertahap)

#### A. Latihan Terbimbing (Ikuti Contoh Langkah demi Langkah)

Selesaikan soal berikut dengan menuliskan langkah perhitungan secara menurun:

1. Tentukan koordinat bayangan dari titik $K(5, -4)$ oleh translasi $T = \begin{pmatrix} -2 \\ 6 \end{pmatrix}$!
2. Tentukan koordinat bayangan dari titik $L(-3, 7)$ jika dicerminkan terhadap:
   - a. Sumbu-$X$
   - b. Garis $y = x$

---

#### B. Penguasaan Prosedur Inti (Fokus Pengulangan Rumus Dasar)

Selesaikan soal-soal latihan konsep dasar berikut:

1. Titik $P(-4, 2)$ ditranslasikan oleh $T = \begin{pmatrix} 5 \\ -3 \end{pmatrix}$. Tentukan koordinat bayangannya!
2. Tentukan persamaan bayangan dari garis $4x - y + 3 = 0$ oleh translasi $T = \begin{pmatrix} 2 \\ 1 \end{pmatrix}$!
3. Tentukan koordinat bayangan dari titik $M(3, 5)$ jika dicerminkan terhadap:
   - a. Sumbu-$Y$
   - b. Garis $y = -x$
4. Tentukan koordinat bayangan dari titik $N(2, -6)$ jika dicerminkan terhadap garis tegak $x = 5$!
5. Tentukan koordinat bayangan dari titik $Q(-1, 4)$ jika dicerminkan terhadap garis mendatar $y = 1$!

---

#### C. Soal Cerita Santri (Aplikasi Kontekstual)

1. **Pergeseran Meja Belajar Asrama:**  
   Sebuah meja belajar di kamar santri mula-mula berada pada posisi koordinat $A(2, 3)$. Karena kamar akan dibersihkan, meja digeser $4\text{ meter}$ ke kiri dan $1\text{ meter}$ ke depan. Tentukan:
   - a. Vektor translasi $T$
   - b. Posisi koordinat baru meja tersebut!

2. **Simetri Cermin Mihrab:**  
   Sebuah ornamen kaligrafi dipasang pada dinding mushola. Salah satu titik sudut bingkai berada di posisi $P(3, 2)$. Jika ornamen tersebut memiliki cermin simetri pada garis vertikal $x = -1$, tentukan koordinat bayangan titik sudut tersebut pada ornamen pasangannya!

---

#### D. Soal Tantangan Ringan (Pemahaman Konsep)

1. Suatu titik $A(2, 4)$ dicerminkan terhadap garis $x = 3$, menghasilkan bayangan $A'$. Kemudian bayangan $A'$ tersebut dicerminkan lagi terhadap garis yang sama yaitu $x = 3$, menghasilkan titik $A''$.  
   Tentukan koordinat $A''$ dan jelaskan mengapa hasilnya kembali ke posisi asal titik $A$!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

#### A. Pola Ingatan Cepat (Jembatan Keledai)

- **Translasi Titik:** Tinggal dijumlahkan langsung:
$$
(x + a, \ y + b)
$$
- **Cermin Sumbu-$X$:** Kebalikannya, huruf $y$ yang berubah tanda $\to (x, -y)$.
- **Cermin Sumbu-$Y$:** Kebalikannya, huruf $x$ yang berubah tanda $\to (-x, y)$.
- **Cermin Garis $y = x$:** Tukar tempat langsung $\to (y, x)$.
- **Cermin Garis $y = -x$:** Tukar tempat dan beri tanda minus pada keduanya $\to (-y, -x)$.
- **Cermin Garis $x = h$:** "Dua kali cermin kurangi asal":
$$
x' = 2h - x \quad (y \text{ tetap})
$$
- **Cermin Garis $y = k$:** "Dua kali cermin kurangi asal":
$$
y' = 2k - y \quad (x \text{ tetap})
$$

#### B. Waspada Jebakan Umum

> [!WARNING]
> 1. **Tanda Substitusi Garis pada Translasi:**  
>    Saat mencari bayangan garis oleh translasi $T = \begin{pmatrix} a \\ b \end{pmatrix}$, yang disubstitusikan ke persamaan adalah:
>    $$x = x' - a \quad \text{dan} \quad y = y' - b$$  
>    (Perhatikan tanda minusnya! Jangan mensubstitusikan $x' + a$).
> 2. **Tertukar Rumus Cermin $x = h$ dan $y = k$:**  
>    - Garis $x = h$ memotong sumbu-$X$, jadi yang dihitung berubah adalah nilai $x$. Nilai $y$ tidak berubah.  
>    - Garis $y = k$ memotong sumbu-$Y$, jadi yang dihitung berubah adalah nilai $y$. Nilai $x$ tidak berubah.

---

### 8. Pekerjaan Rumah (PR Mandiri)

Kerjakan 4 soal berikut di buku tugas secara teratur dan runtut ke bawah.

1. Tentukan bayangan titik $P(-2, 5)$ oleh translasi $T = \begin{pmatrix} 4 \\ -3 \end{pmatrix}$!
2. Tentukan persamaan bayangan garis $3x + 2y - 12 = 0$ oleh translasi $T = \begin{pmatrix} 1 \\ -2 \end{pmatrix}$!
3. Tentukan koordinat bayangan dari titik $K(4, -1)$ jika dicerminkan terhadap:
   - a. Sumbu-$X$
   - b. Sumbu-$Y$
   - c. Garis $y = x$
   - d. Garis $y = -2$
4. **Pertanyaan Pemahaman Konsep:**  
   Buktikan dengan perhitungan menurun bahwa titik $M(2, 3)$ jika dicerminkan terhadap garis $y = x$ lalu dicerminkan lagi terhadap sumbu-$X$, menghasilkan koordinat yang berbeda jika urutan pencerminannya dibalik (sumbu-$X$ terlebih dahulu baru garis $y = x$)!

---

#### Kunci Jawaban Akhir Mandiri (Untuk Cek Hasil Kerja Siswa)

- **Latihan 6A:**
  - `1.` $K'(3, 2)$
  - `2.` (a) $L'(-3, -7)$ ; (b) $L'(7, -3)$

- **Latihan 6B:**
  - `1.` $P'(1, -1)$
  - `2.` $4x - y - 4 = 0$
  - `3.` (a) $M'(-3, 5)$ ; (b) $M'(-5, -3)$
  - `4.` $N'(8, -6)$
  - `5.` $Q'(-1, -2)$

- **Latihan 6C:**
  - `1.` (a) $T = \begin{pmatrix} -4 \\ 1 \end{pmatrix}$ ; (b) Posisi baru: $(-2, 4)$
  - `2.` Bayangan titik: $P'(-5, 2)$

- **Latihan 6D:**
  - `1.` $A'(4, 4)$ dan $A''(2, 4)$. Terbukti kembali ke koordinat awal $A(2, 4)$ karena pencerminan dua kali pada garis yang sama membatalkan pergeseran jarak simetrisnya.

- **PR No. 1–4:**
  - `1.` $P'(2, 2)$
  - `2.` $3x + 2y - 11 = 0$
  - `3.` (a) $K'(4, 1)$ ; (b) $K'(-4, -1)$ ; (c) $K'(-1, 4)$ ; (d) $K'(4, -3)$
  - `4.` Urutan 1: $M(2, 3) \xrightarrow{y=x} (3, 2) \xrightarrow{\text{sb-}X} (3, -2)$.  
    Urutan 2: $M(2, 3) \xrightarrow{\text{sb-}X} (2, -3) \xrightarrow{y=x} (-3, 2)$.  
    Hasilnya berbeda: $(3, -2) \neq (-3, 2)$. Terbukti urutan pencerminan tidak boleh dibalik sembarangan (tidak komutatif).

---

### 9. Ringkasan Materi

1. **Translasi (Pergeseran):** Memindahkan titik dengan menambahkan komponen geser:  
   $x' = x + a$ dan $y' = y + b$. Untuk kurva, substitusikan $x = x' - a$ dan $y = y' - b$.
2. **Refleksi (Pencerminan):** Memindahkan titik dengan sifat simetri cermin datar:
   - Sumbu-$X \implies (x, -y)$
   - Sumbu-$Y \implies (-x, y)$
   - Garis $y = x \implies (y, x)$
   - Garis $y = -x \implies (-y, -x)$
   - Garis $x = h \implies (2h - x, y)$
   - Garis $y = k \implies (x, 2k - y)$
3. **Karakter Isometrik:** Translasi dan refleksi menjaga keutuhan ukuran, sudut, dan bentuk bangun datar sehingga bayangan selalu sama dan sebangun (kongruen) dengan aslinya.
