# MATEMATIKA SMA (JURUSAN IPA & PESANTREN)
## Kelas 12 - Semester Ganjil
### Pertemuan 8: Statistika Deskriptif — Ukuran Penyebaran Data

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Kerjakan 2 soal kilat penyegar ingatan ukuran pemusatan berikut:
1. Diberikan data skor ujian: $6, 7, 8, 9, 10$. Tentukan nilai rata-rata hitung ($\bar{x}$) dan selisih antara nilai tertinggi dan terendah ($X_{\max} - X_{\min}$)!
2. Jika kuartil bawah $Q_1 = 60$ dan kuartil atas $Q_3 = 80$, hitunglah nilai dari $\frac{1}{2}(Q_3 - Q_1)$!

*(Cek singkat: No. 1 = $\bar{x} = 8$, selisih $= 4$; No. 2 = $10$)*

---

#### B. Pengertian Konsep Dasar

Dalam analisis statistika deskriptif, mengetahui nilai ukuran pemusatan (seperti Mean, Median, dan Modus) saja belum cukup untuk menggambarkan karakteristik suatu kelompok data secara utuh. Dua kelompok data bisa memiliki nilai rata-rata yang sama persis, namun memiliki sebaran data yang sangat berbeda.

Oleh karena itu, diperlukan **Ukuran Penyebaran Data (*Measures of Dispersion / Variability*)**, yaitu ukuran statistik yang menunjukkan seberapa jauh nilai-nilai data menyebar, menyimpang, atau merentang dari nilai pusatnya (terutama dari nilai rata-rata hitung $\bar{x}$).

**Urgensi Analisis Penyebaran (Homogenitas vs. Heterogenitas):**
- **Sebaran sempit (Homogen):** Nilai-nilai data mengumpul rapat di sekitar rata-rata, menunjukkan data yang stabil dan konsisten.
- **Sebaran lebar (Heterogen):** Nilai-nilai data terpencar jauh dari rata-rata, menunjukkan variasi data yang tinggi dan timpang.
- Mengukur penyebaran data sangat krusial dalam analisis data karena rata-rata tunggal tanpa ukuran dispersi dapat memberikan kesimpulan yang bias.

---

### 2. Bentuk Umum & Rumus Baku

#### A. Ukuran Penyebaran Sederhana (Berbasis Posisi)
1. **Jangkauan / Rentang (*Range* - $R$):**
   $$
   R = X_{\max} - X_{\min}
   $$
2. **Jangkauan Antarkuartil / Hamparan ($H$):**
   $$
   H = Q_3 - Q_1
   $$
3. **Simpangan Kuartil ($Q_d$):**
   $$
   Q_d = \frac{1}{2} H = \frac{1}{2}(Q_3 - Q_1)
   $$

---

#### B. Ukuran Penyebaran Berbasis Deviasi Rata-Rata & Kuadrat

1. **Simpangan Rata-Rata (*Mean Deviation* - $SR$):**
   - Data Tunggal:
     $$
     SR = \frac{\sum_{i=1}^n \lvert x_i - \bar{x} \rvert}{n}
     $$
   - Data Berkelompok:
     $$
     SR = \frac{\sum_{i=1}^k f_i \lvert x_i - \bar{x} \rvert}{\sum_{i=1}^k f_i}
     $$

2. **Ragam / Varians ($S^2$) dan Simpangan Baku / Standar Deviasi ($S$):**
   - Data Tunggal:
     $$
     S^2 = \frac{\sum_{i=1}^n (x_i - \bar{x})^2}{n}, \quad S = \sqrt{S^2}
     $$
   - Data Berkelompok:
     $$
     S^2 = \frac{\sum_{i=1}^k f_i (x_i - \bar{x})^2}{\sum_{i=1}^k f_i}, \quad S = \sqrt{S^2}
     $$

**Keterangan Simbol:**
- $\bar{x}$: Nilai rata-rata hitung (*Mean*).
- $x_i$: Nilai data ke-$i$ (atau titik tengah kelas ke-$i$ pada data berkelompok).
- $f_i$: Frekuensi kelas ke-$i$.
- $n = \sum f_i$: Total ukuran data sampel.

---

### 3. Sifat-Sifat & Aturan Penting

