<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenusTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true,
                'auto_increment'    => true,
                'null'              => false,
            ],
            'name' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
            ],
            'path' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
                'null'              => true
            ],
            'icon' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
                'null'              => true
            ],
            'active' => [
                'type'              => 'INT',
                'default'           => 1
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('menus');
    }

    public function down()
    {
        $this->forge->dropTable('menus');
    }
}
