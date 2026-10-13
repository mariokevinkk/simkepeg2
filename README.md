# SIM Kepegawaian RSUD Kota Yogyakarta

Aplikasi web untuk mengelola data kepegawaian, dokumen, dan informasi status pegawai berdasarkan unit kerja. Aplikasi dibangun dengan CodeIgniter 4 dan menggunakan MySQL/MariaDB.

## Fitur

- Dashboard dan laporan kepegawaian sesuai cakupan unit akun.
- Data pegawai, profil, pendidikan, riwayat jabatan dan kepangkatan, keluarga, serta data pendukung lainnya.
- Pencarian dan filter data pegawai, termasuk kelengkapan dokumen.
- Ekspor daftar pegawai ke Excel dan formulir data pegawai ke Word.
- Checklist dokumen wajib dan tambahan, unggah dokumen, serta verifikasi atau permintaan perbaikan dengan catatan.
- Pengelolaan akun, unit kerja, katalog jabatan, dan hak akses admin unit.
- Pemantauan pensiun berdasarkan Batas Usia Pensiun (BUP), pengaturan BUP, dan pencatatan pegawai nonaktif.
- Registrasi akun pegawai yang menunggu persetujuan administrator.

## Peran akun

- **Super Admin** mengelola seluruh unit, akun, katalog jabatan, pengaturan BUP, dan data aplikasi.
- **Admin Unit** hanya mengakses unit yang ditugaskan kepadanya. Super Admin dapat mengatur izin melihat, menambah, dan mengubah data pegawai, mengelola dokumen dan riwayat, serta melihat laporan.
- **Pegawai** menggunakan akun yang ditautkan ke data pegawainya untuk mengakses profil dan fitur pegawai yang tersedia.

## Teknologi dan kebutuhan

- PHP 8.2 atau lebih baru.
- Composer.
- MySQL atau MariaDB dengan ekstensi PHP `mysqli`.
- Ekstensi PHP `intl`, `mbstring`, dan `fileinfo`.
- `public/` sebagai document root web server.
- Hak tulis untuk folder `writable/`.

## Menjalankan secara lokal

1. Pasang dependency PHP:

   ```bash
   composer install
   ```

2. Salin file `env` menjadi `.env`. Di PowerShell:

   ```powershell
   Copy-Item env .env
   ```

3. Buat database MySQL, misalnya `simkepeg2`, lalu isi pengaturan aplikasi dan database di `.env`:

   ```ini
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8080/'

   database.default.hostname = 127.0.0.1
   database.default.database = simkepeg2
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

   Sesuaikan nama database, username, dan password dengan konfigurasi MySQL lokal Anda.

4. Buat kunci enkripsi dan jalankan migrasi database:

   ```bash
   php spark key:generate
   php spark migrate
   ```

5. Pilih seed data awal sesuai kebutuhan.

   Untuk instalasi pengembangan/demo, jalankan:

   ```bash
   php spark db:seed DatabaseSeeder
   ```

   Seeder ini menjalankan `InitialSeeder`, `JobTitleSeeder`, dan `EmployeeDummySeeder`. Hasilnya mencakup unit awal, katalog jabatan, akun Super Admin, dan data pegawai contoh.

   Jika tidak memerlukan data pegawai dummy, jalankan seed secara terpisah:

   ```bash
   php spark db:seed InitialSeeder
   php spark db:seed JobTitleSeeder
   ```

6. Jalankan server pengembangan:

   ```bash
   php spark serve
   ```

   Buka `http://localhost:8080/login`.

### Akun awal dan data demo

- Saat `InitialSeeder` membuat akun Super Admin baru, username (biasanya `superadmin`, atau memakai akhiran angka jika nama itu sudah terpakai) dan password acak ditampilkan di terminal. Simpan password tersebut saat pertama kali dibuat; password tidak dapat dibaca kembali dari database. Jika akun Super Admin sudah ada, seeder tidak mengganti akun atau passwordnya.
- Akun pegawai contoh menggunakan NIP sebagai username dan tanggal lahir berformat `ddmmyyyy` sebagai password awal.
- `EmployeeDummySeeder` membuat data fiktif untuk demo/pengembangan. Hindari menjalankan `DatabaseSeeder` pada database produksi yang berisi data nyata.
- Pegawai yang mendaftar melalui halaman `/register` berstatus menunggu persetujuan. Super Admin meninjau akun tersebut melalui menu **Manajemen Akun**.
- Akun pegawai yang dibuat dari menu **Data Pegawai** langsung ditautkan ke data pegawai. Username ditentukan saat pengisian, sedangkan password awal memakai tanggal lahir berformat `ddmmyyyy`; pegawai dapat menggantinya melalui menu **Ubah Password**.

## Dokumen pegawai

Lampiran disimpan di `writable/uploads/employee-documents/`, bukan di folder publik. Aplikasi menerima PDF, JPG, dan PNG dengan ukuran maksimal 10 MB per file. Folder `writable/` harus dapat ditulis oleh PHP.

Cadangkan database dan folder `writable/uploads/` secara berkala karena keduanya menyimpan data yang diperlukan aplikasi.

## Deploy ke hosting

Atur document root domain ke folder `public/`. Jangan menjadikan root project sebagai document root. Siapkan `.env` produksi sendiri dan pastikan folder `writable/` dapat ditulis oleh PHP.

Panduan hosting cPanel, konfigurasi HTTPS, migrasi, database, dan backup tersedia di [PANDUAN-HOSTING.md](PANDUAN-HOSTING.md). Contoh konfigurasi produksi tersedia di [env.production.example](env.production.example); salin ke `.env` di server lalu isi seluruh nilai sesuai hosting. Jangan unggah atau commit `.env` yang berisi kredensial.

## Struktur direktori utama

```text
app/
  Controllers/       Alur halaman dan aksi aplikasi
  Database/          Migrasi dan seeder
  Models/            Akses dan aturan data
  Views/             Tampilan aplikasi
  Templates/         Template ekspor dokumen
public/              Document root dan aset publik
writable/            Cache, log, sesi, dan unggahan dokumen
tests/               Pengujian otomatis
```
