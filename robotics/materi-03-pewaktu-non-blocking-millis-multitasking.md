# MODUL REKAYASA SISTEM TERTANAM & ROBOTIKA
## Tahap 1: Fondasi Digital I/O & Rekayasa Waktu (Arduino Uno)
### Modul 03: Arsitektur Pewaktu Non-Blocking `millis()`, Eliminasi CPU Blocking, & Multitasking I/O
- **Subjek / Jalur Keahlian:** Rekayasa Sistem Tertanam & Robotika (Arduino Uno & Wokwi)
- **Target Level:** Terpadu Bertingkat (SMK & Mahasiswa)
- **Fokus Materi:** Fungsi `millis()`, Eliminasi Pembekuan CPU (*CPU Blocking*), Pola Penjadwalan Waktu Non-Blocking (*State & Timestamp Scheduling*), dan Eksekusi Konkuren Multitasking I/O
- **Tema Berkelanjutan Aktif:** Tema 1 (`Otomasi Fasilitas Ruangan`), Tema 2 (`Robotika Navigasi & Keselamatan`), Tema 3 (`Telemetri IoT ESP32`)

---

### 1. Prasyarat, Jembatan Konsep Rekayasa, & Peta Alur `[INTI: SMK & Mahasiswa]`

#### A. Pengingat Prasyarat Cepat (2 Menit)
Sebelum melangkah ke rekayasa waktu tingkat lanjut, segarkan kembali konsep dari Modul 01 dan Modul 02:
- **Konsep Kunci yang Harus Sudah Dipahami:**
  1. Konfigurasi mode kerja pin melalui `pinMode(pin, OUTPUT)` dan `pinMode(pin, INPUT_PULLUP)`.
  2. Pembacaan status tombol logika biner *Active-LOW* melalui `digitalRead(pin)`.
  3. Penulisan sinyal keluaran biner melalui `digitalWrite(pin, HIGH/LOW)`.
  4. Penggunaan fungsi penunda waktu dasar `delay(ms)` untuk menciptakan jeda sekuensial.
- **Cek Kilat Prasyarat (Jawab Singkat Sebelum Melangkah):**
  1. *Apa yang sebenarnya dilakukan oleh prosesor Arduino saat mengeksekusi instruksi `delay(1000)`?*  
     *(Jawab singkat: CPU terjebak mengeksekusi perulangan instruksi kosong / no-operation tanpa henti, membeku total, dan tidak dapat membaca sensor luar atau merespons penekanan tombol)*
  2. *Pada konfigurasi `INPUT_PULLUP`, nilai logika apa yang dikembalikan oleh `digitalRead(pin)` saat tombol sedang ditekan ke Ground?*  
     *(Jawab singkat: Nilai `LOW` atau integer `0`)*

---

#### B. Jembatan Konsep: Mengapa Sistem Industri Memerlukan Pewaktu Non-Blocking?

Pada Modul 01 dan 02, kita menggunakan fungsi `delay()` untuk mengatur ritme kedipan LED dan siklus lampu lalu lintas. Pendekatan ini disebut **Sistem Pewaktu Blocking (*Blocking Timing*)**. Dalam demonstrasi laboratorium paling sederhana, `delay()` terlihat berfungsi. Namun, dalam rekayasa sistem tertanam (*embedded systems*) dan robotika industri nyata, **penggunaan `delay()` adalah malapetaka fatal**.

##### Bahaya Fenomena Pembekuan CPU (*CPU Starvation / CPU Freeze*)
Ketika CPU mengeksekusi `delay(3000)`, prosesor 8-bit AVR 16 MHz yang mampu menjalankan $16.000.000$ siklus instruksi per detik dipaksa membuang $48.000.000$ siklus waktu berharga hanya untuk menghitung perulangan kosong. Selama 3 detik tersebut:
- Jika sakelar pembatas (*bumper limit switch*) robot menabrak dinding, robot tidak dapat membacanya dan motor terus menabrak hingga terbakar.
- Jika tombol darurat (*Emergency Stop*) ditekan oleh operator pabrik, mesin tidak akan berhenti seketika.
- Jika sensor pejalan kaki ditekan pada lampu lalu lintas, permintaan tersebut diabaikan total karena mikrokontroler sedang "tertidur".

```text
+-----------------------------------------------------------------------------------+
|               PERBANDINGAN MODEL MENTAL: DELAY() VS MILLIS()                      |
+-----------------------------------------------------------------------------------+
|                                                                                   |
|  1. PENDEKATAN BLOCKING: delay(1000) -> "ALARM TIDUR"                             |
|     CPU memejamkan mata dan tertidur pulas selama 1 detik.                        |
|     Dunia luar runtuh atau sensor darurat berteriak, CPU tetap tidak peduli!      |
|                                                                                   |
|  2. PENDEKATAN NON-BLOCKING: millis() -> "MELIRIK STOPWATCH"                      |
|     CPU terus bekerja menyapu sensor, mengontrol motor, dan memproses data.       |
|     Sambil bekerja, CPU sesekali melirik jam tangan (stopwatch):                  |
|     "Apakah sudah lewat 1 detik sejak tugas terakhir? Jika ya, eksekusi aksi,     |
|      jika belum, lanjutkan memeriksa sensor lain tanpa henti!"                   |
|                                                                                   |
+-----------------------------------------------------------------------------------+
```

##### Solusi Rekayasa: Penjadwalan Multitasking Kooperatif Berbasis Waktu
Untuk menjalankan banyak tugas secara bersamaan (*concurrent execution*) pada prosesor bersumber daya tunggal (*single-core*), kita harus menerapkan **arsitektur non-blocking**. Mikrokontroler dibiarkan berputar bebas di dalam blok `loop()` pada kecepatan penuh (ribuan putaran per detik), sambil memeriksa stempel waktu perangkat keras melalui fungsi `millis()`.

---

#### C. Tipe Data `unsigned long` & Fenomena *Rollover* (49,7 Hari)

Fungsi `millis()` mengembalikan jumlah milidetik sejak mikrokontroler pertama kali dinyalakan. Nilai ini disimpan dalam variabel integer tak bertanda 32-bit: **`unsigned long`**.
- Kapasitas maksimal integer tak bertanda 32-bit adalah:
  $$2^{32} - 1 = 4.294.967.295\text{ milidetik}$$
- Jika dikonversi ke satuan hari:
  $$\frac{4.294.967.295\text{ ms}}{1000\text{ ms/s} \times 3600\text{ s/jam} \times 24\text{ jam/hari}} \approx 49.71\text{ hari}$$

Setelah beroperasi selama $49.71$ hari tanpa mati, pencacah waktu perangkat keras akan meluap kembali ke angka $0$. Peristiwa ini disebut **Fenomena Rollover (*Timer Overflow*)**.

