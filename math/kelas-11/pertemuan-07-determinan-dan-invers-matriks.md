# MATEMATIKA SMA (JURUSAN IPA & PESANTREN)
## Kelas 11 - Semester Ganjil
### Pertemuan 7: Determinan dan Invers Matriks

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Kerjakan 2 soal pengingat prasyarat berikut sebelum mempelajari determinan dan invers:
1. Diberikan matriks $P = \begin{pmatrix} 2 & 3 \\ 1 & 4 \end{pmatrix}$. Hitunglah selisih hasil kali diagonal utama dikurangi diagonal samping:
   $$
   (2 \times 4) - (3 \times 1)
   $$
2. Tentukan hasil perkalian matriks identitas $I$ dengan matriks kolom berikut:
   $$
   \begin{pmatrix} 1 & 0 \\ 0 & 1 \end{pmatrix} \begin{pmatrix} 5 \\ -2 \end{pmatrix}
   $$

*(Cek singkat: No. 1 = $5$, No. 2 = $\begin{pmatrix} 5 \\ -2 \end{pmatrix}$)*

---

#### B. Pengertian Konsep Dasar

Dalam ilmu aljabar linear, setiap matriks persegi (matriks berordo $n \times n$) memiliki nilai skalar karakteristik khusus yang dinamakan **determinan**. Determinan mencerminkan sifat geometris matriks, seperti faktor skala perbesaran luas (pada bidang dua dimensi $\mathbb{R}^2$) atau volume (pada ruang tiga dimensi $\mathbb{R}^3$).

**Konsep Operasi Invers Matriks:**
- Jika matriks $A$ merepresentasikan suatu transformasi linear, maka **invers matriks** $A^{-1}$ adalah operasi balikan yang mengembalikan objek ke posisi semula.
- Mengalikan suatu matriks dengan inversnya menghasilkan matriks identitas $I$:
  $$
  A \cdot A^{-1} = A^{-1} \cdot A = I
  $$
