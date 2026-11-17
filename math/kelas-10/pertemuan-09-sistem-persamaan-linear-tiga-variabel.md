# MATEMATIKA SMA (JURUSAN IPA & PESANTREN)
## Kelas 10 - Semester Ganjil
### Pertemuan 9: Sistem Persamaan Linear Tiga Variabel (SPLTV)

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Kerjakan 2 soal pengingat prasyarat berikut sebelum memasuki materi SPLTV:
1. Tentukan himpunan penyelesaian $(x, y)$ dari SPLDV sederhana berikut dengan metode eliminasi-substitusi:
   $$
   \begin{cases}
   x + y = 5 \\
   x - y = 1
   \end{cases}
   $$
2. Jika diperoleh nilai $x = 3$ dan $y = 2$, tentukan nilai $z$ yang memenuhi persamaan:
   $$
   2x + 3y + z = 15
   $$

*(Cek singkat: No. 1 = $(x, y) = (3, 2)$, No. 2 = $z = 3$)*

---

#### B. Pengertian Konsep Dasar

Dalam aljabar, Sistem Persamaan Linear Dua Variabel (SPLDV) merepresentasikan garis lurus pada bidang datar dua dimensi ($\mathbb{R}^2$), dengan penyelesaian berupa titik potong antar-garis.

Ketika suatu permasalahan melibatkan tiga peubah yang saling bergantung secara simultan, sistem tersebut dimodelkan menggunakan **Sistem Persamaan Linear Tiga Variabel (SPLTV)**.

Secara geometris dalam ruang tiga dimensi ($\mathbb{R}^3$), persamaan linear tiga variabel:

$$
ax + by + cz = d
$$

merepresentasikan sebuah **bidang datar**. Menyelesaikan SPLTV pada hakikatnya adalah menentukan **titik temu/persekutuan** $(x_0, y_0, z_0)$ di mana **tiga bidang datar** saling berpotongan secara serentak.

Ketiga persamaan dalam SPLTV saling mengikat satu sama lain sebagai kendala simultan. Jika salah satu koefisien atau konstanta berubah, maka posisi bidang dan titik potong sistem akan bergeser.

```
       Kedudukan Tiga Bidang Datar dalam Ruang Tiga Dimensi (R^3)

        (A) Tepat Satu Solusi         (B) Tak Hingga Solusi         (C) Tidak Ada Solusi
           (Titik Potong)             (Garis Persekutuan)            (Saling Sejajar)

               Bidang 1                     Bidang 1                     Bidang 1
             /          /                 /          /                 /          /
            +----------+                 +----------+                 +----------+
           /          /                 /  Garis   /                 /          /
          +----------+                 +==Persekutuan==+            +----------+
         /   Titik  /                 /          /                 /          /
        +-----(*)--+                 +----------+                 +----------+
            Bidang 2 & 3                 Bidang 2 & 3                 Bidang 2 & 3
```

**Tiga Kemungkinan Geometris Himpunan Penyelesaian:**
1. **Memiliki Tepat Satu Penyelesaian (*Unique Solution*):**  
   Ketiga bidang datar saling berpotongan tepat di **satu titik tunggal** $(x, y, z)$. Sistem ini disebut sistem yang **konsisten dan independen**.
2. **Memiliki Tak Hingga Banyak Penyelesaian (*Infinitely Many Solutions*):**  
   Ketiga bidang saling berpotongan pada **sebuah garis lurus persekutuan** (seperti lembaran-lembaran kitab yang bersatu pada jilidnya) atau ketiga bidang saling berimpit. Ditandai dengan identitas aljabar:
   $$
   0x + 0y + 0z = 0 \iff 0 = 0
   $$
3. **Tidak Memiliki Penyelesaian (*No Solution*):**  
   Ketiga bidang tidak memiliki satupun titik temu bersama secara serentak (misalnya ketiga bidang saling sejajar atau berpotongan membentuk prisma segitiga berongga). Ditandai dengan kontradiksi aljabar:
   $$
   0x + 0y + 0z = k \iff 0 = k \quad (k \neq 0)
   $$

---

### 2. Bentuk Umum & Rumus Baku

#### A. Bentuk Aljabar Standar SPLTV

Bentuk umum Sistem Persamaan Linear Tiga Variabel dengan variabel $x, y,$ dan $z$ dituliskan sebagai:

$$
\begin{cases}
a_1 x + b_1 y + c_1 z = d_1 & \quad \dots \text{ (Persamaan 1)} \\
a_2 x + b_2 y + c_2 z = d_2 & \quad \dots \text{ (Persamaan 2)} \\
a_3 x + b_3 y + c_3 z = d_3 & \quad \dots \text{ (Persamaan 3)}
\end{cases}
$$

**Keterangan Simbol:**
- $x, y, z \in \mathbb{R}$ adalah **variabel** (peubah) yang dicari nilainya.
- $a_1, a_2, a_3$ adalah **koefisien** variabel $x$.
- $b_1, b_2, b_3$ adalah **koefisien** variabel $y$.
- $c_1, c_2, c_3$ adalah **koefisien** variabel $z$.
- $d_1, d_2, d_3 \in \mathbb{R}$ adalah **konstanta** suku kanan persamaan.
- Syarat keberlakuan linear: $a_i, b_i, c_i$ tidak boleh ketiganya bernilai nol secara bersamaan untuk setiap baris persamaan $i \in \{1, 2, 3\}$.

Himpunan penyelesaian dituliskan dalam bentuk pasangan terurut tiga bilangan real:

$$
HP = \{(x, y, z)\}
$$

---

#### B. Bentuk Notasi Matriks dan Aturan Cramer (*Cramer's Rule*)

SPLTV dapat dinyatakan dalam perkalian matriks $A \cdot X = B$:

$$
\begin{pmatrix}
a_1 & b_1 & c_1 \\
a_2 & b_2 & c_2 \\
a_3 & b_3 & c_3
\end{pmatrix}
\begin{pmatrix}
x \\
y \\
z
\end{pmatrix}
=
\begin{pmatrix}
d_1 \\
d_2 \\
d_3
\end{pmatrix}
$$

Determinan matriks dihitung dengan metode ekspansi diagonal Sarrus:

$$
D = \begin{vmatrix}
a_1 & b_1 & c_1 \\
a_2 & b_2 & c_2 \\
a_3 & b_3 & c_3
\end{vmatrix}
= (a_1 b_2 c_3 + b_1 c_2 a_3 + c_1 a_2 b_3) - (c_1 b_2 a_3 + a_1 c_2 b_3 + b_1 a_2 c_3)
$$

Rumus Aturan Cramer untuk menentukan masing-masing variabel:

$$
x = \frac{D_x}{D}, \quad y = \frac{D_y}{D}, \quad z = \frac{D_z}{D}, \quad \text{dengan syarat } D \neq 0
$$

di mana $D_x, D_y, D_z$ diperoleh dengan mengganti kolom variabel terkait dengan kolom konstanta $\begin{pmatrix} d_1 \\ d_2 \\ d_3 \end{pmatrix}$.

---

### 3. Sifat-Sifat & Aturan Penting

#### A. Komparasi Karakteristik Metode Penyelesaian

| Nama Metode | Prinsip Kerja Aljabar | Keunggulan Utama | Catatan Kritis |
| :--- | :--- | :--- | :--- |
| **Metode Gabungan (Campuran)** | Eliminasi 1 variabel dari 2 pasang persamaan hingga menjadi SPLDV, lalu substitusi mundur (*back-substitution*). | **Paling praktis, cepat, dan minim galat aljabar.** Menjadi standar utama ujian. | Wajib konsisten mengeliminasi variabel yang sama pada tahap awal. |
| **Metode Substitusi Murni** | Mengisolasi 1 variabel ($x = \dots$), lalu disubstitusikan ke dua persamaan lainnya. | Sangat cepat jika sudah ada variabel berkoefisien $\pm 1$. | Rentan memunculkan pecahan rumit jika koefisien $> 1$. |
| **Aturan Cramer (Determinan)** | Menghitung 4 determinan matriks $3 \times 3$ menggunakan metode Sarrus. | Prosedural, sistematis, dan langsung menemukan variabel tertentu tanpa mengeliminasi. | Membutuhkan ketelitian hitung diagonal; tidak berlaku jika $D = 0$. |

---

#### B. Penanganan Khusus: SPLTV Bentuk Pecahan Invers

Untuk sistem dengan variabel di penyebut pecahan:

$$
\begin{cases}
\frac{a_1}{x} + \frac{b_1}{y} + \frac{c_1}{z} = d_1 \\
\frac{a_2}{x} + \frac{b_2}{y} + \frac{c_2}{z} = d_2 \\
\frac{a_3}{x} + \frac{b_3}{y} + \frac{c_3}{z} = d_3
\end{cases}
$$

> [!CAUTION]
> **Dilarang Menyamakan Penyebut:** Jangan kalikan persamaan dengan $x y z$ karena akan menghasilkan suku non-linear derajat tiga yang rumit.

**Strategi Baku (Transformasi Linear):**  
Misalkan:

$$
u = \frac{1}{x}, \quad v = \frac{1}{y}, \quad w = \frac{1}{z}
$$

Selesaikan sistem linear dalam $u, v, w$, lalu kembalikan ke nilai asli:

$$
x = \frac{1}{u}, \quad y = \frac{1}{v}, \quad z = \frac{1}{w}
$$

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

#### Contoh 1 (Tingkat Dasar — Bilangan Bulat Ramah Hitung dengan Metode Gabungan)

**Soal:**  
Tentukan himpunan penyelesaian dari SPLTV berikut:

$$
\begin{cases}
x + y + z = 6 & \quad \dots (1) \\
2x - y + z = 3 & \quad \dots (2) \\
x + 2y - z = 2 & \quad \dots (3)
\end{cases}
$$

**Penyelesaian:**
- **Diketahui:**  
  Persamaan (1), (2), dan (3) dengan koefisien bulat sederhana.
- **Ditanyakan:**  
  Himpunan penyelesaian $HP = \{(x, y, z)\}$.
- **Langkah Penyelesaian:**
  1. *Langkah 1 (Eliminasi variabel $z$ dari Persamaan 1 dan 2):*  
     Kurangkan Persamaan (1) dengan Persamaan (2):
     $$
     (x + y + z) - (2x - y + z) = 6 - 3 \implies -x + 2y = 3 \quad \dots (4)
     $$
  2. *Langkah 2 (Eliminasi variabel $z$ dari Persamaan 1 dan 3):*  
     Jumlahkan Persamaan (1) dan Persamaan (3):
     $$
     (x + y + z) + (x + 2y - z) = 6 + 2 \implies 2x + 3y = 8 \quad \dots (5)
     $$
  3. *Langkah 3 (Menyelesaikan SPLDV variabel $x$ dan $y$):*  
     Dari Persamaan (4), nyatakan $x = 2y - 3$. Substitusikan ke Persamaan (5):
     $$
     2(2y - 3) + 3y = 8 \implies 4y - 6 + 3y = 8 \implies 7y = 14 \implies y = 2
     $$
     Maka nilai $x$:
     $$
     x = 2(2) - 3 = 4 - 3 = 1
     $$
  4. *Langkah 4 (Substitusi mundur mencari nilai $z$):*  
     Substitusikan $x = 1$ dan $y = 2$ ke Persamaan (1):
     $$
     1 + 2 + z = 6 \implies 3 + z = 6 \implies z = 3
     $$
- **Kesimpulan:**  
  Himpunan penyelesaian sistem tersebut adalah $HP = \{(1, 2, 3)\}$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Variasi Menengah — Aturan Cramer $3 \times 3$ Sarrus)

**Soal:**  
Tentukan nilai $x$ dari SPLTV berikut menggunakan Aturan Cramer:

$$
\begin{cases}
x + y + z = 6 & \quad \dots (1) \\
2x - y + z = 6 & \quad \dots (2) \\
x + 2y - z = 1 & \quad \dots (3)
\end{cases}
$$

**Penyelesaian:**
- **Diketahui:**  
  Matriks koefisien $A = \begin{pmatrix} 1 & 1 & 1 \\ 2 & -1 & 1 \\ 1 & 2 & -1 \end{pmatrix}$ dan vektor konstanta $B = \begin{pmatrix} 6 \\ 6 \\ 1 \end{pmatrix}$.
- **Ditanyakan:**  
  Nilai variabel $x$.