> [!IMPORTANT]
> **Kaidah Emas Pencegahan Rollover Bug:**  
> Untuk menjamin sistem tidak pernah macet (*hang*) saat terjadi peristiwa rollover setelah 49 hari, evaluasi waktu **WAJIB** ditulis menggunakan operasi pengurangan aritmatika modular tak bertanda:
> 
> $$\text{millis}() - \text{waktuSebelumnya} \ge \text{interval}$$
> 
> **DILARANG KERAS** menulis perbandingan penjumlahan:  
> `millis() >= waktuSebelumnya + interval` $\implies$ **SALAH BESAR!**  
> *(Penjumlahan dapat meluap melebihi batas $2^{32}-1$ dan bernilai kecil, mengunci sistem selamanya)*.

---

#### D. Tujuan Pembelajaran & Diagram Alur Sinyal Konkuren

Setelah menyelesaikan modul ini, peserta didik mampu:
1. Membedah arsitektur perangkat keras Timer 0 AVR dan cara kerja fungsi `millis()`.
2. Menerapkan pola penjadwalan waktu non-blocking (*Blink Without Delay*) menggunakan tipe data `unsigned long`.
3. Menjalankan dua atau lebih tugas berkala asimetris secara bersamaan (*cooperative multitasking*) tanpa saling memblokir.
4. Membangun sistem interaktif responsif: pembacaan tombol instan yang berjalan bersamaan dengan kedipan lampu peringatan dan mesin status lalu lintas 3 fase (*Finite State Machine*).

**Peta Alur Kerja Konkuren (*Concurrent Execution Flow*):**
```text
                     loop() Berputar Bebas (Ribuan Siklus per Detik)
                                       │
        ┌──────────────────────────────┴──────────────────────────────┐
        ▼                                                             ▼
[ TUGAS A: Pembacaan Tombol ]                               [ TUGAS B: Pewaktu LED ]
Periksa digitalRead(Pin 2)                                  Ambil t = millis()
        │                                                             │
Tombol ditekan?                                             (t - t_sebelumnya) >= interval?
├── YA  ──► Respons LED Darurat Instan                      ├── YA  ──► Balik Status LED & Catat t
└── TIDAK ──► Lewati tanpa jeda                             └── TIDAK ──► Lewati tanpa jeda
        │                                                             │
        └──────────────────────────────┬──────────────────────────────┘
                                       │
                     Kembali ke Baris Pertama loop() Seketika!
```

---

### 2. Kamus Teknis: Fungsi C++, Library, & Argumen `[INTI: SMK & Mahasiswa]`

Sebelum merancang skrip konkuren penuh, pelajari format sintaksis baku, tipe data parameter, dan mekanisme internal dari fungsi pengatur waktu berikut:

#### A. `millis()`

- **Kegunaan Utama:**  
  Mengambil nilai waktu berjalan sistem dalam satuan milidetik yang dihitung sejak papan Arduino Uno menerima daya listrik atau sejak tombol reset ditekan. Fungsi ini membaca variabel pencacah global volatile di dalam memori SRAM yang diperbarui oleh rutinitas layanan interupsi perangkat keras (*Interrupt Service Routine* / ISR) dari **Hardware Timer 0**.

- **Mekanisme Perangkat Keras ATmega328P:**  
  Timer 0 adalah modul pencacah 8-bit yang digerakkan oleh frekuensi osilator kristal $16\text{ MHz}$ melalui pembagi frekuensi (*prescaler*) $64$.
  - Frekuensi detak Timer 0:
    $$f_{\text{timer0}} = \frac{16.000.000\text{ Hz}}{64} = 250.000\text{ Hz} \implies T_{\text{detak}} = 4\,\mu\text{s}$$
  - Pencacah 8-bit meluap (*overflow*) setiap $256$ detak:
    $$256 \times 4\,\mu\text{s} = 1024\,\mu\text{s} = 1.024\text{ milidetik}$$
  - Vektor interupsi `TIMER0_OVF_vect` dieksekusi setiap $1.024\text{ ms}$ untuk menambah variabel internal `timer0_millis`.

- **Format Sintaksis Baku:**
  ```cpp
  unsigned long millis(void);
  ```

- **Rincian Parameter:**  
  Fungsi ini tidak memerlukan parameter masukan apapun (*void*).

- **Nilai Kembalian (*Return Value*):**

  | Tipe Data | Rentang Nilai Numerik | Makna Nilai Kembalian |
  | :--- | :--- | :--- |
  | `unsigned long` | $0$ sampai $4.294.967.295$ | Durasi waktu berjalan sistem dalam satuan milidetik ($1\text{ s} = 1000\text{ ms}$). |

- **Contoh Mikro (1–3 Baris Mandiri):**
  ```cpp
  unsigned long waktuSekarang = millis();
  // waktuSekarang menyimpan stempel waktu sistem saat baris ini dieksekusi
  ```
  - *Penjelasan baris:* Mengambil stempel waktu instan dan menyimpannya ke variabel berkapasitas 4 byte.

---

#### B. Pola Kerangka Waktu Standar (*Non-Blocking Template Pattern*)

Pola ini adalah fondasi universal seluruh sistem operasi waktu nyata (*RTOS*) dan penjadwal tugas (*task scheduler*) mikrokontroler:

```cpp
// Pola Baku Penjadwalan Waktu Non-Blocking
if (millis() - waktuSebelumnya >= INTERVAL_TUGAS_MS) {
  waktuSebelumnya = millis(); // Perbarui stempel waktu
  // Eksekusi aksi tugas di sini
}
```

- **Pembedahan Komponen:**
  1. `millis()`: Waktu saat ini (*current timestamp*).
  2. `waktuSebelumnya`: Variabel bertipe `unsigned long` yang menyimpan momen terakhir kali tugas dijalankan.
  3. `INTERVAL_TUGAS_MS`: Konstanta durasi penundaan yang diinginkan (misal $1000\text{ ms}$).
  4. Blok evaluasi `if` bernilai `false` pada $99.9\%$ putaran CPU, sehingga CPU langsung melompat keluar dalam hitungan nanodetik dan bebas mengerjakan tugas lain.

---

### 3. Prasyarat Wokwi, Skema Pinout, & Checkpoint 3 Tema `[INTI: SMK & Mahasiswa]`

#### A. Kebutuhan Lingkungan Simulator & Komponen
- **Simulator Target:** Simulator peramban web Wokwi (`https://wokwi.com/projects/new/arduino-uno`).
- **Papan Pengembangan:** Arduino Uno R3 (Mikrokontroler ATmega328P 16 MHz).
- **Daftar Komponen Elektronika:**
  1. $1$ unit Tombol Tekan (*Pushbutton*) untuk input interaktif penyeberang jalan.
  2. $1$ unit LED 5mm Merah (Lampu Lalu Lintas / Stop).
  3. $1$ unit LED 5mm Kuning (Lampu Peringatan).
  4. $1$ unit LED 5mm Hijau (Lampu Jalur Jalan).
  5. $3$ unit Resistor Film Karbon $220\,\Omega$ (Pembatas arus masing-masing LED).
  6. Kabel *jumper virtual*.

---

#### B. Tabel Pemetaan Pinout Rangkaian (Tema 1: Otomasi Fasilitas)

