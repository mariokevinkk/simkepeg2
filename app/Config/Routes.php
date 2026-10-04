<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/data-pegawai', 'Home::dataPegawai');
$routes->get('/laporan', 'Home::laporan');
$routes->get('/pengaturan', 'Home::pengaturan');
$routes->get('/manajemen-akun', 'Home::manajemenAkun');
$routes->get('/manajemen-admin', 'Home::manajemenAdmin');
$routes->get('/manajemen-bangsal', 'Home::manajemenBangsal');
$routes->get('/detail-bangsal', 'Home::detailBangsal');
$routes->get('/pensiun', 'Home::pensiun');
$routes->get('/profil-pegawai', 'Home::profilPegawai');
$routes->get('/edit-pegawai', 'Home::editPegawai');
$routes->get('/view-pegawai', 'Home::viewPegawai');
$routes->get('/aktivitas', 'Home::aktivitas');
$routes->get('/riwayat-kepangkatan', 'Home::riwayatKepangkatan');
$routes->get('/riwayat-jabatan', 'Home::riwayatJabatan');
$routes->get('/riwayat-pendidikan', 'Home::riwayatPendidikan');
$routes->get('/data-keluarga', 'Home::dataKeluarga');
$routes->get('/data-lainnya', 'Home::dataLainnya');
