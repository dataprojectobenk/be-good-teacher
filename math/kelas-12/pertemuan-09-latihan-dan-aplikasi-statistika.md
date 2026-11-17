# MATEMATIKA SMA (JURUSAN IPA & PESANTREN)
## Kelas 12 - Semester Ganjil
### Pertemuan 9: Statistika Deskriptif Terpadu — Latihan & Aplikasi Analisis Data Riil Santri

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Kerjakan 2 soal kilat pengingat konsep penyajian dan pemusatan data berikut:
1. Diberikan kelas interval nilai ujian $61 - 70$. Tentukan batas bawah, batas atas, tepi bawah ($L$), dan panjang kelas ($p$)!
2. Jika suatu kelas interval memiliki tepi bawah $L = 60{,}5$, panjang kelas $p = 10$, frekuensi kelas $f = 16$, dan selisih frekuensi dengan kelas sebelumnya $d_1 = 6$ serta sesudahnya $d_2 = 4$, hitunglah nilai modusnya:
   $$
   Mo = L + \left(\frac{d_1}{d_1 + d_2}\right) p
   $$

*(Cek singkat: No. 1 = batas bawah $61$, batas atas $70$, $L = 60{,}5$, $p = 10$; No. 2 = $Mo = 60{,}5 + \left(\frac{6}{10}\right) 10 = 66{,}5$)*

---

#### B. Pengertian Konsep Dasar

Statistika deskriptif adalah cabang matematika terapan yang bertujuan untuk menghimpun, menyusun, menyajikan, dan menganalisis data numerik sehingga memberikan gambaran ringkas, objektif, dan terukur mengenai karakteristik suatu kelompok data.

Dalam tahapan pembelajaran statistika, seluruh komponen dipadukan dalam **analisis data terpadu (*Integrated Data Analysis*)**:
1. **Penyajian Data**: Tabel distribusi frekuensi berkelompok, histogram, poligon frekuensi, dan ogive.
2. **Ukuran Pemusatan**: Rata-rata hitung (*Mean* $\bar{x}$), nilai tengah (*Median* $Me$), dan nilai yang paling sering muncul (*Modus* $Mo$).
3. **Ukuran Letak**: Kuartil ($Q_1, Q_2, Q_3$), Desil ($D_i$), dan Persentil ($P_i$).
4. **Ukuran Penyebaran**: Jangkauan ($R$), Simpangan Kuartil ($Q_d$), Simpangan Rata-rata ($SR$), Varians ($S^2$), dan Simpangan Baku ($S$).

Analisis data yang akurat menuntut perpaduan serentak antara nilai pemusatan, letak, dan sebaran agar interpretasi data tidak bias. Nilai rata-rata yang sama pada dua kelompok tidak serta-merta menunjukkan kondisi yang sama jika sebaran datanya berbeda.

---

### 2. Bentuk Umum & Rumus Baku Terpadu

Berikut adalah rangkuman formula kunci statistika deskriptif data berkelompok:

#### A. Aturan Sturges (Pembentukan Kelas Interval)
$$
k \approx 1 + 3{,}3 \log n \quad \text{dan} \quad p = \frac{R}{k} = \frac{X_{\max} - X_{\min}}{k}
$$

#### B. Ukuran Pemusatan Data Berkelompok
1. **Mean ($\bar{x}$):**
   $$
   \bar{x} = \frac{\sum_{i=1}^k f_i x_i}{\sum_{i=1}^k f_i} \quad (x_i = \text{titik tengah kelas})
   $$
2. **Median ($Me$):**
   $$
   Me = L + \left( \frac{\frac{n}{2} - F}{f_{me}} \right) p
   $$
3. **Modus ($Mo$):**
   $$
   Mo = L + \left( \frac{d_1}{d_1 + d_2} \right) p
   $$

#### C. Ukuran Letak & Penyebaran Data Berkelompok
1. **Kuartil ke-$i$ ($Q_i$ untuk $i \in \{1, 2, 3\}$):**
   $$
   Q_i = L + \left( \frac{\frac{i \cdot n}{4} - F}{f_q} \right) p
   $$
2. **Simpangan Baku ($S$):**
   $$
   S = \sqrt{\frac{\sum_{i=1}^k f_i (x_i - \bar{x})^2}{n}}
   $$