| Komponen Elektronika | Terminal Komponen | Sambungan ke Arduino Uno | Fungsi Jalur / Karakteristik Sinyal |
| :--- | :--- | :--- | :--- |
| **Push-Button (PB_PENYEBERANG)** | Terminal Kaki `1.L` | Pin Digital `2` | Input Interaktif Active-LOW (`INPUT_PULLUP`) |
| **Push-Button (PB_PENYEBERANG)** | Terminal Kaki `2.L` | Header Pin `GND` | Bidang Tanah Referensi Penarikan $0\text{V}$ |
| **LED 5mm Merah (LAMPU_MERAH)** | Anoda (via Resistor $220\,\Omega$) | Pin Digital `13` | Output Digital Kendali Lampu Merah Fasilitas |
| **LED 5mm Kuning (LAMPU_KUNING)**| Anoda (via Resistor $220\,\Omega$) | Pin Digital `12` | Output Digital Kendali Lampu Kuning Peringatan |
| **LED 5mm Hijau (LAMPU_HIJAU)** | Anoda (via Resistor $220\,\Omega$) | Pin Digital `11` | Output Digital Kendali Lampu Hijau Bebas |
| **Seluruh Katoda LED (3 Buah)** | Katoda (Kaki Pendek / $-$) | Header Pin `GND` | Rel Pembuangan Arus Bersama (*Common Ground*) |

---

#### C. Diagram Jalur Skematik Rangkaian (ASCII Circuit)

```text
       ARDUINO UNO R3
    +-------------------+
    |                   |
    |            Pin 2  | -------- (Kabel Jumper) --------+
    |                   |                                 |
    |                   |                              +------+
    |                   |                              |  o   | Tombol Penyeberang (Active-LOW)
    |                   |                              | /    | Kontak Normal Terbuka
    |                   |                              |  o   |
    |                   |                              +------+
    |            GND    | -------- (Kabel Jumper) --------+ (Terhubung ke Ground)
    |                   |
    |            Pin 13 | ---- [ Resistor 220 Ω ] ---- (+) [ LED Merah   ] ----+
    |            Pin 12 | ---- [ Resistor 220 Ω ] ---- (+) [ LED Kuning  ] ----+ (Katoda Bersama)
    |            Pin 11 | ---- [ Resistor 220 Ω ] ---- (+) [ LED Hijau   ] ----+
    |                   |                                                      |
    |            GND    | -----------------------------------------------------+ (Ground Bersama)
    +-------------------+
```

---

#### D. Langkah-Langkah Perakitan di Simulator Wokwi (`wokwi.com`)
1. Buka peramban web dan masuk ke proyek: `https://wokwi.com/projects/new/arduino-uno`.
2. Klik tombol **"+"** di panel atas untuk menambahkan komponen:
   - $1$ unit **Pushbutton**.
   - $3$ unit **LED** (atur warna masing-masing menjadi: Merah, Kuning, dan Hijau).
   - $3$ unit **Resistor** (pastikan nilai masing-masing diubah menjadi `220` ohm).
3. **Penyambungan Sirkuit Tombol Tekan**:
   - Sambungkan salah satu terminal pushbutton ke pin **2** Arduino Uno.
   - Sambungkan terminal pasangan pushbutton ke pin **GND**.
4. **Penyambungan Sirkuit 3 Kanal LED**:
   - Sambungkan pin **13** ke kaki Resistor 1 $\to$ Anoda LED Merah.
   - Sambungkan pin **12** ke kaki Resistor 2 $\to$ Anoda LED Kuning.
   - Sambungkan pin **11** ke kaki Resistor 3 $\to$ Anoda LED Hijau.
   - Sambungkan ketiga kaki Katoda LED bersama-sama ke salah satu header pin **GND** Arduino Uno.

---

#### E. Peta Checkpoint 3 Tema Berkelanjutan (*Persistent Lab Tracks*)

| Tema | Domain Sistem | Status dari Modul Sebelumnya | Target Penambahan di Modul Ini |
| :--- | :--- | :--- | :--- |
| **Tema 1 (Inti)** | Otomasi Fasilitas Ruangan | Modul 02: Tombol Input Pull-up Mengendalikan 1 LED Sederhana | Mengembangkan Sistem Lampu Penyeberangan Jalan Multitasking: LED berkedip non-blocking + respons tombol instan (Contoh 1–4, Level 1–2). |
| **Tema 2 (Transfer)** | Robotika Navigasi & Keselamatan | Modul 02: Bumper Limit Switch Tabrakan Sederhana | Mengintegrasikan Lampu Beacon Strobo Navigasi Non-Blocking yang berkedip konstan tanpa mengganggu pembacaan sensor tabrakan (Level 3). |
| **Tema 3 *(Pendalaman)*** | Telemetri IoT ESP32 | Modul 02: Event Trigger Serial Telemetri Sederhana | Mengimplementasikan Penjadwalan Heartbeat Telemetri Periodik setiap 5 detik tanpa jeda blocking pada sistem mikrokontroler (Level 4). |

---

### 4. Implementasi Kode Bersih Bertingkat — Tema 1 *(Minimal 4 Contoh)*

Seluruh contoh kode pada bagian ini berfokus pada **Tema 1 (Otomasi Fasilitas Ruangan)** dengan penambahan satu konsep baru di setiap tingkatannya, mematuhi standar *Embedded Clean Code* (alokasi memori efisien `const uint8_t`, variabel waktu `unsigned long`, evaluasi logika terarah, dan zero *CPU blocking*).

---

#### Contoh 1 — Kasus Paling Dasar: Kedipan LED Tanpa Menunda CPU (*Blink Without Delay*) `[INTI: SMK & Mahasiswa]`
*Mengedipkan LED fasilitas setiap 1000 ms murni menggunakan pembanding waktu `millis()` tanpa menggunakan `delay()` sama sekali.*

- **Format Umum yang Dipakai:**
  ```cpp
  if (millis() - waktuSebelumnya >= INTERVAL_MS) {
    waktuSebelumnya = millis();
    statusLed = !statusLed;
    digitalWrite(PIN_LED, statusLed);
  }
  ```

- **Kode Implementasi Lengkap:**
  ```cpp
  // src/01_blink_tanpa_delay.ino
  // ===================================================================================
  // PROGRAM      : KEDIPAN LAMPU FASILITAS NON-BLOCKING (BLINK WITHOUT DELAY)
  // PLATFORM     : ARDUINO UNO R3 & WOKWI SIMULATOR
  // STANDAR KODE : EMBEDDED C++ CLEAN CODE (ZERO CPU BLOCKING)
  // ===================================================================================

  // Alokasi pin perangkat keras (hemat memori SRAM: 1 byte per konstanta)
  const uint8_t PIN_LED_HIJAU = 11;

  // Interval kedipan waktu (dalam milidetik: 1000 ms = 1 detik)
  const unsigned long INTERVAL_KEDIP_MS = 1000;

  // Variabel penampung status dan waktu sistem
  bool statusLed = false;
  unsigned long waktuSebelumnya = 0;

  void setup() {
    pinMode(PIN_LED_HIJAU, OUTPUT);
    digitalWrite(PIN_LED_HIJAU, LOW);
  }

  void loop() {
    // 1. Ambil stempel waktu saat ini
    const unsigned long waktuSekarang = millis();

    // 2. Evaluasi apakah selisih waktu telah melampaui interval yang ditentukan
    if (waktuSekarang - waktuSebelumnya >= INTERVAL_KEDIP_MS) {
      // Perbarui stempel waktu penanda siklus terakhir
      waktuSebelumnya = waktuSekarang;

      // Balikkan kondisi logika LED (Toggle: Inversi Boolean)
      statusLed = !statusLed;
      digitalWrite(PIN_LED_HIJAU, statusLed ? HIGH : LOW);
    }

    // CPU tidak pernah tidur di sini! Loop berputar bebas mengeksekusi instruksi berikutnya.
  }
  ```

