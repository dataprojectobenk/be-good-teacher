# MATEMATIKA SMA (JURUSAN IPA & PESANTREN)
## Kelas 12 - Semester Ganjil
### Pertemuan 11: Pemantapan Materi Esensial Kelas 10 & 11 — Review Terpadu Aljabar, Fungsi, Matriks, Trigonometri, dan Kalkulus Diferensial

---

### 1. Pemanasan Prasyarat & Konsep Dasar

#### A. Pemanasan Cepat (3 Menit)
Kerjakan 2 butir soal kilat penyegar ingatan fondasi aljabar dan kalkulus berikut:
1. Sederhanakan bentuk eksponen berikut: $\frac{2^5 \cdot 3^2}{2^3 \cdot 3^{-1}}$!
2. Jika $f(x) = 3x^2 - 4x + 5$, tentukan turunan pertama $f'(x)$ dan hitung nilai $f'(2)$!

*(Cek singkat: No. 1 = $2^{5-3} \cdot 3^{2-(-1)} = 4 \cdot 27 = 108$; No. 2 = $f'(x) = 6x - 4 \implies f'(2) = 12 - 4 = 8$)*

---

#### B. Pengertian & Analogi Nyata

Memasuki tahap akhir semester ganjil kelas 12, penguasaan matematika tidak lagi dipelajari secara parsial atau terkotak-kotak per bab, melainkan dituntut untuk menyatu ke dalam satu kerangka berpikir yang utuh. Setiap cabang matematika yang telah dipelajari di Kelas 10 dan Kelas 11 sesungguhnya saling bertaut dan menopang satu sama lain:
- **Aljabar, Eksponen, & Logaritma**: Merupakan bahasa simbolik dan alat hitung dasar untuk menyederhanakan hubungan kuantitatif.
- **Fungsi & Grafiknya**: Menggambarkan model hubungan sebab-akibat antar-variabel di alam semesta.
- **Trigonometri & Geometri**: Menyediakan sarana pemodelan sudut, periodisitas alam, dan orientasi ruang (seperti perhitungan arah kiblat dan waktu salat).
- **Matriks & SPL**: Merupakan instrumen pengorganisasian data multivariabel dan pemecahan sistem simultan.
- **Kalkulus Diferensial**: Merupakan puncak analisis matematika untuk mempelajari laju perubahan sesaat (*rate of change*) dan mencari solusi optimum (nilai maksimum atau minimum).

**Nilai Filosofis & Keteraturan Sunnatullah:**  
Dalam tradisi pesantren, proses mengulang dan memperkuat fondasi keilmuan dikenal dengan istilah **muraja'ah**. Rasulullah SAW bersabda:

> *"Perumpamaan penghafal Al-Qur'an adalah seperti pemilik unta yang terikat. Jika ia selalu menjaganya (merawat dan mengikatnya), maka ia akan tetap memilikinya. Namun jika ia melepaskannya, maka unta itu akan pergi."*  
> **(HR. Bukhari & Muslim)**

Prinsip *muraja'ah* ini berlaku mutlak dalam ilmu matematika. Pemahaman konsep yang tidak dirawat dan dilatih secara berkala akan mudah pudar. Melalui pemantapan terpadu ini, santri melatih ketajaman nalar (*dzaka'*) dan ketelitian (*diqqah*) dalam membedah soal-soal berstandar ujian kelulusan, seleksi perguruan tinggi, maupun uji kompetensi kader ulama cendekia.

---

### 2. Bentuk Umum & Rumus Baku

Berikut adalah intisari formula kunci (*Quick-Reference Formula Vault*):

#### A. Aljabar & Fungsi (Kelas 10)
1. **Sifat Eksponen & Logaritma:**
   $$
   a^m \cdot a^n = a^{m+n}, \quad \frac{a^m}{a^n} = a^{m-n}, \quad {}^a\log b = c \iff a^c = b, \quad {}^a\log(b \cdot c) = {}^a\log b + {}^a\log c
   $$
