# Silabus Terpadu MySQL 8.x (Dasar hingga Siap Produksi)

**Target Pembaca:** Siswa dan mahasiswa yang mempelajari basis data relasional (MySQL 8.x) secara bertahap dari perancangan konsep hingga praktik terapan.
**Jumlah Modul Rekomendasi:** **14 Modul Terpadu** *(setara 1 semester akademik / 14 pertemuan efektif, menyatukan materi serumpun agar ringkas, runtut, dan tidak bertele-tele)*.
**Alur Pembelajaran:** Pengenalan Basis Data & Lingkungan Kerja → Pemodelan Data (ERD & Normalisasi) → DDL (`CREATE TABLE`, `FK`, `ALTER`, `DROP`) → DML (`INSERT`, `UPDATE`, `DELETE`) → `SELECT` → `WHERE` Clause → Agregasi → `JOIN` → `Subquery/CTE` → `View/Procedure/Function` → `Trigger/Event` → `Transaction/Index` → `Backup`.

**Konteks Studi Kasus:** Menggunakan 3 jalur database konsisten dalam konteks bisnis halal dan pendidikan:
1. **`toko_db`** — Toko Ritel & Inventaris Halal *(contoh praktik utama di setiap modul)*
2. **`akademik_db`** — Sistem Akademik Kampus *(praktikum berkelanjutan A)*
3. **`logistik_db`** — Pengiriman & Gudang Logistik *(praktikum berkelanjutan B)*

---

## Peta Alur Pembelajaran (14 Pertemuan)

```text
[Tahap 1: Fondasi & Desain]
  M01: Pengenalan Basis Data, Lingkungan MySQL, & DDL Database
   └──► M02: Perancangan Basis Data — ERD & Normalisasi Tabel (1NF–3NF)

[Tahap 2: Membangun & Mengelola Tabel]
  M03: Tipe Data Inti & DDL Pembuatan Tabel (CREATE TABLE)
   └──► M04: DDL Lanjutan — Constraint, Relasi FOREIGN KEY, ALTER, & DROP
   └──► M05: DML — Manipulasi Data (INSERT, UPDATE, DELETE vs TRUNCATE)

[Tahap 3: Pengolahan & Analisis Kueri]
  M06: Kueri Dasar (SELECT, Alias, DISTINCT, ORDER BY, LIMIT)
   └──► M07: Penyaringan Data (WHERE Clause, Logika, BETWEEN, IN, LIKE, NULL)
   ─── (Titik Evaluasi Tengah / UTS Praktik) ───
   └──► M08: Agregasi & Pengelompokan Data (COUNT, SUM, AVG, GROUP BY, HAVING)
   └──► M09: Relasi Multi-Tabel (INNER, LEFT, RIGHT, Self, CROSS JOIN)
   └──► M10: Kueri Bertingkat & Analisis Lanjut (Subquery, CTE, Window/JSON)

[Tahap 4: Otomasi, Keandalan, & Produksi]
  M11: Abstraksi & Logika Tersimpan (Views, Stored Procedures, Stored Functions)
   └──► M12: Otomasi Basis Data (Triggers & Event Scheduler)
   └──► M13: Manajemen Transaksi (ACID, COMMIT, ROLLBACK) & Optimasi Index
   └──► M14: Backup & Pemulihan Data (mysqldump & mysqlbinlog PITR)
```

---

## Daftar Modul Terpadu (14 Modul)

