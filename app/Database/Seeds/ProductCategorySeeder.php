<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Office Supplies',       'description' => 'Pens, paper, folders, and general office materials',  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Cleaning Materials',     'description' => 'Cleaning solutions, mops, and sanitation supplies',  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Safety Equipment',       'description' => 'PPE, first aid kits, and safety gear',               'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Electronics',            'description' => 'Cables, adapters, and electronic components',        'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Maintenance Tools',      'description' => 'Tools and equipment for maintenance activities',     'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('product_categories')->insertBatch($data);
    }
}
