# MODUL REKAYASA SISTEM TERTANAM & ROBOTIKA
## Tahap 1: Fondasi Digital Output, Siklus Hidup Mikrokontroler, & Simulasi Wokwi
### Modul 01: Kendali Sinyal Digital Biner, Manajemen Waktu Dasar, & Proteksi Arus LED

---

### 1. Pengantar Konsep Rekayasa

#### A. Komputasi Fisik (*Physical Computing*) & Peran Mikrokontroler
Sistem komputer konvensional (seperti laptop atau peladen) berinteraksi dengan manusia melalui antarmuka grafis, papan ketik, dan tetikus. Sebaliknya, **komputasi fisik** adalah paradigma komputasi yang berinteraksi langsung dengan dunia fisik nyata melalui pembacaan besaran analog/digital (sensor) dan pengendalian energi fisik (aktuator seperti motor, katup selenoida, dan relai).

Inti dari sistem komputasi fisik adalah **mikrokontroler**. Berbeda dengan mikroprosesor murni yang hanya bertindak sebagai unit pemroses sentral (CPU) dan membutuhkan chip memori eksternal (RAM, ROM) serta periferal terpisah di motherboard, sebuah mikrokontroler merupakan **System on Chip (SoC)** mandiri. 

Pada papan pengembangan **Arduino Uno R3**, mikrokontroler yang digunakan adalah **ATmega328P** berbasis arsitektur 8-bit AVR RISC (*Reduced Instruction Set Computer*). Di dalam satu chip fisik silikon hitam tersebut telah terintegrasi:
1. **CPU 8-bit** berkecepatan osilator kristal $16\text{ MHz}$ ($16$ juta siklus instruksi per detik).
2. **Memori Program Flash $32\text{ KB}$** untuk menyimpan kode biner hasil kompilasi.
3. **Memori Data Statis SRAM $2\text{ KB}$** untuk menyimpan variabel runtime yang sangat terbatas.
4. **Memori Non-Volatile EEPROM $1\text{ KB}$** untuk penyimpanan konfigurasi permanen.
5. **Periferal I/O Terintegrasi**: $14$ jalur General-Purpose Input/Output (GPIO), modul Analog-to-Digital Converter (ADC) 10-bit, pengatur waktu (*hardware timer/counter*), dan unit komunikasi serial UART.

```
+-----------------------------------------------------------------------------------+
|               ARSITEKTUR INTERNAL MIKROKONTROLER ATMEGA328P                      |
+-----------------------------------------------------------------------------------+
|                                                                                   |
|    +-----------------------+     +------------------------+                       |
|    |  FLASH MEMORY (32 KB) |     |     SRAM (2 KB)        |                       |
|    |   [Instruksi Program] |     | [Variabel / Stack/Heap]|                       |
|    +-----------+-----------+     +-----------+------------+                       |
|                |                             |                                    |
|                +--------------+--------------+                                    |
|                               |                                                   |
|                               v                                                   |
|                    +--------------------+                                         |
|                    |     CPU CORE       | <--- Kristal Clock 16 MHz               |
|                    |   (8-bit AVR RISC) |                                         |
|                    +---------+----------+                                         |
|                              |                                                    |
|                +-------------+-------------+                                      |
|                |                           |                                      |
|                v                           v                                      |
|     +--------------------+       +--------------------+                           |
|     |  REGISTER PORT I/O |       | HARDWARE TIMERS    |                           |
|     | (PORTB, PORTC, D)  |       | (Timer 0, 1, 2)    |                           |
|     +----------+---------+       +--------------------+                           |
|                |                                                                  |
|                v                                                                  |
|     [ Pin GPIO Fisik D0 - D13 & A0 - A5 ] ---> Komponen Luar (LED, Sensor, Driver)|
|                                                                                   |
+-----------------------------------------------------------------------------------+
```

---

#### B. Logika Tegangan Digital Biner (TTL $5\text{V}$)
Pin GPIO mikrokontroler beroperasi menggunakan logika biner dengan standar level tegangan Transistor-Transistor Logic (TTL):
- **Logika $1$ (`HIGH`):** Terminal pin dihubungkan oleh transistor switching internal ke rail catu daya positif $+5.0\text{V}$ DC. Pada status ini, pin bertindak sebagai sumber arus (*current source*) yang dapat menyuplai daya listrik ke beban luar.
- **Logika $0$ (`LOW`):** Terminal pin dihubungkan ke bidang referensi tanah (*Ground* / GND) dengan potensial $0.0\text{V}$ DC. Pada status ini, pin bertindak sebagai penyerap arus (*current sink*).

