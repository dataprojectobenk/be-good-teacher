# Rencana Implementasi: Revisi Pilihan Kelas Formulir Registrasi (XI-PPLG & XII-PPLG)

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:** `src/views/register.php`, `index.php`  
**Tujuan Utama:** Menyesuaikan opsi pilihan kelas pada form pendaftaran siswa baru agar hanya memuat kelas **XI-PPLG** dan **XII-PPLG**, serta menambahkan validasi server-side terkait daftar kelas yang diizinkan.

---

## 1. Analisis Kebutuhan & Ruang Lingkup

1. **Kebutuhan Bisnis / UI**:
   - Pada berkas `src/views/register.php`, dropdown `<select name="kelas">` sebelumnya menyediakan 7 opsi (`XI RPL 1`, `XI RPL 2`, `XI RPL 3`, `XI PPLG 1`, `XI PPLG 2`, `XII RPL 1`, `XII RPL 2`).
   - Sesuai instruksi, opsi tersebut disederhanakan secara eksklusif menjadi 2 pilihan saja:
     1. `XI-PPLG`
     2. `XII-PPLG`
2. **Validasi Server-Side (`index.php`)**:
   - Menambahkan pengecekan *whitelist* nilai kelas pada handler aksi registrasi (`$action === 'register'`) untuk memastikan data yang dikirimkan oleh klien valid dan hanya berasal dari opsi yang telah ditentukan (`XI-PPLG` atau `XII-PPLG`).

---

## 2. Kebutuhan Sistem & Dependensi

- Tidak memerlukan dependensi baru.
- Berjalan di lingkungan PHP 8.2 + MySQL `db_lab_praktikum`.

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                 # Validasi whitelist kelas ['XI-PPLG', 'XII-PPLG'] pada aksi register
└── src/
    └── views/
        └── register.php      # Penyederhanaan elemen <select id="kelas">
```

### Rincian Perubahan Kode:
1. **`src/views/register.php`**:
   ```html
   <select id="kelas" name="kelas" class="form-control" required>
       <option value="">-- Pilih Kelas --</option>
       <option value="XI-PPLG">XI-PPLG</option>
       <option value="XII-PPLG">XII-PPLG</option>
   </select>
   ```
2. **`index.php`**:
   ```php
   $allowedKelas = ['XI-PPLG', 'XII-PPLG'];
   if (!in_array($kelas, $allowedKelas, true)) {
       setFlashMessage('error', 'Pilihan kelas tidak valid. Harap pilih XI-PPLG atau XII-PPLG.');
       header('Location: index.php?page=register');
       exit;
   }
   ```

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Pembaruan Dropdown Kelas pada Tampilan Registrasi**
  - Ubah opsi `<select id="kelas">` di `src/views/register.php` menjadi hanya `XI-PPLG` dan `XII-PPLG`.
- [x] **Fase 2: Penambahan Validasi Whitelist di Backend (`index.php`)**
  - Tambahkan validasi array `$allowedKelas` sebelum query pengecekan NIS dan insert database.
- [x] **Fase 3: Pengujian & Verifikasi Form Registrasi**
  - Akses halaman `index.php?page=register` dan pastikan dropdown hanya menampilkan `XI-PPLG` dan `XII-PPLG`.
  - Uji kirim form dengan kelas valid dan pastikan data tersimpan di basis data.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Data Akun Siswa Lama** | Akun yang sudah terdaftar sebelumnya memiliki format kelas berbeda (`XII RPL 2`) | Pengecekan whitelist hanya diterapkan pada saat pendaftaran akun baru, sehingga tidak mengganggu sesi atau login akun eksisting. |