2. **Fungsi Komposisi & Invers:**
   $$
   (f \circ g)(x) = f(g(x)), \quad (f \circ g)^{-1}(x) = (g^{-1} \circ f^{-1})(x)
   $$
   $$
   f(x) = \frac{ax + b}{cx + d} \implies f^{-1}(x) = \frac{-dx + b}{cx - a} \quad \left(x \neq \frac{a}{c}\right)
   $$

#### B. Matriks & Kalkulus Diferensial (Kelas 11)
1. **Invers Matriks Ordo $2 \times 2$:**
   $$
   A = \begin{pmatrix} a & b \\ c & d \end{pmatrix} \implies A^{-1} = \frac{1}{ad - bc} \begin{pmatrix} d & -b \\ -c & a \end{pmatrix} \quad (ad - bc \neq 0)
   $$
2. **Turunan Aljabar Dasar & Rantai:**
   $$
   \frac{d}{dx}(a x^n) = a n x^{n-1}, \quad \frac{d}{dx}[u(x)]^n = n [u(x)]^{n-1} \cdot u'(x)
   $$
3. **Garis Singgung & Optimasi Stasioner:**
   - Gradien garis singgung di titik $(x_1, y_1)$: $m = f'(x_1)$, persamaan $y - y_1 = m(x - x_1)$.
   - Syarat stasioner (ekstrem maksimum/minimum): $f'(x) = 0$.

---

### 3. Sifat-Sifat & Aturan Penting

1. **Aturan Turunan Hasil Kali & Hasil Bagi:**
   $$
   (u \cdot v)' = u'v + uv', \quad \left(\frac{u}{v}\right)' = \frac{u'v - uv'}{v^2}
   $$
2. **Karakteristik Fungsi Naik & Turun:**
   - Fungsi naik pada interval di mana $f'(x) > 0$.
   - Fungsi turun pada interval di mana $f'(x) < 0$.
3. **Sifat Determinan Matriks:**
   $$
   \det(A \cdot B) = \det(A) \cdot \det(B), \quad \det(A^{-1}) = \frac{1}{\det(A)}
   $$

---

### 4. Contoh Soal & Pembahasan Bertingkat Mikro

#### Contoh 1 (Tingkat Dasar — Eksponen, Logaritma, & Invers Fungsi)

**Soal:**  
1. Tentukan nilai $x$ yang memenuhi persamaan eksponen $3^{2x - 1} = 27^{x - 2}$!  
2. Tentukan rumus fungsi invers dari $f(x) = \frac{3x - 5}{2x + 4}$ dengan $x \neq -2$!

**Penyelesaian:**
- **Bagian 1 (Persamaan Eksponen):**
  Samakan basis ke bilangan pokok $3$:
  $$
  3^{2x - 1} = (3^3)^{x - 2} \implies 3^{2x - 1} = 3^{3x - 6}
  $$
  Samakan eksponen:
  $$
  2x - 1 = 3x - 6 \implies 3x - 2x = 6 - 1 \implies x = 5
  $$
- **Bagian 2 (Invers Fungsi Pecahan):**
  Gunakan rumus cepat $f(x) = \frac{ax+b}{cx+d} \implies f^{-1}(x) = \frac{-dx+b}{cx-a}$ dengan $a = 3, b = -5, c = 2, d = 4$:
  $$
  f^{-1}(x) = \frac{-4x - 5}{2x - 3}, \quad x \neq \frac{3}{2}
  $$
- **Kesimpulan:** Nilai $x = 5$ dan rumus inversnya adalah $f^{-1}(x) = \frac{-4x - 5}{2x - 3}$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 2 (Variasi Menengah — Garis Singgung Kurva dan Nilai Stasioner)

**Soal:**  
Diberikan kurva $y = 2x^2 - 3x + 1$.  
a. Tentukan persamaan garis singgung kurva di titik yang berabsis $x = 2$!  
b. Tentukan koordinat titik puncak/stasioner kurva tersebut!

