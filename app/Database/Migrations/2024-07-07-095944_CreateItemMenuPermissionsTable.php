<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemMenuPermissionsTable extends Migration
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
            'id_item_menu' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'id_role' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true,
                'null'              => true,
            ],
            'add' => [
                'type'              => 'INT',
                'default'           => 0
            ],
            'edit' => [
                'type'              => 'INT',
                'default'           => 0
            ],
            'delete' => [
                'type'              => 'INT',
                'default'           => 0
            ],
            'detail_view' => [
                'type'              => 'INT',
                'default'           => 0
            ],
            'export' => [
                'type'              => 'INT',
                'default'           => 0
            ],
            'import' => [
                'type'              => 'INT',
                'default'           => 0
            ],
            'active' => [
                'type'              => 'INT',
                'default'           => 1
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_item_menu', 'items_menu', 'id', 'cascade');
        $this->forge->addForeignKey('id_role', 'roles', 'id', 'cascade');
        $this->forge->createTable('item_menu_permissions');
    }

    public function down()
    {
        $this->forge->dropForeignKey('items_menu', 'item_menu_permissions_id_item_menu_foreign');
        $this->forge->dropForeignKey('roles', 'item_menu_permissions_id_role_foreign');
        $this->forge->dropTable('item_menu_permissions');
    }
}
