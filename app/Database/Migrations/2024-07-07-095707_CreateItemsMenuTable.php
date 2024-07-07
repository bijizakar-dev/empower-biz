<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemsMenuTable extends Migration
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
            'id_menu' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'name' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
                'null'              => true
            ],
            'path' => [
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
        $this->forge->addForeignKey('id_menu', 'menus', 'id', 'cascade');
        $this->forge->createTable('items_menu');
    }

    public function down()
    {
        $this->forge->dropForeignKey('menus', 'items_menu_id_menu_foreign');
        $this->forge->dropTable('items_menu');
    }
}
