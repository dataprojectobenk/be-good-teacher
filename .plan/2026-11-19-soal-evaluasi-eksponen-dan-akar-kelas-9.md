# Rencana Evaluasi Pembelajaran Matematika Kelas 9: Eksponen dan Bilangan Akar

**Tanggal:** 2026-11-19  
**Topik:** Eksponen (Bilangan Berpangkat) dan Bentuk Akar  
**Sasaran Kelas:** SMP/MTs Kelas 9 Semester Ganjil  
**Target Berkas:** `matematika-smp/kelas-9/evaluasi-eksponen-dan-akar.md`  
**Standar Mutu:** KaTeX LaTeX murni, angka ramah hitung ($\mathbb{Z}$ dan bentuk akar rapi), instruksi terpusat (anti-repetisi), bebas label internal authoring, dan **tanpa merasionalkan bentuk akar**.

---

## 1. Analisis Kebutuhan & Spesifikasi Soal

- **Komposisi 20 Butir Soal:**
  1. **5 Soal Menentukan Nilai Akhir Eksponen:**
     - Operasi penjumlahan/pengurangan pangkat bulat positif dasar.
     - Sifat perkalian dan pembagian bilangan berpangkat berbasis sama.
     - Pangkat bilangan nol ($a^0 = 1$) dan pangkat bulat negatif ($a^{-n} = \frac{1}{a^n}$).
     - Pangkat pecahan (eksponen rasional) dasar ($a^{\frac{m}{n}} = \sqrt[n]{a^m}$).
     - Kombinasi pecahan berpangkat negatif dan pangkat pecahan.
  2. **5 Soal Menyederhanakan Eksponen (Bentuk Aljabar Pangkat Positif):**
     - Perkalian suku aljabar berpangkat: $(a^m b^n) \times (a^p b^q)$.
     - Pembagian bentuk aljabar berpangkat dengan koefisien: $\frac{A x^m y^n}{B x^p y^q}$.
     - Pemangkatan bentuk perkalian aljabar berkoefisien: $(k p^m q^n)^r$.
     - Bentuk aljabar dengan pangkat negatif diubah menjadi pangkat positif sederhana.
     - Pemangkatan pecahan aljabar gabungan dengan koefisien dan penyederhanaan pangkat.
  3. **5 Soal Menentukan Nilai Akar:**
     - Operasi penjumlahan/pengurangan akar kuadrat kuadrat sempurna.
     - Akar kuadrat dari hasil perkalian dan kuadrat sempurna.
     - Perkalian dua bentuk akar yang menghasilkan bilangan kuadrat sempurna.
     - Pembagian dua bentuk akar yang menghasilkan bilangan kuadrat sempurna.
     - Kombinasi akar pangkat tiga ($\sqrt[3]{...}$) dan akar kuadrat.
  4. **5 Soal Menyederhanakan Bentuk Akar (Format $a\sqrt{b}$):**
     - Penarikan faktor kuadrat sempurna tunggal ($\sqrt{72} \to a\sqrt{b}$).
     - Penjumlahan dan pengurangan bentuk akar sejenis langsung.
     - Penjumlahan dan pengurangan akar belum sejenis yang disederhanakan terlebih dahulu.
     - Operasi campuran koefisien dan penyederhanaan tiga suku bentuk akar.
     - Perkalian distributif bentuk akar tunggal terhadap bentuk akar dalam kurung.
- **Batasan Khusus (Strict Constraints):**
  - **TIDAK melibatkan merasionalkan penyebut bentuk akar** (tanpa bentuk $\frac{a}{\sqrt{b}}$ atau pengalian sekawan).
  - Seluruh hasil perhitungan numerik menghasilkan bilangan bulat atau bentuk akar $a\sqrt{b}$ sederhana ramah hitung.
  - Pembahasan disajikan menurun ke bawah baris per baris menggunakan KaTeX `\begin{aligned} ... \end{aligned}` (anti-teks menumpuk secara horizontal).

---

## 2. Rancangan Soal & Verifikasi Solusi

### Bagian A: 5 Soal Menentukan Nilai Akhir Eksponen
1. **Soal 1:**
   $$2^4 + 3^3 - 5^2$$
   *Solusi:* $16 + 27 - 25 = 18$
2. **Soal 2:**
   $$\frac{2^5 \times 2^3}{2^4}$$
   *Solusi:* $2^{5+3-4} = 2^4 = 16$
3. **Soal 3:**
   $$4^{-2} \times 2^6 + 7^0$$
   *Solusi:* $\frac{1}{16} \times 64 + 1 = 4 + 1 = 5$
4. **Soal 4:**
   $$8^{\frac{2}{3}} + 27^{\frac{1}{3}}$$
   *Solusi:* $(2^3)^{\frac{2}{3}} + (3^3)^{\frac{1}{3}} = 2^2 + 3^1 = 4 + 3 = 7$
5. **Soal 5:**
   $$\left(\frac{1}{3}\right)^{-3} - 16^{\frac{3}{4}}$$
   *Solusi:* $3^3 - (2^4)^{\frac{3}{4}} = 27 - 2^3 = 27 - 8 = 19$

