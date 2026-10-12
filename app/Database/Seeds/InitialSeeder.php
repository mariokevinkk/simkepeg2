<?php

namespace App\Database\Seeds;

use App\Models\UnitModel;
use App\Models\UserModel;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        $units = [
            ['name' => 'Bangsal Anggrek', 'code' => 'ANGGREK', 'type' => 'Rawat Inap'],
            ['name' => 'Bangsal Mawar', 'code' => 'MAWAR', 'type' => 'Rawat Inap'],
            ['name' => 'IGD', 'code' => 'IGD', 'type' => 'Gawat Darurat'],
            ['name' => 'ICU', 'code' => 'ICU', 'type' => 'Intensive Care'],
            ['name' => 'Poli Umum', 'code' => 'POLI-UMUM', 'type' => 'Rawat Jalan'],
            ['name' => 'Instalasi Farmasi', 'code' => 'FARMASI', 'type' => 'Penunjang Medis'],
        ];
        $unitModel = new UnitModel();
        foreach ($units as $unit) {
            if ($unitModel->where('name', $unit['name'])->first() === null) {
                $unitModel->insert($unit + ['is_active' => 1]);
            }
        }

        $userModel = new UserModel();
        if ($userModel->where('role', 'superadmin')->first() !== null) {
            CLI::write('Super Admin sudah tersedia; akun yang ada tidak diubah.', 'green');
            return;
        }

        $baseUsername = 'superadmin';
        $username = $baseUsername;
        $suffix = 1;
        while ($userModel->where('username', $username)->first() !== null) {
            $username = $baseUsername . $suffix++;
        }
        $email = $username . '@simkepegawaian.local';
        $password = rtrim(strtr(base64_encode(random_bytes(18)), '+/', '-_'), '=');
        $userModel->insert([
            'name' => 'Super Admin',
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'superadmin',
            'status' => 'active',
            'permissions' => json_encode([], JSON_THROW_ON_ERROR),
        ]);

        CLI::newLine();
        CLI::write('Akun Super Admin awal dibuat:', 'green');
        CLI::write('Username: ' . $username, 'yellow');
        CLI::write('Password awal: ' . $password, 'yellow');
        CLI::write('Simpan password ini sekarang; password tidak disimpan dalam bentuk teks di database.', 'light_gray');
        CLI::newLine();
    }
}
