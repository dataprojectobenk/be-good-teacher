# MODUL REKAYASA SISTEM TERTANAM & ROBOTIKA
## Tahap 1: Fondasi Digital I/O & Rekayasa Waktu (Arduino Uno)
### Modul 02: Deteksi Input Digital, Mekanisme Resistor Pull-Up, & Interaktivitas Tombol Tekan
- **Subjek / Jalur Keahlian:** Rekayasa Sistem Tertanam & Robotika (Arduino Uno & Wokwi)
- **Target Level:** Terpadu Bertingkat (SMK & Mahasiswa)
- **Fokus Materi:** Deteksi Logika Biner Digital Input, Resistor Pull-Up Internal AVR, Penghilangan Pin Mengambang (*Floating State*), dan Deteksi Tepi (*Edge Detection / State Toggle*)
- **Tema Berkelanjutan Aktif:** Tema 1 (`Otomasi Fasilitas Ruangan`), Tema 2 (`Robotika Navigasi & Keselamatan`), Tema 3 (`Telemetri IoT ESP32`)

---

### 1. Prasyarat, Jembatan Konsep Rekayasa, & Peta Alur `[INTI: SMK & Mahasiswa]`

#### A. Pengingat Prasyarat Cepat (2 Menit)
Sebelum mempelajari mekanisme penerimaan sinyal masukan (*input*), segarkan kembali fondasi sinyal keluaran (*output*) dari Modul 01:
- **Konsep Kunci yang Harus Sudah Dipahami:**
  1. Konfigurasi register arah data melalui `pinMode(pin, OUTPUT)`.
  2. Penulisan level tegangan biner melalui `digitalWrite(pin, HIGH/LOW)` dengan standar Transistor-Transistor Logic (TTL: $\text{HIGH} = 5.0\text{V}$, $\text{LOW} = 0.0\text{V}$).
  3. Pembatasan arus beban semikonduktor LED menggunakan resistor $220\,\Omega$ berbasis Hukum Ohm ($V = I \times R$).
- **Cek Kilat Prasyarat (Jawab Singkat Sebelum Melangkah):**
  1. *Berapakah nilai tegangan yang dialirkan pin GPIO saat diinstruksikan `digitalWrite(pin, HIGH)`?*  
     *(Jawab singkat: $+5.0\text{V}$ DC)*
  2. *Mengapa kaki anoda LED dilarang dihubungkan langsung ke pin GPIO $5\text{V}$ tanpa melalui resistor pembatas arus?*  
     *(Jawab singkat: Resistansi dinamis LED sangat rendah sehingga memicu lonjakan arus berlebih melampaui batas aman $20\text{ mA}$, yang dapat membakar sambungan PN LED dan merusak transistor gerbang mikrokontroler)*

---

#### B. Jembatan Konsep: Mengapa Sistem Memerlukan Input & Pengikat Referensi?

Pada Modul 01, mikrokontroler difungsikan sebagai sistem kontrol satu arah (*open-loop actuator*). Mikrokontroler hanya memompa tegangan keluar tanpa memedulikan kondisi lingkungan eksternal. Namun, hakikat sebuah robot otonom atau sistem otomasi fasilitas bertumpu pada kemampuannya **membaca besaran fisik di sekitarnya**, mengevaluasi keputusan berbasis logika, lalu memerintahkan aktuator untuk merespons secara adaptif (*closed-loop system*).

Elemen masukan digital paling mendasar dalam rekayasa elektronika adalah **sakelar mekanik** atau **tombol tekan (*push-button / tactile switch*)**. Pada aplikasi industri dan robotika, tombol tekan menjadi fondasi bagi:
1. **Sakelar Pembatas (*Limit Switch*)**: Mendeteksi batas akhir pergerakan lengan robot atau mekanisme slider agar motor berhenti sebelum terjadi tabrakan mekanis.
2. **Sensor Sentuh (*Bumper Collision Sensor*)**: Mendeteksi benturan fisik robot penjelajah dengan dinding atau rintangan.
3. **Antarmuka Manusia-Mesin (HMI)**: Tombol darurat (*Emergency Stop*), tombol aktivasi fasilitas, atau tombol pilih mode operasional.

##### Bahaya Fenomena Pin Mengambang (*Floating Pin*)
Sebuah pin GPIO yang dikonfigurasi sebagai gerbang masukan (`INPUT`) memiliki impedansi gerbang silikon CMOS internal yang luar biasa tinggi (resistansi internal $>100\text{ M}\Omega$). Karakteristik ini dirancang agar pin tidak membebani sirkuit yang sedang diukur.

Namun, impedansi tinggi ini menimbulkan konsekuensi fisika yang sangat berbahaya jika terminal pin dibiarkan tidak terhubung ke jalur tegangan yang pasti (*dibiarkan menggantung di udara*):
- Terminal pin akan bertindak persis seperti **antena penerima gelombang elektromagnetik**.
- Muatan listrik statis di udara, derau radio frekuensi (*RF noise*), dan induksi medan listrik dari kabel di sekitarnya akan membuat level tegangan pada pin berfluktuasi secara liar antara $0\text{V}$ hingga $5\text{V}$.
- Akibatnya, instruksi pembacaan mikrokontroler akan menghasilkan nilai bit acak yang berganti-ganti tak terkendali antara `0` dan `1`. Fenomena ini disebut **Status Pin Mengambang (*Floating State*)**.

```text
+-----------------------------------------------------------------------------------+
|               KONDISI FISIK PIN MENGAMBANG (FLOATING PIN)                         |
+-----------------------------------------------------------------------------------+
|                                                                                   |
|    Gelombang Noise EM / Medan Statis Udara                                        |
|              ~~~~~~~ ~~~~~~~                                                      |
|                 \       /                                                         |
|                  v     v                                                          |
|    [Kabel Terbuka] ---------> [ Pin Input GPIO ] ---> Register PINx               |
|                               (Impedansi > 100MΩ)     (Nilai Terbaca ACAK: 0, 1, 1, 0, 0, 1)
|                                                                                   |
|    AKIBAT: Sistem berperilaku liar (glitch), aktuator menyala/mati tanpa pemicu!  |
+-----------------------------------------------------------------------------------+
```

Untuk menjamin agar pin input **selalu membaca tegangan yang pasti** saat sakelar tidak ditekan, pin wajib diikat (*tied*) ke salah satu rel referensi tegangan menggunakan sebuah **resistor referensi**.

---

#### C. Komparasi Rangkaian: Resistor Pull-Down Eksternal vs Resistor Pull-Up Internal

Ada dua topologi rangkaian pengikat tegangan referensi:

##### 1. Rangkaian Resistor Pull-Down Eksternal (Logika Aktif-Tinggi / *Active-HIGH*)
- Terminal pin GPIO dihubungkan ke bidang tanah (*Ground* / $0\text{V}$) melalui resistor eksternal sebesar $10\text{ k}\Omega$.
- Tombol dipasang menghubungkan pin GPIO ke rel $+5\text{V}$.
- **Perilaku:**
  - Saat tombol **tidak ditekan**: Pin ditarik ke $0\text{V}$ oleh resistor $\to$ Terbaca `LOW` ($0$).
  - Saat tombol **ditekan**: Arus mengalir dari $+5\text{V}$ ke pin GPIO $\to$ Terbaca `HIGH` ($1$).
