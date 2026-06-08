<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeDepartmentIdNullableInSupplyRequests extends Migration
{
    public function up(): void
    {
        // Make department_id nullable in supply_requests table for OAuth users without department
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->forge->modifyColumn('supply_requests', [
            'department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->forge->modifyColumn('supply_requests', [
            'department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
