<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'username'      => 'admin',
                'email'         => 'admin@warehouse.com',
                'password'      => password_hash('password123', PASSWORD_BCRYPT),
                'role'          => 'admin',
                'department_id' => 1,
                'status'        => 'active',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'warehouse',
                'email'         => 'warehouse@warehouse.com',
                'password'      => password_hash('password123', PASSWORD_BCRYPT),
                'role'          => 'warehouse',
                'department_id' => 4,
                'status'        => 'active',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'staff',
                'email'         => 'staff@warehouse.com',
                'password'      => password_hash('password123', PASSWORD_BCRYPT),
                'role'          => 'staff',
                'department_id' => 2,
                'status'        => 'active',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