3. **Angka Baku ($Z$-score) dan Koefisien Variasi ($KV$):**
   $$
   Z = \frac{x - \bar{x}}{S}, \quad KV = \frac{S}{\bar{x}} \times 100\%
   $$

---

### 3. Sifat-Sifat & Aturan Penting

1. **Hubungan Tiga Parameter Pusat Menentukan Kemiringan Kurva (*Skewness*):**
   - **Simetris / Normal:** $\bar{x} \approx Me \approx Mo$.
   - **Miring ke Kanan (*Positively Skewed*):** $Mo < Me < \bar{x}$ (ekor kurva memanjang ke arah nilai tinggi, sebagian besar santri bernilai rendah).
   - **Miring ke Kiri (*Negatively Skewed*):** $\bar{x} < Me < Mo$ (ekor kurva memanjang ke arah nilai rendah, sebagian besar santri memperoleh nilai tinggi).
2. **Sanity Check Interval:**
   Nilai $Me$, $Mo$, dan $Q_i$ hasil perhitungan wajib selalu berada di dalam batas kelas interval yang terpilih ($L \le \text{Nilai} \le L + p$).

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

#### Contoh 1 (Tingkat Dasar — Analisis Lengkap Data Tunggal Santri)

**Soal:**  
Tercatat skor penilaian kelancaran membaca kitab kuning dari $8$ orang santri:

$$
70, \ 72, \ 75, \ 75, \ 78, \ 80, \ 85, \ 85
$$

Tentukan:
a. Nilai Mean ($\bar{x}$), Median ($Me$), dan Modus ($Mo$).  
b. Kuartil Bawah ($Q_1$) dan Kuartil Atas ($Q_3$).  
c. Jangkauan ($R$) dan Simpangan Kuartil ($Q_d$).

**Penyelesaian:**
- **Langkah 1 (Menghitung Ukuran Pemusatan):**
  - $\bar{x} = \frac{70 + 72 + 75 + 75 + 78 + 80 + 85 + 85}{8} = \frac{620}{8} = 77{,}5$
  - Letak median antara data ke-4 ($75$) dan ke-5 ($78$):
    $$
    Me = \frac{75 + 78}{2} = 76{,}5
    $$
  - Modus: $75$ dan $85$ (bimodal, masing-masing muncul $2$ kali).
- **Langkah 2 (Menghitung Kuartil):**
  - Paruh bawah ($70, \mathbf{72, 75}, 75$) $\implies Q_1 = \frac{72 + 75}{2} = 73{,}5$.
  - Paruh atas ($78, \mathbf{80, 85}, 85$) $\implies Q_3 = \frac{80 + 85}{2} = 82{,}5$.
- **Langkah 3 (Menghitung Ukuran Penyebaran):**
  - Jangkauan: $R = 85 - 70 = 15$.
  - Simpangan Kuartil: $Q_d = \frac{1}{2}(Q_3 - Q_1) = \frac{1}{2}(82{,}5 - 73{,}5) = \frac{1}{2}(9) = 4{,}5$.
- **Kesimpulan:** Nilai $\bar{x} = 77{,}5$, $Me = 76{,}5$, $Q_1 = 73{,}5$, $Q_3 = 82{,}5$, dan $Q_d = 4{,}5$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Variasi Menengah — Analisis Data Berkelompok pada Tabel Distribusi)

**Soal:**  
Perhatikan tabel distribusi nilai ujian tajwid dari $40$ santri berikut:

| Rentang Nilai | Frekuensi ($f_i$) |
| :---: | :---: |
| $61 - 70$ | $8$ |
| $71 - 80$ | $16$ |
| $81 - 90$ | $12$ |
| $91 - 100$ | $4$ |

Tentukan nilai Mean ($\bar{x}$) dan Median ($Me$) dari tabel tersebut!

**Penyelesaian:**
- **Langkah 1 (Menghitung Mean $\bar{x}$):**
  - Titik tengah ($x_i$): $65{,}5; \ 75{,}5; \ 85{,}5; \ 95{,}5$.
  - $\sum f_i x_i = 8(65{,}5) + 16(75{,}5) + 12(85{,}5) + 4(95{,}5) = 524 + 1.208 + 1.026 + 382 = 3.140$.
  - $\bar{x} = \frac{3.140}{40} = 78{,}5$.