- Suatu matriks persegi hanya memiliki invers jika nilai determinannya tidak sama dengan nol ($\det A \neq 0$), yang dinamakan **matriks non-singular**. Jika $\det A = 0$, matriks tersebut dinamakan **matriks singular** dan tidak memiliki invers karena pemetaannya mereduksi dimensi.
- Nilai determinan juga digunakan untuk menyelesaikan Sistem Persamaan Linear Dua Variabel (SPLDV) secara efisien melalui **Aturan Cramer (*Cramer's Rule*)**.

---

### 2. Bentuk Umum & Rumus Baku

#### A. Determinan dan Invers Matriks Ordo $2 \times 2$

Diberikan matriks persegi berordo $2 \times 2$:

$$
A = \begin{pmatrix} a & b \\ c & d \end{pmatrix}
$$

1. **Determinan Matriks $2 \times 2$:**
   $$
   \det(A) = |A| = \begin{vmatrix} a & b \\ c & d \end{vmatrix} = ad - bc
   $$

2. **Invers Matriks $2 \times 2$:**
   $$
   A^{-1} = \frac{1}{\det(A)} \operatorname{Adj}(A) = \frac{1}{ad - bc} \begin{pmatrix} d & -b \\ -c & a \end{pmatrix}, \quad \text{dengan syarat } ad - bc \neq 0
   $$

**Keterangan Simbol:**
- $\det(A)$ atau $|A|$: Determinan dari matriks $A$ (bilangan real skalar).
- $A^{-1}$: Matriks invers dari $A$.
- $\operatorname{Adj}(A) = \begin{pmatrix} d & -b \\ -c & a \end{pmatrix}$: Matriks adjoin dari $A$.
- $I = \begin{pmatrix} 1 & 0 \\ 0 & 1 \end{pmatrix}$: Matriks identitas ordo $2 \times 2$.

---

#### B. Determinan Matriks Ordo $3 \times 3$ (Metode Sarrus)

Diberikan matriks berordo $3 \times 3$:

$$
A = \begin{pmatrix} a_1 & b_1 & c_1 \\ a_2 & b_2 & c_2 \\ a_3 & b_3 & c_3 \end{pmatrix}
$$

Determinan dihitung dengan menambahkan dua kolom pertama di sebelah kanan garis determinan:

$$
\det(A) = (a_1 b_2 c_3 + b_1 c_2 a_3 + c_1 a_2 b_3) - (c_1 b_2 a_3 + a_1 c_2 b_3 + b_1 a_2 c_3)
$$

---

#### C. Penyelesaian SPLDV dengan Aturan Cramer

Untuk sistem persamaan linear dua variabel:

$$
\begin{cases}
a_1 x + b_1 y = c_1 \\
a_2 x + b_2 y = c_2
\end{cases}
$$

Dihitung tiga determinan:

$$
D = \begin{vmatrix} a_1 & b_1 \\ a_2 & b_2 \end{vmatrix} = a_1 b_2 - b_1 a_2
$$

$$
D_x = \begin{vmatrix} c_1 & b_1 \\ c_2 & b_2 \end{vmatrix} = c_1 b_2 - b_1 c_2, \quad D_y = \begin{vmatrix} a_1 & c_1 \\ a_2 & c_2 \end{vmatrix} = a_1 c_2 - c_1 a_2
$$

Rumus nilai variabel:

$$
x = \frac{D_x}{D}, \quad y = \frac{D_y}{D}, \quad \text{dengan syarat } D \neq 0
$$

---

### 3. Sifat-Sifat & Aturan Penting

Beberapa aturan dan sifat esensial determinan dan invers matriks:
1. **Sifat Determinan Transpos & Perkalian:**
   $$
   \det(A^T) = \det(A) \quad \text{dan} \quad \det(A \cdot B) = \det(A) \cdot \det(B)
   $$
2. **Sifat Determinan Invers & Pengali Skalar ($n \times n$):**
   $$
   \det(A^{-1}) = \frac{1}{\det(A)} \quad \text{dan} \quad \det(k \cdot A) = k^n \cdot \det(A)
   $$
3. **Sifat Operasi Invers:**
   $$
   (A^{-1})^{-1} = A \quad \text{dan} \quad (A \cdot B)^{-1} = B^{-1} \cdot A^{-1}
   $$

| Kondisi Matriks | Nilai Determinan | Karakteristik Invers | Status Solusi SPL |
| :--- | :--- | :--- | :--- |
| **Non-Singular** | $\det A \neq 0$ | Memiliki invers unik $A^{-1}$ | Tepat satu solusi tunggal |
| **Singular** | $\det A = 0$ | Tidak memiliki invers | Tak hingga atau tidak ada solusi |

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

#### Contoh 1 (Tingkat Dasar — Determinan & Invers $2 \times 2$ Angka Ramah Hitung)

**Soal:**  
Diberikan matriks $A = \begin{pmatrix} 5 & 7 \\ 2 & 3 \end{pmatrix}$.  
Tentukan determinan matriks $A$, invers matriks $A^{-1}$, dan buktikan bahwa $A \cdot A^{-1} = I$!

**Penyelesaian:**
- **Diketahui:** $A = \begin{pmatrix} 5 & 7 \\ 2 & 3 \end{pmatrix}$.
- **Ditanyakan:** $\det(A)$, $A^{-1}$, dan pembuktian $A \cdot A^{-1} = I$.
- **Langkah Penyelesaian:**
  1. *Menghitung Determinan:*
     $$
     \det(A) = ad - bc = 5(3) - 7(2) = 15 - 14 = 1
     $$
     Karena $\det(A) = 1 \neq 0$, matriks $A$ adalah non-singular.
  2. *Menentukan Invers:*
     $$
     A^{-1} = \frac{1}{\det(A)} \begin{pmatrix} d & -b \\ -c & a \end{pmatrix} = \frac{1}{1} \begin{pmatrix} 3 & -7 \\ -2 & 5 \end{pmatrix} = \begin{pmatrix} 3 & -7 \\ -2 & 5 \end{pmatrix}
     $$
  3. *Verifikasi Perkalian Identitas:*
     $$
     A \cdot A^{-1} = \begin{pmatrix} 5 & 7 \\ 2 & 3 \end{pmatrix} \begin{pmatrix} 3 & -7 \\ -2 & 5 \end{pmatrix} = \begin{pmatrix} 5(3)+7(-2) & 5(-7)+7(5) \\ 2(3)+3(-2) & 2(-7)+3(5) \end{pmatrix} = \begin{pmatrix} 1 & 0 \\ 0 & 1 \end{pmatrix} = I
     $$
- **Kesimpulan:** Invers dari matriks $A$ adalah $A^{-1} = \begin{pmatrix} 3 & -7 \\ -2 & 5 \end{pmatrix}$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Variasi Menengah — Menyelesaikan SPLDV dengan Aturan Cramer)

**Soal:**  
Selesaikan sistem persamaan linear dua variabel berikut menggunakan Aturan Cramer:

$$
\begin{cases}
3x + 2y = 12 \\
2x + 5y = 19
\end{cases}
$$

