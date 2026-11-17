# Rencana Pembaruan Modul 02: Deteksi Input Digital, Mekanisme Resistor Pull-Up, & Interaktivitas Tombol Tekan

**Tanggal:** 2026-11-14  
**Topik:** Rekayasa Sistem Tertanam & Robotika (Arduino Uno & Wokwi)  
**Target Berkas:** `robotics/materi-02-digital-input-pullup-pushbutton.md`  
**Status:** Selesai Diimplementasikan  

---

## 1. Analisis Kebutuhan & Ruang Lingkup Pembaruan
Berdasarkan standar modul pembelajaran yang telah diperbarui (`template-tutor.md` dan spesialisasi `santri-robotics-iot-mentor`), modul `robotics/materi-02-digital-input-pullup-pushbutton.md` sebelumnya masih menggunakan format lama (5 bagian) dan belum mengadopsi struktur 8 bagian terpadu, penanda jalur `[INTI]` vs `[PENDALAMAN]`, serta integrasi 3 Tema Berkelanjutan (*Persistent Lab Tracks*).

Pembaruan telah merestrukturisasi modul secara menyeluruh menjadi format 8 bagian standar:
1. **Bagian 1: Prasyarat, Jembatan Konsep Rekayasa, & Peta Alur `[INTI: SMK & Mahasiswa]`**:
   - Pengingat prasyarat cepat 2 menit (Modul 01: `pinMode(OUTPUT)`, `digitalWrite()`, logika biner 5V/0V, resistor pembatas arus) + 2 pertanyaan kilat.
   - Jembatan konsep (*Why Before How*): komputasi berbasis input, bahaya *floating pin* (impedansi CMOS internal > 100 MΩ, antena penangkap noise elektromagnetik acak), dan komparasi pull-down eksternal vs pull-up internal (logika *Active-LOW*).
   - Tujuan pembelajaran & diagram alur sinyal fisik (ASCII).
2. **Bagian 2: Kamus Teknis: Fungsi C++, Library, & Argumen `[INTI: SMK & Mahasiswa]`**:
   - Pembedahan menyeluruh format penulisan, parameter, tipe data (`uint8_t`), batas tegangan CMOS AVR ($V_{IH} \ge 3.0\text{V}$, $V_{IL} \le 1.5\text{V}$), manipulasi register internal (DDRx, PORTx, MCUCR-PUD, PINx), serta contoh mikro 1–3 baris mandiri untuk `pinMode(pin, INPUT_PULLUP)` dan `digitalRead(pin)`.
3. **Bagian 3: Prasyarat Wokwi, Skema Pinout, & Checkpoint 3 Tema Berkelanjutan `[INTI: SMK & Mahasiswa]`**:
   - Spesifikasi simulator Wokwi Arduino Uno R3, daftar komponen, tabel pemetaan pinout, diagram skematik sirkuit ASCII, dan langkah perakitan interaktif.
   - Peta progres 3 Tema Berkelanjutan: Tema 1 (Otomasi Fasilitas), Tema 2 (Robotika Navigasi & Keselamatan), Tema 3 (Telemetri IoT ESP32).
4. **Bagian 4: Implementasi Kode Bersih Bertingkat — Tema 1 *(Minimal 4 Contoh)* `[INTI]` & `[PENDALAMAN]`**:
   - Contoh 1: Kasus Dasar (*Momentary Switch* mengendalikan LED indikator) `[INTI]`.
   - Contoh 2: Variasi Parameter & Inversi Logika (*Direct Logic Mapping*) + *State Tracing* per siklus loop `[INTI]`.
   - Contoh 3: Kombinasi Menengah (*State Toggle Switch* dengan deteksi tepi jatuh / *falling edge*) + *Step-by-Step State Trace* `[INTI]`.
   - Contoh 4: Implementasi Terapan, Debouncing Perangkat Lunak Non-Blocking (`millis()`), & Keamanan Sistem `[PENDALAMAN]`.
5. **Bagian 5: Verifikasi Rangkaian, Rumus Elektronika, & Debugging (*Common Pitfalls*) `[INTI: SMK & Mahasiswa]`**:
   - Analisis elektronika pembuktian mengapa Active-LOW bebas hubungan singkat, perhitungan arus pull-up internal ($I \approx 142.8\,\mu\text{A}$), disipasi daya termal ($P \approx 0.714\text{ mW}$), dan verifikasi arus LED ($I \approx 13.6\text{ mA}$).
   - Tabel ekspektasi simulasi Contoh 1–4.
   - Tabel troubleshooting kesalahan umum sirkuit dan kode di Wokwi.
6. **Bagian 6: Latihan Praktikum Berkelanjutan Multi-Tema *(4 Level)***:
   - Level 1: Pemahaman Fungsi & Tracing Sinyal *(Tema 1)* `[INTI]`.
   - Level 2: Modifikasi Terarah di Wokwi *(Tema 1 — Sistem Kunci Pintu Fasilitas)* `[INTI]`.
   - Level 3: Transfer ke Subsistem Robotika *(Tema 2 — Bumper Collision Switch / Limit Switch)* `[INTI LANJUT]`.
   - Level 4: Kasus Batas, Optimasi, & Telemetri *(Tema 3 — Penghitung Ketukan Tombol & Format Serial Telemetri)* `[PENDALAMAN]`.
7. **Bagian 7: Cek Mandiri, Milestone Tema, & Checklist Verifikasi Wokwi `[INTI: SMK & Mahasiswa]`**:
   - Indikator keberhasilan tugas & target tampilan Serial Monitor / perilaku visual LED tanpa membocorkan kunci kode penuh.
   - Checklist kesiapan teknis pengujian simulasi Wokwi.
8. **Bagian 8: Ringkasan Fungsi & Refleksi Pemahaman `[INTI: SMK & Mahasiswa]`**:
   - Tabel ringkasan fungsi C++ hemat memori dan parameter kritis.
   - Pertanyaan refleksi analitis lintas modul dan lintas tema.

---

## 2. Tahapan Eksekusi
- [x] Fase 1: Pembuatan dokumen rencana `.plan/2026-11-14-pembaharuan-modul-02-robotics.md`
- [x] Fase 2: Eksekusi otomatis `Start-Process` untuk membuka berkas rencana di sistem pengguna
- [x] Fase 3: Penulisan ulang naskah modul `robotics/materi-02-digital-input-pullup-pushbutton.md` secara komprehensif mengikuti 8 bagian terpadu
- [x] Fase 4: Verifikasi akurasi rumus elektronika, tata bahasa teknis lugas, konsistensi Clean Code, dan pembaruan status rencana menjadi selesai
