# LEMBAR SOAL UJIAN PRAKTIKUM 1 (PEMROGRAMAN DESKTOP C# WINFORMS)
**Mata Pelajaran:** Pemrograman Desktop  
**Kelas:** XI RPL / PPLG  
**Materi Uji:** Penanganan Autentikasi Pengguna & Alur Form Aplikasi  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Buat solusi proyek baru pada Visual Studio tipe **Windows Forms App** dengan nama: `PRAKTIKUM1_DESKTOP_NIS_NAMA`.
2. Siapkan 3 buah form: `FormLogin`, `FormRegister`, dan `FormDashboard`.
3. Untuk tata letak komponen form, desain antarmuka dibuat rapi dan proporsional. Penambahan ikon/gambar bersifat "opsional".
4. Hubungkan aplikasi ke basis data `db_kopi_kasir`.
5. Pastikan seluruh fungsi alur form berjalan sesuai ketentuan yang diinstruksikan.

---

## II. DATA DUMMY
Import skrip basis data `database_petugas.sql` ke dalam MySQL.

**Nama Database:** `db_kopi_kasir`  
**Nama Tabel:** `tb_petugas`  

### Struktur Tabel `tb_petugas`:
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_petugas` | INT (Auto Increment, Primary Key) | ID unik petugas |
| `username` | VARCHAR(50) | Username login (Unique) |
| `password` | VARCHAR(100) | Kata sandi akun |
| `nama_petugas` | VARCHAR(100) | Nama lengkap petugas |
| `shift` | ENUM('Pagi', 'Sore', 'Malam') | Giliran kerja |
| `role` | ENUM('Kasir', 'Barista', 'Supervisor') | Posisi petugas |
| `status_aktif` | TINYINT(1) | Status aktif (`1` = Aktif, `0` = Nonaktif) |
| `waktu_dibuat` | TIMESTAMP | Waktu pembuatan akun |

### Data Pengujian Login:
| Username | Password | Shift | Role | Status | Hasil yang Diharapkan Saat Login |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `kasir01` | `pass123` | Pagi | Kasir | 1 (Aktif) | Form login tertutup, diarahkan ke Form Dashboard |
| `barista01` | `seduh456` | Sore | Barista | 1 (Aktif) | Form login tertutup, diarahkan ke Form Dashboard |
| `kasir_cuti` | `istirahat` | Malam | Kasir | 0 (Nonaktif) | Ditolak (muncul warning status nonaktif) |

---

## III. PETUNJUK SOAL

### 1. Form Registrasi Petugas (`FormRegister`)
Membuat form pendaftaran akun mengacu pada kebutuhan "Registrasi Petugas Baru".
* Pastikan semua field wajib diisi, apabila ada yang kosong muncul warning *"Semua field wajib diisi !"*.
* Kata sandi dan ulangi kata sandi harus sesuai, apabila tidak sesuai muncul warning *"Konfirmasi password tidak sesuai !"*.
* Kata sandi minimal 6 karakter dan wajib mengandung kombinasi angka. Apabila tidak sesuai ketentuan, muncul warning *"Password minimal 6 karakter dan wajib mengandung angka !"*.
* Username tidak boleh sama dengan username yang sudah terdaftar di sistem. Apabila username sudah digunakan, muncul warning *"Username sudah digunakan !"*.
* Jika proses pendaftaran berhasil:
  * Data tersimpan ke sistem dengan status aktif otomatis.
  * Muncul pesan informasi *"Registrasi petugas berhasil !"*.
  * Form registrasi tertutup dan user diarahkan kembali ke form login.

---

### 2. Form Login Petugas (`FormLogin`)
Membuat menu masuk aplikasi mengacu pada "Form Login".
* Pastikan semua field wajib diisi, dan apabila tidak sesuai muncul warning *"Username atau password yang anda masukkan tidak sesuai !"*.
* Apabila akun yang dimasukkan memiliki status nonaktif (`status_aktif = 0`), maka proses login ditolak dan muncul warning *"Akun anda sedang dinonaktifkan !"*.
* Jika proses login berhasil, maka form login akan tertutup dan user akan diarahkan pada form berikutnya:
  * Menampilkan `FormDashboard` sesuai identitas petugas yang sedang masuk.

---

### 3. Form Dashboard Petugas (`FormDashboard`)
Membuat menu utama operasional mengacu pada "Form Dashboard".
* **Proteksi Form:**
  * Form dashboard tidak dapat dibuka secara langsung jika proses login belum berhasil dilakukan. Apabila form dibuka tanpa login, muncul warning *"Akses ditolak, silakan login terlebih dahulu !"*, form dashboard tertutup, dan memanggil form login.
* **Tampilan Data:**
  * Memunculkan secara otomatis nama petugas, jadwal shift, dan posisi role petugas yang sedang login pada antarmuka dashboard.
  * Memunculkan secara otomatis daftar aturan SOP kerja kasir pada ListBox secara terurut lengkap dengan nomor urut.
* **Keluar Sistem (Logout):**
  * Ketika tombol "Logout" diklik, form dashboard tertutup dan user diarahkan kembali pada form login.