- **Kelemahan Rekayasa:** Memerlukan pemasangan komponen resistor fisik tambahan di luar papan sirkuit, menambah biaya pengadaan komponen, memperbesar dimensi PCB, dan memperumit perkabelan.

##### 2. Rangkaian Resistor Pull-Up Internal (Logika Aktif-Rendah / *Active-LOW*)
Mikrokontroler ATmega328P telah dilengkapi **resistor pull-up terintegrasi di dalam silikon chip** dengan nilai nominal antara **$20\text{ k}\Omega$ hingga $50\text{ k}\Omega$** (nilai tipikal rata-rata $35\text{ k}\Omega$) yang terhubung langsung ke rel catu daya internal $+5.0\text{V}$. Resistor ini dapat diaktifkan murni melalui perangkat lunak menggunakan mode `INPUT_PULLUP`.

```text
+-----------------------------------------------------------------------------------+
|             SKEMA INTERNAL PULL-UP RESISTOR ATMEGA328P (ACTIVE-LOW)               |
+-----------------------------------------------------------------------------------+
|                                                                                   |
|             Rel Catu Daya Internal (+5V DC)                                       |
|                       |                                                           |
|                       |                                                           |
|                 [Resistor Pull-Up]  R = 20 kΩ - 50 kΩ (Internal Silikon AVR)      |
|                       |                                                           |
|                       +-------------------------> Ke Gerbang Input Digital CPU    |
|                       |                            (Register PIND / PINB)         |
|                       |                                                           |
|            [Pin Terminal Luar GPIO] (Misal: Pin 2)                                |
|                       |                                                           |
|                       o                                                           |
|                        \  Sakelar Tombol Tekan (Push-Button)                      |
|                       o                                                           |
|                       |                                                           |
|                 [Ground (GND)] (0V DC)                                            |
|                                                                                   |
+-----------------------------------------------------------------------------------+
```

- **Perilaku Fisik Rangkaian Active-LOW:**
  - Saat tombol **terbuka (TIDAK DITEKAN)**: Tidak ada jalur arus ke ground. Resistor pull-up internal menarik potensial terminal pin GPIO ke $+5\text{V}$. Mikrokontroler membaca status logika **`HIGH` ($1$)**.
  - Saat tombol **tertutup (DITEKAN)**: Terminal pin langsung terhubung ke bidang Ground ($0\text{V}$). Arus kecil dari rel $+5\text{V}$ dialirkan secara aman melintasi resistor internal langsung ke ground. Mikrokontroler membaca status logika **`LOW` ($0$)**.

> [!IMPORTANT]
> **Kaidah Emas Logika Active-LOW:**  
> Pada konfigurasi `INPUT_PULLUP`, status pembacaan penekanan tombol adalah **kebalikan dari intuisi awam**:
> - Tombol **LEPAS (Idle / Terbuka)** $\implies$ Terbaca **`HIGH`** (Logika $1$ / $5\text{V}$).
> - Tombol **TEKAN (Aktif / Tertutup)** $\implies$ Terbaca **`LOW`** (Logika $0$ / $0\text{V}$).

---

#### D. Tujuan Pembelajaran & Diagram Alur Sinyal

Setelah menyelesaikan modul ini, peserta didik mampu:
1. Menjelaskan fenomena fisik *floating pin*, batas ambang tegangan CMOS AVR, dan cara kerja resistor pull-up internal ATmega328P.
2. Mengonfigurasi pin digital sebagai masukan dengan mode `INPUT_PULLUP` dan membaca status logika biner menggunakan `digitalRead()`.
3. Menerapkan teknik pemetaan langsung, deteksi tepi jatuh (*falling edge detection*) untuk sakelar toggle, serta debouncing perangkat lunak tanpa memblokir CPU.
4. Mentransfer implementasi tombol input ke subsistem otomasi fasilitas (Tema 1), bumper navigasi robotika (Tema 2), dan pemicu telemetri IoT (Tema 3).

**Diagram Alur Sinyal Fisik (*Signal Flow Chart*):**
```text
[ Kontak Sakelar Fisik ]
        │
        ▼  (Tekan: Hubung ke GND 0V | Lepas: Ditarik Pull-Up ke +5V)
[ Terminal Pin GPIO Fisik (Misal: Pin 2) ]
        │
        ▼  (Melewati Schmitt Trigger Komparator Input AVR)
[ Register Masukan Port I/O (PINx: PIND2) ]
        │
        ▼  (Instruksi digitalRead(2) -> Mengembalikan HIGH (1) atau LOW (0))
[ Unit Pemroses Sentral (CPU) / Evaluasi Logika if-else ]
        │
        ▼  (Instruksi digitalWrite(13, HIGH/LOW))
[ Register Keluaran Port I/O (PORTx: PORTB5) ]
        │
        ▼  (Tegangan 5V / 0V melalui Resistor 220 Ohm)
[ Aktuator / Indikator LED Fisik ]
```

---

### 2. Kamus Teknis: Fungsi C++, Library, & Argumen `[INTI: SMK & Mahasiswa]`

Sebelum menyusun kode program lengkap, pelajari format sintaksis baku, tipe data parameter, dan mekanisme internal dari setiap fungsi inti berikut:

#### A. `pinMode()` dengan Mode `INPUT_PULLUP`

- **Kegunaan Utama:**  
  Mengonfigurasi mode kerja fungsional pin GPIO sebagai terminal masukan berimpedansi tinggi sekaligus menyambungkan sakelar FET internal untuk mengaktifkan resistor pull-up silikon ($20\text{ k}\Omega - 50\text{ k}\Omega$) ke rel catu daya internal $+5.0\text{V}$. Di tingkat register mikrokontroler AVR ATmega328P, instruksi ini membersihkan bit pada register arah data (DDRx) menjadi `0` (mode Input) dan menuliskan bit `1` pada register keluaran data (PORTx) saat bit PUD (*Pull-up Disable*) di register MCUCR bernilai `0`.

- **Format Sintaksis Baku:**
  ```cpp
  void pinMode(uint8_t pin, uint8_t mode);
  ```

- **Rincian Parameter / Argumen:**

  | Nama Parameter | Status | Tipe Data | Penjelasan & Nilai Valid |
  | :--- | :---: | :--- | :--- |
  | `pin` | **Wajib** | `uint8_t` | Nomor pin fisik GPIO yang dikonfigurasi. Nilai valid: Pin digital `2` sampai `13`, serta pin analog `A0` sampai `A5`. *(Hindari Pin 0 dan Pin 1 karena terhubung ke modul komunikasi serial UART)*. |
  | `mode` | **Wajib** | `uint8_t` | Karakteristik elektris pin. Pada modul ini menggunakan konstanta `INPUT_PULLUP`. |