- **Penjelasan Tiap Baris Kunci:**
  - **Baris 9:** `const uint8_t PIN_LED_HIJAU = 11;` mengalokasikan nomor pin pada memori Flash (1 byte), menghemat alokasi RAM ATmega328P.
  - **Baris 12:** `const unsigned long INTERVAL_KEDIP_MS = 1000;` mendefinisikan batas waktu dalam tipe data 32-bit tanpa tanda.
  - **Baris 16:** `unsigned long waktuSebelumnya = 0;` wajib bertipe `unsigned long` agar kompatibel dengan tipe kembalian fungsi `millis()`.
  - **Baris 28:** Evaluasi `if (waktuSekarang - waktuSebelumnya >= INTERVAL_KEDIP_MS)` membandingkan selisih waktu. Selama selisih belum mencapai $1000\text{ ms}$, blok ini dilewati seketika.
  - **Baris 30:** `waktuSebelumnya = waktuSekarang;` menyelaraskan waktu acuan ke momen saat ini untuk siklus berikutnya.

- **Hasil Simulasi Wokwi:**  
  LED Hijau menyala selama $1.0\text{ detik}$, lalu padam selama $1.0\text{ detik}$ secara presisi tanpa mengunci CPU.

---

#### Contoh 2 — Variasi Parameter: Dua Jadwal Independen Asimetris (*Dual Non-Blocking Timers*) `[INTI: SMK & Mahasiswa]`
*Mengembangkan sistem dengan menjalankan dua siklus waktu yang sama sekali berbeda secara independen: LED Hijau berkedip lambat ($1000\text{ ms}$) dan LED Kuning berkedip cepat ($200\text{ ms}$).*

- **Kode Implementasi Lengkap:**
  ```cpp
  // src/02_dua_jadwal_independen_asimetris.ino
  // ===================================================================================
  // PROGRAM      : DUAL INDEPENDENT TIMERS DUA KANAL ASIMETRIS
  // PLATFORM     : ARDUINO UNO R3 & WOKWI SIMULATOR
  // STANDAR KODE : EMBEDDED C++ MULTITASKING KOOPERATIF
  // ===================================================================================

  const uint8_t PIN_LED_HIJAU  = 11;
  const uint8_t PIN_LED_KUNING = 12;

  // Dua interval waktu yang berbeda
  const unsigned long INTERVAL_HIJAU_MS  = 1000; // Siklus lambat 1 detik
  const unsigned long INTERVAL_KUNING_MS = 200;  // Siklus cepat 0.2 detik (strobo peringatan)

  // Variabel status mandiri untuk masing-masing kanal
  bool statusHijau  = false;
  bool statusKuning = false;

  // Dua pencatat stempel waktu terpisah
  unsigned long waktuLaluHijau  = 0;
  unsigned long waktuLaluKuning = 0;

  void setup() {
    pinMode(PIN_LED_HIJAU, OUTPUT);
    pinMode(PIN_LED_KUNING, OUTPUT);

    digitalWrite(PIN_LED_HIJAU, LOW);
    digitalWrite(PIN_LED_KUNING, LOW);
  }

  void loop() {
    const unsigned long sekarang = millis();

    // TUGAS 1: Kelola ritme LED Hijau (Interval 1000 ms)
    if (sekarang - waktuLaluHijau >= INTERVAL_HIJAU_MS) {
      waktuLaluHijau = sekarang;
      statusHijau = !statusHijau;
      digitalWrite(PIN_LED_HIJAU, statusHijau ? HIGH : LOW);
    }

    // TUGAS 2: Kelola ritme LED Kuning (Interval 200 ms)
    if (sekarang - waktuLaluKuning >= INTERVAL_KUNING_MS) {
      waktuLaluKuning = sekarang;
      statusKuning = !statusKuning;
      digitalWrite(PIN_LED_KUNING, statusKuning ? HIGH : LOW);
    }
  }
  ```

- **Perbedaan dengan Contoh 1:**  
  Memiliki dua variabel stempel waktu mandiri (`waktuLaluHijau` dan `waktuLaluKuning`) serta dua variabel penampung status boolean. Kedua tugas dievaluasi bergantian secara cepat di dalam perulangan `loop()`.

- **Pelacakan Kondisi Antara (*State Tracing*):**

  | Waktu Sistem (`sekarang`) | Selisih Waktu Hijau | Aksi Kanal Hijau (Pin 11) | Selisih Waktu Kuning | Aksi Kanal Kuning (Pin 12) |
  | :---: | :---: | :--- | :---: | :--- |
  | **$t = 0\text{ ms}$** | $0\text{ ms}$ | Tetap Padam | $0\text{ ms}$ | Tetap Padam |
  | **$t = 200\text{ ms}$** | $200\text{ ms} < 1000$ | Lewati (Tetap Padam) | $200\text{ ms} \ge 200$ | **TOGGLE $\implies$ MENYALA** |
  | **$t = 400\text{ ms}$** | $400\text{ ms} < 1000$ | Lewati (Tetap Padam) | $200\text{ ms} \ge 200$ | **TOGGLE $\implies$ PADAM** |
  | **$t = 600\text{ ms}$** | $600\text{ ms} < 1000$ | Lewati (Tetap Padam) | $200\text{ ms} \ge 200$ | **TOGGLE $\implies$ MENYALA** |
  | **$t = 800\text{ ms}$** | $800\text{ ms} < 1000$ | Lewati (Tetap Padam) | $200\text{ ms} \ge 200$ | **TOGGLE $\implies$ PADAM** |
  | **$t = 1000\text{ ms}$** | $1000\text{ ms} \ge 1000$ | **TOGGLE $\implies$ MENYALA** | $200\text{ ms} \ge 200$ | **TOGGLE $\implies$ MENYALA** |

- **Hasil Simulasi Wokwi:**  
  Kedua LED berkedip berdampingan dengan irama masing-masing secara harmonis. LED Kuning berkedip cepat 5 kali lipat lebih sering dibandingkan LED Hijau tanpa terjadi tabrakan eksekusi!

---

#### Contoh 3 — Kombinasi Menengah: Multitasking I/O (Kedipan Terjadwal + Respons Tombol Instan) `[INTI: SMK & Mahasiswa]`
*Mengombinasikan penjadwalan waktu non-blocking dengan pembacaan sensor tombol seketika. LED Hijau berkedip berkala di latar belakang, sementara tombol di Pin 2 dipindai setiap mikrodetik untuk menyalakan LED Merah Darurat secara instan.*