**Penyelesaian:**
- **Diketahui:** $a_1=3, b_1=2, c_1=12$ dan $a_2=2, b_2=5, c_2=19$.
- **Ditanyakan:** Nilai $x$ dan $y$.
- **Langkah Penyelesaian:**
  1. *Hitung determinan utama $D$:*
     $$
     D = \begin{vmatrix} 3 & 2 \\ 2 & 5 \end{vmatrix} = 3(5) - 2(2) = 15 - 4 = 11
     $$
  2. *Hitung determinan variabel $D_x$ dan $D_y$:*
     $$
     D_x = \begin{vmatrix} 12 & 2 \\ 19 & 5 \end{vmatrix} = 12(5) - 2(19) = 60 - 38 = 22
     $$
     $$
     D_y = \begin{vmatrix} 3 & 12 \\ 2 & 19 \end{vmatrix} = 3(19) - 12(2) = 57 - 24 = 33
     $$
  3. *Hitung nilai $x$ dan $y$:*
     $$
     x = \frac{D_x}{D} = \frac{22}{11} = 2, \quad y = \frac{D_y}{D} = \frac{33}{11} = 3
     $$
- **Kesimpulan:** Himpunan penyelesaian sistem adalah $HP = \{(2, 3)\}$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 3 (Pengembangan — Determinan Matriks $3 \times 3$ Aturan Sarrus)

**Soal:**  
Tentukan determinan dari matriks $B = \begin{pmatrix} 1 & 2 & 1 \\ 2 & 1 & 0 \\ 3 & 1 & 2 \end{pmatrix}$!

**Penyelesaian:**
- **Diketahui:** Matriks $B$ ordo $3 \times 3$.
- **Ditanyakan:** $\det(B)$.
- **Langkah Penyelesaian:**
  Tuliskan ekspansi Sarrus dengan menjajarkan dua kolom pertama:
  $$
  \det(B) = \begin{vmatrix} 1 & 2 & 1 \\ 2 & 1 & 0 \\ 3 & 1 & 2 \end{vmatrix}
  \begin{matrix} 1 & 2 \\ 2 & 1 \\ 3 & 1 \end{matrix}
  $$
  - Jumlah diagonal utama:
    $$
    [1 \times 1 \times 2] + [2 \times 0 \times 3] + [1 \times 2 \times 1] = 2 + 0 + 2 = 4
    $$
  - Jumlah diagonal samping:
    $$
    [1 \times 1 \times 3] + [1 \times 0 \times 1] + [2 \times 2 \times 2] = 3 + 0 + 8 = 11
    $$
  - Nilai determinan:
    $$
    \det(B) = 4 - 11 = -7
    $$
- **Kesimpulan:** Nilai determinan matriks $B$ adalah $-7$ ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual (Studi Kasus Santri & Kehidupan Nyata)

#### Studi Kasus: Pengadaan Logistik Beras dan Kurma Asrama Santri
Santri bagian pengadaan dapur asrama pondok membeli beras ($x$ karung) dan kurma ($y$ kardus) di pasar grosir:
- **Pekan I:** Membeli $2$ karung beras dan $3$ kardus kurma seharga $\text{Rp } 420.000$.
- **Pekan II:** Membeli $3$ karung beras dan $4$ kardus kurma seharga $\text{Rp } 590.000$.

Berapa harga satuan $1$ karung beras dan $1$ kardus kurma dengan menggunakan Aturan Cramer?

**Langkah Pemecahan:**
1. **Membuat Model Matematika:**  
   $$
   \begin{cases}
   2x + 3y = 420.000 \\
   3x + 4y = 590.000
   \end{cases}
   $$

2. **Perhitungan Aturan Cramer:**
   - Determinan utama:
     $$
     D = \begin{vmatrix} 2 & 3 \\ 3 & 4 \end{vmatrix} = 2(4) - 3(3) = 8 - 9 = -1
     $$
   - Determinan variabel:
     $$
     D_x = \begin{vmatrix} 420.000 & 3 \\ 590.000 & 4 \end{vmatrix} = 4(420.000) - 3(590.000) = 1.680.000 - 1.770.000 = -90.000
     $$
     $$
     D_y = \begin{vmatrix} 2 & 420.000 \\ 3 & 590.000 \end{vmatrix} = 2(590.000) - 3(420.000) = 1.180.000 - 1.260.000 = -80.000
     $$
   - Hitung nilai satuan:
     $$
     x = \frac{D_x}{D} = \frac{-90.000}{-1} = 90.000, \quad y = \frac{D_y}{D} = \frac{-80.000}{-1} = 80.000
     $$

