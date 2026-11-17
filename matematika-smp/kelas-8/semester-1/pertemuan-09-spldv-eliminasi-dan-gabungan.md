# MATEMATIKA SMP KELAS 8 — SEMESTER GANJIL
## Pertemuan 9: SPLDV — Metode Eliminasi & Metode Gabungan (Koefisien Bulat dan Pecahan)

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Sebelum masuk ke cara baru menyelesaikan SPLDV, segarkan kembali dua keterampilan dari pertemuan sebelumnya:

1. Jika $x = 4$, tentukan nilai $y$ yang memenuhi persamaan $2x + y = 11$.
2. Kalikan seluruh suku pada persamaan $\frac{1}{2}x - y = 3$ dengan bilangan $2$.

*(Cek singkat: No. 1 = $y = 3$, No. 2 = $x - 2y = 6$)*

#### B. Pengertian & Analogi Nyata
Pada Pertemuan 8, kamu telah belajar mencari nilai $x$ dan $y$ pada Sistem Persamaan Linear Dua Variabel (SPLDV) menggunakan metode grafik dan substitusi. Namun, metode substitusi terkadang terasa merepotkan jika tidak ada variabel yang berkoefisien $1$.

Untuk mengatasinya, kita menggunakan **Metode Eliminasi** (menghilangkan salah satu variabel dengan menjumlahkan atau mengurangkan kedua persamaan) dan **Metode Gabungan** (mengeliminasi satu variabel terlebih dahulu, lalu mensubstitusikan hasilnya ke salah satu persamaan agar jauh lebih cepat).

**Analogi Sederhana:**
- **Belanja Paket Kantin:** Bayangkan Paket A berisi *2 roti + 1 susu* seharga Rp10.000, dan Paket B berisi *1 roti + 1 susu* seharga Rp7.000. Karena kedua paket sama-sama memuat **1 susu**, jika kita mengurangkan isi Paket A dengan Paket B, item **susu langsung hilang (tereliminasi)** sehingga tersisa harga *1 roti = Rp3.000*. Itulah ide utama metode eliminasi!

---

### 2. Bentuk Umum & Rumus Baku

Bentuk umum Sistem Persamaan Linear Dua Variabel (SPLDV) dalam variabel $x$ dan $y$ adalah:

$$\begin{cases} a_1 x + b_1 y = c_1 \\ a_2 x + b_2 y = c_2 \end{cases}$$

**Keterangan:**
- $x, y$ = variabel yang dicari nilainya (himpunan penyelesaian ditulis $\{(x, y)\}$)
- $a_1, a_2$ = koefisien dari variabel $x$ (boleh bilangan bulat maupun pecahan)
- $b_1, b_2$ = koefisien dari variabel $y$
- $c_1, c_2$ = konstanta di ruas kanan

---

### 3. Sifat-Sifat & Aturan Penting

Tiga aturan utama agar langkah eliminasi dan gabungan berjalan mulus tanpa salah hitung:

1. **Samakan Angka Koefisien Terlebih Dahulu:** Variabel hanya bisa dihilangkan jika angka di depannya sudah sama. Jika belum sama, kalikan salah satu atau kedua persamaan dengan bilangan bulat yang sesuai.
2. **Aturan Tanda Operasi (`+` atau `-`):**
   - Jika tanda koefisien yang disamakan **sama** (sama-sama `+` atau sama-sama `-`), lakukan **pengurangan (`-`)**.
   - Jika tanda koefisien yang disamakan **berlawanan** (satu `+` dan satu `-`), lakukan **penjumlahan (`+`)**.
3. **SPLDV Berkoefisien Pecahan:** Jika muncul koefisien pecahan, kalikan seluruh suku persamaan tersebut dengan **KPK dari penyebutnya** agar berubah menjadi persamaan berkoefisien bilangan bulat yang mudah dihitung.

| Kondisi Koefisien Target | Contoh Pasangan Suku | Operasi Eliminasi | Hasil |
| :--- | :--- | :--- | :---: |
| **Tanda Sama** | $+2y$ dan $+2y$ | Dikurangkan (`-`) | $0$ |
| **Tanda Berlawanan** | $+3y$ dan $-3y$ | Dijumlahkan (`+`) | $0$ |

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

