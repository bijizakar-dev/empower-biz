<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExpiryTokenToUser extends Migration
{
    public function up()
    {
        $fields = [
            'expiry_token' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        // Menghapus kolom jika migration di-rollback
        $this->forge->dropColumn('users', 'expiry_token');
    }
}