- **Langkah 2 (Menghitung Median $Me$):**
  - Letak median: data ke-$\frac{40}{2} = 20$.
  - Frekuensi kumulatif: kelas 1 $= 8$, kelas 2 $= 24$. Maka kelas median adalah $71 - 80$.
  - Tepi bawah $L = 70{,}5$, frekuensi kumulatif sebelum kelas median $F = 8$, frekuensi median $f_{me} = 16$, dan panjang kelas $p = 10$:
    $$
    Me = 70{,}5 + \left(\frac{20 - 8}{16}\right) 10 = 70{,}5 + \left(\frac{12}{16}\right) 10 = 70{,}5 + 7{,}5 = 78
    $$
- **Kesimpulan:** Rata-rata nilai tajwid adalah $78{,}5$ dan nilai median adalah $78$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 3 (Pengembangan — Angka Baku $Z$-score dan Perbandingan Prestasi Relatif)

**Soal:**  
Seorang santri bernama Zaid memperoleh nilai $85$ pada ujian Fiqih (rata-rata kelas $\bar{x}_1 = 75$, simpangan baku $S_1 = 5$) dan memperoleh nilai $88$ pada ujian Nahwu (rata-rata kelas $\bar{x}_2 = 80$, simpangan baku $S_2 = 8$). Pada mata pelajaran manakah prestasi Zaid relatif lebih unggul di kelasnya?

**Penyelesaian:**
- **Langkah 1 (Menghitung $Z$-score Ujian Fiqih):**
  $$
  Z_{\text{Fiqih}} = \frac{85 - 75}{5} = \frac{10}{5} = +2{,}0
  $$
- **Langkah 2 (Menghitung $Z$-score Ujian Nahwu):**
  $$
  Z_{\text{Nahwu}} = \frac{88 - 80}{8} = \frac{8}{8} = +1{,}0
  $$
- **Kesimpulan:** Meskipun nilai mentah Nahwu ($88$) lebih tinggi daripada Fiqih ($85$), nilai $Z_{\text{Fiqih}} = +2{,}0$ lebih tinggi daripada $Z_{\text{Nahwu}} = +1{,}0$. Artinya posisi Zaid pada ujian Fiqih berada $2$ simpangan baku di atas rata-rata kelas, sehingga prestasi Zaid **relatif lebih unggul pada mata pelajaran Fiqih** ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual (Studi Kasus Santri & Kehidupan Nyata)

#### Studi Kasus: Evaluasi Nilai KKM dan Beasiswa Santri
Sebuah pondok pesantren menetapkan bahwa santri yang lulus ujian syafahi (*lisan*) harus melampaui Kriteria Ketuntasan Minimal (KKM) sebesar $72{,}5$. Dari $50$ santri diperoleh tabel distribusi frekuensi:

| Nilai Ujian | Frekuensi ($f_i$) |
| :---: | :---: |
| $51 - 60$ | $6$ |
| $61 - 70$ | $14$ |
| $71 - 80$ | $18$ |
| $81 - 90$ | $8$ |
| $91 - 100$ | $4$ |

1. Tentukan estimasi persentase santri yang berhasil lulus KKM ($> 72{,}5$)!
2. Jika santri $10\%$ terbaik memperoleh beasiswa kitab, tentukan batas nilai terendah penerima beasiswa (gunakan $P_{90}$)!

**Langkah Pemecahan:**
1. **Analisis Lulus KKM:**
   - Frekuensi kumulatif santri bernilai $\le 70{,}5$ adalah $6 + 14 = 20$ santri.
   - Nilai $72{,}5$ berada di kelas $71 - 80$ ($L = 70{,}5, p = 10, f = 18$).
   - Banyak santri dengan nilai antara $70{,}5$ dan $72{,}5$:
     $$
     \Delta n = \frac{72{,}5 - 70{,}5}{10} \times 18 = \frac{2}{10} \times 18 = 3{,}6 \approx 4\text{ santri}
     $$
   - Total santri di bawah KKM $\le 72{,}5$ adalah $20 + 4 = 24$ santri.
   - Santri yang lulus KKM $= 50 - 24 = 26$ santri ($52\%$).