- **Nilai Kembalian (*Return Value*):**  
  Mengembalikan tipe `void` (tidak mengembalikan nilai; langsung memodifikasi konfigurasi register perangkat keras).

- **Contoh Mikro (1–3 Baris Mandiri):**
  ```cpp
  const uint8_t PIN_TOMBOL = 2;
  pinMode(PIN_TOMBOL, INPUT_PULLUP); // Pin 2 siap membaca tombol Active-LOW
  ```
  - *Penjelasan baris:* Pin 2 ditetapkan sebagai jalur masukan dengan resistor penarik $+5\text{V}$ aktif di dalam mikrokontroler.
  - *Efek eksekusi:* Pin 2 berada pada potensial stabil $+5.0\text{V}$ saat tidak ada kabel yang menghubungkannya ke Ground.

---

#### B. `digitalRead()`

- **Kegunaan Utama:**  
  Membaca tingkat tegangan logika biner yang dialami oleh terminal fisik pin GPIO secara seketika. Fungsi ini membaca bit pada register memori I/O **PINx** (*Port Input Pins Address*, misal `PIND` untuk digital pin 0–7, `PINB` untuk digital pin 8–13) melalui komparator *Schmitt Trigger* internal chip yang menolak derau transien kecil.

- **Format Sintaksis Baku:**
  ```cpp
  int digitalRead(uint8_t pin);
  ```

- **Rincian Parameter / Argumen:**

  | Nama Parameter | Status | Tipe Data | Penjelasan & Nilai Valid |
  | :--- | :---: | :--- | :--- |
  | `pin` | **Wajib** | `uint8_t` | Nomor pin fisik GPIO yang hendak dipindai status tegangannya. Nilai valid: `0` hingga `13`, serta `A0` hingga `A5`. |

- **Nilai Kembalian (*Return Value*) & Ambang Batas Tegangan:**  
  Mengembalikan tipe `int` (bernilai konstanta `HIGH` atau `LOW`):
  - **`HIGH` (Konstanta numerik integer `1`):** Dihasilkan jika tegangan pada terminal pin memenuhi ambang batas tegangan tinggi ($V_{\text{IH}} \ge 0.6 \times V_{cc} \implies \ge 3.0\text{ Volt}$ pada $V_{cc} = 5.0\text{V}$).
  - **`LOW` (Konstanta numerik integer `0`):** Dihasilkan jika tegangan pada terminal pin berada di bawah ambang batas tegangan rendah ($V_{\text{IL}} \le 0.3 \times V_{cc} \implies \le 1.5\text{ Volt}$ pada $V_{cc} = 5.0\text{V}$).

- **Contoh Mikro (1–3 Baris Mandiri):**
  ```cpp
  const uint8_t statusBaca = digitalRead(2);
  // statusBaca bernilai LOW saat tombol ditekan ke GND, bernilai HIGH saat dilepas
  ```
  - *Penjelasan baris:* Memindai kondisi elektris pin 2 saat baris instruksi dieksekusi dan menyimpan statusnya ke variabel hemat memori `statusBaca`.
  - *Hasil eksekusi:* Menghasilkan nilai `0` atau `1` tergantung status kontak fisik sakelar.

---

### 3. Prasyarat Wokwi, Skema Pinout, & Checkpoint 3 Tema `[INTI: SMK & Mahasiswa]`

#### A. Kebutuhan Lingkungan Simulator & Komponen
- **Simulator Target:** Simulator peramban web Wokwi (`https://wokwi.com/projects/new/arduino-uno`).
- **Papan Pengembangan:** Arduino Uno R3 (Mikrokontroler ATmega328P 16 MHz).
- **Komponen Elektronika yang Dibutuhkan:**
  1. $1$ unit Tombol Tekan (*Pushbutton / Tactile Switch* 4 pin).
  2. $1$ unit LED 5mm (Warna Merah).
  3. $1$ unit Resistor $220\,\Omega$ (Kode warna: Merah-Merah-Cokelat-Emas).
  4. Kabel *jumper virtual*.

---

#### B. Tabel Pemetaan Pinout Rangkaian (Tema 1: Otomasi Fasilitas)

| Komponen Elektronika | Terminal Komponen | Sambungan ke Arduino Uno | Fungsi Jalur / Karakteristik Sinyal |
| :--- | :--- | :--- | :--- |
| **Push-Button (Tactile Switch)** | Terminal Kaki `1.L` (atau `1`) | Pin Digital `2` | Jalur Pembacaan Sinyal Input (Active-LOW) |
| **Push-Button (Tactile Switch)** | Terminal Kaki `2.L` (atau `2`) | Header Pin `GND` | Jalur Penarikan Potensial ke $0\text{V}$ saat Kontak Menutup |
| **Resistor Pembatas Arus** | Kaki Terminal 1 | Pin Digital `13` | Jalur Keluaran Sinyal Digital Output ($5\text{V} / 0\text{V}$) |
| **Resistor Pembatas Arus** | Kaki Terminal 2 | Anoda LED Merah | Arus Teregulasi Masuk ke Dioda LED ($13.6\text{ mA}$) |
| **LED 5mm Merah** | Anoda (Kaki Panjang / $+$) | Kaki Terminal 2 Resistor | Sisi Masuk Tegangan Positif ($V_f \approx 2.0\text{V}$) |
| **LED 5mm Merah** | Katoda (Kaki Pendek / $-$) | Header Pin `GND` | Jalur Pembuangan Arus Kembali ke Bidang Ground |

---

#### C. Diagram Jalur Skematik Rangkaian (ASCII Circuit)

```text
       ARDUINO UNO R3
    +-------------------+
    |                   |
    |            Pin 2  | -------- (Kabel Jumper) --------+
    |                   |                                 |
    |                   |                              +------+
    |                   |                              |  o   | Push-Button (Tactile Switch)
    |                   |                              | /    | Kontak Terbuka / Tertutup
    |                   |                              |  o   |
    |                   |                              +------+
    |            GND    | -------- (Kabel Jumper) --------+ (Terhubung ke Ground)
    |                   |
    |            Pin 13 | ---- [ Resistor 220 Ω ] ---- (+) [ Anoda LED ]
    |                   |                                  [ LED Merah ]
    |            GND    | ---------------------------- (-) [ Katoda LED]
    +-------------------+
```

---

#### D. Langkah-Langkah Perakitan di Simulator Wokwi (`wokwi.com`)
1. Buka peramban web dan arahkan ke alamat: `https://wokwi.com/projects/new/arduino-uno`.
2. Klik tombol **"+"** (*Add a new part*) di panel atas kanvas simulasi:
   - Tambahkan $1$ buah **Pushbutton**.
   - Tambahkan $1$ buah **LED** (Merah).
   - Tambahkan $1$ buah **Resistor**.