#### Contoh 1 (Tingkat Dasar — Metode Eliminasi Murni, Koefisien Sudah Sama)
**Soal:**
Tentukan himpunan penyelesaian dari SPLDV berikut menggunakan **metode eliminasi**:

$$\begin{cases} 2x + y = 9 \\ x + y = 5 \end{cases}$$

**Penyelesaian:**
- **Diketahui:**
  - Persamaan (1): $2x + y = 9$
  - Persamaan (2): $x + y = 5$
- **Ditanyakan:** Himpunan penyelesaian $\{(x, y)\}$.
- **Langkah Penyelesaian:**
  1. **Eliminasi $y$ untuk mencari $x$** (koefisien $y$ sudah sama-sama $+1$, tanda sama $\rightarrow$ kurangkan):

     $$\begin{aligned} 2x + y &= 9 \\ x + y &= 5 \\ \hline x &= 4 \end{aligned}$$

  2. **Eliminasi $x$ untuk mencari $y$** (samakan koefisien $x$ dengan mengalikan Persamaan (2) dengan $2$, lalu kurangkan):

     $$\begin{aligned} 2x + 2y &= 10 \\ 2x + y &= 9 \\ \hline y &= 1 \end{aligned}$$

- **Kesimpulan:** Jadi, himpunan penyelesaiannya adalah $\{(4, 1)\}$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Variasi Ringan — Metode Gabungan dengan Menyamakan Koefisien)
**Soal:**
Tentukan nilai $x$ dan $y$ dari SPLDV berikut menggunakan **metode gabungan (eliminasi–substitusi)**:

$$\begin{cases} 2x + 3y = 16 \\ x - y = 3 \end{cases}$$

**Penyelesaian:**
- **Diketahui:**
  - Persamaan (1): $2x + 3y = 16$
  - Persamaan (2): $x - y = 3$
- **Ditanyakan:** Nilai $x$ dan $y$.
- **Langkah Penyelesaian:**
  1. **Tahap Eliminasi $y$:** Samakan koefisien $y$ menjadi $3$ dengan mengalikan Persamaan (2) dengan $3$:

     $$x - y = 3 \quad (\times 3) \longrightarrow 3x - 3y = 9$$

     Karena tanda pada $y$ berlawanan ($+3y$ dan $-3y$), **jumlahkan** kedua persamaan:

     $$\begin{aligned} 2x + 3y &= 16 \\ 3x - 3y &= 9 \\ \hline 5x &= 25 \\ x &= \frac{25}{5} = 5 \end{aligned}$$

  2. **Tahap Substitusi:** Masukkan $x = 5$ ke Persamaan (2) yang lebih sederhana:

     $$x - y = 3$$

     $$5 - y = 3$$

     $$-y = 3 - 5$$

     $$-y = -2 \implies y = 2$$

- **Kesimpulan:** Jadi, nilai $x = 5$ dan $y = 2$, atau himpunan penyelesaiannya $\{(5, 2)\}$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 3 (Variasi Pecahan Sederhana — Mengubah ke Bilangan Bulat dengan KPK)
**Soal:**
Tentukan penyelesaian dari SPLDV berkoefisien pecahan berikut dengan **metode gabungan**:

$$\begin{cases} \frac{1}{2}x + \frac{1}{3}y = 4 \\ x - y = 3 \end{cases}$$

**Penyelesaian:**
- **Diketahui:**
  - Persamaan (1): $\frac{1}{2}x + \frac{1}{3}y = 4$
  - Persamaan (2): $x - y = 3$
- **Ditanyakan:** Nilai $x$ dan $y$.
- **Langkah Penyelesaian:**
  1. **Hilangkan pecahan pada Persamaan (1):** Penyebutnya adalah $2$ dan $3$ sehingga $\text{KPK}(2, 3) = 6$. Kalikan seluruh suku Persamaan (1) dengan $6$:

     $$6\left(\frac{1}{2}x\right) + 6\left(\frac{1}{3}y\right) = 6(4)$$

     $$3x + 2y = 24 \quad \text{... (Persamaan 1 baru)}$$

  2. **Tahap Eliminasi $y$:** Kalikan Persamaan (2) dengan $2$ agar koefisien $y$ menjadi $-2$, lalu jumlahkan dengan Persamaan (1) baru:

     $$\begin{aligned} 3x + 2y &= 24 \\ 2x - 2y &= 6 \\ \hline 5x &= 30 \\ x &= 6 \end{aligned}$$

  3. **Tahap Substitusi:** Masukkan $x = 6$ ke Persamaan (2):

     $$6 - y = 3 \implies y = 3$$

