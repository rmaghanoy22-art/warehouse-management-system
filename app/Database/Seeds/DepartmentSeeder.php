<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Administration',    'code' => 'ADMIN', 'description' => 'Administrative operations and management', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Engineering',        'code' => 'ENG',   'description' => 'Engineering and technical operations',     'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Human Resources',    'code' => 'HR',    'description' => 'Human resources and recruitment',          'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Operations',         'code' => 'OPS',   'description' => 'Day-to-day warehouse operations',          'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Quality Assurance',  'code' => 'QA',    'description' => 'Quality control and assurance',            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('departments')->insertBatch($data);
    }
}
