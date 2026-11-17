# MATEMATIKA SMA (JURUSAN IPA & PESANTREN)
## Kelas 11 - Semester Ganjil
### Pertemuan 8: Evaluasi Mandiri — Uji Pemahaman Matriks dan Aplikasinya

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Kerjakan 2 soal kilat penyegar ingatan berikut sebelum memasuki lembar evaluasi mandiri:
1. Jika matriks $A = \begin{pmatrix} 2 & -1 & 4 \\ 3 & 5 & -2 \end{pmatrix}$, tentukan ordo matriks $A$ dan tuliskan matriks transposnya $A^T$!
2. Hitunglah determinan dari matriks $M = \begin{pmatrix} 4 & 3 \\ 1 & 1 \end{pmatrix}$!

*(Cek singkat: No. 1 = ordo $2 \times 3$, $A^T = \begin{pmatrix} 2 & 3 \\ -1 & 5 \\ 4 & -2 \end{pmatrix}$; No. 2 = $\det M = 1$)*

---

#### B. Pengertian & Analogi Nyata

Evaluasi mandiri (*self-assessment*) adalah sarana pembelajaran yang dilakukan secara jujur, bertanggung jawab, dan terukur untuk mengetahui sejauh mana penguasaan konsep, formula, dan keterampilan operasi aljabar matriks telah dikuasai dengan benar.

Dalam perspektif akhlak dan kehidupan pondok pesantren, evaluasi merupakan cermin dari **muhasabah** (introspeksi diri secara objektif). Allah SWT berfirman:

> *"Wahai orang-orang yang beriman! Bertakwalah kepada Allah dan hendaklah setiap diri memperhatikan apa yang telah diperbuatnya untuk hari esok (akhirat)..."*  
> **(QS. Al-Hasyr: 18)**

Matriks adalah instrumen matematika yang sangat teratur. Setiap baris dan kolom mewakili data yang saling terhubung. Dalam modul evaluasi mandiri Pertemuan 8 ini, seluruh kompetensi dasar matriks diuji secara menyeluruh:
1. **Konsep & Operasi Dasar**: Ordo, transpos, kesamaan dua matriks, penjumlahan, dan perkalian skalar.
2. **Perkalian Antar-Matriks**: Syarat kesesuaian dimensi dan sifat non-komutatif ($AB \neq BA$).
3. **Determinan & Invers**: Metode selisih kali silang ordo $2 \times 2$, aturan Sarrus ordo $3 \times 3$, dan adjoin invers.
4. **Persamaan Matriks & Pemecahan SPLDV**: Penyelesaian bentuk $AX = B$ dan Aturan Cramer.

---

### 2. Bentuk Umum & Rumus Baku

Berikut adalah lembar formula inti (*Master Formula Sheet*) untuk matriks:

#### A. Operasi Aljabar & Transpos Matriks
- Transpos Matriks $A = (a_{ij})_{m \times n} \implies A^T = (a_{ji})_{n \times m}$
- Perkalian Dua Matriks:
  $$
  A_{m \times p} \cdot B_{p \times n} = C_{m \times n}, \quad c_{ij} = \sum_{k=1}^p a_{ik} b_{kj}
  $$

#### B. Determinan & Invers Matriks Ordo $2 \times 2$
$$
A = \begin{pmatrix} a & b \\ c & d \end{pmatrix} \implies \det(A) = ad - bc
$$

$$
A^{-1} = \frac{1}{\det(A)} \begin{pmatrix} d & -b \\ -c & a \end{pmatrix}, \quad \text{syarat } \det(A) \neq 0
$$

#### C. Persamaan Matriks & Aturan Cramer
1. **Persamaan Matriks:**
   $$
   A \cdot X = B \implies X = A^{-1} \cdot B
   $$
   $$
   Y \cdot A = B \implies Y = B \cdot A^{-1}
   $$
2. **Aturan Cramer pada SPLDV $\begin{cases} a_1 x + b_1 y = c_1 \\ a_2 x + b_2 y = c_2 \end{cases}$:**
   $$
   x = \frac{D_x}{D}, \quad y = \frac{D_y}{D} \quad (\text{dengan } D \neq 0)
   $$

---

### 3. Sifat-Sifat & Aturan Penting

1. **Sifat Non-Komutatif Perkalian:**
   Secara umum $A \cdot B \neq B \cdot A$. Urutan perkalian matriks wajib dipertahankan.
2. **Sifat Transpos dan Invers Perkalian:**
   $$
   (A \cdot B)^T = B^T \cdot A^T \quad \text{dan} \quad (A \cdot B)^{-1} = B^{-1} \cdot A^{-1}
   $$
