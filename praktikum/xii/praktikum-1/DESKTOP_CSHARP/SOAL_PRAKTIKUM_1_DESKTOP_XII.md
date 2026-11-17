# LEMBAR SOAL UJIAN PRAKTIKUM 1 (PEMROGRAMAN DESKTOP C# WINFORMS)
**Mata Pelajaran:** Pemrograman Desktop  
**Kelas:** XII RPL / PPLG  
**Materi Uji:** Registrasi Petugas Apotek, Login, dan Proteksi Form Operasional (C# Windows Forms)  
**Studi Kasus:** Sistem Petugas & Kasir "Apotek FarmaMedika"  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Buat solusi proyek baru pada Visual Studio tipe **Windows Forms App** dengan nama: `PRAKTIKUM1_DESKTOP_XII_NIS_NAMA`.
2. Siapkan 3 buah form: `FormLogin`, `FormRegister`, dan `FormDashboard`.
3. Tata letak komponen form dirancang rapi dan proporsional. Penambahan ikon/gambar bersifat "opsional".
4. Hubungkan aplikasi ke basis data `db_apotek_petugas`.
5. Pastikan seluruh alur sistem berjalan sesuai spesifikasi kebutuhan yang ditentukan.

---

## II. DATA DUMMY
Import skrip basis data `database_apoteker.sql` ke dalam MySQL.

**Nama Database:** `db_apotek_petugas`  
**Nama Tabel:** `tb_apoteker`  

### Struktur Tabel `tb_apoteker`:
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_petugas` | INT (Auto Increment, Primary Key) | ID unik petugas apotek |
| `username` | VARCHAR(50) | Username login (Unique) |
| `password` | VARCHAR(100) | Kata sandi akun |
| `nama_petugas` | VARCHAR(100) | Nama lengkap petugas |
| `shift` | ENUM('Pagi', 'Siang', 'Malam') | Giliran tugas apotek |
| `role` | ENUM('Apoteker', 'Asisten Apoteker', 'Kasir Apotek') | Posisi penugasan |
| `status_aktif` | TINYINT(1) | Status akun (`1` = Aktif, `0` = Nonaktif) |
| `created_at` | TIMESTAMP | Waktu pendaftaran akun |

### Data Pengujian Login:
| Username | Password | Shift | Role | Status | Hasil yang Diharapkan Saat Login |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `apt01` | `resep123` | Pagi | Apoteker | 1 (Aktif) | Form login tertutup, diarahkan ke Form Dashboard |
| `kasir_apt` | `bayar456` | Siang | Kasir Apotek | 1 (Aktif) | Form login tertutup, diarahkan ke Form Dashboard |
| `apt_cuti` | `istirahat` | Malam | Asisten Apoteker | 0 (Nonaktif) | Ditolak (muncul warning status nonaktif) |

---

## III. PETUNJUK SOAL

### 1. Form Registrasi Petugas Apotek (`FormRegister`)
Membuat form pendaftaran petugas mengacu pada kebutuhan "Registrasi Tenaga Farmasi Baru".
* Pastikan semua field wajib diisi, apabila ada yang kosong muncul warning *"Semua field wajib diisi !"*.
* Kata sandi dan konfirmasi kata sandi harus sesuai, apabila tidak sesuai muncul warning *"Konfirmasi password tidak sesuai !"*.
* Kata sandi minimal 6 karakter dan wajib mengandung kombinasi angka. Apabila tidak memenuhi ketentuan, muncul warning *"Password minimal 6 karakter dan wajib mengandung angka !"*.
* Username tidak boleh sama dengan username yang sudah terdaftar di sistem apotek. Apabila duplikat, muncul warning *"Username sudah digunakan !"*.
* Jika pendaftaran berhasil:
  * Data tersimpan ke sistem dengan status aktif otomatis.
  * Muncul pesan informasi *"Registrasi petugas apotek berhasil !"*.
  * Form registrasi tertutup dan user diarahkan kembali ke form login.

---

### 2. Form Login Petugas Apotek (`FormLogin`)
Membuat menu masuk aplikasi mengacu pada "Form Login Petugas".
* Pastikan semua field wajib diisi, dan apabila data tidak sesuai muncul warning *"Username atau password yang anda masukkan tidak sesuai !"*.
* Apabila akun petugas yang dimasukkan berstatus nonaktif (`status_aktif = 0`), maka proses login ditolak dan muncul warning *"Akun petugas Anda sedang dinonaktifkan !"*.
* Jika proses login berhasil, maka form login akan tertutup dan user diarahkan pada form berikutnya:
  * Menampilkan `FormDashboard` operasional apotek dengan memunculkan identitas petugas yang sedang aktif.

---

### 3. Form Dashboard Operasional Apotek (`FormDashboard`)
Membuat menu utama operasional apotek mengacu pada "Form Dashboard Apotek".
* **Proteksi Akses Form:**
  * Form dashboard tidak dapat dibuka secara langsung jika proses login belum berhasil dilakukan. Apabila form dibuka tanpa otentikasi login, muncul warning *"Akses ditolak, silakan login terlebih dahulu !"*, form dashboard tertutup, dan memanggil form login kembali.
* **Tampilan Data Petugas:**
  * Memunculkan secara otomatis nama lengkap petugas, jadwal shift, dan peran jabatan farmasi yang sedang login pada label antarmuka form.
  * Memunculkan secara otomatis daftar Standar Operasional Prosedur (SOP) dispensing obat pada ListBox secara berurutan lengkap dengan penomoran.
* **Keluar Sistem (Logout):**
  * Ketika tombol "Logout" diklik, form dashboard tertutup dan user diarahkan kembali pada form login.
