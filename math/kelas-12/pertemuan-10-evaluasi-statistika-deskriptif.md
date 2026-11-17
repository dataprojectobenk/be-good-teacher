# MATEMATIKA SMA (JURUSAN IPA & PESANTREN)
## Kelas 12 - Semester Ganjil
### Pertemuan 10: Evaluasi Mandiri — Ulangan Harian Statistika Deskriptif

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Kerjakan 2 butir soal kilat penyegar ingatan komprehensif berikut:
1. Jika terdapat kumpulan data berukuran $n = 100$ data, tentukan banyak kelas interval ideal ($k$) berdasarkan Aturan Sturges $k \approx 1 + 3{,}3 \log n$ (diketahui $\log 100 = 2$)!
2. Jika kuartil pertama $Q_1 = 45$ dan kuartil ketiga $Q_3 = 75$, tentukan jangkauan antarkuartil ($H$) dan simpangan kuartilnya ($Q_d$)!

*(Cek singkat: No. 1 = $k \approx 1 + 3{,}3(2) = 7{,}6 \approx 8\text{ kelas}$; No. 2 = $H = 30, Q_d = 15$)*

---

#### B. Pengertian & Analogi Nyata

Evaluasi mandiri (*self-assessment*) adalah proses pengujian yang dilakukan secara sadar, jujur, dan bertanggung jawab untuk mengukur sejauh mana seluruh konsep, formula, dan keterampilan analitis statistika deskriptif telah dikuasai dengan benar.

Dalam tradisi keilmuan Islam dan kehidupan pondok pesantren, evaluasi merupakan perwujudan dari konsep **muhasabah** (introspeksi diri secara objektif). Allah SWT berfirman:

> *"Wahai orang-orang yang beriman! Bertakwalah kepada Allah dan hendaklah setiap diri memperhatikan apa yang telah diperbuatnya untuk hari esok (akhirat), dan bertakwalah kepada Allah. Sungguh, Allah Mahateliti terhadap apa yang kamu kerjakan."*  
> **(QS. Al-Hasyr: 18)**

Sebagaimana hisab di akhirat didasarkan pada catatan amal yang teliti tanpa ada yang terlewat sedikit pun (*QS. Al-Kahfi: 49*), penguasaan statistika deskriptif menguji kemampuan santri dalam membaca, menyajikan, dan menghitung data riil secara presisi. Modul Pertemuan 10 ini menyatukan lima pilar statistika deskriptif:
1. **Penyajian Data**: Tabel distribusi frekuensi, histogram, poligon, dan kurva ogive.
2. **Ukuran Pemusatan**: Rata-rata hitung (*Mean* $\bar{x}$), nilai tengah (*Median* $Me$), dan nilai dominan (*Modus* $Mo$).
3. **Ukuran Letak**: Kuartil ($Q_1, Q_2, Q_3$), Desil ($D_i$), dan Persentil ($P_i$).
4. **Ukuran Penyebaran**: Jangkauan ($R$), Simpangan Kuartil ($Q_d$), Simpangan Rata-rata ($SR$), Varians ($S^2$), dan Simpangan Baku ($S$).
5. **Analisis Relatif & Sebaran**: Angka Baku ($Z$-score), Koefisien Variasi ($KV$), dan identifikasi kemiringan kurva (*skewness*).

---

### 2. Bentuk Umum & Rumus Baku

Berikut adalah lembar kompilasi formula resmi (*Master Formula Sheet*):

#### A. Pembentukan Distribusi Frekuensi Data Berkelompok (Aturan Sturges)
Untuk data mentah berukuran $n$ dengan jangkauan $R = X_{\max} - X_{\min}$:
$$
k \approx 1 + 3{,}3 \log n \quad \text{dan} \quad p = \frac{R}{k}
$$

#### B. Ukuran Pemusatan Data
- **Mean Data Tunggal & Kelompok:**
  $$
  \bar{x} = \frac{\sum x_i}{n} \quad \text{atau} \quad \bar{x} = \frac{\sum f_i x_i}{\sum f_i}
  $$