3. Klik pada komponen resistor dan pastikan nilainya diatur menjadi `220` ohm (bukan kilo-ohm).
4. **Penyambungan Sirkuit Tombol Tekan**:
   - Tarik kabel dari terminal **1.L** pada Pushbutton ke pin **2** Arduino Uno.
   - Tarik kabel dari terminal **2.L** pada Pushbutton ke pin **GND** Arduino Uno.  
     *(Perhatikan: Sirkuit tombol sama sekali tidak memerlukan resistor eksternal karena kita menggunakan mode `INPUT_PULLUP`)*.
5. **Penyambungan Sirkuit Indikator LED**:
   - Tarik kabel dari pin **13** Arduino Uno ke salah satu kaki Resistor $220\,\Omega$.
   - Tarik kabel dari kaki resistor yang satunya ke kaki **Anode** (Anoda) LED Merah.
   - Tarik kabel dari kaki **Cathode** (Katoda) LED Merah ke pin **GND** Arduino Uno.

---

#### E. Peta Checkpoint 3 Tema Berkelanjutan (*Persistent Lab Tracks*)

| Tema | Domain Sistem | Status dari Modul Sebelumnya | Target Penambahan di Modul Ini |
| :--- | :--- | :--- | :--- |
| **Tema 1 (Inti)** | Otomasi Fasilitas Ruangan | Modul 01: Kendali LED Output Blinking Tunggal | Menambahkan Tombol Tekan Active-LOW untuk kendali interaktif fasilitas (Contoh 1–4, Latihan Level 1–2). |
| **Tema 2 (Transfer)** | Robotika Navigasi & Keselamatan | Modul 01: Sistem Indikator Strobo Dual Kanal | Mengimplementasikan Tombol Pembatas (*Limit Switch / Bumper*) untuk deteksi tabrakan fisik sasis robot (Latihan Level 3). |
| **Tema 3 *(Pendalaman)*** | Telemetri IoT ESP32 | Modul 01: Heartbeat LED Status Daya | Mengembangkan Tombol Pemicu Telemetri (*Push-to-Send Event*) dan pencatatan ketukan berbasis serial JSON (Latihan Level 4). |

---

### 4. Implementasi Kode Bersih Bertingkat — Tema 1 *(Minimal 4 Contoh)*

Seluruh contoh kode pada bagian ini berfokus pada **Tema 1 (Otomasi Fasilitas Ruangan)** dengan penambahan satu konsep baru di setiap tingkatannya, mematuhi standar *Embedded Clean Code* (alokasi memori efisien `const uint8_t`, evaluasi logika eksplisit, dan bebas *CPU blocking*).

---

#### Contoh 1 — Kasus Paling Dasar: Sakelar Sesaat (*Momentary Switch*) `[INTI: SMK & Mahasiswa]`
*Mengontrol indikator LED fasilitas secara langsung berdasarkan status penekanan tombol saat ini.*

- **Format Umum yang Dipakai:**
  ```cpp
  if (digitalRead(PIN_INPUT) == LOW) {
    // Tombol ditekan (Active-LOW) -> Aktifkan aktuator
  } else {
    // Tombol dilepas -> Nonaktifkan aktuator
  }
  ```

- **Kode Implementasi Lengkap:**
  ```cpp
  // src/01_tombol_dasar_momentary.ino
  // ===================================================================================
  // PROGRAM      : KONTROL LAMPU FASILITAS SESAAT (MOMENTARY PUSH-BUTTON)
  // PLATFORM     : ARDUINO UNO R3 & WOKWI SIMULATOR
  // STANDAR KODE : EMBEDDED C++ CLEAN CODE (ACTIVE-LOW LOGIC)
  // ===================================================================================

  // Alokasi pin perangkat keras (hemat memori SRAM: 1 byte per konstanta)
  const uint8_t PIN_TOMBOL_INPUT  = 2;   // Push-button Active-LOW (ke GND)
  const uint8_t PIN_LED_INDIKATOR = 13;  // LED indikator fasilitas (melalui resistor 220 Ohm)

  void setup() {
    // Konfigurasi pin tombol sebagai INPUT dengan resistor PULL-UP internal aktif
    pinMode(PIN_TOMBOL_INPUT, INPUT_PULLUP);

    // Konfigurasi pin LED sebagai OUTPUT
    pinMode(PIN_LED_INDIKATOR, OUTPUT);

    // Pastikan LED padam pada kondisi awal
    digitalWrite(PIN_LED_INDIKATOR, LOW);
  }

  void loop() {
    // Membaca level logika biner pada pin tombol fisik
    const uint8_t statusTombol = digitalRead(PIN_TOMBOL_INPUT);

    // Evaluasi Logika Active-LOW:
    // Jika bernilai LOW (0), sakelar tertutup menghubungkan pin ke Ground
    if (statusTombol == LOW) {
      digitalWrite(PIN_LED_INDIKATOR, HIGH); // Nyalakan lampu fasilitas
    } else {
      digitalWrite(PIN_LED_INDIKATOR, LOW);  // Matikan lampu saat tombol dilepas
    }
  }
  ```

- **Penjelasan Tiap Baris Kunci:**
  - **Baris 9–10:** `const uint8_t PIN_... = ...;` mendeklarasikan nomor pin menggunakan tipe data bilangan bulat tak bertanda 8-bit. Tipe data ini hanya memakan 1 byte memori Flash, berbeda dengan tipe `int` pada arsitektur AVR yang memakan 2 byte memori SRAM.
  - **Baris 14:** `pinMode(PIN_TOMBOL_INPUT, INPUT_PULLUP);` mengaktifkan resistor internal $35\text{ k}\Omega$ ke rel $+5\text{V}$, mengeliminasi *floating pin*.
  - **Baris 24:** `const uint8_t statusTombol = digitalRead(PIN_TOMBOL_INPUT);` membaca kondisi pin 2 dan menguncinya ke dalam konstanta lokal siklus loop.
  - **Baris 28–32:** Evaluasi percabangan membandingkan `statusTombol` dengan `LOW` karena penekanan fisik mengalirkan tegangan pin langsung ke ground ($0\text{V}$).

- **Hasil Simulasi Wokwi:**  
  Saat tombol ditekan dan ditahan, LED merah menyala terang. Begitu tombol dilepas, LED seketika padam.

---

#### Contoh 2 — Variasi Parameter: Pemetaan Logika Langsung & Inversi Logika `[INTI: SMK & Mahasiswa]`
*Menyederhanakan struktur kode dengan memetakan nilai pembacaan sensor langsung ke keluaran aktuator tanpa blok percabangan `if-else`.*

- **Kode Implementasi Lengkap:**
  ```cpp
  // src/02_pemetaan_langsung_inversi.ino
  // ===================================================================================
  // PROGRAM      : PEMETAAN LOGIKA LANGSUNG (DIRECT INVERTED LOGIC MAPPING)
  // PLATFORM     : ARDUINO UNO R3 & WOKWI SIMULATOR
  // STANDAR KODE : EMBEDDED C++ CLEAN CODE
  // ===================================================================================

  const uint8_t PIN_TOMBOL = 2;
  const uint8_t PIN_LED    = 13;

  void setup() {
    pinMode(PIN_TOMBOL, INPUT_PULLUP);
    pinMode(PIN_LED, OUTPUT);
  }

  void loop() {
    // Alirkan langsung nilai pembacaan digitalRead ke pin LED
    digitalWrite(PIN_LED, digitalRead(PIN_TOMBOL));
  }
  ```