3. **Karakteristik Determinan:**
   $$
   \det(A^T) = \det(A), \quad \det(A^{-1}) = \frac{1}{\det(A)}, \quad \det(A \cdot B) = \det(A) \cdot \det(B)
   $$

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

#### Contoh 1 (Tingkat Dasar — Kesamaan Dua Matriks)

**Soal:**  
Diketahui kesamaan dua matriks:

$$
\begin{pmatrix} 2x + y & 3 \\ 4 & x - 2y \end{pmatrix} = \begin{pmatrix} 7 & 3 \\ 4 & -4 \end{pmatrix}
$$

Tentukan nilai dari bentuk aljabar $3x + 2y$!

**Penyelesaian:**
- **Diketahui:** Elemen-elemen yang seletak bernilai sama.
- **Ditanyakan:** Nilai $3x + 2y$.
- **Langkah Penyelesaian:**
  1. Samakan elemen baris 1 kolom 1 dan baris 2 kolom 2:
     $$
     \begin{cases}
     2x + y = 7 & \quad \dots (1) \\
     x - 2y = -4 & \quad \dots (2)
     \end{cases}
     $$
  2. Dari Persamaan (2), nyatakan $x = 2y - 4$. Substitusikan ke Persamaan (1):
     $$
     2(2y - 4) + y = 7 \implies 4y - 8 + y = 7 \implies 5y = 15 \implies y = 3
     $$
  3. Hitung nilai $x$:
     $$
     x = 2(3) - 4 = 6 - 4 = 2
     $$
  4. Hitung nilai yang ditanyakan:
     $$
     3x + 2y = 3(2) + 2(3) = 6 + 6 = 12
     $$
- **Kesimpulan:** Nilai dari $3x + 2y$ adalah $12$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Variasi Menengah — Persamaan Matriks $AX = B$ vs $YA = B$)

**Soal:**  
Diberikan matriks $A = \begin{pmatrix} 4 & 3 \\ 1 & 1 \end{pmatrix}$ dan $B = \begin{pmatrix} 5 & 4 \\ 2 & 1 \end{pmatrix}$.  
Tentukan matriks $X$ jika $A \cdot X = B$ dan matriks $Y$ jika $Y \cdot A = B$!

**Penyelesaian:**
- **Diketahui:** $A = \begin{pmatrix} 4 & 3 \\ 1 & 1 \end{pmatrix}$, $B = \begin{pmatrix} 5 & 4 \\ 2 & 1 \end{pmatrix}$.
- **Ditanyakan:** Matriks $X$ dan $Y$.
- **Langkah Penyelesaian:**
  1. *Hitung determinan dan invers matriks $A$:*
     $$
     \det(A) = 4(1) - 3(1) = 1 \implies A^{-1} = \frac{1}{1} \begin{pmatrix} 1 & -3 \\ -1 & 4 \end{pmatrix} = \begin{pmatrix} 1 & -3 \\ -1 & 4 \end{pmatrix}
     $$
  2. *Menentukan matriks $X = A^{-1} \cdot B$:*
     $$
     X = \begin{pmatrix} 1 & -3 \\ -1 & 4 \end{pmatrix} \begin{pmatrix} 5 & 4 \\ 2 & 1 \end{pmatrix} = \begin{pmatrix} 1(5) - 3(2) & 1(4) - 3(1) \\ -1(5) + 4(2) & -1(4) + 4(1) \end{pmatrix} = \begin{pmatrix} -1 & 1 \\ 3 & 0 \end{pmatrix}
     $$
  3. *Menentukan matriks $Y = B \cdot A^{-1}$:*
     $$
     Y = \begin{pmatrix} 5 & 4 \\ 2 & 1 \end{pmatrix} \begin{pmatrix} 1 & -3 \\ -1 & 4 \end{pmatrix} = \begin{pmatrix} 5(1) + 4(-1) & 5(-3) + 4(4) \\ 2(1) + 1(-1) & 2(-3) + 1(4) \end{pmatrix} = \begin{pmatrix} 1 & 1 \\ 1 & -2 \end{pmatrix}
     $$
- **Kesimpulan:** Matriks $X = \begin{pmatrix} -1 & 1 \\ 3 & 0 \end{pmatrix}$ dan $Y = \begin{pmatrix} 1 & 1 \\ 1 & -2 \end{pmatrix}$. Perbedaan $X \neq Y$ membuktikan bahwa perkalian matriks tidak komutatif ($\checkmark\ \text{Benar}$).