- **Median & Modus Kelompok:**
  $$
  Me = L + \left( \frac{\frac{n}{2} - F}{f_{me}} \right) p, \quad Mo = L + \left( \frac{d_1}{d_1 + d_2} \right) p
  $$

#### C. Ukuran Letak & Penyebaran
- **Kuartil ke-$k$ ($k \in \{1, 2, 3\}$):**
  $$
  Q_k = L + \left( \frac{\frac{k \cdot n}{4} - F}{f_q} \right) p
  $$
- **Simpangan Baku ($S$):**
  $$
  S = \sqrt{\frac{\sum f_i (x_i - \bar{x})^2}{\sum f_i}}
  $$
- **Angka Baku ($Z$-score) & Koefisien Variasi ($KV$):**
  $$
  Z = \frac{x - \bar{x}}{S}, \quad KV = \frac{S}{\bar{x}} \times 100\%
  $$

---

### 3. Sifat-Sifat & Aturan Penting

1. **Invariansi Ukuran Sebaran Terhadap Pergeseran:**
   Jika seluruh data ditambah atau dikurang konstanta yang sama, nilai ukuran penyebaran ($R, Q_d, SR, S$) **tidak berubah** ($S_{\text{baru}} = S_{\text{lama}}$). Namun jika dikali atau dibagi, ukuran sebaran ikut membesar atau mengecil secara proporsional.
2. **Karakteristik Kurva Kemiringan (*Skewness*):**
   - Simetris: $\bar{x} \approx Me \approx Mo$.
   - Miring Positif (Kanan): $Mo < Me < \bar{x}$.
   - Miring Negatif (Kiri): $\bar{x} < Me < Mo$.

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

#### Contoh 1 (Tingkat Dasar — Evaluasi Komprehensif Data Tunggal Santri)

**Soal:**  
Tercatat skor penilaian kelancaran membaca kitab kuning dari $10$ orang santri teladan:

$$
68, \ 70, \ 74, \ 74, \ 74, \ 78, \ 82, \ 84, \ 86, \ 90
$$

Tentukan:  
a. Mean ($\bar{x}$), Median ($Me$), dan Modus ($Mo$).  
b. Kuartil Bawah ($Q_1$), Kuartil Atas ($Q_3$), dan Simpangan Kuartil ($Q_d$).  
c. Simpangan Rata-rata ($SR$) dan Simpangan Baku ($S$).

**Penyelesaian:**
- **Langkah 1 (Ukuran Pemusatan):**
  - $\bar{x} = \frac{68 + 70 + 74 + 74 + 74 + 78 + 82 + 84 + 86 + 90}{10} = \frac{780}{10} = 78$
  - Letak median antara data ke-5 ($74$) dan ke-6 ($78$): $Me = \frac{74 + 78}{2} = 76$
  - Modus adalah $74$ (muncul $3$ kali).
- **Langkah 2 (Ukuran Letak):**
  - Paruh bawah ($68, 70, \mathbf{74}, 74, 74$) $\implies Q_1 = 74$.
  - Paruh atas ($78, 82, \mathbf{84}, 86, 90$) $\implies Q_3 = 84$.
  - Simpangan Kuartil: $Q_d = \frac{1}{2}(84 - 74) = 5$.
- **Langkah 3 (Ukuran Penyebaran):**
  - Selisih mutlak $\sum \lvert x_i - 78 \rvert = 10 + 8 + 4 + 4 + 4 + 0 + 4 + 6 + 8 + 12 = 60 \implies SR = \frac{60}{10} = 6$.
  - Kuadrat selisih $\sum (x_i - 78)^2 = 100 + 64 + 16 + 16 + 16 + 0 + 16 + 36 + 64 + 144 = 472$.
  - $S^2 = \frac{472}{10} = 47{,}2 \implies S = \sqrt{47{,}2} \approx 6{,}87$.
