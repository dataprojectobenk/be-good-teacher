# MATEMATIKA SMA (JURUSAN IPA & PESANTREN)
## Kelas 12 - Semester Ganjil
### Pertemuan 12: Review Komprehensif Semester Ganjil & Persiapan Penilaian Akhir Semester (PAS) — Dimensi Tiga & Statistika Deskriptif

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Kerjakan 2 butir soal kilat penyegar ingatan komprehensif berikut:
1. Diberikan kubus $ABCD.EFGH$ dengan panjang rusuk $6\text{ cm}$. Tentukan panjang diagonal bidang ($s\sqrt{2}$) dan diagonal ruangnya ($s\sqrt{3}$)!
2. Jika suatu kelas median data berkelompok memiliki tepi bawah $L = 50{,}5$, $\frac{n}{2} = 20$, frekuensi kumulatif sebelumnya $F = 11$, frekuensi kelas median $f = 15$, dan panjang kelas $p = 10$, hitung nilai mediannya!

*(Cek singkat: No. 1 = diagonal bidang $6\sqrt{2}\text{ cm}$, diagonal ruang $6\sqrt{3}\text{ cm}$; No. 2 = $Me = 50{,}5 + \left(\frac{20-11}{15}\right)10 = 50{,}5 + 6 = 56{,}5$)*

---

#### B. Pengertian & Analogi Nyata

Pertemuan ke-12 merupakan puncak rangkaian pembelajaran matematika Semester Ganjil Kelas 12. Pada tahap ini, santri menyatukan dua pilar kompetensi utama yang telah dipelajari secara mendalam:
1. **Geometri Ruang (Dimensi Tiga)**: Mengembangkan kecerdasan spasial, abstraksi bentuk tiga dimensi, proyeksi garis dan bidang, serta pengukuran jarak dan sudut. Kompetensi ini sangat aplikatif dalam penentuan orientasi kiblat, perancangan arsitektur kubah dan menara masjid, serta pemodelan struktur bangunan fisik.
2. **Statistika Deskriptif**: Mengembangkan ketelitian kuantitatif, analisis keterpusatan data, posisi fraktil, dan variabilitas/sebaran data. Kompetensi ini merupakan instrumen utama dalam evaluasi capaian hafalan santri, audit logistik, dan pengambilan keputusan berbasis data yang berkeadilan.

**Nilai Filosofis & Nilai Luhur Itqan:**  
Dalam perspektif akhlak dan thalabul 'ilmi, persiapan menghadapi Penilaian Akhir Semester (PAS) adalah momentum pengamalan nilai **itqan** (profesionalitas, kesungguhan, dan kemantapan mutu ilmu). Rasulullah SAW bersabda:

> *"Sesungguhnya Allah mencintai seseorang yang apabila melakukan suatu pekerjaan, ia melakukannya secara itqan (rapi, sungguh-sungguh, dan tuntas)."*  
> **(HR. Thabrani)**

Ujian akhir bukan sekadar pembuktian angka di atas kertas, melainkan cermin kedisiplinan belajar, ketelitian berhitung, dan kejujuran ilmiah seorang penuntut ilmu.

---

### 2. Bentuk Umum & Rumus Baku

Berikut adalah lembar ringkasan formula resmi (*Master Formula Vault*):

#### A. Geometri Ruang (Dimensi Tiga)
1. **Karakteristik Metrik Kubus Rusuk $s$:**
   $$
   \text{Diagonal Bidang: } d_b = s\sqrt{2}, \quad \text{Diagonal Ruang: } d_r = s\sqrt{3}
   $$
2. **Metode Penentuan Jarak Titik ke Garis (Kesamaan Luas Segitiga):**
   $$
   L = \frac{1}{2} \cdot a \cdot t_1 = \frac{1}{2} \cdot b \cdot t_2 \implies t_2 = \frac{a \cdot t_1}{b}
   $$