Tegangan di antara ambang batas (misalnya $1.5\text{V} - 2.5\text{V}$) merupakan kondisi mengambang (*floating state*) yang tidak terdefinisi dan wajib dihindari dalam rekayasa digital.

---

#### C. Siklus Hidup Eksekusi Mikrokontroler (`setup()` vs `loop()`)
Program pada komputer berbasis sistem operasi (seperti Linux atau Windows) memiliki titik henti (*exit code* 0). Namun pada sistem tertanam (*bare-metal* tanpa sistem operasi), mikrokontroler tidak boleh pernah berhenti berjalan selama arus listrik tetap mengalir.

Arsitektur program mikrokontroler dibagi menjadi dua fase mutlak:

```cpp
void setup() {
  // Fase Inisialisasi: Dieksekusi TEPAT SATU KALI saat mikrokontroler dinyalakan (Power-On)
  // atau saat tombol RESET ditekan.
}

void loop() {
  // Fase Operasional: Dieksekusi BERULANG KALI SECARA SIKLIS TANPA HENTI
  // setelah blok setup() selesai dijalankan.
}
```

1. **Fungsi `setup()`**: Digunakan secara ketat untuk konfigurasi arah register I/O (`pinMode`), inisialisasi laju transmisi baud rate komunikasi serial, pemasangan interrupt, dan inisialisasi pustaka perangkat keras.
2. **Fungsi `loop()`**: Berisi mesin status (*finite state machine*), pemindaian berkala sensor, evaluasi logika kontrol, dan penulisan status aktuator. Ketika instruksi baris terakhir di dalam `loop()` selesai dieksekusi, CPU secara otomatis melompat kembali ke baris pertama `loop()`.

---

### 2. Kamus Teknis: Fungsi dan Argumen

---

#### 1. `pinMode()`
* **Fungsi**:  
  Mengonfigurasi mode kerja fungsional dari satu pin GPIO fisik tertentu dengan memanipulasi bit pada register pengarah data (*Data Direction Register* / DDR) mikrokontroler. Konfigurasi ini menentukan apakah transistor penggerak pin disiapkan untuk memompa arus keluar (*OUTPUT*) atau sebagai gerbang berimpedansi tinggi untuk mendeteksi tegangan dari luar (*INPUT*).

* **Format Sintaksis**:
  ```cpp
  void pinMode(uint8_t pin, uint8_t mode);
  ```

* **Argumen yang Tersedia**:
  * **Argumen 1 (`pin`)**:
    * **Tipe Data**: `uint8_t` (bilangan bulat tak bertanda 8-bit, rentang nilai: $0$ sampai $255$).
    * **Peran**: Menentukan nomor pin fisik GPIO yang hendak dikonfigurasi.
    * **Nilai yang Valid**:
      - Pin Digital: $0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13$. *(Catatan teknis: Pin 0 dan 1 terhubung ke modul UART RX/TX; hindari penggunaan kedua pin ini untuk beban output agar tidak mengganggu pemrograman via USB)*.
      - Pin Analog yang dialihfungsikan sebagai Digital: `A0, A1, A2, A3, A4, A5`.
  * **Argumen 2 (`mode`)**:
    * **Tipe Data**: `uint8_t`.
    * **Peran**: Menentukan karakteristik impedansi dan arah aliran sinyal terminal gerbang GPIO.
    * **Pilihan Konstanta Sah**:
      - `OUTPUT`: Mengonfigurasi pin ke mode impedansi rendah (*low impedance*). Pin mampu mengalirkan (*source*) atau menyerap (*sink*) arus beban hingga batas aman desain ($20\text{ mA}$, maksimum absolut $40\text{ mA}$).
      - `INPUT`: Mengonfigurasi pin ke mode impedansi tinggi (*high impedance*, resistansi internal $>100\text{ M}\Omega$). Pin hanya mengukur keberadaan tegangan luar dengan konsumsi arus mendekati nol.
      - `INPUT_PULLUP`: Mengonfigurasi pin sebagai input sekaligus mengaktifkan resistor *pull-up* internal silikon ($20\text{ k}\Omega - 50\text{ k}\Omega$) yang terhubung langsung ke rel $+5\text{V}$, mencegah pin berada dalam status mengambang (*floating*).