- **Perbedaan dengan Contoh 1:**  
  Menghilangkan seluruh struktur kontrol percabangan `if-else`. Nilai kembalian fungsi `digitalRead()` diteruskan langsung sebagai parameter kedua pada fungsi `digitalWrite()`.

- **Pelacakan Kondisi Antara (*State Tracing*):**

  | Kondisi Fisik Tombol | Tegangan Pin 2 | Register PIND2 | Hasil `digitalRead(2)` | Parameter `digitalWrite(13, ...)` | Kondisi Fisik LED | Perilaku Sistem |
  | :--- | :---: | :---: | :---: | :---: | :---: | :--- |
  | **Dilepas (Idle)** | $+5.0\text{V}$ | `1` | `HIGH` ($1$) | `HIGH` | **Menyala** | Normal: Lampu menyala siaga |
  | **Ditekan (Aktif)** | $0.0\text{V}$ | `0` | `LOW` ($0$) | `LOW` | **Padam** | Ditekan: Lampu dipadamkan |

- **Hasil Simulasi Wokwi:**  
  Sistem berperilaku terbalik (*Normally-ON*). LED merah secara bawaan menyala terang saat tombol dibiarkan bebas, dan padam seketika saat tombol ditekan.

---

#### Contoh 3 — Kombinasi Menengah: Sakelar Penahan Status (*State Toggle Switch*) dengan Deteksi Tepi `[INTI: SMK & Mahasiswa]`
*Mengubah tombol tekan sesaat (momentary) menjadi sakelar penahan status (ditekan sekali lampu hidup dan tetap hidup meski tombol dilepas; ditekan sekali lagi lampu mati).*

- **Konsep Kunci: Deteksi Tepi Jatuh (*Falling Edge Detection*)**:  
  Mikrokontroler tidak boleh hanya memeriksa status tombol saat ini, melainkan harus membandingkan **status tombol saat ini (*current state*)** dengan **status tombol pada siklus pembacaan sebelumnya (*previous state*)**. Pembalikan status LED hanya dieksekusi ketika terjadi transisi dari tidak ditekan (`HIGH`) menuju ditekan (`LOW`).

- **Kode Implementasi Lengkap:**
  ```cpp
  // src/03_sakelar_toggle_deteksi_tepi.ino
  // ===================================================================================
  // PROGRAM      : SAKELAR PENAHAN STATUS (STATE TOGGLE DENGAN EDGE DETECTION)
  // PLATFORM     : ARDUINO UNO R3 & WOKWI SIMULATOR
  // STANDAR KODE : EMBEDDED C++ CLEAN CODE
  // ===================================================================================

  const uint8_t PIN_TOMBOL = 2;
  const uint8_t PIN_LED    = 13;

  // Variabel memori penyimpan status sistem
  bool statusLampu = false;               // false = Padam, true = Menyala
  uint8_t statusTombolSebelumnya = HIGH; // Status awal idle tombol Active-LOW adalah HIGH

  void setup() {
    pinMode(PIN_TOMBOL, INPUT_PULLUP);
    pinMode(PIN_LED, OUTPUT);
    digitalWrite(PIN_LED, LOW);
  }

  void loop() {
    // 1. Baca status fisik tombol saat ini
    const uint8_t statusTombolSekarang = digitalRead(PIN_TOMBOL);

    // 2. Evaluasi Deteksi Tepi Jatuh (Falling Edge):
    // Kondisi: Sebelumnya HIGH (dilepas) dan sekarang menjadi LOW (baru saja ditekan)
    if (statusTombolSebelumnya == HIGH && statusTombolSekarang == LOW) {
      // Balikkan status boolean lampu (Inversi Boolean: NOT operator)
      statusLampu = !statusLampu;
      digitalWrite(PIN_LED, statusLampu ? HIGH : LOW);

      // Jeda transisi kontak mekanik dasar (50 ms)
      delay(50);
    }

    // 3. Simpan status siklus ini untuk dibandingkan pada siklus loop() berikutnya
    statusTombolSebelumnya = statusTombolSekarang;
  }
  ```

- **Pelacakan Alur Sinyal (*Step-by-Step Trace*):**

  ```text
  Siklus 1 (Idle):   prev=HIGH, curr=HIGH -> Kondisi SALAH -> Lampu tetap PADAM
  Siklus 2 (Tekan):  prev=HIGH, curr=LOW  -> TEPI JATUH! -> statusLampu = TRUE -> Lampu MENYALA
  Siklus 3 (Tahan):  prev=LOW,  curr=LOW  -> Kondisi SALAH -> Lampu tetap MENYALA (Tidak melompat mati)
  Siklus 4 (Lepas):  prev=LOW,  curr=HIGH -> Kondisi SALAH -> Lampu tetap MENYALA
  Siklus 5 (Tekan):  prev=HIGH, curr=LOW  -> TEPI JATUH! -> statusLampu = FALSE -> Lampu PADAM
  ```

- **Hasil Simulasi Wokwi:**  
  Klik tombol satu kali: LED menyala konstan meskipun kursor mouse telah melepas tombol. Klik tombol sekali lagi: LED padam seketika.

---

#### Contoh 4 — Implementasi Terapan, Debouncing Perangkat Lunak Non-Blocking, & Keamanan Sistem `[PENDALAMAN: Mahasiswa / SMK Lanjutan]`
> **Catatan Pendalaman:** Bagian ini menganalisis fenomena fisika getaran mekanis sakelar (*bouncing*) dan mengimplementasikan filter penyaring digital tanpa memblokir CPU (*non-blocking timer*).

##### Fenomena Getaran Mekanis (*Contact Bouncing*)
Sakelar tombol tekan tersusun dari pelat pegas logam elastis. Ketika tombol ditekan atau dilepas, permukaan logam tidak langsung menutup secara sempurna, melainkan **bergetar dan memantul bolak-balik** selama rentang waktu $5\text{ ms}$ hingga $20\text{ ms}$ sebelum stabil.

```text
Tegangan Pin 2
   5V |--------\  /\  /\
      |         \/  \/  \___________________ (Stabil LOW / 0V)
   0V |
      +-------------------------------------> Waktu (t)
               |<-- 5 - 20 ms -->|
                 (Bouncing Zone)
```

Mikrokontroler 16 MHz mampu mengeksekusi belasan ribu instruksi dalam durasi pantulan tersebut. Tanpa peredam (*debounce*), mikrokontroler akan mendeteksi pulsa transisi palsu belasan kali dalam satu kali penekanan jari, menyebabkan sakelar toggle melompat-lompat statusnya secara acak. Penggunaan `delay(50)` pada Contoh 3 menyelesaikan masalah getaran tetapi melumpuhkan CPU. Pada sistem industri berkecepatan tinggi, kita wajib menggunakan pewaktu non-blocking berbasis `millis()`.