3. **Formula Proyeksi Cepat pada Kubus Rusuk $s$:**
   - Jarak titik sudut ke diagonal ruang yang tidak memuat titik tersebut:
     $$
     d = \frac{1}{3} s\sqrt{6}
     $$

#### B. Statistika Deskriptif Terpadu
1. **Ukuran Pemusatan & Letak:**
   $$
   \bar{x} = \frac{\sum f_i x_i}{\sum f_i}, \quad Me = L + \left( \frac{\frac{n}{2} - F}{f_{me}} \right) p, \quad Mo = L + \left( \frac{d_1}{d_1 + d_2} \right) p
   $$
2. **Ukuran Penyebaran:**
   $$
   S = \sqrt{\frac{\sum f_i (x_i - \bar{x})^2}{n}}, \quad KV = \frac{S}{\bar{x}} \times 100\%, \quad Z = \frac{x - \bar{x}}{S}
   $$

---

### 3. Sifat-Sifat & Aturan Penting

1. **Prinsip Bidang Pembantu pada Dimensi Tiga:**
   Untuk mencari jarak titik ke garis atau titik ke bidang, selalu cari **segitiga pembantu** yang memuat titik tersebut dan garis target, lalu gunakan Teorema Pythagoras atau perbandingan luas segitiga.
2. **Invariansi Ukuran Sebaran Terhadap Pergeseran:**
   Ukuran penyebaran ($R, Q_d, SR, S$) kebal terhadap operasi penjumlahan/pengurangan konstanta ($S_{\text{baru}} = S_{\text{lama}}$), namun membesar/mengecil secara proporsional terhadap perkalian ($S_{\text{baru}} = \lvert a \rvert S_{\text{lama}}$).

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

#### Contoh 1 (Tingkat Dasar — Jarak Titik ke Garis pada Kubus)

**Soal:**  
Diberikan kubus $ABCD.EFGH$ dengan panjang rusuk $6\text{ cm}$. Tentukan jarak titik $H$ ke garis diagonal ruang $AG$!

**Penyelesaian:**
- **Langkah 1 (Membuat Segitiga Pembantu):**
  Perhatikan segitiga $AHG$. Panjang sisi-sisinya:
  - $AH = 6\sqrt{2}\text{ cm}$ (diagonal bidang sisi kiri).
  - $HG = 6\text{ cm}$ (rusuk kubus).
  - $AG = 6\sqrt{3}\text{ cm}$ (diagonal ruang).
  Karena rusuk $HG$ tegak lurus bidang sisi kiri $ADHE$, maka segitiga $AHG$ siku-siku di titik $H$.
- **Langkah 2 (Menghitung Jarak dengan Kesamaan Luas Segitiga):**
  Tarik garis tinggi dari titik $H$ tegak lurus ke sisi miring $AG$ di titik $H'$. Panjang $HH'$ adalah jarak yang dicari:
  $$
  \text{Luas } \triangle AHG = \frac{1}{2} \cdot AH \cdot HG = \frac{1}{2} \cdot AG \cdot HH'
  $$
  $$
  (6\sqrt{2}) \cdot 6 = (6\sqrt{3}) \cdot HH' \implies HH' = \frac{36\sqrt{2}}{6\sqrt{3}} = \frac{6\sqrt{2}}{\sqrt{3}} = 2\sqrt{6}\text{ cm}
  $$
- **Kesimpulan:** Jarak titik $H$ ke diagonal ruang $AG$ adalah $2\sqrt{6}\text{ cm}$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Variasi Menengah — Analisis Terpadu Data Ujian Santri)

**Soal:**  
Tabel berikut menyajikan data nilai ujian fiqih dari $40$ santri:

| Skor Ujian | Frekuensi ($f_i$) |
| :---: | :---: |
| $51 - 60$ | $8$ |
| $61 - 70$ | $16$ |
| $71 - 80$ | $12$ |
| $81 - 90$ | $4$ |

Tentukan nilai Mean ($\bar{x}$) dan Modus ($Mo$) data tersebut!