- **Kesimpulan:** Jadi, himpunan penyelesaiannya adalah $\{(6, 3)\}$ ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual (Studi Kasus Nyata / Santri)

#### Contoh Kasus:
> Di kantin pondok pesantren, Ahmad membeli **2 buku tulis dan 1 pulpen** dengan membayar **Rp11.000**. Di tempat yang sama, Bilal membeli **3 buku tulis dan 2 pulpen** seharga **Rp18.000**. Berapakah harga satuan 1 buku tulis dan 1 pulpen?

**Langkah Pemecahan:**
1. **Membuat Model Matematika:**
   - Misalkan $x$ = harga 1 buku tulis, $y$ = harga 1 pulpen.
   - Persamaan (1): $2x + y = 11.000$
   - Persamaan (2): $3x + 2y = 18.000$
2. **Perhitungan (Metode Gabungan):**
   - Kalikan Persamaan (1) dengan $2$ untuk menyamakan koefisien $y$: $4x + 2y = 22.000$.
   - Eliminasi $y$ dengan pengurangan:

     $$\begin{aligned} 4x + 2y &= 22.000 \\ 3x + 2y &= 18.000 \\ \hline x &= 4.000 \end{aligned}$$

   - Substitusikan $x = 4.000$ ke Persamaan (1):

     $$2(4.000) + y = 11.000$$

     $$8.000 + y = 11.000 \implies y = 3.000$$

3. **Jawaban Kontekstual:**
   Jadi, harga 1 buku tulis adalah **Rp4.000** dan harga 1 pulpen adalah **Rp3.000**.

---

### 6. Latihan Soal Kelas (Bertingkat & Terstruktur)

Kerjakan soal-soal berikut di buku tugas dengan menuliskan langkah penyelesaian secara rapi.

#### A. Latihan Terbimbing (Ikuti Langkah Contoh)
1. Tentukan penyelesaian dari SPLDV berikut dengan **metode eliminasi**:

   $$\begin{cases} 3x + y = 13 \\ x + y = 7 \end{cases}$$

   *(Petunjuk: Koefisien $y$ sudah sama; kurangkan kedua persamaan untuk mencari $x$, lalu samakan koefisien $x$ untuk mencari $y$.)*

2. Tentukan nilai $x$ dan $y$ dari SPLDV berikut dengan **metode gabungan**:

   $$\begin{cases} 2x + 3y = 17 \\ x - y = 1 \end{cases}$$

   *(Petunjuk: Kalikan persamaan kedua dengan $3$, lalu jumlahkan kedua persamaan untuk mengeliminasi $y$.)*

#### B. Penguasaan Prosedur Inti (Latihan Bertahap)
Tentukan himpunan penyelesaian dari setiap SPLDV berikut menggunakan metode gabungan:

1. $\begin{cases} 4x + y = 11 \\ 2x + y = 7 \end{cases}$
2. $\begin{cases} x + 2y = 9 \\ x - 2y = 1 \end{cases}$
3. $\begin{cases} 2x + 3y = 19 \\ x + y = 7 \end{cases}$
4. $\begin{cases} 2x + 3y = 12 \\ 3x + 2y = 13 \end{cases}$
5. $\begin{cases} \frac{1}{2}x + \frac{1}{4}y = 4 \\ x + y = 12 \end{cases}$

#### C. Soal Cerita Kontekstual
1. Di kantin sekolah, harga **3 kotak susu dan 2 roti** adalah **Rp19.000**, sedangkan harga **1 kotak susu dan 2 roti** adalah **Rp9.000**. Tentukan harga 1 kotak susu dan harga 1 roti.
2. Jumlah dua bilangan bulat adalah **17**, sedangkan selisih kedua bilangan tersebut adalah **5**. Tentukan kedua bilangan tersebut.

#### D. Soal Tantangan *(Opsional / Pengayaan)*
*(Kerjakan hanya jika Bagian A–C sudah selesai dengan tuntas.)*