---

#### 2. `digitalWrite()`
* **Fungsi**:  
  Menuliskan status logika biner tinggi (`HIGH`) atau rendah (`LOW`) pada pin GPIO digital yang sebelumnya **wajib** telah dikonfigurasi sebagai `OUTPUT`. Di tingkat arsitektur mikro, fungsi ini mengubah status bit pada register keluaran data (PORTB, PORTC, atau PORTD).

* **Format Sintaksis**:
  ```cpp
  void digitalWrite(uint8_t pin, uint8_t value);
  ```

* **Argumen yang Tersedia**:
  * **Argumen 1 (`pin`)**:
    * **Tipe Data**: `uint8_t`.
    * **Peran**: Menentukan nomor pin keluaran target yang hendak diubah tingkat tegangannya.
    * **Nilai yang Valid**: $0$ hingga $13$, serta pin analog $A0$ hingga $A5$.
  * **Argumen 2 (`value`)**:
    * **Tipe Data**: `uint8_t`.
    * **Peran**: Menentukan tingkat tegangan keluaran terminal fisik.
    * **Pilihan Konstanta Sah**:
      - `HIGH` (Konstanta numerik integer `1`): Menghubungkan pin ke rel catu daya $+5.0\text{V}$ DC melalui transistor internal.
      - `LOW` (Konstanta numerik integer `0`): Menghubungkan pin ke ground $0.0\text{V}$ DC.

---

#### 3. `delay()`
* **Fungsi**:  
  Menghentikan eksekusi aliran instruksi CPU selama durasi waktu tertentu. Fungsi ini bekerja dengan cara menjalankan perulangan instruksi kosong (*no-operation counting loop*) secara internal berdasarkan frekuensi osilator kristal hingga jumlah siklus clock terpenuhi.

* **Format Sintaksis**:
  ```cpp
  void delay(unsigned long ms);
  ```

* **Argumen yang Tersedia**:
  * **Argumen 1 (`ms`)**:
    * **Tipe Data**: `unsigned long` (bilangan bulat tak bertanda 32-bit, rentang nilai: $0$ hingga $4.294.967.295$).
    * **Peran**: Menentukan durasi penundaan eksekusi program dalam satuan milidetik ($1\text{ detik} = 1000\text{ milidetik}$).
    * **Nilai yang Valid**: Bilangan bulat non-negatif (contoh: `1000` untuk jeda $1$ detik, `500` untuk jeda setengah detik).

* **Peringatan Rekayasa (*CPU Blocking Alert*)**:  
  Fungsi `delay()` bersifat **blocking**. Selama fungsi ini berjalan, CPU tidak melakukan pekerjaan lain: mikrokontroler tidak dapat memproses pembacaan tombol interupsi, tidak dapat memindai sensor ultrasonik, dan tidak dapat mengontrol motor. Pada tahap awal pemahaman digital output, `delay()` digunakan untuk demonstrasi ritme waktu dasar. Pada modul lanjutan, kita akan mempelajari arsitektur pewaktu non-blocking (*non-blocking timer*) berbasis fungsi `millis()`.

---

### 3. Implementasi Kode Bersih (*Clean Code*)

Berikut adalah implementasi kode program C++ yang memenuhi kaidah rekayasa sistem tertanam standar industri:
1. **Hemat Alokasi Memori**: Penamaan pin menggunakan tipe konstanta `const uint8_t` (memakan $1$ byte memori Flash, $0$ byte SRAM), bukan tipe `int` (yang memakan $2$ byte memori SRAM AVR).
2. **Deklaratif**: Penamaan variabel konstan menggunakan huruf kapital (*Screaming Snake Case*) yang mencerminkan fungsi perangkat keras secara eksplisit.
3. **Struktur Terisolasi**: Modul kontrol terpisah rapi antara fungsi konfigurasi dan fungsi siklis.

