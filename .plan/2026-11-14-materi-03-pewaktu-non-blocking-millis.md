# Rencana Penyusunan Modul 03: Arsitektur Pewaktu Non-Blocking millis(), Eliminasi CPU Blocking, & Multitasking I/O

**Tanggal:** 2026-11-14  
**Topik:** Rekayasa Sistem Tertanam & Robotika (Arduino Uno & Wokwi)  
**Target Berkas:** `robotics/materi-03-pewaktu-non-blocking-millis-multitasking.md`  
**Status:** Selesai Diimplementasikan  

---

## 1. Analisis Kebutuhan & Ruang Lingkup Materi 03
Melanjutkan silabus kurikulum Tahap 1 (Fondasi Digital I/O & Rekayasa Waktu), materi ketiga difokuskan pada penguasaan manajemen waktu tingkat industri:
- Menggantikan fungsi pembeku CPU `delay()` dengan fungsi pewaktu non-blocking `millis()`.
- Menyelesaikan masalah kelaparan proses (*CPU Starvation*) agar mikrokontroler mampu menjalankan konkurensi tugas (*cooperative multitasking*).
- Menghubungkan pembacaan input tombol interaktif (Modul 02) dengan kendali multi-aktuator LED (Modul 01) secara bersamaan.
- Membangun proyek penutup Tahap 1: Sistem Lampu Lalu Lintas Adaptif & Penyeberangan Jalan Pejalan Kaki Non-Blocking (*Pedestrian Pelican Crossing*).
- Mengintegrasikan penanda jalur `[INTI: SMK & Mahasiswa]` vs `[PENDALAMAN: Mahasiswa / SMK Lanjutan]` serta 3 Tema Berkelanjutan (*Otomasi Fasilitas*, *Robotika Navigasi*, dan *Telemetri IoT ESP32*).

---

## 2. Struktur Modul Terpadu (8 Bagian Standar)
1. **Bagian 1: Prasyarat, Jembatan Konsep Rekayasa, & Peta Alur `[INTI]`**: Refresh Modul 01–02, bahaya CPU blocking di industri, model mental stopwatch vs alarm tidur, aritmatika modular rollover `unsigned long`, dan diagram alur sinyal konkuren.
2. **Bagian 2: Kamus Teknis: Fungsi C++, Library, & Argumen `[INTI]`**: Pembedahan fungsi `millis()`, register Timer 0 AVR, interupsi overflow, pola kerangka waktu non-blocking, serta contoh mikro 1–3 baris.
3. **Bagian 3: Prasyarat Wokwi, Skema Pinout, & Checkpoint 3 Tema `[INTI]`**: Kebutuhan simulator Wokwi, tabel pemetaan pinout multi-LED + tombol, diagram skematik ASCII, langkah perakitan, dan peta checkpoint 3 tema.
4. **Bagian 4: Implementasi Kode Bersih Bertingkat — Tema 1 *(Minimal 4 Contoh)* `[INTI]` & `[PENDALAMAN]`**:
   - Contoh 1: Kasus Paling Dasar (Blink Without Delay).
   - Contoh 2: Variasi Dua Jadwal Independen Asimetris + *State Tracing* tabel waktu.
   - Contoh 3: Multitasking I/O (Lampu Berkedip + Tombol Input Respons Instan) + *Step-by-Step Trace*.
   - Contoh 4: Penerapan Terapan: Mesin Status (*Finite State Machine*) Sistem Lampu Lalu Lintas Adaptif Non-Blocking `[PENDALAMAN]`.
5. **Bagian 5: Verifikasi Rangkaian, Rumus Elektronika, & Debugging `[INTI]`**: Pembuktian matematis kekebalan rollover unsigned subtraction, analisis beban daya total sirkuit multi-LED (Hukum Arus Kirchhoff), dan tabel troubleshooting Wokwi.
6. **Bagian 6: Latihan Praktikum Berkelanjutan Multi-Tema *(4 Level)*:**
   - Level 1: Pemahaman Fungsi & Tracing Sinyal *(Tema 1)* `[INTI]`.
   - Level 2: Modifikasi Terarah di Wokwi *(Tema 1: Strobo Gerbang Otomasi 3 Interval)* `[INTI]`.
   - Level 3: Transfer ke Subsistem Robotika *(Tema 2: Beacon Strobo Sasis + Deteksi Bumper Non-Blocking)* `[INTI LANJUT]`.
   - Level 4: Kasus Batas, Optimasi, & Telemetri *(Tema 3: Heartbeat Telemetri IoT ESP32 + Rate Limiter)* `[PENDALAMAN]`.
7. **Bagian 7: Cek Mandiri, Milestone Tema, & Checklist Verifikasi Wokwi `[INTI]`**: Indikator keberhasilan visual dan serial output tanpa membocorkan kunci kode penuh.
8. **Bagian 8: Ringkasan Fungsi & Refleksi Pemahaman `[INTI]`**: Tabel ringkasan fungsi/pola non-blocking dan 2 pertanyaan refleksi analitis.

---

## 3. Tahapan Eksekusi
- [x] Fase 1: Pembuatan dokumen rencana `.plan/2026-11-14-materi-03-pewaktu-non-blocking-millis.md`
- [x] Fase 2: Eksekusi otomatis `Start-Process` untuk membuka berkas rencana di sistem pengguna
- [x] Fase 3: Penulisan naskah modul `robotics/materi-03-pewaktu-non-blocking-millis-multitasking.md` lengkap 8 bagian
- [x] Fase 4: Verifikasi integritas notasi KaTeX, rumus elektronika, kompilasi Wokwi-ready, dan pembaruan status rencana menjadi selesai
