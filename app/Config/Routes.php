<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/login', 'AuthController::loginForm');
$routes->post('/login', 'AuthController::login', ['filter' => 'csrf']);
$routes->get('/register', 'AuthController::registerForm');
$routes->post('/register', 'AuthController::register', ['filter' => 'csrf']);
$routes->post('/logout', 'AuthController::logout', ['filter' => 'csrf']);

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Home::index');
    $routes->get('/data-pegawai', 'Home::dataPegawai');
    $routes->get('/data-pegawai/excel', 'Home::exportExcel');
    $routes->get('/data-pegawai/word', 'Home::exportWord');
    $routes->get('/laporan', 'Home::laporan');
    $routes->get('/pengaturan', 'Home::pengaturan');
    $routes->get('/manajemen-akun', 'AdminManagementController::accounts');
    $routes->get('/manajemen-admin', 'AdminManagementController::index');
    $routes->post('/manajemen-admin', 'AdminManagementController::create', ['filter' => 'csrf']);
    $routes->post('/manajemen-admin/(:num)/access', 'AdminManagementController::update/$1', ['filter' => 'csrf']);
    $routes->post('/manajemen-akun/(:num)/status', 'AdminManagementController::updateStatus/$1', ['filter' => 'csrf']);
    $routes->get('/manajemen-bangsal', 'Home::manajemenBangsal');
    $routes->post('/manajemen-bangsal', 'AdminManagementController::createUnit', ['filter' => 'csrf']);
    $routes->post('/manajemen-bangsal/(:num)/status', 'AdminManagementController::updateUnitStatus/$1', ['filter' => 'csrf']);
    $routes->get('/manajemen-jabatan', 'JobTitleController::index');
    $routes->post('/manajemen-jabatan', 'JobTitleController::create', ['filter' => 'csrf']);
    $routes->post('/manajemen-jabatan/(:num)/status', 'JobTitleController::updateStatus/$1', ['filter' => 'csrf']);
    $routes->get('/detail-bangsal', 'Home::detailBangsal');
    $routes->get('/pensiun', 'Home::pensiun');
    $routes->post('/pensiun/(:num)/tetapkan', 'EmployeeController::retire/$1', ['filter' => 'csrf']);
    $routes->get('/pegawai-nonaktif', 'Home::pegawaiNonaktif');
    $routes->post('/pegawai-nonaktif', 'EmployeeController::nonaktifkan', ['filter' => 'csrf']);
    $routes->post('/pegawai-nonaktif/bup', 'EmployeeController::updateRetirementBup', ['filter' => 'csrf']);
    $routes->get('/profil-pegawai', 'Home::profilPegawai');
    $routes->get('/edit-pegawai', 'Home::editPegawai');
    $routes->get('/view-pegawai', 'Home::viewPegawai');
    $routes->get('/employees/new', 'EmployeeController::createForm');
    $routes->post('/employees', 'EmployeeController::create', ['filter' => 'csrf']);
    $routes->post('/employees/(:num)/update', 'EmployeeController::update/$1', ['filter' => 'csrf']);
    $routes->post('/employees/(:num)/documents/(:segment)', 'EmployeeController::uploadDocument/$1/$2', ['filter' => 'csrf']);
    $routes->get('/documents/(:num)', 'EmployeeController::showDocument/$1');
    $routes->post('/documents/(:num)/verification', 'EmployeeController::verifyDocument/$1', ['filter' => 'csrf']);
    $routes->get('/aktivitas', 'Home::aktivitas');
    $routes->get('/riwayat-kepangkatan', 'Home::riwayatKepangkatan');
    $routes->get('/riwayat-jabatan', 'Home::riwayatJabatan');
    $routes->get('/sip', 'Home::sip');
    $routes->get('/str', 'Home::str');
    $routes->get('/riwayat-pendidikan', 'Home::riwayatPendidikan');
    $routes->get('/data-keluarga', 'Home::dataKeluarga');
    $routes->get('/data-lainnya', 'Home::dataLainnya');
    $routes->get('/dokumen-pegawai', 'Home::dokumenPegawai');
    $routes->get('/ubah-password', 'AuthController::changePasswordForm');
    $routes->post('/ubah-password', 'AuthController::changePassword', ['filter' => 'csrf']);
});
