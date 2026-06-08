<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('DepartmentSeeder');
        $this->call('ProductCategorySeeder');
        $this->call('UserSeeder');
        $this->call('ProductSeeder');
    }
}