---

#### Contoh 3 (Pengembangan — Teorema Karakteristik Cayley-Hamilton)

**Soal:**  
Diberikan matriks $A = \begin{pmatrix} 4 & 1 \\ 2 & 3 \end{pmatrix}$. Buktikan bahwa $A^2 - 7A + 10I = \mathcal{O}$!

**Penyelesaian:**
- **Diketahui:** $A = \begin{pmatrix} 4 & 1 \\ 2 & 3 \end{pmatrix}$, $I = \begin{pmatrix} 1 & 0 \\ 0 & 1 \end{pmatrix}$.
- **Ditanyakan:** Pembuktian persamaan matriks menghasilkan matriks nol $\mathcal{O}$.
- **Langkah Penyelesaian:**
  1. *Hitung $A^2$:*
     $$
     A^2 = \begin{pmatrix} 4 & 1 \\ 2 & 3 \end{pmatrix} \begin{pmatrix} 4 & 1 \\ 2 & 3 \end{pmatrix} = \begin{pmatrix} 16 + 2 & 4 + 3 \\ 8 + 6 & 2 + 9 \end{pmatrix} = \begin{pmatrix} 18 & 7 \\ 14 & 11 \end{pmatrix}
     $$
  2. *Hitung $7A$ dan $10I$:*
     $$
     7A = \begin{pmatrix} 28 & 7 \\ 14 & 21 \end{pmatrix}, \quad 10I = \begin{pmatrix} 10 & 0 \\ 0 & 10 \end{pmatrix}
     $$
  3. *Hitung $A^2 - 7A + 10I$:*
     $$
     \begin{pmatrix} 18 & 7 \\ 14 & 11 \end{pmatrix} - \begin{pmatrix} 28 & 7 \\ 14 & 21 \end{pmatrix} + \begin{pmatrix} 10 & 0 \\ 0 & 10 \end{pmatrix} = \begin{pmatrix} 18-28+10 & 7-7+0 \\ 14-14+0 & 11-21+10 \end{pmatrix} = \begin{pmatrix} 0 & 0 \\ 0 & 0 \end{pmatrix} = \mathcal{O}
     $$
- **Kesimpulan:** Terbukti bahwa $A^2 - 7A + 10I = \mathcal{O}$ ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual (Studi Kasus Santri & Kehidupan Nyata)

#### Studi Kasus: Audit Logistik Pengadaan Alat Tulis Toko Kitab Pesantren
Pengurus Toko Kitab Pesantren mendata transaksi pembelian tiga jenis perlengkapan belajar santri: buku tulis ($x$), pulpen ($y$), dan pensil ($z$):
- **Paket I:** $2$ buku tulis, $1$ pulpen, dan $1$ pensil seharga $\text{Rp } 11.000$.
- **Paket II:** $1$ buku tulis, $2$ pulpen, dan $1$ pensil seharga $\text{Rp } 10.000$.
- **Paket III:** $3$ buku tulis, $1$ pulpen, dan $2$ pensil seharga $\text{Rp } 17.000$.

Jika seorang santri ingin membeli **$5$ buku tulis, $3$ pulpen, dan $2$ pensil**, berapa total harga yang harus dibayar?

**Langkah Pemecahan:**
1. **Model Matriks:**
   $$
   \begin{pmatrix} 2 & 1 & 1 \\ 1 & 2 & 1 \\ 3 & 1 & 2 \end{pmatrix} \begin{pmatrix} x \\ y \\ z \end{pmatrix} = \begin{pmatrix} 11.000 \\ 10.000 \\ 17.000 \end{pmatrix}
   $$

2. **Perhitungan dengan Aturan Cramer:**
   - $D = \begin{vmatrix} 2 & 1 & 1 \\ 1 & 2 & 1 \\ 3 & 1 & 2 \end{vmatrix} = (8 + 3 + 1) - (6 + 2 + 2) = 12 - 10 = 2$
   - $D_x = \begin{vmatrix} 11.000 & 1 & 1 \\ 10.000 & 2 & 1 \\ 17.000 & 1 & 2 \end{vmatrix} = 6.000 \implies x = \frac{6.000}{2} = 3.000$
   - $D_y = \begin{vmatrix} 2 & 11.000 & 1 \\ 1 & 10.000 & 1 \\ 3 & 17.000 & 2 \end{vmatrix} = 4.000 \implies y = \frac{4.000}{2} = 2.000$
   - $D_z = \begin{vmatrix} 2 & 1 & 11.000 \\ 1 & 2 & 10.000 \\ 3 & 1 & 17.000 \end{vmatrix} = 6.000 \implies z = \frac{6.000}{2} = 3.000$