- **Langkah Penyelesaian:**
  1. *Langkah 1 (Menghitung determinan utama $D$):*
     $$
     \begin{aligned}
     D &= \begin{vmatrix} 1 & 1 & 1 \\ 2 & -1 & 1 \\ 1 & 2 & -1 \end{vmatrix} \\
     &= [1(-1)(-1) + 1(1)(1) + 1(2)(2)] - [1(-1)(1) + 1(1)(2) + 1(2)(-1)] \\
     &= [1 + 1 + 4] - [-1 + 2 - 2] = 6 - (-1) = 7
     \end{aligned}
     $$
     Karena $D = 7 \neq 0$, sistem memiliki tepat satu penyelesaian unik.
  2. *Langkah 2 (Menghitung determinan $D_x$):*  
     Ganti kolom pertama dengan vektor konstanta $B$:
     $$
     \begin{aligned}
     D_x &= \begin{vmatrix} 6 & 1 & 1 \\ 6 & -1 & 1 \\ 1 & 2 & -1 \end{vmatrix} \\
     &= [6(-1)(-1) + 1(1)(1) + 1(6)(2)] - [1(-1)(1) + 6(1)(2) + 1(6)(-1)] \\
     &= [6 + 1 + 12] - [-1 + 12 - 6] = 19 - 5 = 14
     \end{aligned}
     $$
  3. *Langkah 3 (Menghitung nilai $x$):*
     $$
     x = \frac{D_x}{D} = \frac{14}{7} = 2
     $$
- **Kesimpulan:**  
  Nilai $x$ yang memenuhi SPLTV tersebut adalah $x = 2$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 3 (Pengembangan — SPLTV Bentuk Pecahan Invers)

**Soal:**  
Tentukan himpunan penyelesaian dari SPLTV bentuk pecahan:

$$
\begin{cases}
\frac{1}{x} + \frac{1}{y} + \frac{1}{z} = 6 \\
\frac{2}{x} + \frac{1}{y} - \frac{1}{z} = 1 \\
\frac{1}{x} - \frac{1}{y} + \frac{2}{z} = 5
\end{cases}
$$

**Penyelesaian:**
- **Diketahui:**  
  Variabel berada pada penyebut dengan syarat $x \neq 0, y \neq 0, z \neq 0$.
- **Ditanyakan:**  
  Himpunan penyelesaian $HP = \{(x, y, z)\}$.