1. Diketahui sistem persamaan berkoefisien pecahan berikut:

   $$\begin{cases} \frac{x}{3} + \frac{y}{2} = 4 \\ \frac{x}{6} - \frac{y}{4} = 0 \end{cases}$$

   Tentukan nilai dari $x + y$!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

- **Pola Kunci yang Terbentuk:**
  - **"Samakan angkanya $\rightarrow$ Tanda SAMA dikurang, Tanda BEDA ditambah"** (*SaKur-BeTam*).
  - **Metode paling hemat waktu:** Cari variabel pertama dengan **eliminasi**, lalu segera **substitusikan** ke persamaan yang koefisiennya paling kecil untuk mendapat variabel kedua.
  - **Cara Cek Cepat Hasil:** Masukkan nilai $(x, y)$ hasil akhir ke **kedua persamaan awal** secara mental; keduanya harus bernilai benar.
- **Waspada Jebakan Umum:**
  - ⚠️ **Lupa mengalikan ruas kanan:** Saat menyamakan koefisien atau menghilangkan pecahan dengan KPK, seluruh suku termasuk angka di sebelah kanan tanda `=` **wajib ikut dikalikan**.
  - ⚠️ **Keliru tanda saat mengurangkan negatif:** Hati-hati jika mengurangkan suku bertanda minus, misalnya $2y - (-y) = 3y$, bukan $y$.

---

### 8. Pekerjaan Rumah (PR Pondok / Mandiri)

**Petunjuk:**
- Kerjakan di buku tugas masing-masing dengan langkah yang rapi.
- Setelah selesai, cocokkan hasil akhirmu dengan **Kunci Jawaban Akhir** di bawah untuk memastikan proses hitungmu sudah tepat.

1. Tentukan himpunan penyelesaian dari $\begin{cases} 5x + 2y = 19 \\ x + 2y = 7 \end{cases}$ menggunakan metode gabungan.
2. Tentukan himpunan penyelesaian dari $\begin{cases} \frac{1}{2}x + \frac{1}{3}y = 6 \\ x - y = 2 \end{cases}$.
3. **(Soal Cerita):** Harga **2 kg apel dan 1 kg jeruk** adalah **Rp46.000**, sedangkan harga **1 kg apel dan 2 kg jeruk** adalah **Rp44.000**. Tentukan harga 1 kg apel dan harga 1 kg jeruk.
4. **Pertanyaan Pemahaman Konsep:**
   *Jelaskan dengan kata-katamu sendiri: kapan kita menggunakan operasi penjumlahan (`+`) dan kapan menggunakan pengurangan (`-`) saat mengeliminasi sebuah variabel pada SPLDV? Berikan satu contoh kecil untuk masing-masing kondisi.*

**Kunci Jawaban Akhir Ringkas (Cek Mandiri):**
- **Latihan 6A:** `1.` $\{(3, 4)\}$ | `2.` $\{(4, 3)\}$
- **Latihan 6B:** `1.` $\{(2, 3)\}$ | `2.` $\{(5, 2)\}$ | `3.` $\{(2, 5)\}$ | `4.` $\{(3, 2)\}$ | `5.` $\{(4, 8)\}$
- **Latihan 6C:** `1.` Susu = Rp5.000, Roti = Rp2.000 | `2.` Bilangan tersebut adalah $11$ dan $6$
- **Latihan 6D (Opsional):** `1.` $x + y = 10$ (dari $x = 6$ dan $y = 4$)
- **PR No. 1–3:** `1.` $\{(3, 2)\}$ | `2.` $\{(8, 6)\}$ | `3.` Apel = Rp16.000/kg, Jeruk = Rp14.000/kg

---

### 9. Ringkasan Materi

- **Poin 1:** **Metode Eliminasi** menghilangkan satu variabel dengan menyamakan koefisiennya terlebih dahulu.
- **Poin 2:** Gunakan aturan **SaKur–BeTam** (tanda sama dikurangkan, tanda berlawanan dijumlahkan) dan ingat untuk mengalikan seluruh suku termasuk ruas kanan.
- **Poin 3:** Pada soal berkoefisien pecahan, kalikan seluruh persamaan dengan **KPK penyebutnya** terlebih dahulu sebelum mengeliminasi.
- **Poin 4:** **Metode Gabungan** memadukan eliminasi (untuk variabel pertama) dan substitusi (untuk variabel kedua) agar penyelesaian cepat, rapi, dan minim kesalahan hitung.