- **Kode Implementasi Lengkap:**
  ```cpp
  // src/04_debouncing_non_blocking_millis.ino
  // ===================================================================================
  // PROGRAM      : DEBOUNCING TOMBOL NON-BLOCKING BERBASIS MILLIS()
  // PLATFORM     : ARDUINO UNO R3 & WOKWI SIMULATOR
  // STANDAR KODE : EMBEDDED C++ INDUSTRIAL REAL-TIME
  // ===================================================================================

  const uint8_t PIN_TOMBOL = 2;
  const uint8_t PIN_LED    = 13;

  // Waktu filter peredam getaran mekanis (dalam milidetik)
  const unsigned long JEDA_DEBOUNCE_MS = 50;

  // Variabel status logika sistem
  bool statusLampu = false;
  uint8_t statusStabilTombol     = HIGH;
  uint8_t bacaanTombolTerakhir   = HIGH;

  // Variabel penampung stempel waktu non-blocking
  unsigned long waktuPerubahanTerakhir = 0;

  void setup() {
    pinMode(PIN_TOMBOL, INPUT_PULLUP);
    pinMode(PIN_LED, OUTPUT);
    digitalWrite(PIN_LED, LOW);
  }

  void loop() {
    // 1. Baca kondisi fisik instan pin tombol
    const uint8_t bacaanSekarang = digitalRead(PIN_TOMBOL);

    // 2. Jika terjadi perubahan bacaan (akibat getaran atau penekanan riil), catat stempel waktu
    if (bacaanSekarang != bacaanTombolTerakhir) {
      waktuPerubahanTerakhir = millis();
    }

    // 3. Evaluasi apakah bacaan telah bertahan stabil melampaui batas jendela debounce
    if ((millis() - waktuPerubahanTerakhir) > JEDA_DEBOUNCE_MS) {
      // Jika status bacaan stabil berbeda dari status yang tersimpan di sistem
      if (bacaanSekarang != statusStabilTombol) {
        statusStabilTombol = bacaanSekarang;

        // Deteksi Tepi Jatuh pada status yang telah tervalidasi bersih dari noise
        if (statusStabilTombol == LOW) {
          statusLampu = !statusLampu;
          digitalWrite(PIN_LED, statusLampu ? HIGH : LOW);
        }
      }
    }

    // 4. Simpan bacaan saat ini untuk evaluasi siklus berikutnya
    bacaanTombolTerakhir = bacaanSekarang;

    // CPU bebas mengeksekusi tugas telemetri atau kendali sensor lain di sini tanpa jeda blocking!
  }
  ```

- **Analisis Keandalan & Efisiensi Sistem:**  
  Dengan arsitektur non-blocking di atas, CPU Arduino tetap dapat memindai sensor ultrasonik, membaca komunikasi UART, atau mengendalikan motor di dalam siklus `loop()` yang sama tanpa terinterupsi atau mengalami pembekuan operasi (*zero CPU freeze*).

---

### 5. Verifikasi Rangkaian, Rumus Elektronika, & Debugging `[INTI: SMK & Mahasiswa]`

#### A. Analisis Elektronika & Pembuktian Sirkuit

##### 1. Mengapa Menghubungkan Pin 2 ke Ground Tidak Memicu Korsleting?
Banyak teknisi pemula khawatir bahwa menghubungkan tombol langsung dari Pin 2 ke pin `GND` akan menimbulkan hubungan singkat (*short-circuit*). Pemahaman ini keliru secara hukum fisika:
- Terminal Pin 2 berada dalam mode masukan impedansi tinggi, bukan sumber tegangan murni.
- Arus yang mengalir ke Ground saat tombol ditekan dibatasi secara ketat oleh **resistor pull-up internal** chip.

##### 2. Perhitungan Beban Arus Pull-Up (*Pull-Up Sinking Current*)
Berdasarkan lembar data resmi Microchip ATmega328P, nilai resistansi pull-up internal silikon berada pada rentang $R_{\text{pullup}} = 20\text{ k}\Omega$ hingga $50\text{ k}\Omega$ (nilai nominal tipikal $35\text{ k}\Omega$).

Berdasarkan Hukum Ohm:

$$I_{\text{tombol}} = \frac{V_{cc}}{R_{\text{pullup}}} = \frac{5.0\text{ Volt}}{35.000\,\Omega} \approx 0.0001428\text{ Ampere} = 142.8\,\mu\text{A}$$

Arus sebesar $142.8\text{ mikroampere}$ adalah besaran arus yang sangat kecil dan aman bagi semikonduktor chip, jauh di bawah batas maksimum pin ($40\text{ mA}$).

##### 3. Perhitungan Disipasi Daya Termal Resistor Internal ($P$)

$$P = V_{cc} \times I_{\text{tombol}} = 5.0\text{V} \times 0.0001428\text{A} \approx 0.000714\text{ Watt} = 0.714\text{ mW}$$

Daya termal yang dihasilkan hanya $0.714\text{ miliwatt}$, sehingga mikrokontroler bekerja sangat dingin tanpa kenaikan temperatur silikon.

##### 4. Verifikasi Resistor Beban Indikator LED Merah ($220\,\Omega$)

$$I_{\text{LED}} = \frac{V_s - V_f}{R} = \frac{5.0\text{V} - 2.0\text{V}}{220\,\Omega} = \frac{3.0\text{V}}{220\,\Omega} \approx 13.6\text{ mA}$$

Arus $13.6\text{ mA}$ menjamin LED menyala terang optimal dan menjaga integritas transistor keluaran Pin 13 tetap awet.

---

#### B. Ringkasan Ekspektasi Output Contoh 1–4

| Contoh | Jalur | Fokus Fitur yang Diuji | Indikator Berhasil / Perilaku di Wokwi |
| :--- | :---: | :--- | :--- |
| **Contoh 1** | Inti | `digitalRead()` + Percabangan `if-else` | LED merah menyala hanya saat tombol ditekan; padam saat dilepas. |
| **Contoh 2** | Inti | Pemetaan langsung ke `digitalWrite()` | Inversi otomatis: LED normalnya menyala, padam saat tombol ditekan. |
| **Contoh 3** | Inti | Deteksi tepi jatuh (*Edge Detection*) | Sakelar toggle: Tekan 1x LED menyala stabil, tekan 1x lagi LED padam. |
| **Contoh 4** | Pendalaman | Debouncing non-blocking `millis()` | Sakelar toggle bebas lonjakan getaran mekanis tanpa membekukan CPU. |

---

#### C. Kesalahan Umum & Panduan Troubleshooting di Wokwi

