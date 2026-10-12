# CodeIgniter 4 Application Starter

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Installation & updates

`composer create-project codeigniter4/appstarter` then `composer update` whenever
there is a new release of the framework.

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

### Menyiapkan SIM Kepegawaian

Untuk MySQL Laragon dengan konfigurasi bawaan, buat database `simkepeg2` melalui
phpMyAdmin atau HeidiSQL, lalu gunakan pengaturan `.env` berikut. Jika nama
database atau kredensial MySQL berbeda, sesuaikan nilainya:

```ini
database.default.hostname = 127.0.0.1
database.default.database = simkepeg2
database.default.username = root
database.default.password =
database.default.port = 3306
```

Lalu buat tabel dan akun awal:

```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

Seeder membuat daftar unit awal, satu akun Super Admin, dan lima pegawai dummy
di setiap unit aktif. Jika hanya ingin menambah pegawai contoh setelah setup awal,
jalankan `php spark db:seed EmployeeDummySeeder`. Seeder pegawai aman dijalankan
ulang, memakai NIP berawalan `DUMMY`, dan menautkan setiap pegawai dummy ke akun
login. Username pegawai dummy adalah NIP, sedangkan password awal memakai tanggal
lahir berformat `ddmmyyyy`.
Password awal Super Admin acak
ditampilkan satu kali di terminal saat seeder berjalan; simpan password tersebut.
Pegawai asli dapat mendaftar melalui `/register`
dan menunggu persetujuan Super Admin, atau dibuat dari menu Data Pegawai.
Pegawai yang dibuat lewat menu Data Pegawai langsung mendapat akun login aktif;
Super Admin mengisi username dan tanggal lahir, lalu password awalnya adalah
tanggal lahir berformat `ddmmyyyy`. Jika email kosong, pegawai login memakai
username atau NIP. Password dapat diganti melalui menu **Ubah Password**.
Untuk data lama tanpa akun login, buka **Edit Data Pegawai**, isi username dan
tanggal lahir, lalu simpan untuk membuat serta menautkan akun.

Super Admin membuat admin unit dari menu **Manajemen Admin**, memilih satu atau
lebih unit, lalu memberi hak seperti melihat, membuat, atau mengubah data pegawai,
mengelola dokumen, dan melihat laporan. Cakupan unit diterapkan saat daftar dan
detail pegawai dimuat.

File lampiran disimpan di `writable/uploads/employee-documents`; folder ini ikut
perlu dicadangkan bersama database.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.2 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - The end of life date for PHP 8.1 was December 31, 2025.
> - If you are still using below PHP 8.2, you should upgrade immediately.
> - The end of life date for PHP 8.2 will be December 31, 2026.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