**Penyelesaian:**
- **Langkah 1 (Menghitung Mean):**
  - Titik tengah ($x_i$): $55{,}5; \ 65{,}5; \ 75{,}5; \ 85{,}5$.
  - $\sum f_i x_i = 8(55{,}5) + 16(65{,}5) + 12(75{,}5) + 4(85{,}5) = 444 + 1.048 + 906 + 342 = 2.740$.
  - $\bar{x} = \frac{2.740}{40} = 68{,}5$.
- **Langkah 2 (Menghitung Modus):**
  - Kelas modus adalah $61 - 70$ ($f = 16$).
  - $L = 60{,}5, p = 10, d_1 = 16 - 8 = 8, d_2 = 16 - 12 = 4$.
  - $Mo = 60{,}5 + \left(\frac{8}{8 + 4}\right) 10 = 60{,}5 + \frac{80}{12} = 60{,}5 + 6{,}67 = 67{,}17$.
- **Kesimpulan:** Rata-rata nilai fiqih adalah $68{,}5$ dan modusnya adalah $67{,}17$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 3 (Pengembangan — Besar Sudut antara Garis dan Bidang pada Kubus)

**Soal:**  
Pada kubus $ABCD.EFGH$, tentukan nilai tangen sudut yang dibentuk antara garis diagonal ruang $AG$ dengan bidang alas $ABCD$!

**Penyelesaian:**
- **Langkah 1 (Menentukan Garis Proyeksi):**
  Proyeksi titik $A$ pada bidang $ABCD$ adalah titik $A$ itu sendiri. Proyeksi titik $G$ tegak lurus ke bidang $ABCD$ adalah titik $C$.
  Maka proyeksi garis $AG$ pada bidang alas adalah garis diagonal bidang $AC$.
- **Langkah 2 (Menghitung Tangen Sudut):**
  Sudut yang terbentuk adalah $\angle CAG$ pada segitiga siku-siku $ACG$ (siku-siku di $C$):
  - Sisi depan: $CG = s$
  - Sisi samping: $AC = s\sqrt{2}$
  $$
  \tan(\angle CAG) = \frac{CG}{AC} = \frac{s}{s\sqrt{2}} = \frac{1}{\sqrt{2}} = \frac{1}{2}\sqrt{2}
  $$
- **Kesimpulan:** Nilai tangen sudut antara garis $AG$ dan bidang alas adalah $\frac{1}{2}\sqrt{2}$ ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual (Studi Kasus Santri & Kehidupan Nyata)

#### Studi Kasus: Perancangan Atap Limas Aula Pesantren
Pengurus pesantren merancang atap aula pertemuan santri berbentuk **limas segiempat beraturan $T.ABCD$**:
- Bidang alas $ABCD$ berbentuk persegi dengan sisi $AB = 8\text{ meter}$.
- Panjang rusuk tegak atap $TA = TB = TC = TD = 4\sqrt{6}\text{ meter}$.

Pengurus perlu memasang tiang penyangga utama dari puncak atap $T$ tegak lurus ke titik pusat lantai alas $O$, serta kawat penyeimbang dari titik $O$ tegak lurus ke rusuk $TC$.
Berapakah panjang tiang penyangga $TO$ dan panjang kawat penyeimbang yang harus disiapkan?

**Langkah Pemecahan:**
1. **Panjang Diagonal Alas:**
   $AC = 8\sqrt{2}\text{ m} \implies OC = \frac{1}{2} AC = 4\sqrt{2}\text{ meter}$.
2. **Panjang Tiang Penyangga Utama ($TO$):**
   Segitiga $TOC$ siku-siku di $O$:
   $$
   TO = \sqrt{TC^2 - OC^2} = \sqrt{(4\sqrt{6})^2 - (4\sqrt{2})^2} = \sqrt{96 - 32} = \sqrt{64} = 8\text{ meter}
   $$