| Gejala Masalah di Wokwi | Penyebab Utama pada Rangkaian/Kode | Solusi Tepat |
| :--- | :--- | :--- |
| **LED menyala/mati acak (glitch) saat tombol tidak disentuh sama sekali.** | Mode pin dikonfigurasi sebagai `INPUT` biasa, bukan `INPUT_PULLUP`. Pin berada dalam status mengambang (*floating*). | Ubah baris inisialisasi di `setup()` menjadi `pinMode(PIN_TOMBOL, INPUT_PULLUP);`. |
| **LED menyala terbalik dari instruksi (saat tombol ditekan justru padam).** | Salah asumsi logika. Menganggap tombol tekan menghasilkan logika `HIGH`, padahal sirkuit pull-up berlogika *Active-LOW*. | Evaluasi penekanan tombol menggunakan kondisi `if (statusTombol == LOW)`. |
| **Satu kali klik tombol membuat status LED melompat ganda atau tidak konsisten.** | Terjadi getaran mekanis (*contact bouncing*) yang dibaca sebagai beberapa klik cepat oleh CPU. | Terapkan teknik deteksi tepi dipadu filter penundaan debouncing atau algoritma `millis()` non-blocking (Contoh 4). |
| **Tombol ditekan tetapi LED tidak bereaksi sama sekali (tegangan pin tetap 5V).** | Kabel jumper pada Pushbutton dipasang pada kaki pasangan internal yang sebidang (*shorted terminal*). | Pada komponen pushbutton Wokwi, gunakan kaki terminal diagonal atau pastikan kabel terpasang pada kaki `1.L` dan `2.L`. |

---

### 6. Latihan Praktikum Berkelanjutan Multi-Tema *(4 Level)*

Perhatikan spesifikasi pemetaan pinout perangkat keras acuan praktikum berikut sebelum mengerjakan lembar tugas:

#### Tabel Acuan Perangkat Keras Praktikum Multi-Tema

| Identitas Komponen | Tipe Antarmuka | Sambungan Pin Arduino | Mode Operasional / Catatan |
| :--- | :--- | :--- | :--- |
| **Tombol 1 (PB_UTAMA)** | Digital Input | Pin Digital `2` | `INPUT_PULLUP` (Active-LOW, terhubung ke GND) |
| **Tombol 2 (PB_SEKUNDER)** | Digital Input | Pin Digital `3` | `INPUT_PULLUP` (Active-LOW, terhubung ke GND) |
| **LED Merah (LED_STATUS_1)** | Digital Output | Pin Digital `13` | Melalui resistor pembatas $220\,\Omega$ ke GND |
| **LED Hijau (LED_STATUS_2)** | Digital Output | Pin Digital `12` | Melalui resistor pembatas $220\,\Omega$ ke GND |

---

#### Level 1 — Pemahaman Fungsi & Tracing Sinyal *(Tema 1)* `[INTI: SMK & Mahasiswa]`
1. Perhatikan potongan kode berikut:
   ```cpp
   pinMode(2, INPUT_PULLUP);
   uint8_t nilai = digitalRead(2);
   digitalWrite(13, !nilai);
   ```
   Tentukan nilai variabel `nilai` dan status fisik LED pada Pin 13 ketika tombol di Pin 2 sedang **ditekan ke Ground**!
2. Jelaskan mengapa instruksi `pinMode(2, INPUT);` yang disambungkan ke tombol menuju GND tanpa resistor eksternal akan menyebabkan pembacaan serial menampilkan angka `0` dan `1` yang melompat-lompat secara acak!
3. Tuliskan satu baris perbaikan kode pada fungsi `setup()` jika seorang teknisi salah mengetik `pinMode(PIN_TOMBOL, OUTPUT);` yang menyebabkan tombol tidak dapat mendeteksi masukan!

---

#### Level 2 — Modifikasi Terarah di Wokwi *(Tema 1: Sistem Otomasi Kunci Pintu Fasilitas)* `[INTI: SMK & Mahasiswa]`
4. Rancang sirkuit dan tulis kode program sistem indikator kunci gerbang otomatis di Wokwi menggunakan $1$ tombol di Pin 2, LED Merah di Pin 13, dan LED Hijau di Pin 12:
   - **Status Terkunci (Normal):** LED Merah menyala, LED Hijau padam.
   - Saat tombol di Pin 2 ditekan dan ditahan oleh pengguna: LED Merah seketika padam, dan LED Hijau menyala (menandakan kunci terbuka).
   - Saat tombol dilepas: sistem kembali ke status Terkunci (LED Merah menyala, LED Hijau padam).
5. Modifikasi sistem gerbang pada butir 4 menjadi **Sistem Toggle Kait Magnetik (*Latching Door Lock*)**:
   - Ditekan satu kali: Gerbang terbuka (LED Hijau menyala, Merah padam) dan **tetap bertahan terbuka** meskipun tombol sudah dilepas.
   - Ditekan satu kali lagi: Gerbang kembali terkunci (LED Merah menyala, Hijau padam). Gunakan algoritma deteksi tepi jatuh.

---

#### Level 3 — Praktikum Berkelanjutan: Transfer ke Subsistem Robotika *(Tema 2: Bumper Tabrakan Sasis)* `[INTI LANJUT: SMK Lanjutan & Mahasiswa]`
6. Pada sasis robot penjelajah (*mobile robot*), dipasang dua tombol pembatas fisik (*limit switch*) sebagai sensor tabrakan: **Bumper Kiri** di Pin 2 dan **Bumper Kanan** di Pin 3. Dua LED (Pin 13: Indikator Motor Kiri, Pin 12: Indikator Motor Kanan) merepresentasikan status penggerak roda.
   - Tuliskan kode program agar dalam kondisi normal (kedua bumper bebas / `HIGH`), kedua LED menyala penuh (menandakan robot bergerak maju lurus).
   - Jika **Bumper Kiri menabrak dinding** (Pin 2 terbaca `LOW`): LED Motor Kiri padam, LED Motor Kanan tetap menyala selama $1$ detik (simulasi robot bermanuver memutar badan ke kanan untuk menghindari rintangan).
   - Jika **Bumper Kanan menabrak dinding** (Pin 3 terbaca `LOW`): LED Motor Kanan padam, LED Motor Kiri tetap menyala selama $1$ detik (simulasi robot berputar menghindar ke kiri).
   - Setelah manuver selesai, sistem kembali ke status maju lurus.
7. Analisis kondisi keselamatan kritis: Apa tindakan yang harus diambil oleh logika program jika **kedua bumper menabrak secara bersamaan** (Pin 2 dan Pin 3 sama-sama terbaca `LOW`)? Tuliskan blok kondisional pengaman untuk memadamkan kedua LED motor seketika (*Emergency Stop*).

---

#### Level 4 — Praktikum Berkelanjutan: Kasus Batas, Optimasi, & Telemetri *(Tema 3: Node Telemetri IoT)* `[PENDALAMAN: Mahasiswa / Pengayaan SMK]`
8. Rancang program penghitung ketukan fasilitas (*Facility Access Counter*) pada Pin 2 dengan filter debouncing non-blocking `millis()`. Setiap kali terjadi satu kali penekanan tombol yang valid:
   - Variabel pencacah `totalAkses` bertambah satu ($+1$).
   - Mikrokontroler memancarkan stempel data telemetri ke port serial komunikasi UART (Baud rate 9600) dalam format payload terstruktur persis seperti berikut:
     ```text
     {"event":"button_press","count":1,"uptime_ms":1420}
     ```
   - Pastikan pengiriman data serial hanya terjadi **tepat satu kali per penekanan tombol** (tidak membanjiri serial saat tombol ditahan lama).