- **Kode Implementasi Lengkap:**
  ```cpp
  // src/03_multitasking_io_tombol_dan_kedip.ino
  // ===================================================================================
  // PROGRAM      : MULTITASKING I/O NON-BLOCKING (SISTEM LAMPU FASILITAS RESPONSIF)
  // PLATFORM     : ARDUINO UNO R3 & WOKWI SIMULATOR
  // STANDAR KODE : EMBEDDED C++ CONCURRENT EXECUTION
  // ===================================================================================

  const uint8_t PIN_TOMBOL_INPUT  = 2;  // Tombol interaktif (Active-LOW)
  const uint8_t PIN_LED_MERAH     = 13; // Indikator interaksi seketika
  const uint8_t PIN_LED_HIJAU     = 11; // Indikator latar belakang berkala

  const unsigned long INTERVAL_LATAR_BELAKANG_MS = 500;

  bool statusHijau = false;
  unsigned long waktuLaluLatar = 0;

  void setup() {
    pinMode(PIN_TOMBOL_INPUT, INPUT_PULLUP);
    pinMode(PIN_LED_MERAH, OUTPUT);
    pinMode(PIN_LED_HIJAU, OUTPUT);

    digitalWrite(PIN_LED_MERAH, LOW);
    digitalWrite(PIN_LED_HIJAU, LOW);
  }

  void loop() {
    const unsigned long sekarang = millis();

    // ---------------------------------------------------------------------------------
    // TUGAS 1: TUGAS PERIODIK LATAR BELAKANG (NON-BLOCKING)
    // ---------------------------------------------------------------------------------
    if (sekarang - waktuLaluLatar >= INTERVAL_LATAR_BELAKANG_MS) {
      waktuLaluLatar = sekarang;
      statusHijau = !statusHijau;
      digitalWrite(PIN_LED_HIJAU, statusHijau ? HIGH : LOW);
    }

    // ---------------------------------------------------------------------------------
    // TUGAS 2: TUGAS PEMINDAIAN INPUT SENSOR RESPONSIF (REAL-TIME POLLING)
    // ---------------------------------------------------------------------------------
    const uint8_t statusTombol = digitalRead(PIN_TOMBOL_INPUT);

    // Jika tombol ditekan (Active-LOW: LOW), nyalakan LED Merah seketika
    if (statusTombol == LOW) {
      digitalWrite(PIN_LED_MERAH, HIGH);
    } else {
      digitalWrite(PIN_LED_MERAH, LOW);
    }
  }
  ```

- **Pelacakan Alur Sinyal (*Step-by-Step Trace*):**

  ```text
  Alur Putaran Siklus loop() (~50.000 putaran per detik):
  Siklus 1 (t = 100 ms):
  ├── Tugas 1: 100 - 0 = 100 ms (< 500) -> Lewati
  └── Tugas 2: digitalRead(2) -> HIGH -> LED Merah = LOW

  Siklus N (t = 245 ms) -> USER MENEKAN TOMBOL PIN 2 SECARA TIBA-TIBA:
  ├── Tugas 1: 245 - 0 = 245 ms (< 500) -> Lewati
  └── Tugas 2: digitalRead(2) -> LOW! -> SEKETIKA NYALAKAN LED MERAH = HIGH!
      (Latensi reaksi < 20 mikrodetik, tanpa tertunda oleh jeda waktu apapun!)

  Siklus M (t = 500 ms) -> WAKTU TUGAS 1 TERPENUHI:
  ├── Tugas 1: 500 - 0 = 500 ms (>= 500) -> Toggle LED Hijau = HIGH, waktuLalu = 500 ms
  └── Tugas 2: digitalRead(2) -> Tetap melayani tombol secara simultan!
  ```

- **Hasil Simulasi Wokwi:**  
  LED Hijau berkedip teratur dengan ritme $500\text{ ms}$. Kapanpun Anda menekan tombol di kanvas Wokwi, LED Merah langsung merespons menyala seketika tanpa ada gejala lag atau penundaan sedikitpun.

---

#### Contoh 4 — Penerapan Terapan: Mesin Status Lampu Lalu Lintas Adaptif Non-Blocking `[PENDALAMAN: Mahasiswa / SMK Lanjutan]`
> **Catatan Pendalaman:** Contoh ini membangun arsitektur kendali industri berbasis **Mesin Status Hingga (*Finite State Machine* / FSM)**. Sistem mengelola siklus lampu lalu lintas 3 fase sekaligus memantau tombol permintaan penyeberang jalan. Jika tombol ditekan, fase hijau segera ditransisikan ke fase penyeberangan secara aman tanpa mengacaukan waktu prosesor.

- **Diagram Transisi Mesin Status (*FSM State Diagram*):**
  ```text
  [ FASE_HIJAU ] ──(Waktu 5000ms Habis ATAU Tombol Penyeberang Ditekan)──► [ FASE_KUNING ]
        ▲                                                                        │
        │                                                               (Waktu 2000ms Habis)
        │                                                                        ▼
  [ FASE_KUNING_KEDIP ] ◄──────────(Waktu 6000ms Habis)───────────── [ FASE_MERAH ]
  (Berkedip 2x @400ms)
  ```