3. **Panjang Kawat Penyeimbang (Tegak Lurus ke $TC$ di Titik $O'$):**
   Gunakan perbandingan luas segitiga siku-siku $TOC$:
   $$
   \frac{1}{2} \cdot OC \cdot TO = \frac{1}{2} \cdot TC \cdot OO'
   $$
   $$
   (4\sqrt{2}) \cdot 8 = (4\sqrt{6}) \cdot OO' \implies OO' = \frac{32\sqrt{2}}{4\sqrt{6}} = \frac{8}{\sqrt{3}} = \frac{8}{3}\sqrt{3}\text{ meter}
   $$
4. **Jawaban Kontekstual:**  
   Panjang tiang penyangga utama $TO$ adalah $8\text{ meter}$ dan panjang kawat penyeimbang adalah $\frac{8}{3}\sqrt{3}\text{ meter}$ ($\approx 4{,}62\text{ meter}$).

---

### 6. Latihan Soal Kelas (Bertingkat & Terstruktur)

#### A. Latihan Terbimbing (Ikuti Langkah Contoh)
Kerjakan soal berikut dengan mengikuti urutan langkah pada contoh yang telah dipelajari:
1. Diberikan kubus $ABCD.EFGH$ dengan panjang rusuk $8\text{ cm}$. Tentukan jarak titik $C$ ke garis diagonal bidang $BD$!
2. Diberikan tabel frekuensi:
   | Nilai | Frekuensi |
   | :---: | :---: |
   | $41 - 50$ | $6$ |
   | $51 - 60$ | $14$ |
   | $61 - 70$ | $10$ |
   Tentukan nilai Median ($Me$) dari tabel tersebut!

#### B. Penguasaan Prosedur Inti (Latihan Bertahap)
1. Pada kubus $ABCD.EFGH$ dengan panjang rusuk $6\text{ cm}$, tentukan jarak titik $A$ ke bidang diagonal $BDHF$!
2. Pada kubus $ABCD.EFGH$ dengan rusuk $9\text{ cm}$, tentukan jarak titik $E$ ke bidang segitiga diagonal $BDG$!
3. Diberikan balok $ABCD.EFGH$ dengan panjang $AB = 8\text{ cm}$, lebar $BC = 6\text{ cm}$, dan tinggi $CG = 5\text{ cm}$. Tentukan jarak titik $B$ ke titik sudut $H$!
4. Dari tabel pada soal 6A nomor 2, hitunglah nilai Modus ($Mo$)-nya!
5. Suatu kelompok data memiliki rata-rata $65$ dan simpangan baku $8$. Jika setiap data dikalikan $2$ kemudian ditambah $10$, tentukan nilai rata-rata dan simpangan baku yang baru!

#### C. Soal Cerita Kontekstual (Terpisah)
1. **Kubah Masjid Pesantren:**  
   Masjid pesantren memiliki kubah berbentuk limas segiempat beraturan dengan panjang sisi alas $6\text{ meter}$ dan tinggi puncak kubah $4\text{ meter}$. Tentukan panjang rusuk miring kubah masjid tersebut!
2. **Audit Konsumsi Dapur Santri:**  
   Dapur umum mencatat konsumsi telur (dalam butir/hari) selama $7$ hari: $120, 130, 135, 140, 140, 150, 165$. Tentukan nilai median dan simpangan kuartilnya ($Q_d$)!

#### D. Soal Tantangan (Opsional / Pengayaan)
1. Diberikan limas segiempat beraturan $T.ABCD$ dengan rusuk alas $AB = 12\text{ cm}$ dan rusuk tegak $TA = 10\text{ cm}$. Titik $O$ adalah perpotongan diagonal alas. Tentukan tinggi limas $TO$ dan jarak titik $O$ ke bidang sisi tegak $TBC$!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

- **Trik Cepat Kubus Rusuk $s$:**
  - Jarak titik sudut ke diagonal ruang berseberangan: $\frac{1}{3} s\sqrt{6}$.
  - Jarak titik sudut ke bidang segitiga diagonal: $\frac{1}{3} s\sqrt{3}$ (dekat) atau $\frac{2}{3} s\sqrt{3}$ (jauh).

- **Waspada Jebakan Umum:**
  - ⚠️ **Kekeliruan Diagonal Ruang vs Bidang:** Diagonal bidang adalah $s\sqrt{2}$, sedangkan diagonal ruang adalah $s\sqrt{3}$. Jangan sampai tertukar!
  - ⚠️ **Satuan Pengukuran:** Pastikan satuan panjang (cm atau m) dan satuan waktu/berat dituliskan secara seragam dan lengkap.

---

### 8. Pekerjaan Rumah (PR Pondok / Simulasi Mandiri Final PAS)

**Petunjuk:**
- Kerjakan di buku catatan tugas matematika secara tertib, jujur, dan mandiri.
- Periksa hasil akhirmu dengan mencocokkan pada kunci jawaban di bawah.

1. Pada kubus $ABCD.EFGH$ dengan panjang rusuk $12\text{ cm}$, tentukan jarak titik $F$ ke garis diagonal bidang $AC$!
2. Diberikan tabel distribusi frekuensi:
   | Rentang Nilai | Frekuensi |
   | :---: | :---: |
   | $51 - 60$ | $5$ |
   | $61 - 70$ | $15$ |
   | $71 - 80$ | $12$ |
   | $81 - 90$ | $8$ |
   Tentukan nilai Mean ($\bar{x}$) dan Modus ($Mo$)!
3. **Penyimpanan Air Wudhu Asrama:**  
   Bak penampungan air wudhu santri berbentuk balok berukuran $2\text{ m} \times 1\text{ m} \times 1\text{ m}$. Tentukan panjang pipa penyedot air lurus terpanjang yang dapat diletakkan di dalam bak tersebut!
4. **Pertanyaan Pemahaman Konsep:**  
   *Jelaskan hubungan antara konsep itqan (ketelitian dan kesungguhan) dalam ajaran Islam dengan penalaran geometris dan statistika yang telah kamu pelajari selama satu semester ini! Mengapa santri harus memiliki ketelitian spasial dan kejujuran data yang tinggi?*

**Kunci Jawaban Akhir Ringkas (Cek Mandiri):**
- **Latihan 6A:** `1.` $4\sqrt{2}\text{ cm}$ | `2.` $Me = 56{,}93$
- **Latihan 6B:** `1.` $3\sqrt{2}\text{ cm}$ | `2.` $6\sqrt{3}\text{ cm}$ | `3.` $5\sqrt{5}\text{ cm}$ | `4.` $Mo = 56{,}74$ | `5.` $\bar{x}_{\text{baru}} = 140, S_{\text{baru}} = 16$
- **Latihan 6C:** `1.` $\sqrt{34}\text{ m} \approx 5{,}83\text{ m}$ | `2.` $Me = 140, Q_d = 10$
- **PR No. 1–3:** `1.` $6\sqrt{6}\text{ cm}$ | `2.` $\bar{x} = 70{,}75, Mo = 67{,}17$ | `3.` $\sqrt{6}\text{ meter} \approx 2{,}45\text{ m}$

---

### 9. Ringkasan Materi

- **Dimensi Tiga**: Penentuan jarak titik ke garis atau bidang selalu bertumpu pada pembentukan segitiga siku-siku pembantu, penerapan teorema Pythagoras, atau kesamaan luas segitiga.
- **Statistika Deskriptif Terpadu**: Evaluasi yang adil dan objektif memadukan ukuran pemusatan (Mean, Median, Modus) dengan ukuran penyebaran (Simpangan Baku, $KV$) untuk mendeteksi kesetaraan belajar santri.
- **Karakter Ketahanan Dispersi**: Ukuran variabilitas data tidak berubah terhadap pergeseran nilai ($S_{\text{baru}} = S_{\text{lama}}$), namun berubah proporsional terhadap pengali.
- **Nilai Luhur Itqan**: Penguasaan matematika semester ganjil membina santri untuk berpikir runtut, teliti, berkeadilan hisab, dan mengagumi keteraturan sunnatullah di alam semesta.