9. Analisis efisiensi alokasi memori sistem: Pada mikrokontroler AVR dengan SRAM terbatas ($2\text{ KB}$), mengapa penggunaan objek `String` dinamis (contoh: `String payload = "{\"count\":" + String(c);`) sangat dilarang untuk membentuk payload telemetri di atas? Tuliskan cara alternatif membentuk string keluaran serial yang aman dari fragmentasi memori *heap*!

---

### 7. Panduan Evaluasi & Checklist Mandiri `[INTI: SMK & Mahasiswa]`

Gunakan panduan berikut untuk memverifikasi kebenaran hasil simulasi praktikum Anda secara mandiri di simulator Wokwi:

#### A. Indikator Keberhasilan Tugas & Target Tampilan Output

- **Target Respon Logika Soal 1 (Level 1):**
  - Variabel `nilai` bernilai integer `0` (`LOW`).
  - Ekspresi `!nilai` bernilai logika `1` (`HIGH`).
  - Hasil fisik: LED Merah Pin 13 **MENYALA TERANG**.

- **Target Respon Visual Gerbang Fasilitas (Soal 4 & 5 - Level 2):**
  - Kondisi Standby: `[Pin 13: MERAH HIDUP]` | `[Pin 12: HIJAU PADAM]`
  - Tombol Ditekan: `[Pin 13: MERAH PADAM]` | `[Pin 12: HIJAU HIDUP]`
  - Transisi toggle pada Soal 5 terjadi mulus tanpa gejala *flickering* (berkedip liar).

- **Target Respon Bumper Robotika (Soal 6 & 7 - Level 3):**
  - Status Standby: `Motor Kiri = ON (13)`, `Motor Kanan = ON (12)`.
  - Tabrakan Bumper Kiri: `Motor Kiri = OFF`, `Motor Kanan = ON` selama tepat $1000\text{ ms}$.
  - Tabrakan Ganda: Kedua motor `OFF` seketika.

- **Target Output Serial Monitor Telemetri (Soal 8 - Level 4):**
  Buka tab *Serial Monitor* di Wokwi (atur kecepatan `9600 baud`). Setiap penekanan tombol wajib menghasilkan satu baris data tunggal:
  ```text
  {"event":"button_press","count":1,"uptime_ms":1250}
  {"event":"button_press","count":2,"uptime_ms":3410}
  {"event":"button_press","count":3,"uptime_ms":5890}
  ```

---

#### B. Checklist Kesiapan Teknis & Verifikasi Wokwi
- [ ] Mode tombol dikonfigurasi menggunakan konstanta `INPUT_PULLUP` di dalam blok `setup()`.
- [ ] Tombol fisik dihubungkan antara pin digital Arduino (Pin 2) dan pin `GND` (tanpa menyambungkan kabel tombol ke rel $5\text{V}$).
- [ ] Indikator LED tidak pernah terhubung langsung ke pin GPIO tanpa melalui resistor pembatas arus $220\,\Omega$.
- [ ] Evaluasi penekanan tombol memeriksa kondisi logika `LOW` (*Active-LOW*).
- [ ] Algoritma sakelar penahan status (*toggle*) menerapkan deteksi transisi tepi (*falling edge*) dan bebas dari pembacaan ganda akibat getaran kontak (*bouncing*).
- [ ] Tidak ada penggunaan objek dinamis `String` yang memicu kebocoran alokasi memori pada sistem mikrokontroler.

> **Catatan Buku Pegangan Pengajar:**  
> Kunci jawaban implementasi kode program lengkap dan rancangan skematik untuk seluruh butir latihan soal dihimpun secara terpisah pada dokumen panduan instruktur laboratorium (`KUNCI_JAWABAN_PRAKTIKUM_ROBOTICS.md`).

---

### 8. Ringkasan Fungsi & Refleksi Pemahaman `[INTI: SMK & Mahasiswa]`

#### A. Tabel Ringkasan Fungsi C++ Sistem Masukan Digital

| Fungsi C++ / Sintaksis | Kegunaan Utama | Parameter Kritis | Tipe Data & Nilai Kembalian | Karakteristik Elektris / Memori |
| :--- | :--- | :--- | :--- | :--- |
| `pinMode(pin, INPUT_PULLUP)` | Mengonfigurasi pin sebagai input sekaligus mengaktifkan resistor pull-up internal silikon. | `pin`: Nomor GPIO ($0-255$).<br>`mode`: `INPUT_PULLUP`. | Parameter: `uint8_t`<br>Kembalian: `void` | Menghubungkan resistor $20\text{ k}\Omega - 50\text{ k}\Omega$ ke rel $+5\text{V}$, meniadakan fenomena *floating pin*. |
| `digitalRead(pin)` | Memindai tingkat tegangan logika pada terminal pin melalui register PINx. | `pin`: Nomor GPIO yang dipindai. | Parameter: `uint8_t`<br>Kembalian: `int` (`HIGH`/`LOW`) | Ambang batas CMOS: $\text{HIGH} \ge 3.0\text{V}$, $\text{LOW} \le 1.5\text{V}$. Eksekusi instan 1 siklus clock register. |
| `statusSebelumnya == HIGH && statusSekarang == LOW` | Pola deteksi transisi tepi jatuh (*falling edge*). | Nilai pembacaan siklus sebelumnya vs siklus saat ini. | Evaluasi ekspresi logika: `bool` (`true`/`false`) | Kunci sakelar toggle; hanya merespons momen penekanan awal, mengabaikan durasi tombol saat ditahan. |
| `millis()` | Mengambil stempel waktu pewaktu perangkat keras internal sejak sistem menyala. | Tidak membutuhkan parameter (*void*). | Kembalian: `unsigned long` ($0 - 4.294.967.295$) | Pewaktu non-blocking standar industri untuk filter debouncing tanpa menghentikan eksekusi CPU. |

---

#### B. Pertanyaan Refleksi Teknis

1. *Mengapa pada perancangan perangkat keras elektronika industri dan robotika, topologi tombol masukan berbasis resistor pull-up internal (Active-LOW) jauh lebih banyak diadopsi dibandingkan topologi resistor pull-down eksternal (Active-HIGH)? Tinjau dari aspek keandalan komponen mekanis, efisiensi jalur PCB, dan konsumsi arus listrik!*
2. *Perhatikan transfer logika sistem: Bagaimana pola deteksi penekanan tombol di Tema 1 (Otomasi Fasilitas) memiliki struktur algoritma yang identik dengan sensor sentuh tabrakan bumper di Tema 2 (Robotika Sasis) dan sakelar pemicu transmisi telemetri di Tema 3 (IoT)? Bagian kode mana yang tetap sama, dan bagian mana yang beradaptasi sesuai aktuatornya?*