| No | Judul Modul | Topik Inti & Perintah SQL | Status | Berkas Modul |
| :--- | :--- | :--- | :--- | :--- |
| **01** | **Pengenalan Basis Data, Lingkungan MySQL, & DDL Database** | Konsep dasar basis data (Data, Informasi, Tabel, Record, Field, Key, DBMS vs RDBMS), arsitektur Client–Server, menjalankan SQL di CLI / VS Code / phpMyAdmin, `SELECT VERSION()`, `SHOW DATABASES`, `CREATE DATABASE`, `USE`, `DROP DATABASE` | **Selesai** | [materi-01-pengenalan-mysql-editor-dan-ddl-database.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-01-pengenalan-mysql-editor-dan-ddl-database.md) |
| **02** | **Perancangan Basis Data: ERD & Normalisasi Tabel** | Pembuktian 3 Anomali Data, Normalisasi bertahap (`UNF → 1NF → 2NF → 3NF`), Komponen ERD (Entitas, Atribut, PK/FK, Kardinalitas `1:1`, `1:N`, pemecahan `M:N`), serta Kamus Data studi kasus `toko_db`, `akademik_db`, `logistik_db` | **Selesai** | [materi-02-erd-dan-normalisasi-tabel.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-02-erd-dan-normalisasi-tabel.md) |
| **03** | **Tipe Data Inti & DDL Pembuatan Tabel** | Format Umum `CREATE TABLE`, tipe data (`INT`, `DECIMAL`, `VARCHAR`, `CHAR`, `TEXT`, `DATE`, `DATETIME`), `PRIMARY KEY`, `AUTO_INCREMENT`, `NOT NULL`, `DEFAULT`, `SHOW TABLES`, `DESCRIBE` | **Selesai** | [materi-03-tipe-data-dan-ddl-pembuatan-tabel.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-03-tipe-data-dan-ddl-pembuatan-tabel.md) |
| **04** | **DDL Lanjutan: Constraint, Relasi `FOREIGN KEY`, `ALTER`, & `DROP`** | Menerjemahkan ERD `1:N` & `M:N` ke tabel nyata, `UNIQUE`, `CHECK`, `ENUM`, `FOREIGN KEY` (`RESTRICT`/`CASCADE`/`SET NULL`), `ALTER TABLE` (`ADD`/`MODIFY`/`DROP`), `RENAME`, `DROP TABLE` | **Selesai** | [materi-04-ddl-lanjutan-constraint-fk-alter-drop.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-04-ddl-lanjutan-constraint-fk-alter-drop.md) |
| **05** | **DML: Manipulasi Data** | Format Umum DML, `INSERT INTO` (tunggal & banyak baris), `UPDATE ... SET`, `DELETE FROM`, perbedaan `DELETE` vs `TRUNCATE`, keamanan pembaruan data | **Selesai** | [materi-05-dml-manipulasi-data-insert-update-delete.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-05-dml-manipulasi-data-insert-update-delete.md) |
| **06** | **Kueri Dasar (`SELECT`) & Pengurutan** | Format Umum `SELECT`, memilih kolom spesifik vs `*`, alias `AS`, `DISTINCT`, `ORDER BY` (`ASC`/`DESC`), `LIMIT` & `OFFSET` | **Selesai** | [materi-06-kueri-dasar-select-alias-distinct-order-by-limit.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-06-kueri-dasar-select-alias-distinct-order-by-limit.md) |
| **07** | **Penyaringan Data (`WHERE` Clause)** | Operator perbandingan (`=`, `<>`, `<`, `>`), logika (`AND`, `OR`, `NOT`), rentang `BETWEEN`, daftar `IN`, pola `LIKE`, penanganan `IS NULL` | **Selesai** | [materi-07-penyaringan-data-where-clause-operator-logika-null.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-07-penyaringan-data-where-clause-operator-logika-null.md) |
| **08** | **Agregasi & Pengelompokan Data** | Fungsi agregat (`COUNT`, `SUM`, `AVG`, `MIN`, `MAX`), pengelompokan `GROUP BY`, penyaringan kelompok `HAVING`, perbedaan `WHERE` vs `HAVING` | **Selesai** | [materi-08-agregasi-dan-pengelompokan-data-group-by-having.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-08-agregasi-dan-pengelompokan-data-group-by-having.md) |
| **09** | **Relasi Multi-Tabel (`JOIN`)** | `INNER JOIN`, `LEFT JOIN`, `RIGHT JOIN`, `Self Join`, `CROSS JOIN`, dan teknik mencegah baris ganda saat menggabungkan tabel berelasi | **Selesai** | [materi-09-relasi-multi-tabel-join.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-09-relasi-multi-tabel-join.md) |
| **10** | **Kueri Bertingkat & Analisis Lanjut (`Subquery` & `CTE`)** | Scalar/row/table `Subquery`, `EXISTS`/`NOT EXISTS`, *Common Table Expression* (`WITH ... AS`), serta pengenalan ringkas `Window Functions` & `JSON` | Menunggu Pembuatan | *(Belum dibuat)* |
| **11** | **Abstraksi & Logika Tersimpan (`View`, `Procedure`, `Function`)** | `CREATE VIEW`, `Stored Procedure` (`IN`/`OUT`, `DELIMITER`, percabangan `IF`), serta `Stored Function` | Menunggu Pembuatan | *(Belum dibuat)* |
| **12** | **Otomasi Basis Data (`Trigger` & `Event`)** | `BEFORE`/`AFTER` `Trigger` (`INSERT`/`UPDATE`/`DELETE`) dengan `OLD`/`NEW`, audit otomatis, serta `Event Scheduler` berkala | Menunggu Pembuatan | *(Belum dibuat)* |
| **13** | **Manajemen Transaksi (`Transaction`) & Optimasi `Index`** | Prinsip ACID, `START TRANSACTION`, `COMMIT`, `ROLLBACK`, `SAVEPOINT`, konsep *B-Tree Index*, `CREATE INDEX`, dan pembacaan `EXPLAIN` | Menunggu Pembuatan | *(Belum dibuat)* |
| **14** | **`Backup` & Pemulihan Data** | Strategi pencadangan logis dengan `mysqldump`, memulihkan berkas `.sql`, serta pengenalan *Binary Log* untuk *Point-in-Time Recovery* (PITR) | Menunggu Pembuatan | *(Belum dibuat)* |

---

## Dokumen Pendukung & Pegangan Pengajar

- **Buku Kunci Jawaban & Pembahasan Latihan Soal:** [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md)  
  *Menghimpun seluruh kunci jawaban kueri SQL, rancangan dekomposisi normalisasi tabel, kamus data, dan pembahasan analitis mendalam untuk setiap butir latihan soal praktikum (Modul 01 s.d. 14) secara terpisah dari modul belajar siswa.*
