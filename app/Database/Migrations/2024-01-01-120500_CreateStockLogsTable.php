<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'log_type' => [
                'type'       => 'ENUM',
                'constraint' => ['add', 'remove', 'adjust'],
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'previous_quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'new_quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'reference_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'performed_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id');
        $this->forge->addKey('log_type');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('performed_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stock_logs');
    }

    public function down(): void
    {
        $this->forge->dropTable('stock_logs');
    }
}