3. **Jawaban Kontekstual:**  
   Harga $1$ karung beras adalah **$\text{Rp } 90.000$** dan harga $1$ kardus kurma adalah **$\text{Rp } 80.000$** ($\checkmark\ \text{Benar}$).

---

### 6. Latihan Soal Kelas (Bertingkat & Terstruktur)

#### A. Latihan Terbimbing (Ikuti Langkah Contoh)
Kerjakan soal berikut dengan mengikuti urutan langkah pada contoh yang telah dipelajari:
1. Tentukan determinan dan invers dari matriks $A = \begin{pmatrix} 4 & 5 \\ 3 & 4 \end{pmatrix}$!
2. Tentukan nilai $x$ dan $y$ dari sistem berikut menggunakan Aturan Cramer:
   $$
   \begin{cases}
   4x + 3y = 18 \\
   2x - y = 4
   \end{cases}
   $$

#### B. Penguasaan Prosedur Inti (Latihan Bertahap)
Tentukan hasil perhitungan matriks dan SPLDV berikut secara runtut:
1. Tentukan invers dari matriks:
   $$
   B = \begin{pmatrix} 3 & -2 \\ -4 & 3 \end{pmatrix}
   $$
2. Hitung determinan matriks ordo $3 \times 3$ berikut:
   $$
   C = \begin{pmatrix} 2 & 1 & 3 \\ 1 & 0 & 2 \\ 2 & 1 & 1 \end{pmatrix}
   $$
3. Tentukan nilai $x$ dan $y$ dari sistem persamaan berikut:
   $$
   \begin{cases}
   5x + 2y = 19 \\
   3x + 4y = 17
   \end{cases}
   $$
4. Selesaikan persamaan matriks $A \cdot X = B$ untuk menentukan matriks kolom $X = \begin{pmatrix} x \\ y \end{pmatrix}$ jika diketahui $A = \begin{pmatrix} 3 & 1 \\ 5 & 2 \end{pmatrix}$ dan $B = \begin{pmatrix} 4 \\ 7 \end{pmatrix}$!
5. Tentukan nilai $p$ agar matriks $K = \begin{pmatrix} p+2 & 4 \\ 3 & p-2 \end{pmatrix}$ menjadi matriks singular!

#### C. Soal Cerita Kontekstual (Terpisah)
1. **Logistik Dapur Santri:**  
   Santri bagian logistik membeli bahan lauk sarapan santri:
   - Pembelian I: $4\text{ kg}$ telur ayam dan $5\text{ papan}$ tempe seharga $\text{Rp } 142.000$.
   - Pembelian II: $3\text{ kg}$ telur ayam dan $2\text{ papan}$ tempe seharga $\text{Rp } 85.000$.  
   Tentukan harga satuan $1\text{ kg}$ telur ayam dan $1\text{ papan}$ tempe dengan Aturan Cramer!
2. **Pengadaan Kitab di Perpustakaan:**  
   Pengurus perpustakaan membeli dua jenis kitab referensi:
   - Paket A: $3$ jilid kamus bahasa Arab dan $2$ jilid kitab fiqih seharga $\text{Rp } 240.000$.
   - Paket B: $2$ jilid kamus bahasa Arab dan $1$ jilid kitab fiqih seharga $\text{Rp } 150.000$.  
   Tentukan harga $1$ jilid kamus bahasa Arab dan $1$ jilid kitab fiqih menggunakan invers matriks!

#### D. Soal Tantangan (Opsional / Pengayaan)
1. Diberikan matriks persegi $M$ berordo $3 \times 3$ dengan determinan $\det(M) = 4$. Berdasarkan sifat-sifat determinan, tentukan nilai dari $\det(2M)$ dan $\det(M^{-1})$!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

- **Pola Kunci Invers $2 \times 2$:**
  - *"Tukar posisi diagonal utama, balikkan tanda diagonal samping, lalu kalikan dengan seperdeterminan."*
  - **Cara Cek Cepat Hasil:** Kalikan matriks asal $A$ dengan $A^{-1}$ yang kalian dapatkan. Hasilnya wajib membentuk matriks identitas $\begin{pmatrix} 1 & 0 \\ 0 & 1 \end{pmatrix}$.

