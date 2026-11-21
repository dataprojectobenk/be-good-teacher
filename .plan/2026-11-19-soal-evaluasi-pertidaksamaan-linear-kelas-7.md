# Rencana Evaluasi Pembelajaran Matematika Kelas 7: Pertidaksamaan Linear Satu Variabel (PtLSV)

**Tanggal:** 2026-11-19  
**Topik:** Pertidaksamaan Linear Satu Variabel (PtLSV)  
**Target Berkas:** `math/kelas-7/evaluasi-pertidaksamaan-linear.md`  
**Standar:** KaTeX LaTeX murni, angka ramah hitung (solusi bulat $\mathbb{Z}$, zero pecahan sulit), zero teacher rubric.

---

## 1. Analisis Kebutuhan & Spesifikasi Soal
- **Target Pembaca:** Siswa SMP/MTs Kelas 7 Semester Ganjil.
- **Komposisi 10 Butir Soal:**
  1. **5 Soal Menentukan Nilai/Penyelesaian $x$ (Aljabar Murni):**
     - Variasi tanda ketidaksamaan ($>, <, \ge, \le$).
     - Variasi koefisien positif, variabel di kedua ruas, sifat distributif kurung, dan sifat pembalikan tanda saat perkalian/pembagian bilangan negatif.
  2. **3 Soal Cerita Menentukan Bentuk Pertidaksamaan (Model Matematika):**
     - Memodelkan frasa kontekstual: "maksimum / tidak lebih dari" ($\le$), "tidak melebihi / paling banyak" ($\le$), dan "minimal / sekurang-kurangnya" ($\ge$).
  3. **2 Soal Cerita Menentukan Nilai $x$:**
     - Soal cerita kontekstual terapan (geometri/keliling & target pengumpulan dana) diselesaikan hingga menemukan batas dan nilai bulat $x$.
- **Karakteristik Numerik:**
  - Seluruh nilai akhir menghasilkan bilangan bulat ramah hitung ($\mathbb{Z}$).
  - Tidak melibatkan pecahan rumit/sulit.

---

## 2. Rancangan Soal & Verifikasi Solusi

### Bagian A: 5 Soal Menentukan Nilai $x$
1. **Soal 1 (Bentuk Dasar $ax + b > c$):**
   $$2x + 4 > 10$$
   *Solusi:* $2x > 6 \implies x > 3$ (Himpunan penyelesaian untuk $x \in \mathbb{Z}$: $\{4, 5, 6, \dots\}$)
2. **Soal 2 (Bentuk $ax - b \le c$):**
   $$3x - 5 \le 7$$
   *Solusi:* $3x \le 12 \implies x \le 4$ (Himpunan penyelesaian untuk $x \in \mathbb{Z}$: $\{\dots, 2, 3, 4\}$)
3. **Soal 3 (Variabel di Kedua Ruas):**
   $$5x - 3 < 2x + 9$$
   *Solusi:* $3x < 12 \implies x < 4$
4. **Soal 4 (Koefisien Negatif & Pembalikan Tanda):**
   $$-3x + 7 \le -2$$
   *Solusi:* $-3x \le -9 \implies x \ge 3$
5. **Soal 5 (Bentuk Kurung Distributif & Variabel Kedua Ruas):**
   $$2(x - 3) + 5 \ge 4x - 7$$
   *Solusi:* $2x - 1 \ge 4x - 7 \implies -2x \ge -6 \implies x \le 3$

### Bagian B: 3 Soal Cerita Menentukan Bentuk Pertidaksamaan
6. **Soal 6 (Kapasitas Muatan Lift Barang):**
   Beban maksimal $450\text{ kg}$, berat operator $65\text{ kg}$, berat tiap kardus $25\text{ kg}$ untuk $x$ kardus.  
   *Model:* $25x + 65 \le 450$
7. **Soal 7 (Batas Anggaran Belanja Alat Tulis):**
   Uang saku $\text{Rp}50.000$, membeli $x$ buku @ $\text{Rp}4.000$ dan $1$ penggaris $\text{Rp}6.000$.  
   *Model:* $4.000x + 6.000 \le 50.000$ (atau $4x + 6 \le 50$)
8. **Soal 8 (Syarat Skor Kelulusan Ujian Hafalan):**
   Syarat minimal $80$ poin, nilai awal $56$ poin, tambahan $x$ lembar hafalan bernilai $3$ poin/lembar.  
   *Model:* $3x + 56 \ge 80$

### Bagian C: 2 Soal Cerita Menentukan Nilai $x$
9. **Soal 9 (Keliling Papan Pengumuman):**
   Panjang $(2x + 1)\text{ cm}$, lebar $8\text{ cm}$, keliling tidak lebih dari $42\text{ cm}$.  
   *Model:* $2((2x + 1) + 8) \le 42 \implies 4x + 18 \le 42 \implies 4x \le 24 \implies x \le 6$.  
   *Solusi:* Batas nilai $x \le 6$ (nilai bulat maksimum $x = 6$).
10. **Soal 10 (Target Penjualan Kalender Dana Baksos):**
    Target minimal $\text{Rp}600.000$, kas awal $\text{Rp}150.000$, harga kalender $\text{Rp}15.000$ per eksemplar untuk $x$ eksemplar.  
    *Model:* $15.000x + 150.000 \ge 600.000 \implies 15x \ge 450 \implies x \ge 30$.  
    *Solusi:* Minimal $30$ paket kalender harus terjual ($x \ge 30$).

---

## 3. Tahapan Eksekusi
- [x] Perancangan komposisi 10 soal dengan kalibrasi angka bulat ramah hitung.
- [x] Penulisan berkas lembar evaluasi lengkap di `math/kelas-7/evaluasi-pertidaksamaan-linear.md`.
- [x] Revisi sesuai permintaan pengguna: penyesuaian format judul menjadi `Lembar Evaluasi - Pertidaksamaan Linear`, menghapus petunjuk pengerjaan, dan menghilangkan kunci jawaban (lembar soal murni).
- [x] Status: Selesai Diimplementasikan.