- **Langkah Penyelesaian:**
  1. *Langkah 1 (Transformasi linear):*  
     Misalkan $u = \frac{1}{x}, v = \frac{1}{y}, w = \frac{1}{z}$. Sistem menjadi:
     $$
     \begin{cases}
     u + v + w = 6 & \quad \dots (1') \\
     2u + v - w = 1 & \quad \dots (2') \\
     u - v + 2w = 5 & \quad \dots (3')
     \end{cases}
     $$
  2. *Langkah 2 (Eliminasi $w$):*  
     - Dari $(1') + (2')$:
       $$
       (u + v + w) + (2u + v - w) = 6 + 1 \implies 3u + 2v = 7 \quad \dots (4')
       $$
     - Dari $2(1') - (3')$:
       $$
       2(u + v + w) - (u - v + 2w) = 12 - 5 \implies u + 3v = 7 \quad \dots (5')
       $$
  3. *Langkah 3 (Menyelesaikan SPLDV $u$ dan $v$):*  
     Dari $(5')$, $u = 7 - 3v$. Substitusi ke $(4')$:
     $$
     3(7 - 3v) + 2v = 7 \implies 21 - 7v = 7 \implies 7v = 14 \implies v = 2
     $$
     Maka $u = 7 - 3(2) = 1$.
  4. *Langkah 4 (Mencari $w$ dan mengembalikan ke $x, y, z$):*  
     Substitusi ke $(1')$: $1 + 2 + w = 6 \implies w = 3$.  
     Maka nilai asli:
     $$
     x = \frac{1}{u} = \frac{1}{1} = 1, \quad y = \frac{1}{v} = \frac{1}{2}, \quad z = \frac{1}{w} = \frac{1}{3}
     $$
- **Kesimpulan:**  
  Himpunan penyelesaiannya adalah $HP = \left\{\left(1, \frac{1}{2}, \frac{1}{3}\right)\right\}$ ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual (Studi Kasus Santri & Kehidupan Nyata)

#### Studi Kasus: Pengadaan Logistik Dapur Asrama Pondok Pesantren
Pengurus seksi dapur santri sebuah pondok pesantren berbelanja kebutuhan bahan pokok mingguan untuk santri di pasar induk amanah. Pengurus mencatat transaksi belanja dalam tiga kesempatan:
1. **Pekan I:** Membeli $2$ karung beras, $1$ peti telur ayam, dan $3$ jeriken minyak goreng dengan total biaya $\text{Rp } 860.000$.
2. **Pekan II:** Membeli $1$ karung beras, $2$ peti telur ayam, dan $1$ jeriken minyak goreng dengan total biaya $\text{Rp } 570.000$.
3. **Pekan III:** Membeli $3$ karung beras, $2$ peti telur ayam, dan $2$ jeriken minyak goreng dengan total biaya $\text{Rp } 1.150.000$.

Jika menjelang acara peringatan hari santri pengurus dapur perlu memesan **$4$ karung beras, $3$ peti telur, dan $2$ jeriken minyak goreng**, berapa total anggaran kas pesantren yang wajib disiapkan bendahara?

**Langkah Pemecahan:**
1. **Membuat Model Matematika:**  
   Misalkan (dalam satuan ribuan Rupiah):
   - $x$ = harga 1 karung beras
   - $y$ = harga 1 peti telur ayam
   - $z$ = harga 1 jeriken minyak goreng
   
   Model sistem persamaan:
   $$
   \begin{cases}
   2x + y + 3z = 860 & \quad \dots (1) \\
   x + 2y + z = 570 & \quad \dots (2) \\
   3x + 2y + 2z = 1150 & \quad \dots (3)
   \end{cases}
   $$

2. **Perhitungan Sistematis:**
   - Eliminasi variabel $y$ dari Persamaan (3) dan (2):
     $$
     (3x + 2y + 2z) - (x + 2y + z) = 1150 - 570 \implies 2x + z = 580 \implies z = 580 - 2x \quad \dots (4)
     $$
   - Eliminasi variabel $y$ dari $2 \times \text{Persamaan (1)} - \text{Persamaan (2)}$:
     $$
     (4x + 2y + 6z) - (x + 2y + z) = 1720 - 570 \implies 3x + 5z = 1150 \quad \dots (5)
     $$
   - Substitusi $z$ ke Persamaan (5):
     $$
     3x + 5(580 - 2x) = 1150 \implies 3x + 2900 - 10x = 1150 \implies -7x = -1750 \implies x = 250
     $$
   - Hitung nilai $z$ dan $y$:
     $$
     z = 580 - 2(250) = 80
     $$
     Substitusi $x = 250$ dan $z = 80$ ke Persamaan (2):
     $$
     250 + 2y + 80 = 570 \implies 2y = 240 \implies y = 120
     $$

3. **Jawaban Kontekstual:**  
   Harga satuan: $1$ karung beras $=\text{Rp } 250.000$, $1$ peti telur $=\text{Rp } 120.000$, dan $1$ jeriken minyak $=\text{Rp } 80.000$.  
   Total kebutuhan anggaran untuk $4x + 3y + 2z$:
   $$
   \text{Total} = 4(250.000) + 3(120.000) + 2(80.000) = 1.000.000 + 360.000 + 160.000 = \text{Rp } 1.520.000
   $$
   Jadi, total anggaran kas pesantren yang wajib disiapkan adalah **$\text{Rp } 1.520.000$** ($\checkmark\ \text{Benar}$).

---

### 6. Latihan Soal Kelas (Bertingkat & Terstruktur)

#### A. Latihan Terbimbing (Ikuti Langkah Contoh)
Kerjakan soal berikut dengan mengikuti urutan langkah pada contoh yang telah dipelajari:
1. Tentukan himpunan penyelesaian dari SPLTV berikut:
   $$
   \begin{cases}
   x + y + z = 6 \\
   x + 2y + z = 8 \\
   2x + y - z = 1
   \end{cases}
   $$
   *(Petunjuk: Kurangkan Persamaan 2 dengan Persamaan 1 untuk langsung mendapatkan nilai $y$)*
2. Tentukan nilai $z$ dari SPLTV berikut dengan metode gabungan atau Aturan Cramer:
   $$
   \begin{cases}
   x + y + z = 7 \\
   2x + y - z = 4 \\
   x - y + 2z = 5
   \end{cases}
   $$

#### B. Penguasaan Prosedur Inti (Latihan Bertahap)
Tentukan himpunan penyelesaian $HP = \{(x, y, z)\}$ dari sistem persamaan linear tiga variabel berikut secara sistematis:
1. $$
   \begin{cases}
   x + y + z = 9 \\
   2x + y - z = 4 \\
   x - y + z = 3
   \end{cases}
   $$
2. $$
   \begin{cases}
   2x + y + z = 7 \\
   x - 2y + z = 2 \\
   3x - y - z = 2
   \end{cases}
   $$
3. $$
   \begin{cases}
   3x - y + 2z = 16 \\
   2x + y + z = 11 \\
   x + 2y - z = 1
   \end{cases}
   $$
4. $$
   \begin{cases}
   \frac{2}{x} + \frac{1}{y} + \frac{1}{z} = 4 \\
   \frac{1}{x} - \frac{2}{y} + \frac{3}{z} = 7 \\
   \frac{3}{x} + \frac{2}{y} - \frac{1}{z} = 2
   \end{cases}
   $$
5. $$
   \begin{cases}
   x + y = 7 \\
   y + z = 9 \\
   x + z = 8
   \end{cases}
   $$

#### C. Soal Cerita Kontekstual (Terpisah)
1. **Pengadaan Kitab di Toko Kitab Pesantren:**  
   Tiga santri membeli tiga jenis kitab di Toko Kitab Pesantren: Kitab Fiqih ($x$), Kitab Nahwu ($y$), dan Kamus Bahasa Arab ($z$).
   - Ahmad membeli $1$ Fiqih, $1$ Nahwu, dan $1$ Kamus seharga $\text{Rp } 135.000$.
   - Bilal membeli $2$ Fiqih, $1$ Nahwu, dan $3$ Kamus seharga $\text{Rp } 300.000$.
   - Chaidir membeli $3$ Fiqih, $2$ Nahwu, dan $1$ Kamus seharga $\text{Rp } 255.000$.  
   Tentukan harga satuan dari $1$ eksemplar Kitab Fiqih, Kitab Nahwu, dan Kamus Bahasa Arab!
2. **Penyaluran Infak Beras Santri Dhuafa:**  
   Lembaga Amil Zakat Pesantren menyalurkan beras dalam tiga jenis karung: Karung Kecil ($x$ kg), Karung Sedang ($y$ kg), dan Karung Besar ($z$ kg).  
   - $3$ karung kecil, $2$ karung sedang, dan $1$ karung besar berat totalnya $35\text{ kg}$.
   - $2$ karung kecil, $3$ karung sedang, dan $2$ karung besar berat totalnya $50\text{ kg}$.
   - $1$ karung kecil, $1$ karung sedang, dan $3$ karung besar berat totalnya $45\text{ kg}$.  
   Berapakah kapasitas muatan masing-masing karung beras?

#### D. Soal Tantangan (Opsional / Pengayaan)
1. Diberikan sistem persamaan:
   $$
   \begin{cases}
   x + 2y - z = 3 \\
   2x + y + z = 6 \\
   3x + 3y = 9
   \end{cases}
   $$
   Tunjukkan secara aljabar bahwa sistem persamaan di atas memiliki tak hingga banyak penyelesaian, dan nyatakan himpunan penyelesaiannya dalam bentuk parameter $z = t$ ($t \in \mathbb{R}$)!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

- **Pola Kunci Alur Eliminasi Konsisten:**
  - *"Tetapkan satu variabel target eliminasi, hilangkan dari pasangan (1)&(2) dan (1)&(3), selesaikan SPLDV yang terbentuk, lalu substitusi mundur."*
  - **Cara Cek Cepat Hasil:** Substitusikan nilai $(x, y, z)$ yang diperoleh ke persamaan ketiga (yang belum dipakai pada tahap akhir) untuk memastikan kedua ruas bernilai sama.

- **Waspada Jebakan Umum:**
  - ⚠️ **Kesalahan Tanda Minus:** Saat mengurangkan dua baris persamaan dengan suku negatif: $(2x - 3y) - (x - 5y) = x + 2y$ (bukan $x - 8y$).
  - ⚠️ **Inkonsistensi Variabel Eliminasi:** Jangan mengeliminasi $z$ pada pasangan pertama lalu mengeliminasi $y$ pada pasangan kedua, karena tidak akan mereduksi variabel.
  - ⚠️ **Menyamakan Penyebut pada Pecahan Invers:** Jangan mengalikan dengan $xyz$. Gunakan pemisalan linear $u = \frac{1}{x}, v = \frac{1}{y}, w = \frac{1}{z}$.

---

### 8. Pekerjaan Rumah (PR Pondok / Mandiri)

**Petunjuk:**
- Kerjakan di buku tugas matematika secara mandiri, runtut, dan rapi.
- Setelah selesai, cocokkan hasil akhirmu dengan **Kunci Jawaban Akhir** di bawah.

1. Tentukan himpunan penyelesaian dari SPLTV berikut dengan metode gabungan:
   $$
   \begin{cases}
   x + y + 2z = 9 \\
   2x + 4y - 3z = 1 \\
   3x + 6y - 5z = 0
   \end{cases}
   $$
2. Selesaikan SPLTV berikut dengan Aturan Cramer:
   $$
   \begin{cases}
   2x + y + z = 9 \\
   x + 2y - z = 6 \\
   3x - y + 2z = 7
   \end{cases}
   $$
3. **Pengadaan Inventaris Majelis Santri:**  
   Majelis taklim santri membeli perlengkapan ibadah: mushaf Al-Qur'an ($x$), sajadah gulung ($y$), dan rehal kayu ($z$):
   - Transaksi 1: $10$ mushaf, $2$ sajadah, dan $5$ rehal seharga $\text{Rp } 1.350.000$.
   - Transaksi 2: $5$ mushaf, $3$ sajadah, dan $10$ rehal seharga $\text{Rp } 1.400.000$.
   - Transaksi 3: $15$ mushaf, $1$ sajadah, dan $8$ rehal seharga $\text{Rp } 1.540.000$.  
   Tentukan harga satuan masing-masing barang!
4. **Pertanyaan Pemahaman Konsep:**  
   *Jelaskan dengan bahasamu sendiri: mengapa persamaan linear tiga variabel $ax + by + cz = d$ merepresentasikan sebuah bidang datar di ruang $\mathbb{R}^3$, dan apa kondisi geometris yang menyebabkan SPLTV tidak memiliki penyelesaian sama sekali?*

**Kunci Jawaban Akhir Ringkas (Cek Mandiri):**
- **Latihan 6A:** `1.` $(x, y, z) = (1, 2, 3)$ | `2.` $z = 4$
- **Latihan 6B:** `1.` $(2, 3, 4)$ | `2.` $(1, 2, 3)$ | `3.` $(3, 1, 4)$ | `4.` $\left(1, \frac{1}{2}, \frac{1}{3}\right)$ | `5.` $(3, 4, 5)$
- **Latihan 6C:** `1.` Fiqih $=\text{Rp } 45.000$, Nahwu $=\text{Rp } 30.000$, Kamus $=\text{Rp } 60.000$ | `2.` Kecil $=5\text{ kg}$, Sedang $=10\text{ kg}$, Besar $=10\text{ kg}$
- **PR No. 1–3:** `1.` $(x, y, z) = (2, 1, 3)$ | `2.` $(x, y, z) = (2, 3, 2)$ | `3.` Mushaf $=\text{Rp } 70.000$, Sajadah $=\text{Rp } 250.000$, Rehal $=\text{Rp } 30.000$

---

### 9. Ringkasan Materi

- **Definisi Geometris SPLTV**: SPLTV terdiri atas tiga persamaan linear derajat satu. Dalam ruang $\mathbb{R}^3$, setiap persamaan merepresentasikan bidang datar, dan penyelesaiannya adalah titik persekutuan ketiga bidang tersebut $(x, y, z)$.
- **Tiga Karakteristik Solusi**: Memiliki 1 solusi unik ($D \neq 0$), tak hingga banyak solusi ($D = 0$ dan $D_x=D_y=D_z=0$, bidang bersekutu pada garis), atau tidak memiliki solusi ($D = 0$ dan minimal satu $D_i \neq 0$, bidang saling sejajar).
- **Metode Gabungan (Eliminasi-Substitusi)**: Pilih satu variabel yang konsisten dieliminasi dari dua pasang persamaan hingga tersisa SPLDV dua variabel, lalu gunakan substitusi mundur.
- **Bentuk Pecahan Invers**: Gunakan pemisalan variabel linear $u = \frac{1}{x}, v = \frac{1}{y}, w = \frac{1}{z}$ sebelum menyelesaikan, dan jangan sekali-kali menyamakan penyebut dengan mengalikan $xyz$.
