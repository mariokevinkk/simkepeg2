<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('InitialSeeder');
        $this->call('JobTitleSeeder');
        $this->call('EmployeeDummySeeder');
    }
}