**Penyelesaian:**
- **Bagian a (Persamaan Garis Singgung):**
  1. Ordinat titik singgung: $y_1 = 2(2)^2 - 3(2) + 1 = 8 - 6 + 1 = 3 \implies (2, 3)$.
  2. Gradien garis singgung: $m = y' = 4x - 3 \implies m = 4(2) - 3 = 5$.
  3. Persamaan garis singgung:
     $$
     y - 3 = 5(x - 2) \implies y - 3 = 5x - 10 \implies y = 5x - 7
     $$
- **Bagian b (Titik Stasioner):**
  Syarat stasioner $y' = 0$:
  $$
  4x - 3 = 0 \implies x = \frac{3}{4}
  $$
  Ordinat puncak:
  $$
  y = 2\left(\frac{3}{4}\right)^2 - 3\left(\frac{3}{4}\right) + 1 = 2\left(\frac{9}{16}\right) - \frac{9}{4} + 1 = \frac{9}{8} - \frac{18}{8} + \frac{8}{8} = -\frac{1}{8}
  $$
- **Kesimpulan:** Garis singgung kurva adalah $y = 5x - 7$ dan titik stasioner kurva adalah $\left(\frac{3}{4}, -\frac{1}{8}\right)$ ($\checkmark\ \text{Benar}$).

---

#### Contoh 3 (Pengembangan — Optimasi Luas Kebun Sayur Pesantren)

**Soal:**  
Pengurus santri agribisnis pesantren ingin memagari kebun sayur berbentuk persegi panjang yang menempel pada dinding asrama (sisi dinding tidak perlu dipagari). Kawat pagar yang tersedia sepanjang $80\text{ meter}$. Tentukan luas maksimum kebun sayur tersebut!

**Penyelesaian:**
- **Langkah 1 (Membuat Model Matematika):**
  Misalkan panjang sisi yang sejajar tembok $= x$, dan lebar dua sisi tegak lurus tembok $= y$.
  $$
  x + 2y = 80 \implies x = 80 - 2y
  $$
- **Langkah 2 (Fungsi Luas $L(y)$):**
  $$
  L(y) = x \cdot y = (80 - 2y)y = 80y - 2y^2
  $$