### Bagian B: 5 Soal Menyederhanakan Eksponen
6. **Soal 6:**
   $$(a^3 b^2) \times (a^4 b^5)$$
   *Solusi:* $a^{3+4} b^{2+5} = a^7 b^7$
7. **Soal 7:**
   $$\frac{12x^7 y^5}{3x^2 y^3}$$
   *Solusi:* $\frac{12}{3} x^{7-2} y^{5-3} = 4x^5 y^2$
8. **Soal 8:**
   $$\left(2p^3 q^4\right)^3$$
   *Solusi:* $2^3 \cdot (p^3)^3 \cdot (q^4)^3 = 8p^9 q^{12}$
9. **Soal 9:**
   $$\frac{a^5 b^{-3}}{a^2 b^4}$$
   *Solusi:* $a^{5-2} b^{-3-4} = a^3 b^{-7} = \frac{a^3}{b^7}$
10. **Soal 10:**
    $$\left(\frac{3x^4 y^{-2}}{x y^3}\right)^2$$
    *Solusi:* $\left(3x^{4-1} y^{-2-3}\right)^2 = (3x^3 y^{-5})^2 = 9x^6 y^{-10} = \frac{9x^6}{y^{10}}$

### Bagian C: 5 Soal Menentukan Nilai Akar
11. **Soal 11:**
    $$\sqrt{144} + \sqrt{81} - \sqrt{49}$$
    *Solusi:* $12 + 9 - 7 = 14$
12. **Soal 12:**
    $$\sqrt{4 \times 36} + \sqrt{25}$$
    *Solusi:* $\sqrt{144} + 5 = 12 + 5 = 17$
13. **Soal 13:**
    $$\sqrt{8} \times \sqrt{18}$$
    *Solusi:* $\sqrt{8 \times 18} = \sqrt{144} = 12$
14. **Soal 14:**
    $$\frac{\sqrt{300}}{\sqrt{3}} - \sqrt{64}$$
    *Solusi:* $\sqrt{\frac{300}{3}} - 8 = \sqrt{100} - 8 = 10 - 8 = 2$
15. **Soal 15:**
    $$\sqrt[3]{64} + \sqrt[3]{125} + \sqrt{169}$$
    *Solusi:* $4 + 5 + 13 = 22$

### Bagian D: 5 Soal Menyederhanakan Bentuk Akar
16. **Soal 16:**
    $$\sqrt{72}$$
    *Solusi:* $\sqrt{36 \times 2} = 6\sqrt{2}$
17. **Soal 17:**
    $$5\sqrt{3} + 4\sqrt{3} - 2\sqrt{3}$$
    *Solusi:* $(5 + 4 - 2)\sqrt{3} = 7\sqrt{3}$
18. **Soal 18:**
    $$\sqrt{50} + \sqrt{18} - \sqrt{8}$$
    *Solusi:* $5\sqrt{2} + 3\sqrt{2} - 2\sqrt{2} = 6\sqrt{2}$
19. **Soal 19:**
    $$2\sqrt{48} - 3\sqrt{12} + \sqrt{75}$$
    *Solusi:* $2(4\sqrt{3}) - 3(2\sqrt{3}) + 5\sqrt{3} = 8\sqrt{3} - 6\sqrt{3} + 5\sqrt{3} = 7\sqrt{3}$
20. **Soal 20:**
    $$\sqrt{2}(\sqrt{6} + \sqrt{24})$$
    *Solusi:* $\sqrt{12} + \sqrt{48} = 2\sqrt{3} + 4\sqrt{3} = 6\sqrt{3}$

---

## 3. Struktur Berkas yang Dihasilkan
Berkas `matematika-smp/kelas-9/evaluasi-eksponen-dan-akar.md` akan memuat:
1. **Header & Informasi Evaluasi** (Kelas 9 - Semester Ganjil).
2. **Lembar Soal Evaluasi 20 Butir**:
   - Bagian A: 5 Soal Menentukan Nilai Akhir Eksponen (No. 1–5).
   - Bagian B: 5 Soal Menyederhanakan Eksponen (No. 6–10).
   - Bagian C: 5 Soal Menentukan Nilai Akar (No. 11–15).
   - Bagian D: 5 Soal Menyederhanakan Bentuk Akar (No. 16–20).
3. **Kunci Jawaban & Pembahasan Lengkap Menurun Baris per Baris** (Langkah sistematis untuk setiap butir soal).
4. **Tabel Ringkasan Kunci Jawaban** (Rekapitulasi cepat untuk evaluasi mandiri siswa).

---

## 4. Tahapan Eksekusi
- [x] Analisis kebutuhan kurikulum Kelas 9 dan batasan tidak melibatkan rasionalkan akar.
- [x] Perancangan dan verifikasi manual 20 butir soal beserta kunci jawabannya.
- [x] Penulisan berkas rencana evaluasi di `.plan/2026-11-19-soal-evaluasi-eksponen-dan-akar-kelas-9.md`.
- [x] Buka berkas rencana otomatis via PowerShell `Start-Process`.
- [x] Implementasi berkas evaluasi lengkap di `matematika-smp/kelas-9/evaluasi-eksponen-dan-akar.md`.
- [x] Status: Selesai Diimplementasikan.