- **Kesimpulan:** Nilai $\bar{x} = 78$, $Me = 76$, $Mo = 74$, $Q_d = 5$, $SR = 6$, dan $S \approx 6{,}87$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Variasi Menengah — Evaluasi Data Berkelompok pada Tabel Distribusi)

**Soal:**  
Tabel berikut menyajikan data durasi belajar mandiri (*muthala'ah*) santri:

| Durasi (Menit) | Frekuensi ($f_i$) |
| :---: | :---: |
| $41 - 50$ | $6$ |
| $51 - 60$ | $12$ |
| $61 - 70$ | $18$ |
| $71 - 80$ | $10$ |
| $81 - 90$ | $4$ |

Tentukan nilai Mean ($\bar{x}$) dan Modus ($Mo$) dari data tersebut!

**Penyelesaian:**
- **Langkah 1 (Menghitung Mean):**
  - Titik tengah ($x_i$): $45{,}5; \ 55{,}5; \ 65{,}5; \ 75{,}5; \ 85{,}5$.
  - $\sum f_i x_i = 6(45{,}5) + 12(55{,}5) + 18(65{,}5) + 10(75{,}5) + 4(85{,}5) = 273 + 666 + 1.179 + 755 + 342 = 3.215$.
  - $\bar{x} = \frac{3.215}{50} = 64{,}3\text{ menit}$.
- **Langkah 2 (Menghitung Modus):**
  - Kelas modus adalah $61 - 70$ ($f = 18$).
  - Tepi bawah $L = 60{,}5$, panjang kelas $p = 10$.
  - $d_1 = 18 - 12 = 6$, $d_2 = 18 - 10 = 8$.
  - $Mo = 60{,}5 + \left(\frac{6}{6 + 8}\right) 10 = 60{,}5 + \frac{60}{14} = 60{,}5 + 4{,}29 = 64{,}79\text{ menit}$.
- **Kesimpulan:** Rata-rata durasi belajar adalah $64{,}3$ menit dan nilai modus adalah $64{,}79$ menit ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual (Studi Kasus Santri & Kehidupan Nyata)

#### Studi Kasus: Evaluasi Dispersi Nilai Ujian Tahfiz Antarkelas
Pembina tahfiz mendata nilai ujian musabaqah hifdzil Qur'an dari dua halaqah santri:
- **Kelas Al-Fatih:** Rata-rata $\bar{x}_1 = 80$ dengan simpangan baku $S_1 = 4$.
- **Kelas An-Nasr:** Rata-rata $\bar{x}_2 = 75$ dengan simpangan baku $S_2 = 7{,}5$.

Seorang santri bernama Abdullah dari Kelas Al-Fatih memperoleh skor $88$, sedangkan Zaid dari Kelas An-Nasr memperoleh skor $87$.
1. Hitung Koefisien Variasi ($KV$) masing-masing kelas!
2. Kelas manakah yang hafalannya lebih homogen dan stabil?
3. Siapakah yang memiliki pencapaian relatif lebih tinggi terhadap teman sekelasnya berdasarkan $Z$-score?

**Langkah Pemecahan:**
1. **Koefisien Variasi:**
   $$
   KV_1 = \frac{4}{80} \times 100\% = 5\%, \quad KV_2 = \frac{7{,}5}{75} \times 100\% = 10\%
   $$
2. **Tingkat Homogenitas:**  
   Kelas Al-Fatih lebih homogen dan konsisten karena $KV_1 = 5\% < 10\%$.
3. **Analisis $Z$-score:**
   $$
   Z_{\text{Abdullah}} = \frac{88 - 80}{4} = +2{,}0
   $$
   $$
   Z_{\text{Zaid}} = \frac{87 - 75}{7{,}5} = \frac{12}{7{,}5} = +1{,}6
   $$
   Pencapaian Abdullah ($+2{,}0$) relatif lebih unggul terhadap teman sekelasnya dibandingkan Zaid ($+1{,}6$).

---

### 6. Latihan Soal Kelas (Uji Evaluasi Bertingkat)

#### A. Latihan Terbimbing (Ikuti Langkah Contoh)
Kerjakan soal berikut dengan mengikuti urutan langkah pada contoh yang telah dipelajari:
1. Diberikan data skor tes nahwu santri: $7, 5, 8, 9, 7, 6, 10, 7, 8, 6$. Tentukan nilai Mean ($\bar{x}$), Median ($Me$), dan Modus ($Mo$)!
2. Diberikan tabel distribusi frekuensi:
   | Nilai Ujian | Frekuensi ($f_i$) |
   | :---: | :---: |
   | $51 - 60$ | $6$ |
   | $61 - 70$ | $14$ |
   | $71 - 80$ | $16$ |
   | $81 - 90$ | $4$ |
   Tentukan nilai Median ($Me$) dan Modus ($Mo$) dari tabel tersebut!

#### B. Penguasaan Prosedur Inti (Latihan Bertahap)
1. Dari data pada soal 6A nomor 1, tentukan jangkauan ($R$), simpangan kuartil ($Q_d$), dan simpangan rata-rata ($SR$)-nya!
2. Tentukan nilai varians ($S^2$) dan simpangan baku ($S$) dari data: $4, 6, 8, 10, 12$!
3. Dari tabel distribusi pada soal 6A nomor 2, hitunglah nilai Kuartil Bawah ($Q_1$) dan Kuartil Atas ($Q_3$)!
4. Nilai ujian musabaqah santri memiliki rata-rata $82$ dengan simpangan baku $6$. Seorang santri bernama Hasan memperoleh skor $94$. Tentukan nilai $Z$-score Hasan!
5. Sekumpulan data berukuran $n = 8$ memiliki rata-rata $15$ dan simpangan baku $3$. Jika setiap data dikalikan $2$ lalu ditambah $5$, tentukan rata-rata dan simpangan baku yang baru!

#### C. Soal Cerita Kontekstual (Terpisah)
1. **Pengukuran Suhu Tubuh Poskestren:**  
   Pengurus poskestren mencatat suhu tubuh (dalam $^\circ\text{C}$) santri yang flu: $36{,}8; \ 37{,}2; \ 37{,}5; \ 37{,}5; \ 38{,}0; \ 38{,}4^\circ\text{C}$. Tentukan modus ($Mo$) dan simpangan kuartil ($Q_d$) suhu tubuh santri!
2. **Donasi Infak Sukarela Alumni:**  
   Panitia hari santri mendata donasi infak sukarela dari $6$ alumni: $\text{Rp } 100.000, \text{Rp } 150.000, \text{Rp } 150.000, \text{Rp } 200.000, \text{Rp } 250.000, \text{Rp } 350.000$. Tentukan nilai rata-rata dan simpangan bakunya!

#### D. Soal Tantangan (Opsional / Pengayaan)
1. Suatu kurva distribusi frekuensi nilai ujian santri memiliki rata-rata $\bar{x} = 72$, median $Me = 75$, dan modus $Mo = 80$. Selidikilah bentuk kemiringan kurva distribusinya (*skewness*) dan jelaskan artinya bagi kualitas pemahaman santri!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

- **Checklist Verifikasi Mandiri (Sanity Check):**
  1. Apakah nilai Median dan Modus berada di dalam rentang kelas interval yang terpilih?
  2. Apakah nilai Simpangan Kuartil $Q_d \le \text{Jangkauan } R$?
  3. Apakah tanda $Z$-score konsisten dengan posisinya terhadap rata-rata? (Positif jika $x > \bar{x}$).

- **Waspada Jebakan Umum:**
  - ⚠️ **Kekeliruan Invariansi:** Jangan mengalikan simpangan baku dengan konstanta penambahan! $S(x + 5) = S(x)$, bukan $S(x) + 5$.
  - ⚠️ **Tertukar Tepi Bawah ($L$):** Tepi bawah kelas interval $61 - 70$ adalah $60{,}5$ (bukan $61$).

---

### 8. Pekerjaan Rumah (PR Pondok / Remedial & Pengayaan Mandiri)

**Petunjuk:**
- Kerjakan di buku tugas matematika secara tertib, teliti, dan mandiri.
- Periksa hasil akhirmu dengan mencocokkan pada kunci jawaban di bawah.

1. Tentukan Mean, Median, Modus, dan Simpangan Baku dari data setoran hafalan:
   $$
   60, \ 65, \ 70, \ 75, \ 75, \ 80, \ 85, \ 90
   $$
2. Perhatikan tabel nilai ujian tafsir:
   | Rentang Nilai | Frekuensi ($f_i$) |
   | :---: | :---: |
   | $51 - 60$ | $4$ |
   | $61 - 70$ | $8$ |
   | $71 - 80$ | $14$ |
   | $81 - 90$ | $10$ |
   | $91 - 100$ | $4$ |
   Tentukan nilai Mean ($\bar{x}$) dan Median ($Me$)!
3. **Komparasi Disiplin Asrama Santri:**  
   Pembina asrama mendata poin pelanggaran santri:
   - Asrama Al-Farabi: Rata-rata $= 14$ dengan simpangan baku $S = 2{,}1$
   - Asrama Ibnu Rusyd: Rata-rata $= 14$ dengan simpangan baku $S = 6{,}3$  
   Hitung Koefisien Variasi masing-masing asrama dan simpulkan asrama mana yang kedisiplinannya lebih merata!
4. **Pertanyaan Pemahaman Konsep:**  
   *Jelaskan hubungan filosofis antara hisab yang adil dalam pandangan Islam dengan pentingnya menyajikan ukuran pemusatan (Mean/Median) secara berdampingan dengan ukuran penyebaran (Simpangan Baku/Varians)! Mengapa pengambil kebijakan di pesantren tidak boleh hanya mengandalkan nilai rata-rata semata?*

**Kunci Jawaban Akhir Ringkas (Cek Mandiri):**
- **Latihan 6A:** `1.` $\bar{x} = 7{,}3, Me = 7, Mo = 7$ | `2.` $Me = 70{,}5, Mo = 71{,}83$
- **Latihan 6B:** `1.` $R = 5, Q_d = 1, SR = 1{,}14$ | `2.` $S^2 = 8, S = 2\sqrt{2}$ | `3.` $Q_1 = 63{,}36, Q_3 = 76{,}75$ | `4.` $Z = +2{,}0$ | `5.` $\bar{x}_{\text{baru}} = 35, S_{\text{baru}} = 6$
- **Latihan 6C:** `1.` $Mo = 37{,}5^\circ\text{C}, Q_d = 0{,}4^\circ\text{C}$ | `2.` $\bar{x} = \text{Rp } 200.000, S = \text{Rp } 81.650$
- **PR No. 1–3:** `1.` $\bar{x} = 75, Me = 75, Mo = 75, S \approx 9{,}35$ | `2.` $\bar{x} = 75{,}5, Me = 76{,}21$ | `3.` $KV_1 = 15\%, KV_2 = 45\%$ (Asrama Al-Farabi jauh lebih tertib dan merata)

---

### 9. Ringkasan Materi

- **Hakikat Evaluasi Statistika**: Mengukur penguasaan materi secara menyeluruh dan jujur, mengintegrasikan kemampuan penyajian, pemusatan, fraktil letak, serta dispersi sebaran data.
- **Invariansi Ukuran Dispersi**: Penjumlahan konstanta tidak mempengaruhi ukuran sebaran ($S_{\text{baru}} = S_{\text{lama}}$). Hanya perkalian/pembagian yang merubah dispersi secara proporsional.
- **Deteksi Kemiringan Sebaran**: $\bar{x} < Me < Mo$ mengindikasikan kemiringan negatif (sebagian besar bernilai tinggi), sedangkan $Mo < Me < \bar{x}$ mengindikasikan kemiringan positif.
- **Kejujuran Hisab Data**: Statistika deskriptif mendidik santri untuk bersikap teliti, adil, dan objektif dalam mencatat dan mengevaluasi kenyataan amal.