- **Kode Implementasi Lengkap:**
  ```cpp
  // src/04_mesin_status_lampu_lalu_lintas_adaptif.ino
  // ===================================================================================
  // PROGRAM      : SISTEM LAMPU LALU LINTAS ADAPTIF NON-BLOCKING (FINITE STATE MACHINE)
  // PLATFORM     : ARDUINO UNO R3 & WOKWI SIMULATOR
  // STANDAR KODE : EMBEDDED C++ PRODUCTION REAL-TIME FSM
  // ===================================================================================

  const uint8_t PIN_TOMBOL_PENYEBERANG = 2;  // Tombol santri penyeberang (Active-LOW)
  const uint8_t PIN_LAMPU_MERAH        = 13;
  const uint8_t PIN_LAMPU_KUNING       = 12;
  const uint8_t PIN_LAMPU_HIJAU        = 11;

  // Definisi status sistem lalu lintas menggunakan enumerasi hemat memori (1 byte)
  enum StatusLaluLintas : uint8_t {
    FASE_HIJAU,
    FASE_KUNING,
    FASE_MERAH,
    FASE_KUNING_KEDIP
  };

  StatusLaluLintas statusSekarang = FASE_HIJAU;

  // Durasi konfigurasi waktu setiap fase (dalam milidetik)
  const unsigned long DURASI_HIJAU_NORMAL_MS = 5000;
  const unsigned long DURASI_KUNING_MS        = 2000;
  const unsigned long DURASI_MERAH_AMAN_MS    = 6000;
  const unsigned long INTERVAL_KEDIP_KUNING   = 300;

  // Variabel penampung waktu dan kontrol transisi
  unsigned long waktuMulaiFase = 0;
  unsigned long waktuLaluKedip = 0;
  bool statusKuningBlink       = false;
  uint8_t hitunganKedip        = 0;

  // Fungsi pembantu modular penulisan output
  void aturLampu(bool merah, bool kuning, bool hijau) {
    digitalWrite(PIN_LAMPU_MERAH,  merah  ? HIGH : LOW);
    digitalWrite(PIN_LAMPU_KUNING, kuning ? HIGH : LOW);
    digitalWrite(PIN_LAMPU_HIJAU,  hijau  ? HIGH : LOW);
  }

  void setup() {
    pinMode(PIN_TOMBOL_PENYEBERANG, INPUT_PULLUP);
    pinMode(PIN_LAMPU_MERAH,  OUTPUT);
    pinMode(PIN_LAMPU_KUNING, OUTPUT);
    pinMode(PIN_LAMPU_HIJAU,  OUTPUT);

    // Kondisi awal: Jalur kendaraan terbuka aman
    aturLampu(false, false, true);
    waktuMulaiFase = millis();
  }

  void loop() {
    const unsigned long sekarang = millis();

    switch (statusSekarang) {
      // -------------------------------------------------------------------------------
      // FASE 1: HIJAU AKTIF (Jalur Kendaraan Melintas Bebas)
      // -------------------------------------------------------------------------------
      case FASE_HIJAU:
        aturLampu(false, false, true);

        // Transisi ke Kuning terjadi jika waktu habis ATAU tombol penyeberang ditekan
        // (dengan syarat hijau telah menyala minimal 2 detik demi keselamatan laju mobil)
        if ((sekarang - waktuMulaiFase >= DURASI_HIJAU_NORMAL_MS) ||
            (digitalRead(PIN_TOMBOL_PENYEBERANG) == LOW && (sekarang - waktuMulaiFase >= 2000))) {
          statusSekarang = FASE_KUNING;
          waktuMulaiFase = sekarang;
        }
        break;

      // -------------------------------------------------------------------------------
      // FASE 2: KUNING AKTIF (Peringatan Perlambatan Kendaraan)
      // -------------------------------------------------------------------------------
      case FASE_KUNING:
        aturLampu(false, true, false);

        if (sekarang - waktuMulaiFase >= DURASI_KUNING_MS) {
          statusSekarang = FASE_MERAH;
          waktuMulaiFase = sekarang;
        }
        break;

      // -------------------------------------------------------------------------------
      // FASE 3: MERAH AKTIF (Kendaraan Berhenti Penuh, Pejalan Kaki Menyeberang Aman)
      // -------------------------------------------------------------------------------
      case FASE_MERAH:
        aturLampu(true, false, false);

        if (sekarang - waktuMulaiFase >= DURASI_MERAH_AMAN_MS) {
          statusSekarang = FASE_KUNING_KEDIP;
          waktuMulaiFase = sekarang;
          waktuLaluKedip = sekarang;
          hitunganKedip  = 0;
          statusKuningBlink = false;
        }
        break;

      // -------------------------------------------------------------------------------
      // FASE 4: KUNING BERKEDIP NON-BLOCKING (Persiapan Berjalan Kembali)
      // -------------------------------------------------------------------------------
      case FASE_KUNING_KEDIP:
        aturLampu(false, statusKuningBlink, false);

        if (sekarang - waktuLaluKedip >= INTERVAL_KEDIP_KUNING) {
          waktuLaluKedip = sekarang;
          statusKuningBlink = !statusKuningBlink;
          if (statusKuningBlink) {
            hitunganKedip++;
          }
        }

        // Setelah berkedip 2 kali penuh, kembali ke fase Hijau
        if (hitunganKedip >= 2) {
          statusSekarang = FASE_HIJAU;
          waktuMulaiFase = sekarang;
        }
        break;
    }
  }
  ```

- **Analisis Keandalan Rekayasa:**  
  Seluruh transisi fase dijalankan secara presisi melalui mesin status FSM. Tidak ada satupun pemanggilan fungsi `delay()`. Tombol penyeberang dipantau secara adaptif: jika lampu hijau baru saja menyala, sistem memberi toleransi minimal 2 detik sebelum memotong ke fase kuning agar kendaraan tidak berhenti mendadak.

---

### 5. Verifikasi Rangkaian, Rumus Elektronika, & Debugging `[INTI: SMK & Mahasiswa]`

#### A. Analisis Elektronika & Pembuktian Sirkuit

##### 1. Pembuktian Matematis Kekebalan Rollover Aritmatika Tak Bertanda
Mengapa formula `millis() - waktuSebelumnya >= interval` kebal terhadap fenomena rollover pada hari ke-49?

Misalkan stempel waktu sebelum rollover berada di angka puncak:
$$t_{\text{sebelumnya}} = 4.294.967.200\text{ ms}$$
Beberapa saat kemudian, pencacah 32-bit meluap melewati $4.294.967.295$ dan kembali ke:
$$t_{\text{sekarang}} = 50\text{ ms}$$

Dalam aturan matematika komputer berbasis biner tak bertanda (*unsigned 32-bit modular arithmetic*), hasil pengurangan dihitung secara modulo $2^{32}$:

$$t_{\text{selisih}} = (50 - 4.294.967.200) \pmod{2^{32}}$$
$$t_{\text{selisih}} = 50 + (2^{32} - 4.294.967.200) = 50 + 96 = 146\text{ milidetik}$$

Perhitungannya menghasilkan angka positif $146\text{ ms}$ secara eksak dan sempurna tanpa menghasilkan angka negatif ataupun kesalahan logika!

##### 2. Analisis Arus Total Sirkuit Multi-LED (Hukum Arus Kirchhoff / KCL)
Pada rangkaian Contoh 4, terdapat momen transisi di mana lebih dari satu LED dapat aktif secara simultan. Kita wajib memastikan arus total tidak melampaui batas absolut rel daya ATmega328P ($I_{\text{maks\_VCC}} = 200\text{ mA}$).

Berdasarkan perhitungan Hukum Ohm pada resistor $220\,\Omega$:
- Arus LED Merah ($V_f \approx 2.0\text{V}$):
  $$I_{\text{merah}} = \frac{5.0\text{V} - 2.0\text{V}}{220\,\Omega} = \frac{3.0\text{V}}{220\,\Omega} \approx 13.6\text{ mA}$$
- Arus LED Kuning ($V_f \approx 2.1\text{V}$):
  $$I_{\text{kuning}} = \frac{5.0\text{V} - 2.1\text{V}}{220\,\Omega} = \frac{2.9\text{V}}{220\,\Omega} \approx 13.2\text{ mA}$$
- Arus LED Hijau ($V_f \approx 2.2\text{V}$):
  $$I_{\text{hijau}} = \frac{5.0\text{V} - 2.2\text{V}}{220\,\Omega} = \frac{2.8\text{V}}{220\,\Omega} \approx 12.7\text{ mA}$$

Beban arus total terburuk (*worst-case scenario* jika ketiga LED menyala bersamaan):
$$I_{\text{total}} = 13.6\text{ mA} + 13.2\text{ mA} + 12.7\text{ mA} = 39.5\text{ mA}$$

