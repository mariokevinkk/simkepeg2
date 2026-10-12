# Panduan Upload SIM Kepegawaian ke Hosting

File yang dipakai ada di folder `dist/` (tidak ikut ke Git):

| File | Untuk apa |
|---|---|
| `simkepeg2-hosting.zip` | Aplikasi siap upload (tanpa `.env`, tanpa data lokal) |
| `database-awal.sql` | Database awal: semua tabel, 6 unit, 1 akun superadmin (dipakai jika hosting **tidak** punya Terminal/SSH) |
| `AKUN-SUPERADMIN-AWAL.txt` | Username & password superadmin untuk `database-awal.sql`. **Jangan diunggah, jangan dibagikan.** |

Isi `<...>` di bawah dengan data Anda sendiri.

---

## 1. Siapkan panel hosting

1. **Domain**: pastikan domain sudah terhubung ke hosting (status aktif di member area).
2. **Versi PHP**: menu *Select PHP Version* / *MultiPHP Manager* → pilih **PHP 8.2 atau lebih baru**.
   Centang ekstensi `intl`, `mbstring`, `mysqli`, `fileinfo` (biasanya sudah aktif).
   Jika ada pengaturan PHP: `upload_max_filesize` dan `post_max_size` minimal **12M**.
3. **Database**: menu *MySQL Databases*
   - buat database, mis. `<akun>_simkepeg`
   - buat user database + password kuat
   - *Add User to Database* → centang **ALL PRIVILEGES**
   - catat: nama database, user, password.

## 2. Upload aplikasi

1. Buka *File Manager*, masuk ke folder **home** (folder yang berisi `public_html`, **bukan** di dalam `public_html`).
2. Upload `simkepeg2-hosting.zip` → klik kanan → *Extract*. Hasilnya folder `simkepeg2/`.
3. Arahkan domain ke folder `public` aplikasi (pilih salah satu):
   - **Cara A (disarankan)**: menu *Domains* → domain Anda → ubah **Document Root** menjadi `simkepeg2/public`.
   - **Cara B** (jika document root tidak bisa diubah): salin **isi** folder `simkepeg2/public/` (termasuk file tersembunyi `.htaccess`) ke `public_html/`, lalu edit `public_html/index.php` baris
     `require FCPATH . '../app/Config/Paths.php';` menjadi
     `require FCPATH . '../simkepeg2/app/Config/Paths.php';`

   Dengan cara A/B, folder `app/`, `.env`, dan dokumen pegawai di `writable/` tidak bisa dibuka dari internet.

## 3. Buat file `.env`

1. Di folder `simkepeg2/`, salin `env.production.example` menjadi `.env`.
2. Edit `.env`: isi `app.baseURL` (mis. `https://namadomain.my.id/`) dan data database dari langkah 1.
3. `encryption.key`: jika ada Terminal jalankan `php spark key:generate` (langkah 4). Jika tidak, minta saya buatkan nilainya.

## 4. Isi database (pilih salah satu)

**Jika ada Terminal/SSH** (menu *Terminal*):

```bash
cd ~/simkepeg2
php spark key:generate
php spark migrate --all
php spark db:seed InitialSeeder
```

Perintah terakhir menampilkan username & password superadmin **sekali saja**; catat.
Untuk data contoh (demo sidang) tambahkan: `php spark db:seed EmployeeDummySeeder`.

**Jika tidak ada Terminal**: buka *phpMyAdmin* → pilih database → tab *Import* → pilih `database-awal.sql` → *Go*.
Login memakai akun di `AKUN-SUPERADMIN-AWAL.txt`.

## 5. Izin folder

Folder `simkepeg2/writable` beserta isinya (`cache`, `logs`, `session`, `uploads`) harus bisa ditulis server:
File Manager → klik kanan → *Change Permissions* → **755** (jika error saat login/upload, coba **775**).

## 6. SSL (HTTPS)

Menu *SSL/TLS Status* atau *Let's Encrypt* → aktifkan untuk domain Anda. Tunggu sampai `https://` terbuka.
`.env` sudah memaksa HTTPS (`app.forceGlobalSecureRequests = true`), jadi aktifkan SSL **sebelum** mencoba login.

## 7. Uji coba

1. Buka `https://<domain-anda>/login` → login superadmin → **langsung ganti password** (menu Ubah Password).
2. Tambah pegawai, unggah satu dokumen PDF, verifikasi di menu Aktivitas.
3. Daftar akun pegawai dari halaman Registrasi, lalu setujui di **Manajemen Akun**.

Jika muncul halaman error kosong: lihat file terbaru di `simkepeg2/writable/logs/`.

## 8. Backup

- Aktifkan *Daily Backup* (addon) atau backup manual berkala dari panel.
- Yang wajib dicadangkan: **database** (phpMyAdmin → *Export*) dan folder **`simkepeg2/writable/uploads`** (dokumen pegawai).

## Memperbarui aplikasi nanti

Upload ulang hanya folder yang berubah (biasanya `app/` dan `public/`), **jangan** menimpa `.env` dan `writable/`.
Jika ada migrasi baru, jalankan `php spark migrate --all` (atau minta file SQL perubahannya).