2. **Batas Beasiswa ($P_{90}$):**
   - Letak $P_{90} = \frac{90}{100} \times 50 = 45$.
   - Kelas $P_{90}$ berada pada $81 - 90$ (kumulatif sebelum kelas $= 38$, frekuensi $f = 8$, $L = 80{,}5$):
     $$
     P_{90} = 80{,}5 + \left(\frac{45 - 38}{8}\right) 10 = 80{,}5 + \left(\frac{7}{8}\right) 10 = 80{,}5 + 8{,}75 = 89{,}25
     $$
3. **Jawaban Kontekstual:**  
   Sebanyak $52\%$ santri dinyatakan lulus KKM, dan batas nilai terendah untuk mendapatkan beasiswa kitab adalah **$89{,}25$** ($\checkmark\ \text{Benar}$).

---

### 6. Latihan Soal Kelas (Bertingkat & Terstruktur)

#### A. Latihan Terbimbing (Ikuti Langkah Contoh)
Kerjakan soal berikut dengan mengikuti urutan langkah pada contoh yang telah dipelajari:
1. Diberikan data skor ujian: $60, 65, 70, 70, 75, 80, 85, 95$. Tentukan nilai Mean ($\bar{x}$), Median ($Me$), dan Jangkauan ($R$)!
2. Tentukan nilai Modus dari tabel distribusi frekuensi berikut:
   | Nilai | Frekuensi |
   | :---: | :---: |
   | $51 - 60$ | $5$ |
   | $61 - 70$ | $15$ |
   | $71 - 80$ | $10$ |

#### B. Penguasaan Prosedur Inti (Latihan Bertahap)
1. Diberikan data: $4, 6, 8, x, 10$. Jika rata-rata dari data tersebut adalah $7$, tentukan nilai $x$ dan nilai simpangan bakunya!
2. Dari tabel frekuensi pada Latihan 6A nomor 2, hitunglah nilai Kuartil Bawah ($Q_1$) dan Kuartil Atas ($Q_3$)!
3. Hitunglah nilai simpangan baku dari data tunggal: $3, 5, 7, 9, 11$!
4. Ahmad memperoleh nilai tes bahasa Arab $84$ (rata-rata kelas $76, S = 4$). Hitung angka baku ($Z$-score) Ahmad dan simpulkan posisinya!
5. Kelas A memiliki rata-rata hafalan $80$ dengan $S = 4$, sedangkan Kelas B memiliki rata-rata $70$ dengan $S = 7$. Hitung Koefisien Variasi ($KV$) kedua kelas dan tentukan kelas yang lebih homogen!

#### C. Soal Cerita Kontekstual (Terpisah)
1. **Penerimaan Santri Baru:**  
   Panitia PSB mencatat data nilai ujian tes potensi dari $40$ pendaftar dalam tabel:
   | Nilai | Frekuensi |
   | :---: | :---: |
   | $61 - 70$ | $6$ |
   | $71 - 80$ | $14$ |
   | $81 - 90$ | $16$ |
   | $91 - 100$ | $4$ |
   Tentukan nilai rata-rata dan nilai median dari hasil tes pendaftar santri baru tersebut!
2. **Audit Waktu Muthala'ah Santri:**  
   Santri mencatat waktu belajar malam (dalam menit): $45, 50, 55, 60, 60, 65, 70, 75$. Tentukan jangkauan antarkuartil ($H$) dan simpangan kuartil ($Q_d$) waktu belajar tersebut!

#### D. Soal Tantangan (Opsional / Pengayaan)
1. Suatu kelompok data berukuran $n = 10$ memiliki rata-rata $\bar{x} = 15$ dan simpangan baku $S = 3$. Jika data tersebut diubah dengan aturan baru $y_i = 4x_i - 10$, tentukan nilai rata-rata baru, varians baru, dan simpangan baku baru!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

- **Pola Kunci Fraktil Data Berkelompok:**
  - *"Tepi bawah ditambah selisih posisi frekuensi kumulatif dibagi frekuensi kelasnya, dikali panjang kelas."*
  - **Cara Cek Cepat:** Pastikan posisi fraktil selalu terletak di antara tepi bawah dan tepi atas kelas interval yang dipilih.