3. **Jawaban Kontekstual:**  
   Harga satuan: buku tulis $=\text{Rp } 3.000$, pulpen $=\text{Rp } 2.000$, dan pensil $=\text{Rp } 3.000$.  
   Total belanja untuk $5x + 3y + 2z$:
   $$
   \text{Total} = 5(3.000) + 3(2.000) + 2(3.000) = 15.000 + 6.000 + 6.000 = \text{Rp } 27.000
   $$
   Jadi, total harga yang harus dibayar santri adalah **$\text{Rp } 27.000$** ($\checkmark\ \text{Benar}$).

---

### 6. Latihan Soal Kelas (Uji Evaluasi Bertingkat)

#### A. Latihan Terbimbing (Ikuti Langkah Contoh)
1. Diberikan matriks $A = \begin{pmatrix} 3 & -2 \\ 4 & 1 \end{pmatrix}$ dan $B = \begin{pmatrix} -1 & 5 \\ 2 & -3 \end{pmatrix}$.  
   Tentukan matriks $C$ jika $C = 2A - 3B$!
2. Selesaikan SPLDV berikut menggunakan Aturan Cramer:
   $$
   \begin{cases}
   3x + 4y = 11 \\
   2x + 3y = 8
   \end{cases}
   $$

#### B. Penguasaan Prosedur Inti (Latihan Bertahap)
1. Tentukan nilai $a$ dan $b$ dari kesamaan matriks:
   $$
   \begin{pmatrix} a + 2b & -1 \\ 6 & 2a - b \end{pmatrix} = \begin{pmatrix} 8 & -1 \\ 6 & 1 \end{pmatrix}
   $$
2. Hitung hasil perkalian matriks berikut:
   $$
   \begin{pmatrix} 1 & 2 & -1 \\ 3 & 0 & 4 \end{pmatrix} \begin{pmatrix} 2 & 1 \\ -1 & 3 \\ 0 & 2 \end{pmatrix}
   $$
3. Tentukan invers dari matriks $P = \begin{pmatrix} 4 & -2 \\ -3 & 1 \end{pmatrix}$!
4. Selesaikan persamaan matriks $A \cdot X = B$ untuk $A = \begin{pmatrix} 2 & 3 \\ 1 & 2 \end{pmatrix}$ dan $B = \begin{pmatrix} 8 \\ 5 \end{pmatrix}$!
5. Tentukan determinan matriks $3 \times 3$ berikut dengan aturan Sarrus:
   $$
   \begin{vmatrix} 3 & -1 & 2 \\ 4 & 0 & 5 \\ -2 & 1 & 3 \end{vmatrix}
   $$

#### C. Soal Cerita Kontekstual (Terpisah)
1. **Pengadaan Seragam Santri:**  
   Pengurus asrama membagikan perlengkapan salat bagi santri baru:
   - Paket I: $3$ helai baju koko dan $2$ helai sarung seharga $\text{Rp } 310.000$.
   - Paket II: $2$ helai baju koko dan $4$ helai sarung seharga $\text{Rp } 340.000$.  
   Tentukan harga satuan $1$ helai baju koko dan $1$ helai sarung dengan metode invers matriks!
2. **Kebutuhan Perlengkapan Kebersihan Asrama:**  
   Santri bagian sarana membeli perlengkapan kamar mandi:
   - Pembelian I: $5$ ember dan $4$ gayung seharga $\text{Rp } 130.000$.
   - Pembelian II: $3$ ember dan $2$ gayung seharga $\text{Rp } 74.000$.  
   Berapakah harga satuan $1$ buah ember dan $1$ buah gayung?

#### D. Soal Tantangan (Opsional / Pengayaan)
1. Diberikan matriks $M = \begin{pmatrix} 6 & 8 \\ 2 & 4 \end{pmatrix}$. Uraikan matriks $M$ menjadi penjumlahan dua matriks $M = S + K$, di mana $S = \frac{1}{2}(M + M^T)$ adalah matriks simetris ($S^T = S$) dan $K = \frac{1}{2}(M - M^T)$ adalah matriks antisimetris ($K^T = -K$)!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

- **Pola Kunci Persamaan Matriks:**
  - Posisi pengali menentukan letak invers: Jika $AX = B \implies X = A^{-1}B$ (invers di depan). Jika $XA = B \implies X = B A^{-1}$ (invers di belakang).
  - **Cara Cek Cepat Hasil:** Pada SPLDV, substitusikan nilai $(x, y)$ yang diperoleh ke persamaan kedua untuk memastikan keabsahannya.