1. **Invariansi Ukuran Penyebaran Terhadap Pergeseran:**
   Jika setiap data ditambah atau dikurangi suatu konstanta $b$ ($x'_i = x_i \pm b$), nilai seluruh ukuran penyebaran ($R, H, Q_d, SR, S$) **sama sekali tidak berubah**:
   $$
   S_{\text{baru}} = S_{\text{lama}}
   $$
2. **Perubahan Skalar Terhadap Perkalian/Pembagian:**
   Jika setiap data dikalikan konstanta $a$ ($x'_i = a \cdot x_i$), maka:
   $$
   S_{\text{baru}} = \lvert a \rvert \cdot S_{\text{lama}} \quad \text{dan} \quad S^2_{\text{baru}} = a^2 \cdot S^2_{\text{lama}}
   $$
3. **Interpretasi Homogenitas Nilai $S$:**
   - $S$ kecil $\implies$ data mengumpul rapat di sekitar rata-rata (homogen, konsisten, stabil).
   - $S$ besar $\implies$ data tersebar menjauh dari rata-rata (heterogen, variatif, timpang).

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

#### Contoh 1 (Tingkat Dasar — Data Tunggal Angka Ramah Hitung)

**Soal:**  
Diberikan data nilai setoran hafalan nadzam dari $6$ orang santri:

$$
4, \ 5, \ 6, \ 7, \ 8, \ 12
$$

Tentukan:
a. Jangkauan ($R$)  
b. Simpangan Rata-Rata ($SR$)  
c. Varians ($S^2$) dan Simpangan Baku ($S$)!

**Penyelesaian:**
- **Langkah 1 (Menghitung Rata-rata Hitung $\bar{x}$):**
  $$
  \bar{x} = \frac{4 + 5 + 6 + 7 + 8 + 12}{6} = \frac{42}{6} = 7
  $$
- **Langkah 2 (Menghitung Jangkauan $R$):**
  $$
  R = X_{\max} - X_{\min} = 12 - 4 = 8
  $$
- **Langkah 3 (Menghitung Simpangan Rata-rata $SR$):**
  Selisih mutlak terhadap $\bar{x} = 7$:
  $$
  \lvert 4-7 \rvert + \lvert 5-7 \rvert + \lvert 6-7 \rvert + \lvert 7-7 \rvert + \lvert 8-7 \rvert + \lvert 12-7 \rvert = 3 + 2 + 1 + 0 + 1 + 5 = 12
  $$
  $$
  SR = \frac{12}{6} = 2
  $$
- **Langkah 4 (Menghitung Varians $S^2$ dan Simpangan Baku $S$):**
  Kuadrat selisih terhadap $\bar{x} = 7$:
  $$
  (4-7)^2 + (5-7)^2 + (6-7)^2 + (7-7)^2 + (8-7)^2 + (12-7)^2 = 9 + 4 + 1 + 0 + 1 + 25 = 40
  $$
  $$
  S^2 = \frac{40}{6} = \frac{20}{3} \approx 6{,}67
  $$
  $$
  S = \sqrt{\frac{20}{3}} = \frac{2}{3}\sqrt{15} \approx 2{,}58
  $$
- **Kesimpulan:** Nilai $R = 8$, $SR = 2$, dan $S = \frac{2}{3}\sqrt{15}$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Variasi Menengah — Data Berkelompok pada Tabel Frekuensi)

**Soal:**  
Tabel berikut menyajikan data durasi belajar mandiri (*muthala'ah*) santri:

| Interval Waktu | Frekuensi ($f_i$) |
| :---: | :---: |
| $21 - 25$ | $2$ |
| $26 - 30$ | $8$ |
| $31 - 35$ | $6$ |
| $36 - 40$ | $4$ |

Tentukan nilai Simpangan Rata-rata ($SR$) dari data berkelompok tersebut!

**Penyelesaian:**
- **Langkah 1 (Menyusun Tabel Penolong Komputasi $\bar{x}$):**

| Interval | $f_i$ | Titik Tengah ($x_i$) | $f_i x_i$ | $\lvert x_i - \bar{x} \rvert$ | $f_i \lvert x_i - \bar{x} \rvert$ |
| :---: | :---: | :---: | :---: | :---: | :---: |
| $21 - 25$ | $2$ | $23$ | $46$ | $8$ | $16$ |
| $26 - 30$ | $8$ | $28$ | $224$ | $3$ | $24$ |
| $31 - 35$ | $6$ | $33$ | $198$ | $2$ | $12$ |
| $36 - 40$ | $4$ | $38$ | $152$ | $7$ | $28$ |
| **Total** | **$20$** | — | **$620$** | — | **$80$** |

- **Langkah 2 (Menghitung Mean $\bar{x}$):**
  $$
  \bar{x} = \frac{620}{20} = 31
  $$
- **Langkah 3 (Menghitung Simpangan Rata-rata $SR$):**
  $$
  SR = \frac{\sum f_i \lvert x_i - \bar{x} \rvert}{\sum f_i} = \frac{80}{20} = 4
  $$
- **Kesimpulan:** Nilai simpangan rata-rata dari data kelompok tersebut adalah $4$ menit ($\checkmark\ \text{Benar}$).

---

#### Contoh 3 (Pengembangan — Transformasi Linier Data)

**Soal:**  
Sekumpulan data memiliki rata-rata $\bar{x} = 20$ dan simpangan baku $S = 4$. Jika setiap nilai data dikalikan $2$ kemudian dikurangi $5$, tentukan nilai rata-rata dan simpangan baku data yang baru!

**Penyelesaian:**
- **Diketahui:** $y_i = 2x_i - 5$, dengan $\bar{x} = 20$ dan $S_x = 4$.
- **Langkah Penyelesaian:**
  1. *Rata-rata Baru:* Terpengaruh oleh perkalian dan pengurangan:
     $$
     \bar{y} = 2\bar{x} - 5 = 2(20) - 5 = 35
     $$
  2. *Simpangan Baku Baru:* Hanya terpengaruh oleh faktor pengali:
     $$
     S_y = \lvert 2 \rvert \cdot S_x = 2 \times 4 = 8
     $$
- **Kesimpulan:** Rata-rata yang baru adalah $35$ dan simpangan baku yang baru adalah $8$ ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual (Studi Kasus Santri & Kehidupan Nyata)

#### Studi Kasus: Evaluasi Konsistensi Dua Halaqah Tahfiz Al-Qur'an
Ustadz pembina tahfiz membandingkan capaian hafalan Al-Qur'an santri pada dua halaqah:
- **Halaqah Abu Bakar ($5$ santri):** $14, 15, 15, 15, 16\text{ juz}$.
- **Halaqah Umar ($5$ santri):** $6, 10, 15, 20, 24\text{ juz}$.

1. Hitung nilai rata-rata hafalan masing-masing halaqah!
2. Hitung simpangan baku ($S$) masing-masing halaqah!
3. Halaqah manakah yang capaian santrinya lebih merata dan teratur?

**Langkah Pemecahan:**
1. **Rata-Rata:**
   - Halaqah Abu Bakar: $\bar{x}_1 = \frac{14+15+15+15+16}{5} = \frac{75}{5} = 15\text{ juz}$.
   - Halaqah Umar: $\bar{x}_2 = \frac{6+10+15+20+24}{5} = \frac{75}{5} = 15\text{ juz}$.
2. **Simpangan Baku:**
   - Halaqah Abu Bakar:
     $$
     \sum (x_i - 15)^2 = (-1)^2 + 0^2 + 0^2 + 0^2 + 1^2 = 2 \implies S_1 = \sqrt{\frac{2}{5}} = \sqrt{0{,}4} \approx 0{,}63\text{ juz}
     $$
   - Halaqah Umar:
     $$
     \sum (x_i - 15)^2 = (-9)^2 + (-5)^2 + 0^2 + 5^2 + 9^2 = 81 + 25 + 0 + 25 + 81 = 212
     $$
     $$
     S_2 = \sqrt{\frac{212}{5}} = \sqrt{42{,}4} \approx 6{,}51\text{ juz}
     $$
3. **Analisis Kontekstual:**  
   Walaupun rata-rata kedua kelompok sama ($15\text{ juz}$), Halaqah Abu Bakar memiliki $S = 0{,}63$ yang jauh lebih kecil daripada Halaqah Umar ($S = 6{,}51$). Ini membuktikan Halaqah Abu Bakar memiliki capaian yang sangat homogen dan konsisten, sedangkan Halaqah Umar mengalami ketimpangan belajar yang lebar sehingga santri dengan hafalan rendah membutuhkan bimbingan intensif.

---

### 6. Latihan Soal Kelas (Bertingkat & Terstruktur)

#### A. Latihan Terbimbing (Ikuti Langkah Contoh)
Kerjakan soal berikut dengan mengikuti urutan langkah pada contoh yang telah dipelajari:
1. Diberikan data tunggal: $3, 4, 5, 6, 7$. Tentukan nilai rata-rata hitung ($\bar{x}$), jangkauan ($R$), dan simpangan rata-ratanya ($SR$)!
2. Hitung nilai Simpangan Rata-rata dari tabel frekuensi berikut:
   | Nilai | Frekuensi |
   | :---: | :---: |
   | $1 - 5$ | $3$ |
   | $6 - 10$ | $5$ |
   | $11 - 15$ | $2$ |

#### B. Penguasaan Prosedur Inti (Latihan Bertahap)
1. Diberikan data: $6, 7, 8, 8, 9, 10, 12, 12$. Tentukan nilai Jangkauan ($R$), Jangkauan Antarkuartil ($H$), dan Simpangan Kuartil ($Q_d$)!
2. Tentukan nilai varians ($S^2$) dan simpangan baku ($S$) dari data nilai santri: $2, 4, 6, 8, 10$!
3. Diberikan data berukuran $n = 5$ dengan rata-rata $10$ dan varians $4$. Jika setiap data dikalikan $3$ kemudian ditambah $7$, tentukan rata-rata dan simpangan baku yang baru!
4. Tentukan simpangan rata-rata dari tabel data berkelompok berikut:
   | Interval | Frekuensi |
   | :---: | :---: |
   | $10 - 14$ | $4$ |
   | $15 - 19$ | $6$ |
   | $20 - 24$ | $7$ |
   | $25 - 29$ | $3$ |
5. Hitung varians dan simpangan baku dari data: $5, 5, 5, 5, 5$. Berikan penjelasan logis atas nilai yang didapat!

#### C. Soal Cerita Kontekstual (Terpisah)
1. **Pemeriksaan Suhu Tubuh Santri:**  
   Pengurus poskestren mencatat suhu tubuh (dalam $^\circ\text{C}$) santri yang sakit: $36{,}2; 36{,}4; 36{,}5; 36{,}5; 36{,}6; 36{,}8; 37{,}0$. Tentukan jangkauan ($R$) dan simpangan rata-rata suhu tubuh santri tersebut!
2. **Kedisiplinan Waktu Tidur Santri Asrama:**  
   Pengurus membandingkan kedisiplinan waktu tidur santri di dua kamar:
   - Kamar A: Rata-rata pukul $22.00$ dengan simpangan baku $S = 6\text{ menit}$.
   - Kamar B: Rata-rata pukul $22.00$ dengan simpangan baku $S = 25\text{ menit}$.  
   Kamar manakah yang memiliki keteraturan waktu tidur lebih konsisten? Jelaskan dampaknya bagi ketenangan malam di asrama!

#### D. Soal Tantangan (Opsional / Pengayaan)
1. Diketahui lima buah bilangan bulat berurutan: $n, n+1, n+2, n+3, n+4$. Tunjukkan secara aljabar bahwa simpangan baku dari kelima bilangan tersebut selalu bernilai $\sqrt{2}$ tanpa terpengaruh oleh berapapun nilai $n$!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

- **Pola Kunci Pemahaman Dispersi:**
  - Simpangan Rata-rata menggunakan nilai mutlak jarak $\lvert x_i - \bar{x} \rvert$.
  - Varians menggunakan kuadrat jarak $(x_i - \bar{x})^2$.
  - Simpangan Baku adalah akar dari varians ($S = \sqrt{S^2}$).
  - **Cara Cek Cepat:** Nilai simpangan baku dan varians tidak pernah bertanda negatif ($S \ge 0$). Jika data seluruhnya identik (konstan), maka $S = 0$.

- **Waspada Jebakan Umum:**
  - ⚠️ **Lupa Membagi dengan Total Frekuensi ($n$):** Pada data kelompok, pembaginya adalah $\sum f_i$, bukan banyaknya baris kelas.
  - ⚠️ **Kekeliruan Efek Transformasi:** Menambah/mengurangi konstanta **tidak pernah mengubah** $S$ ($S_{\text{baru}} = S_{\text{lama}}$). Hanya perkalian/pembagian yang mengubah nilai $S$.

---

### 8. Pekerjaan Rumah (PR Pondok / Mandiri)

**Petunjuk:**
- Kerjakan di buku catatan tugas matematika secara tertib dan mandiri.
- Periksa hasil akhirmu dengan mencocokkan pada kunci jawaban di bawah.

1. Tentukan nilai $R, SR, S^2,$ dan $S$ dari data nilai ujian fiqih berikut:
   $$
   5, \ 6, \ 7, \ 8, \ 9
   $$
2. Diberikan tabel distribusi frekuensi:
   | Nilai | Frekuensi |
   | :---: | :---: |
   | $61 - 65$ | $3$ |
   | $66 - 70$ | $5$ |
   | $71 - 75$ | $8$ |
   | $76 - 80$ | $4$ |
   Hitunglah nilai rata-rata ($\bar{x}$) dan Simpangan Rata-rata ($SR$)-nya!
3. **Produksi Kebun Buah Tin Pesantren:**  
   Catatan panen buah tin pada $5$ pohon percontohan (dalam ons) adalah: $32, 34, 35, 36, 38$. Hitung jangkauan ($R$) dan simpangan bakunya ($S$)!
4. **Pertanyaan Pemahaman Konsep:**  
   *Jelaskan dengan kata-katamu sendiri: Mengapa nilai rata-rata (mean) saja tidak cukup untuk menggambarkan kualitas suatu kelompok data? Mengapa kita perlu menghitung simpangan baku (standar deviasi)? Berikan satu contoh konkret di lingkungan pondok pesantren!*

**Kunci Jawaban Akhir Ringkas (Cek Mandiri):**
- **Latihan 6A:** `1.` $\bar{x} = 5, R = 4, SR = 1{,}2$ | `2.` $\bar{x} = 7{,}5, SR = 2{,}8$
- **Latihan 6B:** `1.` $R = 6, H = 4, Q_d = 2$ | `2.` $S^2 = 8, S = 2\sqrt{2}$ | `3.` $\bar{x}_{\text{baru}} = 37, S_{\text{baru}} = 6$ | `4.` $\bar{x} = 19, SR = 3{,}8$ | `5.` $S^2 = 0, S = 0$ (karena tidak ada variasi sebaran data)
- **Latihan 6C:** `1.` $R = 0{,}8^\circ\text{C}, SR \approx 0{,}19^\circ\text{C}$ | `2.` Kamar A lebih konsisten karena simpangan bakunya jauh lebih kecil ($6 < 25$)
- **PR No. 1–3:** `1.` $R = 4, SR = 1{,}2, S^2 = 2, S = \sqrt{2}$ | `2.` $\bar{x} = 70{,}75, SR = 3{,}7$ | `3.` $R = 6\text{ ons}, S = 2\text{ ons}$

---

### 9. Ringkasan Materi

- **Ukuran Penyebaran Data**: Menilai derajat heterogenitas data di sekitar nilai rata-ratanya, mencakup Jangkauan ($R$), Simpangan Kuartil ($Q_d$), Simpangan Rata-rata ($SR$), Varians ($S^2$), dan Simpangan Baku ($S$).
- **Simpangan Baku ($S$)**: Tolok ukur utama dispersi; semakin kecil nilai $S$, data semakin seragam, homogen, dan konsisten.
- **Sifat Ketahanan Dispersi**: Penjumlahan/pengurangan konstanta tidak mempengaruhi ukuran penyebaran ($S_{\text{baru}} = S_{\text{lama}}$).
- **Efek Perkalian Skalar**: Seluruh ukuran penyebaran linier terpengaruh sebesar nilai mutlak faktor pengali ($S_{\text{baru}} = \lvert a \rvert S_{\text{lama}}$) dan varians terpengaruh kuadratnya ($S^2_{\text{baru}} = a^2 S^2_{\text{lama}}$).
