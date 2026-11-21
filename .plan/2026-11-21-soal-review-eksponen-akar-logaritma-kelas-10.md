# Rencana Revisi Soal Latihan Review: Penguatan Fondasi Sifat Eksponen, Akar, dan Logaritma

**Tanggal:** 2026-11-21  
**Topik:** Penguatan Fondasi & Sifat-Sifat Operasi Eksponen, Bentuk Akar, dan Logaritma Dasar  
**Sasaran Jenjang:** SMA / MA Kelas 10 (Fase E) — Santri / Siswa Pembelajar Mandiri  
**Target Berkas:** `math/kelas-10/evaluasi/latihan-review-eksponen-akar-logaritma.md`  
**Prinsip Kalibrasi Baru Sesuai Arahan Pengguna:**
1. **Bebas Rasionalisasi Akar:** Menghapus seluruh soal merasionalkan penyebut pecahan akar (tanpa bentuk $\frac{a}{\sqrt{b}}$ atau pengalian sekawan).
2. **Bebas Pangkat Pecahan yang Memecah Basis:** Menghindari pangkat pecahan kompleks (tanpa $27^{2/3}$ atau $16^{-1/2}$); fokus pada pemahaman pangkat bulat positif, nol, negatif, dan sifat perkalian/pembagian/pemangkatan.
3. **Fokus Sifat Dasar Teruji:** Pemahaman matang terhadap sifat-sifat eksponen, akar, dan logaritma.
4. **Format Baku Permanen:** Instruksi terpusat satu kali pada sub-judul, setiap nomor langsung menyajikan bentuk matematis murni.
5. **Penyelesaian Menurun Vertikal:** KaTeX `\begin{aligned} ... \end{aligned}` baris per baris.

---

## 1. Distribusi 20 Butir Soal Baru

### Bagian A: Sifat-Sifat & Perhitungan Eksponen Dasar (No. 1–6)
- **1:** Operasi hitung bilangan berpangkat bulat dasar: $$2^5 - 3^3 + 4^2$$ (Solusi: $21$)
- **2:** Sifat perkalian basis sama ($a^m \cdot a^n = a^{m+n}$): $$2^3 \times 2^4 \times 2^2$$ (Solusi: $2^9 = 512$)
- **3:** Sifat pembagian basis sama ($\frac{a^m}{a^n} = a^{m-n}$): $$\frac{5^7}{5^4}$$ (Solusi: $5^3 = 125$)
- **4:** Sifat pemangkatan suku aljabar ($(a^m b^n)^p = a^{mp} b^{np}$): $$(a^3 b^2)^4$$ (Solusi: $a^{12} b^8$)
- **5:** Sifat pangkat nol ($a^0=1$) dan pangkat bulat negatif ($a^{-1} = \frac{1}{a}$): $$7^0 + 3^{-1} \times 9$$ (Solusi: $4$)
- **6:** Sifat pembagian aljabar eksponen: $$\frac{12x^5 y^4}{3x^2 y}$$ (Solusi: $4x^3 y^3$)

### Bagian B: Sifat-Sifat & Operasi Bentuk Akar Dasar (No. 7–12) — Bebas Rasionalisasi
- **7:** Penarikan akar kuadrat sempurna dasar: $$\sqrt{144} - \sqrt{64} + \sqrt{25}$$ (Solusi: $9$)
- **8:** Menyederhanakan akar tunggal ($\sqrt{a \cdot b} = \sqrt{a}\sqrt{b}$): $$\sqrt{48}$$ (Solusi: $4\sqrt{3}$)
- **9:** Operasi penjumlahan dan pengurangan akar sejenis: $$7\sqrt{5} + 4\sqrt{5} - 3\sqrt{5}$$ (Solusi: $8\sqrt{5}$)
- **10:** Operasi akar sejenis setelah disederhanakan: $$\sqrt{50} + \sqrt{18} - \sqrt{8}$$ (Solusi: $6\sqrt{2}$)
- **11:** Perkalian dua bentuk akar sederhana ($\sqrt{a} \times \sqrt{b} = \sqrt{ab}$): $$\sqrt{6} \times \sqrt{8}$$ (Solusi: $4\sqrt{3}$)
- **12:** Perkalian bentuk akar menghasilkan bilangan bulat ($(\sqrt{a})^2 = a$): $$3\sqrt{2} \times 4\sqrt{2}$$ (Solusi: $24$)

### Bagian C: Sifat-Sifat & Perhitungan Logaritma Dasar (No. 13–20)
- **13:** Definisi dasar logaritma (${}^a\log a^n = n, {}^a\log 1 = 0$): $${}^2\log 16 + {}^3\log 27 - {}^5\log 1$$ (Solusi: $7$)
- **14:** Sifat penjumlahan logaritma basis sama (${}^a\log x + {}^a\log y = {}^a\log(xy)$): $${}^2\log 4 + {}^2\log 8$$ (Solusi: $5$)
- **15:** Sifat pengurangan logaritma basis sama (${}^a\log x - {}^a\log y = {}^a\log(x/y)$): $${}^3\log 54 - {}^3\log 2$$ (Solusi: $3$)
- **16:** Operasi gabungan penjumlahan & pengurangan: $${}^5\log 50 + {}^5\log 10 - {}^5\log 4$$ (Solusi: $3$)
- **17:** Sifat perkalian rantai logaritma (${}^a\log b \times {}^b\log c = {}^a\log c$): $${}^2\log 3 \times {}^3\log 5 \times {}^5\log 8$$ (Solusi: $3$)
- **18:** Sifat pemangkatan numerus (${}^a\log x^n = n \cdot {}^a\log x$): Jika ${}^2\log 3 = a$, nyatakan $${}^2\log 27$$ dalam $a$ (Solusi: $3a$)
- **19:** Persamaan logaritma definisi dasar invers: $${}^2\log x = 5$$ (Solusi: $x = 32$)
- **20:** Persamaan logaritma kesamaan numerus (${}^a\log f(x) = {}^a\log b$): $${}^3\log(x + 5) = {}^3\log 12$$ (Solusi: $x = 7$)

---

## 2. Tahapan Eksekusi
- [x] Perancangan ulang 20 butir soal dasar tanpa rasionalisasi akar dan tanpa pangkat pecahan rumit.
- [x] Penerapan format baku instruksi terpusat.
- [x] Penulisan berkas revisi lengkap di `math/kelas-10/evaluasi/latihan-review-eksponen-akar-logaritma.md`.
- [x] Status: Selesai Diimplementasikan.