- **Waspada Jebakan Umum:**
  - ⚠️ **Kekeliruan Non-Komutatif:** Jangan pernah menukar $A^{-1}B$ menjadi $BA^{-1}$ karena hasilnya akan berbeda secara mendasar!
  - ⚠️ **Lupa Mengubah Tanda Diagonal Samping:** Saat membentuk matriks adjoin ordo $2 \times 2$, elemen $b$ dan $c$ wajib dinegatifkan.

---

### 8. Pekerjaan Rumah (PR Pondok / Remedial & Pengayaan Mandiri)

**Petunjuk:**
- Kerjakan di buku catatan tugas matematika secara tertib dan mandiri.
- Periksa hasil akhirmu dengan mencocokkan pada kunci jawaban di bawah.

1. Tentukan determinan dan invers dari matriks $M = \begin{pmatrix} 7 & 4 \\ 5 & 3 \end{pmatrix}$!
2. Selesaikan SPLDV berikut dengan Aturan Cramer:
   $$
   \begin{cases}
   5x + 3y = 29 \\
   2x + 4y = 20
   \end{cases}
   $$
3. **Pengadaan Lampu Hemat Energi Mushola:**  
   Pengurus mushola asrama membeli lampu LED:
   - Pembelian I: $6$ lampu $10\text{ Watt}$ dan $4$ lampu $18\text{ Watt}$ seharga $\text{Rp } 210.000$.
   - Pembelian II: $4$ lampu $10\text{ Watt}$ dan $5$ lampu $18\text{ Watt}$ seharga $\text{Rp } 215.000$.  
   Tentukan harga masing-masing lampu LED tersebut!
4. **Pertanyaan Pemahaman Konsep:**  
   *Jelaskan mengapa dalam operasi aljabar matriks, perkalian matriks tidak bersifat komutatif ($AB \neq BA$). Berikan satu contoh kecil matriks $2 \times 2$ yang menunjukkan bahwa $AB \neq BA$!*

**Kunci Jawaban Akhir Ringkas (Cek Mandiri):**
- **Latihan 6A:** `1.` $C = \begin{pmatrix} 9 & -19 \\ 2 & 11 \end{pmatrix}$ | `2.` $(x, y) = (1, 2)$
- **Latihan 6B:** `1.` $a = 2, b = 3$ | `2.` $\begin{pmatrix} 0 & 5 \\ 6 & 11 \end{pmatrix}$ | `3.` $P^{-1} = \begin{pmatrix} -0{,}5 & -1 \\ -1{,}5 & -2 \end{pmatrix}$ | `4.` $X = \begin{pmatrix} 1 \\ 2 \end{pmatrix}$ | `5.` $\det = -33$
- **Latihan 6C:** `1.` Koko $=\text{Rp } 70.000$, Sarung $=\text{Rp } 50.000$ | `2.` Ember $=\text{Rp } 18.000$, Gayung $=\text{Rp } 10.000$
- **PR No. 1–3:** `1.` $\det M = 1, M^{-1} = \begin{pmatrix} 3 & -4 \\ -5 & 7 \end{pmatrix}$ | `2.` $(x, y) = (4, 3)$ | `3.` Lampu $10\text{W} = \text{Rp } 15.000$, Lampu $18\text{W} = \text{Rp } 30.000$

---

### 9. Ringkasan Materi

- **Operasi Aljabar Matriks**: Penjumlahan dan pengurangan hanya berlaku untuk matriks berordo sama. Perkalian $A_{m \times p} \cdot B_{p \times n}$ membutuhkan kesamaan jumlah kolom $A$ dengan baris $B$ dan tidak bersifat komutatif ($AB \neq BA$).
- **Invers Matriks Ordo $2 \times 2$**: Dihitung melalui rumus $A^{-1} = \frac{1}{\det A} \begin{pmatrix} d & -b \\ -c & a \end{pmatrix}$, yang hanya ada jika $\det A \neq 0$.
- **Penyelesaian Persamaan Matriks**: Bentuk $AX = B$ diselesaikan dengan $X = A^{-1}B$, sedangkan bentuk $YA = B$ diselesaikan dengan $Y = B A^{-1}$.
- **Aturan Cramer**: Memberikan metode praktis berbasis determinan $x = \frac{D_x}{D}, y = \frac{D_y}{D}$ untuk menyelesaikan sistem persamaan linear secara langsung.