```cpp
// ===================================================================================
// PROGRAM      : KONTROL DIGITAL OUTPUT LED BLINKING PRESISI
// PLATFORM     : ARDUINO UNO R3 (ATMEGA328P) & WOKWI SIMULATOR
// STANDAR KODE : EMBEDDED C++ CLEAN CODE (AVR MEMORY-CONSCIOUS)
// ===================================================================================

// Deklarasi konstanta alokasi pin hardware menggunakan tipe hemat memori (1 byte)
const uint8_t PIN_LED_UTAMA = 13;

// Deklarasi konstanta interval durasi waktu (dalam satuan milidetik)
const unsigned long DURASI_NYALA_MS = 1000;  // 1000 ms = 1.0 detik
const unsigned long DURASI_PADAM_MS = 1000;  // 1000 ms = 1.0 detik

void setup() {
  // Inisialisasi register pengarah data (DDR): Konfigurasi Pin 13 sebagai OUTPUT
  pinMode(PIN_LED_UTAMA, OUTPUT);

  // Pastikan status awal LED dalam kondisi mati terdefinisi (LOW / 0 Volt)
  digitalWrite(PIN_LED_UTAMA, LOW);
}

void loop() {
  // 1. Alirkan tegangan +5V ke Pin 13 untuk menyalakan LED
  digitalWrite(PIN_LED_UTAMA, HIGH);

  // 2. Tahan status nyala selama 1000 milidetik
  delay(DURASI_NYALA_MS);

  // 3. Putuskan tegangan (0V / Ground) pada Pin 13 untuk memadamkan LED
  digitalWrite(PIN_LED_UTAMA, LOW);

  // 4. Tahan status padam selama 1000 milidetik
  delay(DURASI_PADAM_MS);
}
```

---

### 4. Skema Perakitan Virtual di Wokwi

#### A. Tabel Pemetaan Pinout Sirkuit

| Nama Komponen Fisik | Terminal Komponen | Sambungan ke Arduino Uno | Fungsi Jalur / Tegangan |
| :--- | :--- | :--- | :--- |
| **Papan Arduino Uno R3** | Header Pin `13` | Terminal Resistor 1 | Jalur Sinyal Digital Output ($5\text{V} / 0\text{V}$) |
| **Papan Arduino Uno R3** | Header Pin `GND` | Katoda LED (Kaki Pendek) | Jalur Referensi Tanah ($0\text{V}$) |
| **Resistor Film Karbon** | Kaki Terminal 1 | Pin `13` Arduino Uno | Pembatas Arus Masuk (*Current Limiter*) |
| **Resistor Film Karbon** | Kaki Terminal 2 | Anoda LED (Kaki Panjang) | Arus Teregulasi Masuk ke Dioda LED |
| **LED 5mm Merah** | Anoda (Kaki Panjang / $+$) | Kaki Terminal 2 Resistor | Sisi Masuk Arus Positif |
| **LED 5mm Merah** | Katoda (Kaki Pendek / $-$) | Pin `GND` Arduino Uno | Jalur Pembuangan Arus Kembali ke Sumber |

---

#### B. Diagram Jalur Skematik Rangkaian (ASCII Circuit)

```
        +5V Sinyal Terkendali
     [Arduino Pin 13]
            |
            | (Kabel Jumper)
            v
       +---------+
       | Resistor|  R = 220 Ohm (Merah-Merah-Cokelat-Emas)
       | 220 Ω   |  Menahan arus berlebih agar tidak merusak LED & Pin
       +---------+
            |
            v
         +------+
         | Anoda| (+) Kaki Panjang LED
       +----------+
       |   LED    | Dioda Emisi Cahaya Merah (Drop Tegangan Vf = 2.0 Volt)
       +----------+
         | Katoda | (-) Kaki Pendek LED (Sisi Berlekuk / Flat Rim)
         +------+
            |
            | (Kabel Jumper)
            v
     [Arduino GND]  Bidang Referensi Tanah (0 Volt)
```

---

#### C. Langkah-Langkah Perakitan di Simulator Wokwi (`wokwi.com`)
1. Buka peramban web dan arahkan ke simulator: `https://wokwi.com/projects/new/arduino-uno`.
2. Klik tombol **"+"** (*Add a new part*) di panel atas kanvas simulasi.
3. Tambahkan komponen berikut ke kanvas:
   - $1$ unit **Resistor**
   - $1$ unit **LED** (pilih warna Merah)
4. Konfigurasikan resistansi: Klik pada komponen resistor, ubah nilai resistansinya menjadi `220` ohm (pastikan satuan bukan kilo-ohm).
5. Sambungkan kabel sirkuit virtual:
   - Tarik garis koneksi dari pin **13** pada papan Arduino Uno ke salah satu kaki **Resistor**.
   - Tarik garis koneksi dari kaki resistor yang satunya ke kaki **Anode** (Anoda) pada LED.
   - Tarik garis koneksi dari kaki **Cathode** (Katoda) pada LED ke pin **GND** pada papan Arduino Uno.