- **Waspada Jebakan Umum:**
  - ⚠️ **Kekeliruan Nilai $F$ pada Median/Kuartil:** $F$ adalah frekuensi kumulatif **sebelum** kelas yang bersangkutan, bukan frekuensi kumulatif kelas itu sendiri.
  - ⚠️ **Perbedaan $d_1$ dan $d_2$ pada Modus:** $d_1 = f_{mo} - f_{sebelum}$ dan $d_2 = f_{mo} - f_{sesudah}$ (selisih selalu bernilai positif).

---

### 8. Pekerjaan Rumah (PR Pondok / Mandiri)

**Petunjuk:**
- Kerjakan di buku tugas matematika secara tertib dan mandiri.
- Periksa hasil akhirmu dengan mencocokkan pada kunci jawaban di bawah.

1. Tentukan Mean, Median, Modus, dan Simpangan Baku dari data: $6, 7, 7, 8, 9, 11$!
2. Diberikan tabel distribusi frekuensi:
   | Skor | Frekuensi |
   | :---: | :---: |
   | $41 - 50$ | $4$ |
   | $51 - 60$ | $10$ |
   | $61 - 70$ | $16$ |
   | $71 - 80$ | $10$ |
   Tentukan nilai Median ($Me$) dan Modus ($Mo$)-nya!
3. **Komparasi Prestasi Santri:**  
   Umar memperoleh nilai $82$ pada ujian Fiqih ($\bar{x} = 74, S = 4$) dan nilai $85$ pada ujian Hadits ($\bar{x} = 75, S = 5$). Tentukan pada ujian manakah prestasi Umar relatif lebih baik!
4. **Pertanyaan Pemahaman Konsep:**  
   *Jelaskan hubungan antara nilai Mean, Median, dan Modus dalam menentukan kemiringan (*skewness*) kurva distribusi frekuensi! Apa maknanya bagi pembina pondok jika nilai Modus santri jauh lebih tinggi daripada nilai Mean-nya?*

**Kunci Jawaban Akhir Ringkas (Cek Mandiri):**
- **Latihan 6A:** `1.` $\bar{x} = 75, Me = 72{,}5, R = 35$ | `2.` $Mo = 67$
- **Latihan 6B:** `1.` $x = 7, S = 2$ | `2.` $Q_1 = 62{,}17, Q_3 = 74$ | `3.` $S = 2\sqrt{2}$ | `4.` $Z = +2{,}0$ (berada $2$ standar deviasi di atas rata-rata) | `5.` $KV_A = 5\%, KV_B = 10\%$ (Kelas A lebih homogen)
- **Latihan 6C:** `1.` $\bar{x} = 80, Me = 80{,}5$ | `2.` $H = 15, Q_d = 7{,}5$
- **PR No. 1–3:** `1.` $\bar{x} = 8, Me = 7{,}5, Mo = 7, S = \sqrt{\frac{16}{6}} \approx 1{,}63$ | `2.` $Me = 64{,}25, Mo = 64{,}25$ | `3.` Fiqih ($Z = +2{,}0$ vs Hadits $Z = +2{,}0$, kedua prestasi relatif setara)

---

### 9. Ringkasan Materi

- **Integrasi Statistika Deskriptif**: Pemahaman tuntas mengenai data menuntut analisis terpadu dari ukuran pemusatan (Mean, Median, Modus), ukuran letak (Kuartil, Desil, Persentil), dan ukuran penyebaran (Simpangan Baku, $KV$).
- **Deteksi Kemiringan Sebaran**: Posisi relatif ketiga nilai pusat menentukan arah kurva: simetris jika $\bar{x} \approx Me \approx Mo$, miring kanan jika $Mo < Me < \bar{x}$, dan miring kiri jika $\bar{x} < Me < Mo$.
- **Peran Ukuran Relatif**: $Z$-score memungkinkan pembandingan prestasi santri lintas mata pelajaran yang memiliki skala dan tingkat kesulitan sebaran berbeda.
- **Keadilan Berbasis Data**: Statistika deskriptif menuntun santri untuk menegakkan kejujuran hisab, keteraturan amal, dan objektivitas evaluasi demi kemaslahatan bersama di pondok pesantren.