Besaran arus $39.5\text{ mA}$ berada sangat aman di bawah kapasitas batas absolut $200\text{ mA}$, menjamin mikrokontroler beroperasi stabil tanpa drop tegangan.

---

#### B. Ringkasan Ekspektasi Output Contoh 1–4

| Contoh | Jalur | Fokus Fitur yang Diuji | Indikator Berhasil / Perilaku di Wokwi |
| :--- | :---: | :--- | :--- |
| **Contoh 1** | Inti | Pola Baku `millis() - waktuSebelumnya` | LED Hijau berkedip ritmis $1000\text{ ms}$ menyala / $1000\text{ ms}$ padam. |
| **Contoh 2** | Inti | Dua Interval Waktu Asimetris | Hijau berkedip lambat ($1000\text{ ms}$), Kuning berkedip cepat ($200\text{ ms}$) simultan. |
| **Contoh 3** | Inti | Multitasking I/O Konkuren | Hijau berkedip konstan; tombol Pin 2 ditekan seketika menyalakan LED Merah tanpa lag. |
| **Contoh 4** | Pendalaman | Mesin Status FSM Adaptif | Siklus lampu berjalan otomatis; tombol penyeberang memotong fase hijau secara aman. |

---

#### C. Kesalahan Umum & Panduan Troubleshooting di Wokwi

| Gejala Masalah di Wokwi | Penyebab Utama pada Rangkaian/Kode | Solusi Tepat |
| :--- | :--- | :--- |
| **LED menyala terus tanpa berkedip atau berkedip terlalu cepat tak kasat mata.** | Variabel `waktuSebelumnya` dideklarasikan sebagai variabel lokal biasa di dalam `loop()`, sehingga nilainya selalu ter-reset ke nol pada setiap putaran. | Deklarasikan `waktuSebelumnya` sebagai variabel **global** di luar fungsi `setup()` dan `loop()`, atau gunakan penanda `static unsigned long`. |
| **Sistem terkunci/macet setelah berjalan beberapa saat atau tidak akurat.** | Variabel penampung stempel waktu menggunakan tipe data `int` (kapasitas hanya sampai $32.767\text{ ms} \approx 32\text{ detik}$) atau `long` bertanda. | Wajib gunakan tipe data **`unsigned long`** untuk seluruh variabel penampung hasil `millis()`. |
| **Respons tombol tetap terasa lambat (lag) meskipun sudah memakai `millis()`.** | Masih ada fungsi pembeku `delay()` yang tersisa di bagian lain dalam blok `loop()`. | Eliminasi seluruh instruksi `delay()` dari seluruh fungsi program tanpa terkecuali. |
| **Interval waktu bergeser semakin lama (*drift timing*).** | Memperbarui waktu acuan menggunakan `waktuSebelumnya = millis();` pada sistem bertugas berat. | Gunakan akumulator presisi: `waktuSebelumnya += INTERVAL_MS;` untuk menjaga ketepatan fase waktu absolut. |

---

### 6. Latihan Praktikum Berkelanjutan Multi-Tema *(4 Level)*

Perhatikan spesifikasi pemetaan pinout perangkat keras acuan praktikum berikut sebelum mengerjakan tugas:

#### Tabel Acuan Perangkat Keras Praktikum Multi-Tema

| Identitas Komponen | Tipe Antarmuka | Sambungan Pin Arduino | Mode Operasional / Catatan |
| :--- | :--- | :--- | :--- |
| **Tombol 1 (PB_UTAMA)** | Digital Input | Pin Digital `2` | `INPUT_PULLUP` (Active-LOW, terhubung ke GND) |
| **LED Merah (LED_MERAH)** | Digital Output | Pin Digital `13` | Melalui resistor pembatas $220\,\Omega$ ke GND |
| **LED Kuning (LED_KUNING)** | Digital Output | Pin Digital `12` | Melalui resistor pembatas $220\,\Omega$ ke GND |
| **LED Hijau (LED_HIJAU)** | Digital Output | Pin Digital `11` | Melalui resistor pembatas $220\,\Omega$ ke GND |

---

#### Level 1 — Pemahaman Fungsi & Tracing Sinyal *(Tema 1)* `[INTI: SMK & Mahasiswa]`
1. Perhatikan potongan kode berikut:
   ```cpp
   unsigned long t_lalu = 0;
   void loop() {
     if (millis() - t_lalu >= 500) {
       t_lalu = millis();
       digitalWrite(13, !digitalRead(13));
     }
   }
   ```
   Hitung berapa kali LED pada Pin 13 mengalami pembalikan status (*toggle*) dalam rentang waktu $5\text{ detik}$ ($5000\text{ ms}$)!
2. Tunjukkan secara matematis mengapa deklarasi `int t_lalu = 0;` akan menyebabkan kegagalan sistem fatal (*overflow*) ketika Arduino Uno telah menyala selama lebih dari $32.768\text{ milidetik}$ ($32.7\text{ detik}$)!
3. Tuliskan perbaikan satu baris kode jika seorang teknisi keliru menuliskan pola waktu non-blocking sebagai berikut:
   ```cpp
   // KODE SALAH:
   if (millis() >= t_lalu + 1000)
   ```

---

#### Level 2 — Modifikasi Terarah di Wokwi *(Tema 1: Strobo Peringatan Gerbang Otomasi 3 Interval)* `[INTI: SMK & Mahasiswa]`
4. Buatlah program simulasi di Wokwi untuk mengendalikan tiga lampu indikator gerbang fasilitas menggunakan konsep non-blocking:
   - LED Hijau (Pin 11) berkedip dengan periode: $1000\text{ ms}$ Nyala, $1000\text{ ms}$ Padam (Indikator Sistem Normal).
   - LED Kuning (Pin 12) berkedip dengan periode cepat: $250\text{ ms}$ Nyala, $250\text{ ms}$ Padam (Indikator Peringatan Gerak Gerbang).
   - LED Merah (Pin 13) menyala hanya saat tombol darurat di Pin 2 ditekan dan ditahan oleh operator, tanpa menghentikan atau mengacaukan ritme kedipan LED Hijau dan Kuning!

---

#### Level 3 — Praktikum Berkelanjutan: Transfer ke Subsistem Robotika *(Tema 2: Beacon Strobo Sasis + Deteksi Bumper Non-Blocking)* `[INTI LANJUT: SMK Lanjutan & Mahasiswa]`
5. Pada sasis robot pemadam api dan penjelajah (*Autonomous Mobile Robot*), dipasang lampu suar (*safety beacon strobo*) pada Pin 12 dan sensor benturan fisik (*bumper switch*) pada Pin 2.
   - Tuliskan kode program agar lampu beacon (LED Kuning Pin 12) berkedip asimetris khas lampu strobo darurat: $100\text{ ms}$ Menyala sangat cepat, lalu $900\text{ ms}$ Padam, secara berulang menggunakan arsitektur `millis()`.
   - Secara simultan pada setiap siklus `loop()`, mikrokontroler memindai status Bumper di Pin 2. Jika bumper membentur dinding (terbaca `LOW`), LED Merah di Pin 13 **wajib menyala seketika** sebagai penanda rem darurat, dan padam kembali seketika saat sasis ditarik menjauh dari dinding.
   - Pastikan kedipan lampu strobo tidak pernah terhenti atau melambat saat bumper disentuh!

