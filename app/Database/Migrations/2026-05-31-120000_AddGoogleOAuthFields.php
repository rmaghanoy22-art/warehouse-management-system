<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleOAuthFields extends Migration
{
    public function up(): void
    {
        // 1. Add new columns for Google OAuth
        $this->forge->addColumn('users', [
            'google_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'oauth_provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'local',
            ],
            'oauth_token' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'profile_picture' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);

        // 2. Add Unique constraint on google_id
        $this->db->query("ALTER TABLE users ADD UNIQUE (google_id)");

        // 3. Make password column nullable
        $this->forge->modifyColumn('users', [
            'password' => [
                'name'       => 'password',
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
    }

    public function down(): void
    {
        // 1. Restore password to NOT NULL (Note: only works if no users have null passwords)
        $this->forge->modifyColumn('users', [
            'password' => [
                'name'       => 'password',
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
        ]);

        // 2. Drop the newly added columns
        $this->forge->dropColumn('users', ['google_id', 'oauth_provider', 'oauth_token', 'profile_picture']);
    }
}
