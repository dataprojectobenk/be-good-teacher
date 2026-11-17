# Rencana Implementasi: Revisi Reset Password Berbasis NIS Mandiri

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:** `src/views/reset_password.php`, `index.php`  
**Tujuan Utama:** Menyederhanakan formulir dan verifikasi reset kata sandi mandiri siswa agar hanya menggunakan **NIS (Nomor Induk Siswa)** sebagai parameter identifikasi tunggal, menghapus input nama dan kelas yang rentan mengalami *typo* (salah pengetikan).

---

## 1. Analisis Kebutuhan & Ruang Lingkup

1. **Latar Belakang Masalah**:
   - Pengetikan nama lengkap seringkali mengalami ketidakcocokan format (misalnya huruf kapital/kecil, spasi ganda, singkatan nama, tanda titik) sehingga menyulitkan siswa yang ingin mereset kata sandi mandiri.
2. **Kebutuhan Bisnis & Pengguna**:
   - NIS merupakan identitas unik (*unique identifier*) setiap siswa di basis data.
   - Formulir reset kata sandi mandiri hanya meminta:
     1. **NIS (Nomor Induk Siswa)**
     2. **Kata Sandi Baru** (minimal 6 karakter)
     3. **Konfirmasi Kata Sandi Baru**
   - Menghapus kolom input `nama` dan dropdown `kelas` dari tampilan formulir.
3. **Batasan Keamanan**:
   - Tetap memberlakukan proteksi `role === 'siswa'` agar akun `admin` tidak dapat direset melalui formulir ini.

---

## 2. Kebutuhan Sistem & Dependensi

- Basis data MySQL `db_lab_praktikum` (tabel `users` kolom `nis` bertipe `UNIQUE`).
- PHP 8.2+ dengan fungsi `password_hash()` BCRYPT.

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                      # Penyederhanaan verifikasi hanya berbasis NIS & role siswa
└── src/
    └── views/
        └── reset_password.php     # Menghapus input nama dan kelas dari formulir UI
```

### Rincian Perubahan Logika:
1. **`src/views/reset_password.php`**:
   - Menghapus elemen input `nama` dan select `kelas`.
   - Hanya menyisakan input `nis`, `new_password`, dan `confirm_password`.
2. **`index.php` (Aksi `reset_password`)**:
   - Validasi kelengkapan `nis`, `new_password`, dan `confirm_password`.
   - Validasi `new_password === confirm_password` dan `strlen >= 6`.
   - Kueri akun:
     ```php
     $stmtCek = $pdo->prepare("SELECT id, nis, nama, role FROM users WHERE nis = :nis LIMIT 1");
     $stmtCek->execute([':nis' => $nis]);
     $user = $stmtCek->fetch();
     $stmtCek->closeCursor();

     if (!$user || $user['role'] !== 'siswa') {
         setFlashMessage('error', 'NIS siswa tidak terdaftar di sistem.');
         header('Location: index.php?page=reset_password');
         exit;
     }
     ```
   - Lakukan update password:
     ```php
     $stmtUpdate = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
     $stmtUpdate->execute([
         ':password' => password_hash($newPassword, PASSWORD_BCRYPT),
         ':id' => $user['id']
     ]);
     $stmtUpdate->closeCursor();
     ```
   - Berikan flash message sukses yang menyapa nama siswa: *"Kata sandi untuk [Nama Siswa] (NIS: [NIS]) berhasil diperbarui! Silakan masuk."*

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Pembaruan Tampilan Formulir (`src/views/reset_password.php`)**
  - Hapus input field `nama` dan select dropdown `kelas`.
  - Rapikan layout form agar fokus pada NIS dan Kata Sandi Baru.
- [x] **Fase 2: Refaktorisasi Logika Handler pada `index.php`**
  - Hapus dependensi input `nama` dan `kelas` pada blok `if ($action === 'reset_password')`.
  - Jalankan pencarian user murni berbasis `nis` dan validasi proteksi `role === 'siswa'`.
- [x] **Fase 3: Verifikasi & Pengujian Fungsional**
  - Uji penolakan jika memasukkan NIS admin.
  - Uji penolakan jika NIS tidak terdaftar.
  - Uji sukses reset kata sandi menggunakan NIS siswa yang valid.
  - Uji login menggunakan kata sandi yang baru di-reset.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Siswa Iseng Mereset Akun Teman jika Tahu NIS** | Siswa lain harus mereset ulang sandinya jika diubah teman | Sistem lab praktikum mengunci identitas PC/telemetri IP dan hostname pada sesi pengumpulan tugas. Siswa dapat dengan mudah mereset kembali kata sandi akunnya sendiri kapan saja. |
| **Penyalahgunaan Akun Guru / Pengawas** | Akun admin di-reset oleh siswa | Pengecekan `role === 'siswa'` tetap aktif, sehingga akun admin tidak pernah bisa direset melalui form NIS ini. |