---

#### Level 4 — Praktikum Berkelanjutan: Kasus Batas, Optimasi, & Telemetri *(Tema 3: Heartbeat Telemetri Periodik ESP32 + Rate Limiter)* `[PENDALAMAN: Mahasiswa / Pengayaan SMK]`
6. Rancang sistem pengirim telemetri berkala non-blocking (*Periodic Telemetry Heartbeat*) yang dipadukan dengan pembatas laju penekanan tombol (*Rate Limiter*):
   - **Tugas A (Heartbeat Terjadwal):** Setiap tepat $5000\text{ ms}$ ($5\text{ detik}$), mikrokontroler memancarkan stempel denyut detak ke Serial Monitor (9600 baud) dengan format:
     ```text
     [TELEMETRI] Status: OK | Uptime: <waktu_detik> s
     ```
   - **Tugas B (Interaksi Tombol Rate-Limited):** Tombol di Pin 2 berfungsi sebagai tombol pemicu event manual (*Push Event*). Namun, sistem membatasi pengguna agar hanya dapat mengirim event maksimal satu kali setiap $2000\text{ ms}$ (mencegah spam event ke server broker IoT). Jika tombol ditekan berulang-ulang sebelum jendela 2 detik terlampaui, event diabaikan.
   - Tuliskan kode lengkap siap uji coba di Wokwi dengan memanfaatkan dua variabel pelacak `unsigned long` terpisah!

---

### 7. Panduan Evaluasi & Checklist Mandiri `[INTI: SMK & Mahasiswa]`

Gunakan petunjuk ringkas berikut untuk memverifikasi kebenaran implementasi sistem non-blocking Anda secara mandiri di simulator Wokwi:

#### A. Indikator Keberhasilan Tugas & Target Tampilan Output

- **Target Logika Soal 1 (Level 1):**
  - Dalam durasi $5000\text{ ms}$ dengan interval $500\text{ ms}$, terjadi tepat **$10$ kali pembalikan status (*toggle*)**, menghasilkan $5$ siklus kedipan gelombang penuh.

- **Target Respon Visual Strobo Gerbang Fasilitas (Soal 4 - Level 2):**
  - LED Hijau berkedip santai dengan ritme $1\text{ detik}$.
  - LED Kuning berkedip cepat sebanyak $2$ kedipan penuh setiap detik.
  - Saat tombol Pin 2 ditekan, LED Merah menyala terang secara mulus tanpa membuat LED Hijau atau Kuning tersentak/berhenti sesaat.

- **Target Respon Sasis Robotika (Soal 5 - Level 3):**
  - Lampu strobo Kuning berkedip kilat ($100\text{ ms}$ nyala pendek, $900\text{ ms}$ mati panjang).
  - Sentuhan bumper direspons dengan latensi seketika ($< 1\text{ ms}$).

- **Target Output Serial Monitor Telemetri (Soal 6 - Level 4):**
  Buka tab *Serial Monitor* di Wokwi (atur baud rate `9600`). Baris telemetri otomatis muncul stabil setiap 5 detik tanpa terganggu oleh aksi tombol:
  ```text
  [TELEMETRI] Status: OK | Uptime: 5 s
  [TELEMETRI] Status: OK | Uptime: 10 s
  [EVENT] Tombol Terverifikasi | Waktu: 11240 ms
  [TELEMETRI] Status: OK | Uptime: 15 s
  ```

---

#### B. Checklist Kesiapan Teknis & Verifikasi Wokwi
- [ ] Tidak ada satupun instruksi `delay()` yang tersisa di dalam fungsi `loop()`.
- [ ] Seluruh variabel penampung stempel waktu dideklarasikan menggunakan tipe data `unsigned long`.
- [ ] Evaluasi jeda waktu menerapkan pola pengurangan aman: `millis() - waktuSebelumnya >= interval`.
- [ ] Variabel stempel waktu diperbarui secara tepat di dalam blok evaluasi `if`.
- [ ] Pembacaan tombol interaktif dapat merespons penekanan instan tanpa ada penundaan visual pada kedipan LED di sekitarnya.
- [ ] Resistor pembatas arus $220\,\Omega$ terpasang benar pada ketiga jalur LED.

> **Catatan Buku Pegangan Pengajar:**  
> Kunci jawaban kode program lengkap dan pembahasan analitis untuk seluruh butir latihan soal dihimpun secara terpisah pada dokumen panduan instruktur laboratorium (`KUNCI_JAWABAN_PRAKTIKUM_ROBOTICS.md`).

---

### 8. Ringkasan Fungsi & Refleksi Pemahaman `[INTI: SMK & Mahasiswa]`

#### A. Tabel Ringkasan Fungsi & Pola Rekayasa Waktu

| Elemen Sintaksis C++ | Kegunaan Utama | Tipe Data & Format | Karakteristik Kritis / Efek Eksekusi |
| :--- | :--- | :--- | :--- |
| `millis()` | Membaca waktu berjalan sistem sejak awal penyalaan. | Parameter: `void`<br>Kembalian: `unsigned long` | Dihitung dari interupsi Timer 0 AVR ($1.024\text{ ms}$). Nilai meluap kembali ke 0 setiap $49.71$ hari (*rollover*). |
| `unsigned long` | Tipe data bilangan bulat tak bertanda 32-bit ($4$ byte). | Rentang: $0$ hingga $4.294.967.295$ | **Wajib** digunakan untuk variabel waktu agar kebal terhadap kesalahan tanda (*signed overflow*). |
| `millis() - t_lalu >= interval` | Formula evaluasi waktu non-blocking standar industri. | Ekspresi kondisional: `bool` (`true`/`false`) | Kebal terhadap fenomena rollover karena operasi pengurangan aritmatika modular tak bertanda. |
| `t_lalu = millis();` | Memperbarui waktu patokan siklus tugas. | Penugasan variabel (*assignment*) | Menandai titik awal baru untuk perhitungan jeda interval berikutnya. |

---

#### B. Pertanyaan Refleksi Teknis

1. *Jelaskan perbedaan mendasar antara penjadwalan multitasking preemptive pada sistem operasi modern (seperti Linux/FreeRTOS) dengan penjadwalan multitasking kooperatif berbasis perulangan `millis()` pada mikrokontroler bare-metal ATmega328P! Apa syarat mutlak yang harus dipenuhi oleh setiap fungsi agar multitasking kooperatif tidak macet?*
2. *Dari penerapannya pada Tema 1 (Lampu Lalu Lintas Adaptif) ke Tema 2 (Robotika Beacon & Bumper) dan Tema 3 (IoT Heartbeat), apa pola dasar arsitektur perangkat lunak yang selalu berulang meskipun aktuator dan sensor fisiknya berbeda? Mengapa pemisahan stempel waktu mandiri untuk setiap tugas menjadi kunci keberhasilan sistem terpadu?*
