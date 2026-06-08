<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['code' => 'OFF-001', 'name' => 'A4 Bond Paper (Ream)',    'category_id' => 1, 'unit_of_measurement' => 'reams',  'quantity_in_stock' => 150, 'reorder_level' => 20, 'expiration_date' => null,           'status' => 'active', 'description' => 'Standard A4 bond paper, 500 sheets per ream',      'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['code' => 'OFF-002', 'name' => 'Ballpoint Pens (Box)',    'category_id' => 1, 'unit_of_measurement' => 'boxes',  'quantity_in_stock' => 75,  'reorder_level' => 15, 'expiration_date' => null,           'status' => 'active', 'description' => 'Blue ballpoint pens, 12 per box',                  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['code' => 'CLN-001', 'name' => 'Floor Disinfectant',      'category_id' => 2, 'unit_of_measurement' => 'liters', 'quantity_in_stock' => 40,  'reorder_level' => 10, 'expiration_date' => '2026-08-15',   'status' => 'active', 'description' => 'Industrial-grade floor disinfectant solution',     'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['code' => 'CLN-002', 'name' => 'Hand Sanitizer (500ml)',  'category_id' => 2, 'unit_of_measurement' => 'pieces', 'quantity_in_stock' => 8,   'reorder_level' => 15, 'expiration_date' => '2026-06-10',   'status' => 'active', 'description' => 'Alcohol-based hand sanitizer, 500ml bottle',       'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['code' => 'SAF-001', 'name' => 'Safety Goggles',          'category_id' => 3, 'unit_of_measurement' => 'pieces', 'quantity_in_stock' => 30,  'reorder_level' => 5,  'expiration_date' => null,           'status' => 'active', 'description' => 'Clear polycarbonate safety goggles',               'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['code' => 'SAF-002', 'name' => 'First Aid Kit',           'category_id' => 3, 'unit_of_measurement' => 'pieces', 'quantity_in_stock' => 12,  'reorder_level' => 3,  'expiration_date' => '2027-01-01',   'status' => 'active', 'description' => 'Complete first aid kit with 50 items',             'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['code' => 'ELC-001', 'name' => 'USB-C Cables (1m)',       'category_id' => 4, 'unit_of_measurement' => 'pieces', 'quantity_in_stock' => 45,  'reorder_level' => 10, 'expiration_date' => null,           'status' => 'active', 'description' => 'USB Type-C charging and data cables, 1 meter',     'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['code' => 'ELC-002', 'name' => 'Power Strip (6-outlet)',  'category_id' => 4, 'unit_of_measurement' => 'pieces', 'quantity_in_stock' => 5,   'reorder_level' => 5,  'expiration_date' => null,           'status' => 'active', 'description' => 'Surge-protected 6-outlet power strip',             'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['code' => 'MNT-001', 'name' => 'WD-40 Lubricant Spray',  'category_id' => 5, 'unit_of_measurement' => 'pieces', 'quantity_in_stock' => 20,  'reorder_level' => 5,  'expiration_date' => '2028-06-01',   'status' => 'active', 'description' => 'Multi-use lubricant spray, 400ml can',             'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['code' => 'MNT-002', 'name' => 'Duct Tape Roll',         'category_id' => 5, 'unit_of_measurement' => 'rolls',  'quantity_in_stock' => 35,  'reorder_level' => 8,  'expiration_date' => null,           'status' => 'active', 'description' => 'Heavy-duty silver duct tape, 50 yards per roll',   'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