6. Salin kode implementasi C++ bersih dari Bagian 3 ke tab penyunting `sketch.ino`.
7. Tekan tombol **Play** (ikon panah segitiga hijau) untuk memulai simulasi waktu nyata. Perhatikan LED merah akan berkedip dengan interval presisi $1$ detik menyala dan $1$ detik padam.

---

#### D. Analisis Rangkaian Elektronika & Perhitungan Hukum Ohm

> [!CAUTION]
> **Peringatan Kerusakan Perangkat Keras (*Hardware Destruction Warning*):**  
> Dilarang keras menghubungkan kaki anoda LED langsung ke pin GPIO $5\text{V}$ tanpa melewati resistor pembatas arus! LED adalah komponen semikonduktor non-linear. Tanpa resistor, resistansi internal LED mendekati nol saat mencapai tegangan konduksi, yang akan memicu lonjakan arus (*short-circuit condition*) melampaui batas maksimum absolut pin mikrokontroler ($40\text{ mA}$). Hal ini akan menghanguskan sambungan PN pada LED dan membakar transistor gerbang I/O chip ATmega328P secara permanen.

Untuk menentukan nilai resistansi yang aman dan optimal, kita menerapkan **Hukum Tegangan Kirchhoff (Kirchhoff's Voltage Law / KVL)** dan **Hukum Ohm**:

$$V = I \times R \iff R = \frac{V}{I}$$

**Parameter Komponen Fisik:**
1. Tegangan Sumber Pasokan Mikrokontroler ($V_s$): $+5.0\text{ Volt}$.
2. Tegangan Maju LED Merah Standar (*Forward Voltage Drop* / $V_f$): $+2.0\text{ Volt}$.
3. Target Arus Maju Kerja Efisien (*Forward Current* / $I_f$): $15\text{ mA} = 0.015\text{ Ampere}$ (batas aman standar operasional diode $5\text{ mm}$ agar terang optimal dan dingin).

**Perhitungan Penurunan Tegangan pada Resistor ($V_R$):**
$$V_R = V_s - V_f = 5.0\text{V} - 2.0\text{V} = 3.0\text{ Volt}$$

Tegangan sebesar $3.0\text{ Volt}$ inilah yang harus diredam sepenuhnya oleh resistor pembatas arus.

**Perhitungan Nilai Resistansi ($R$):**
$$R = \frac{V_R}{I_f} = \frac{3.0\text{ Volt}}{0.015\text{ Ampere}} = 200\,\Omega$$

Dalam standar deret resistansi komersial internasional (E12 series), nilai resistor terdekat yang tersedia di atas $200\,\Omega$ adalah **$220\,\Omega$** (kode warna: Merah - Merah - Cokelat - Emas).

**Verifikasi Arus Nyata pada Nilai $220\,\Omega$:**
$$I_{\text{aktual}} = \frac{3.0\text{ Volt}}{220\,\Omega} \approx 0.0136\text{ Ampere} = 13.6\text{ mA}$$
Arus sebesar $13.6\text{ mA}$ berada sangat ideal di bawah batas aman $20\text{ mA}$, menjaga chip ATmega328P tetap dingin dan memperpanjang umur pakai diode LED.

**Perhitungan Disipasi Daya Termal Resistor ($P$):**
$$P = I^2 \times R = (0.0136\text{A})^2 \times 220\,\Omega \approx 0.0407\text{ Watt} = 40.7\text{ mW}$$
Karena daya yang dilepaskan hanya $40.7\text{ mW}$, resistor standar berdaya $1/4\text{ Watt}$ ($250\text{ mW}$) bekerja sangat aman jauh di bawah kapasitas termal maksimalnya.

---

### 5. Lembar Latihan & Penguatan Logika

---

#### Latihan 1: Manipulasi Nilai Argumen & Eksperimen *Duty Cycle* Asimetris
* **Tujuan Rekayasa**:  
  Memahami pengaruh manipulasi parameter argumen `ms` pada fungsi `delay()` terhadap ritme kerja visual (*duty cycle* dan frekuensi).
* **Tantangan Tugas**:
  1. Ubah durasi nyala menjadi sangat singkat ($100\text{ ms}$) dan durasi padam menjadi panjang ($900\text{ ms}$). Amati perilaku LED di Wokwi.
  2. Hitung frekuensi kerja sistem:
     $$\text{Periode } (T) = \text{Waktu Nyala} + \text{Waktu Padam} = 100\text{ ms} + 900\text{ ms} = 1000\text{ ms} = 1.0\text{ detik}$$
     $$\text{Frekuensi } (f) = \frac{1}{T} = \frac{1}{1.0\text{ s}} = 1\text{ Hz}$$
     $$\text{Duty Cycle} = \frac{\text{Waktu Nyala}}{T} \times 100\% = \frac{100\text{ ms}}{1000\text{ ms}} \times 100\% = 10\%$$
  3. Lakukan uji coba kedua dengan frekuensi tinggi: ubah durasi nyala menjadi $10\text{ ms}$ dan durasi padam menjadi $10\text{ ms}$ ($T = 20\text{ ms}$, $f = 50\text{ Hz}$).
  4. **Analisis Refleksi**: Mengapa pada $50\text{ Hz}$ mata manusia tidak lagi melihat kedipan terputus melainkan cahaya redup konstan? *(Konsep: Persistence of Vision / Retensi Penglihatan Retina Manusia)*.

---

#### Latihan 2: Penambahan Komponen & Sistem Strobo Dua Kanal (*Flip-Flop*)
* **Tujuan Rekayasa**:  
  Mengembangkan sirkuit dengan menambahkan kanal periferal output kedua dan menyusun logika kendali fase terbalik (*anti-phase switching*).

```
+-----------------------------------------------------------------------------------+
|               SKEMA DUAL KANAL DIGITAL OUTPUT FLIP-FLOP                           |
+-----------------------------------------------------------------------------------+
|    [Arduino Pin 13] ---> [Resistor 220Ω] ---> [LED Merah (Anoda)]                 |
|    [Arduino Pin 12] ---> [Resistor 220Ω] ---> [LED Kuning (Anoda)]                |
|    [Arduino GND]    ---> [Katoda Bersama Kedua LED]                               |
+-----------------------------------------------------------------------------------+
```

* **Instruksi Perakitan di Wokwi**:
  1. Tambahkan $1$ buah resistor $220\,\Omega$ baru dan $1$ buah LED Kuning.
  2. Sambungkan Pin **12** Arduino Uno ke Resistor kedua $\rightarrow$ Anoda LED Kuning.
  3. Sambungkan Katoda LED Kuning ke jalur ground bersama (Pin `GND`).
* **Spesifikasi Kode C++ Bersih**:
  Tuliskan kode program agar kedua LED menyala bergantian secara bolak-balik setiap $500\text{ ms}$:
  - Saat LED Merah menyala, LED Kuning **wajib** padam.
  - Saat LED Kuning menyala, LED Merah **wajib** padam.

```cpp
// Contoh Solusi Arsitektur Kode Latihan 2
const uint8_t PIN_LED_MERAH  = 13;
const uint8_t PIN_LED_KUNING = 12;

const unsigned long INTERVAL_FLIP_FLOP_MS = 500;

void setup() {
  pinMode(PIN_LED_MERAH, OUTPUT);
  pinMode(PIN_LED_KUNING, OUTPUT);

  digitalWrite(PIN_LED_MERAH, LOW);
  digitalWrite(PIN_LED_KUNING, LOW);
}

void loop() {
  // Fase 1: Merah Nyala, Kuning Padam
  digitalWrite(PIN_LED_MERAH, HIGH);
  digitalWrite(PIN_LED_KUNING, LOW);
  delay(INTERVAL_FLIP_FLOP_MS);

  // Fase 2: Merah Padam, Kuning Nyala
  digitalWrite(PIN_LED_MERAH, LOW);
  digitalWrite(PIN_LED_KUNING, HIGH);
  delay(INTERVAL_FLIP_FLOP_MS);
}
```

---

#### Latihan 3: Studi Kasus Otomasi Nyata: Prototipe Kendali Lampu Lalu Lintas 3 Fase
* **Skenario Sistem Rekayasa**:  
  Sebagai pengembang otomasi sistem transportasi kota pintar, santri diminta merancang mesin kendali sekuensial untuk lampu lalu lintas persimpangan jalan satu arah menggunakan tiga indikator LED:
  - **LED Merah** (Terhubung ke Pin `13` melalui resistor $220\,\Omega$).
  - **LED Kuning** (Terhubung ke Pin `12` melalui resistor $220\,\Omega$).
  - **LED Hijau** (Terhubung ke Pin `11` melalui resistor $220\,\Omega$).

* **Tabel Urutan Siklus Lalu Lintas (*State Transition Table*)**:

| Fase | Status LED Merah | Status LED Kuning | Status LED Hijau | Durasi Waktu | Makna Rekayasa |
| :---: | :---: | :---: | :---: | :---: | :--- |
| **Fase 1** | `HIGH` ($5\text{V}$) | `LOW` ($0\text{V}$) | `LOW` ($0\text{V}$) | $5000\text{ ms}$ | Seluruh kendaraan wajib berhenti penuh. |
| **Fase 2** | `HIGH` ($5\text{V}$) | `HIGH` ($5\text{V}$) | `LOW` ($0\text{V}$) | $2000\text{ ms}$ | Persiapan transmisi gerak kendaraan. |
| **Fase 3** | `LOW` ($0\text{V}$) | `LOW` ($0\text{V}$) | `HIGH` ($5\text{V}$) | $5000\text{ ms}$ | Jalur terbuka aman untuk melaju. |
| **Fase 4** | `LOW` ($0\text{V}$) | `HIGH` ($5\text{V}$) | `LOW` ($0\text{V}$) | $2000\text{ ms}$ | Peringatan perlambatan menjelang berhenti. |

* **Tantangan Rekayasa Kode Bersih (Arsitektur Modular)**:  
  Buatlah fungsi bantuan terpisah `aturLampu(bool statusMerah, bool statusKuning, bool statusHijau)` agar blok `loop()` terbaca bersih dan terstruktur tanpa pengulangan baris `digitalWrite` berulang kali!

```cpp
// ===================================================================================
// SOLUSI ARSITEKTUR TINGKAT TINGGI: TRAFFIC LIGHT CONTROLLER MODULAR
// ===================================================================================

const uint8_t PIN_MERAH  = 13;
const uint8_t PIN_KUNING = 12;
const uint8_t PIN_HIJAU  = 11;

// Prototipe fungsi bantuan modular
void aturLampu(bool statusMerah, bool statusKuning, bool statusHijau) {
  digitalWrite(PIN_MERAH, statusMerah ? HIGH : LOW);
  digitalWrite(PIN_KUNING, statusKuning ? HIGH : LOW);
  digitalWrite(PIN_HIJAU, statusHijau ? HIGH : LOW);
}

void setup() {
  pinMode(PIN_MERAH, OUTPUT);
  pinMode(PIN_KUNING, OUTPUT);
  pinMode(PIN_HIJAU, OUTPUT);

  // Status awal seluruh lampu dimatikan
  aturLampu(false, false, false);
}

void loop() {
  // Fase 1: Kendaraan Berhenti (Merah Aktif)
  aturLampu(true, false, false);
  delay(5000);

  // Fase 2: Persiapan Maju (Merah & Kuning Aktif)
  aturLampu(true, true, false);
  delay(2000);

  // Fase 3: Kendaraan Melaju (Hijau Aktif)
  aturLampu(false, false, true);
  delay(5000);

  // Fase 4: Peringatan Berhenti (Kuning Aktif)
  aturLampu(false, true, false);
  delay(2000);
}
```

---

### Rangkuman Cepat Modul 01

1. **Mikrokontroler ATmega328P** mengeksekusi instruksi program secara siklis berkelanjutan dalam kecepatan $16\text{ MHz}$ tanpa sistem operasi.
2. **`pinMode(pin, mode)`** wajib didefinisikan satu kali di dalam `setup()` untuk mengatur arah register data GPIO (mode `OUTPUT` untuk mengontrol beban arus).
3. **`digitalWrite(pin, value)`** mengalirkan tegangan $+5\text{V}$ (`HIGH`) atau $0\text{V}$ (`LOW`) ke terminal fisik.
4. **Resistor Pembatas Arus** ($220\,\Omega$) adalah komponen pengaman mutlak berdasarkan **Hukum Ohm** ($V = I \times R$) untuk mencegah kerusakan termal pada diode LED dan pin mikrokontroler.
5. **Prinsip *Clean Code Embedded***: Selalu gunakan `const uint8_t` untuk nomor pin demi menghemat alokasi memori SRAM mikrokontroler yang terbatas.