- **Langkah 3 (Optimasi Turunan Pertama $L'(y) = 0$):**
  $$
  L'(y) = 80 - 4y = 0 \implies 4y = 80 \implies y = 20\text{ meter}
  $$
  Panjang kebun: $x = 80 - 2(20) = 40\text{ meter}$.
- **Langkah 4 (Luas Maksimum):**
  $$
  L_{\max} = 40 \times 20 = 800\text{ m}^2
  $$
- **Kesimpulan:** Luas maksimum kebun sayur pesantren adalah $800\text{ m}^2$ ($\checkmark\ \text{Benar}$).

---

### 5. Penerapan Kontekstual (Studi Kasus Santri & Kehidupan Nyata)

#### Studi Kasus: Desain Kapasitas Ruang Kamar Asrama Santri
Pengurus pesantren merencanakan pembangunan kamar asrama baru berbentuk balok dengan alas persegi berukuran sisi $x\text{ meter}$ dan tinggi $y\text{ meter}$. Karena keterbatasan bahan dinding kedap suara, total luas permukaan $4$ dinding tegak dan $1$ atap kamar ditetapkan sebesar $48\text{ m}^2$ (lantai kamar tidak menggunakan bahan ini).

Berapakah ukuran alas $x$ dan tinggi $y$ agar volume ruang kamar santri mencapai kapasitas maksimum?

**Langkah Pemecahan:**
1. **Model Matematika:**
   Luas atap $= x^2$, luas 4 dinding $= 4xy$.
   $$
   x^2 + 4xy = 48 \implies 4xy = 48 - x^2 \implies y = \frac{48 - x^2}{4x} = \frac{12}{x} - \frac{1}{4}x
   $$
2. **Fungsi Volume Ruangan $V(x)$:**
   $$
   V(x) = x^2 \cdot y = x^2 \left(\frac{12}{x} - \frac{1}{4}x\right) = 12x - \frac{1}{4}x^3
   $$
3. **Syarat Maksimum ($V'(x) = 0$):**
   $$
   V'(x) = 12 - \frac{3}{4}x^2 = 0 \implies \frac{3}{4}x^2 = 12 \implies x^2 = 16 \implies x = 4\text{ meter} \quad (x > 0)
   $$
4. **Tinggi dan Volume Maksimum:**
   $$
   y = \frac{12}{4} - \frac{1}{4}(4) = 3 - 1 = 2\text{ meter}
   $$
   $$
   V_{\max} = 4^2 \times 2 = 32\text{ m}^3
   $$
5. **Jawaban Kontekstual:**  
   Kamar santri harus dibangun dengan ukuran alas $4\text{ m} \times 4\text{ m}$ dan tinggi $2\text{ m}$ untuk menghasilkan volume maksimum $32\text{ m}^3$.

---

### 6. Latihan Soal Kelas (Bertingkat & Terstruktur)

#### A. Latihan Terbimbing (Ikuti Langkah Contoh)
Kerjakan soal berikut dengan mengikuti urutan langkah pada contoh yang telah dipelajari:
1. Tentukan nilai $x$ dari persamaan eksponen $2^{3x - 2} = 16^{x - 1}$!
2. Tentukan persamaan garis singgung pada kurva $y = x^2 - 4x + 5$ di titik yang berabsis $x = 3$!

#### B. Penguasaan Prosedur Inti (Latihan Bertahap)
1. Hitunglah nilai dari ekspresi logaritma: ${}^2\log 48 + {}^2\log \sqrt{2} - {}^2\log 3$!
2. Diketahui $f(x) = 3x - 2$ dan $g(x) = x^2 + 1$. Tentukan rumus fungsi komposisi $(g \circ f)(x)$!
3. Tentukan invers dari fungsi $f(x) = \frac{2x + 1}{x - 3}$ dengan $x \neq 3$!
4. Tentukan turunan pertama dari fungsi rasional $f(x) = \frac{2x - 1}{3x + 4}$!
5. Tentukan interval fungsi naik dari kurva $f(x) = \frac{1}{3}x^3 - x^2 - 3x + 4$!

#### C. Soal Cerita Kontekstual (Terpisah)
1. **Debit Pengisian Kolam Ikan Santri:**  
   Volume air kolam ikan pondok setelah $t\text{ menit}$ memenuhi persamaan $V(t) = 100t - 5t^2\text{ liter}$. Tentukan laju debit aliran air sesaat pada saat $t = 4\text{ menit}$!
2. **Efisiensi Pakan Budidaya Lele:**  
   Biaya pakan harian kolam lele santri (dalam ribuan rupiah) dinyatakan oleh fungsi $B(x) = 2x^2 - 40x + 300$, di mana $x$ adalah banyak kilogram pakan. Tentukan banyak pakan $x$ agar biaya harian minimum, serta hitung biaya minimum tersebut!

#### D. Soal Tantangan (Opsional / Pengayaan)
1. Panitia panggung perpisahan santri membuat panggung berbentuk persegi panjang dengan keliling pembatas $60\text{ meter}$. Buktikan dengan konsep kalkulus turunan bahwa luas panggung akan bernilai maksimum jika panggung tersebut berbentuk bujur sangkar (persegi), dan hitung luas maksimumnya!

---

### 7. Pola Kunci, Jebakan Umum, & Cara Cepat

- **Trik Cepat Turunan Fungsi Rasional Linear:**
  $$
  f(x) = \frac{ax + b}{cx + d} \implies f'(x) = \frac{ad - bc}{(cx + d)^2}
  $$
- **Trik Titik Balik Parabola:**
  Titik stasioner kurva $y = ax^2 + bx + c$ selalu tercapai saat $y' = 0 \implies x_p = -\frac{b}{2a}$.

- **Waspada Jebakan Umum:**
  - ⚠️ **Kekeliruan Aturan Rantai:** Turunan dari $[u(x)]^n$ adalah $n[u(x)]^{n-1} \cdot u'(x)$. Jangan lupa mengalikan dengan turunan fungsi dalamnya ($u'(x)$)!
  - ⚠️ **Syarat Fungsi Naik/Turun:** Fungsi naik syaratnya $f'(x) > 0$, fungsi turun syaratnya $f'(x) < 0$.

---

### 8. Pekerjaan Rumah (PR Pondok / Mandiri)

**Petunjuk:**
- Kerjakan di buku tugas matematika secara mandiri, teratur, dan sistematis.
- Periksa hasil akhirmu dengan mencocokkan pada kunci jawaban di bawah.

1. Diketahui fungsi $f(x) = 2x - 3$ dan $g(x) = \frac{x+1}{x-2} \ (x \neq 2)$. Tentukan rumus fungsi komposisi $(f \circ g)(x)$ dan tentukan nilai dari $(f \circ g)(4)$!
2. Selesaikan sistem persamaan linear tiga variabel berikut:
   $$
   \begin{cases}
   x + y + z = 6 \\
   2x - y + z = 3 \\
   x + 2y - z = 2
   \end{cases}
   $$
3. **Optimasi Lahan Kebun Sayur Pesantren:**  
   Santri memagari kebun persegi panjang sepanjang $120\text{ meter}$ yang dibagi menjadi dua petak berdampingan (dengan $1$ pagar pemisah di tengah). Tentukan ukuran panjang dan lebar kebun agar luas total maksimum, serta hitung luas maksimumnya!
4. **Pertanyaan Pemahaman Konsep:**  
   *Jelaskan mengapa konsep turunan pertama ($f'(x) = 0$) dapat digunakan untuk menentukan nilai maksimum atau minimum dari suatu fungsi kehidupan nyata! Buatlah ilustrasi grafis sederhana yang menunjukkan posisi titik puncak tersebut!*

**Kunci Jawaban Akhir Ringkas (Cek Mandiri):**
- **Latihan 6A:** `1.` $x = 2$ | `2.` $y = 2x - 4$
- **Latihan 6B:** `1.` $4{,}5$ | `2.` $(g \circ f)(x) = 9x^2 - 12x + 5$ | `3.` $f^{-1}(x) = \frac{3x + 1}{x - 2}$ | `4.` $f'(x) = \frac{11}{(3x + 4)^2}$ | `5.` $x < -1 \text{ atau } x > 3$
- **Latihan 6C:** `1.` $60\text{ liter/menit}$ | `2.` $x = 10\text{ kg}$, biaya minimum $=\text{Rp } 100.000$
- **PR No. 1–3:** `1.` $(f \circ g)(x) = \frac{-x + 8}{x - 2}$, $(f \circ g)(4) = 2$ | `2.` $(x, y, z) = (1, 2, 3)$ | `3.` Ukuran $30\text{ m} \times 20\text{ m}$, Luas Maksimum $= 600\text{ m}^2$

---

### 9. Ringkasan Materi

- **Hakikat Muraja'ah**: Merawat dan mengaitkan konsep-konsep aljabar, fungsi, matriks, dan kalkulus agar menyatu menjadi pemahaman yang utuh dan siap menghadapi ujian akhir.
- **Keterpaduan Aljabar & Geometri**: Persamaan linear diselesaikan lewat matriks dan determinan, sedangkan periodisitas dan orientasi spasial dimodelkan lewat trigonometri.
- **Daya Analisis Kalkulus**: Turunan pertama $f'(x)$ menjadi instrumen utama dalam menentukan gradien garis singgung kurva, interval kemonotonan fungsi, dan optimasi nilai ekstrem nyata.
- **Keteraturan Sunnatullah**: Setiap teorema matematika melatih santri berpikir runtut, presisi, dan bersungguh-sungguh (*itqan*) dalam menyelesaikan permasalahan umat.