- **Waspada Jebakan Umum:**
  - ⚠️ **Lupa Memeriksa $\det A$:** Jika $\det A = 0$, matriks tidak memiliki invers. Jangan membagi dengan nol!
  - ⚠️ **Kekeliruan Tanda Diagonal Samping:** Elemen diagonal samping $b$ dan $c$ hanya berganti tanda ($-b$ dan $-c$), posisinya tidak bertukar.
  - ⚠️ **Pengali Skalar pada Determinan:** Ingat bahwa untuk matriks berordo $n \times n$, berlaku $\det(k A) = k^n \det(A)$. Untuk ordo $3 \times 3$, $\det(2M) = 2^3 \det(M) = 8 \det(M)$ (bukan $2 \det(M)$!).

---

### 8. Pekerjaan Rumah (PR Pondok / Mandiri)

**Petunjuk:**
- Kerjakan di buku tugas matematika secara mandiri, sistematis, dan rapi.
- Setelah selesai, periksa hasil akhirmu dengan **Kunci Jawaban Akhir** di bawah.

1. Tentukan determinan dan invers dari matriks $A = \begin{pmatrix} 6 & 8 \\ 2 & 3 \end{pmatrix}$, kemudian buktikan bahwa $A \cdot A^{-1} = I$!
2. Selesaikan SPLDV berikut menggunakan Aturan Cramer:
   $$
   \begin{cases}
   4x - 3y = 17 \\
   2x + 5y = 2
   \end{cases}
   $$
3. **Persediaan Obat Herbal Poskestren:**  
   Pengurus pos kesehatan pesantren membeli dua jenis obat herbal:
   - Pembelian I: $3$ botol minyak habbatussauda dan $2$ botol madu murni seharga $\text{Rp } 190.000$.
   - Pembelian II: $4$ botol minyak habbatussauda dan $3$ botol madu murni seharga $\text{Rp } 265.000$.  
   Tentukan harga satuan $1$ botol minyak habbatussauda dan $1$ botol madu murni!
4. **Pertanyaan Pemahaman Konsep:**  
   *Jelaskan dengan kata-katamu sendiri: Mengapa sebuah matriks yang memiliki determinan nol ($\det A = 0$) tidak mungkin memiliki invers? Hubungkan penjelasanmu dengan konsep pembagian aljabar dan tafsiran geometris garis pada SPLDV!*

**Kunci Jawaban Akhir Ringkas (Cek Mandiri):**
- **Latihan 6A:** `1.` $\det A = 1, A^{-1} = \begin{pmatrix} 4 & -5 \\ -3 & 4 \end{pmatrix}$ | `2.` $(x, y) = (3, 2)$
- **Latihan 6B:** `1.` $B^{-1} = \begin{pmatrix} 3 & 2 \\ 4 & 3 \end{pmatrix}$ | `2.` $\det C = 4$ | `3.` $(x, y) = (3, 2)$ | `4.` $X = \begin{pmatrix} 1 \\ 1 \end{pmatrix}$ | `5.` $p = 4 \text{ atau } p = -4$
- **Latihan 6C:** `1.` Telur $=\text{Rp } 19.000/\text{kg}$, Tempe $=\text{Rp } 13.200/\text{papan}$ (atau dengan pembulatan ramah: $x = 20.000, y = 12.000$) | `2.` Kamus $=\text{Rp } 60.000$, Fiqih $=\text{Rp } 30.000$
- **PR No. 1–3:** `1.` $\det A = 2, A^{-1} = \begin{pmatrix} 1{,}5 & -4 \\ -1 & 3 \end{pmatrix}$ | `2.` $(x, y) = (3{,}5, -1)$ | `3.` Habbatussauda $=\text{Rp } 40.000$, Madu $=\text{Rp } 35.000$

---

### 9. Ringkasan Materi

- **Determinan Matriks**: Skalar yang mencerminkan faktor skala geometris. Untuk ordo $2 \times 2$, $\det(A) = ad - bc$. Untuk ordo $3 \times 3$, dihitung menggunakan aturan diagonal Sarrus.
- **Invers Matriks $2 \times 2$**: $A^{-1} = \frac{1}{\det(A)} \begin{pmatrix} d & -b \\ -c & a \end{pmatrix}$, terdefinisi jika dan hanya jika matriks bersifat non-singular ($\det(A) \neq 0$).
- **Aturan Cramer**: Penyelesaian instan SPLDV melalui perbandingan nilai determinan: $x = \frac{D_x}{D}$ dan $y = \frac{D_y}{D}$ (dengan $D \neq 0$).
- **Sifat Ordo Tinggi**: Determinan pengali skalar pada ordo $n$ memenuhi $\det(k A) = k^n \det(A)$, serta $\det(A \cdot B) = \det(A) \cdot \det(B)$ dan $(AB)^{-1} = B^{-1} A^{-1}$.
