<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemsTable extends Migration
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
            'id_warehouse' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true,
                'null'              => true,
            ],
            'id_unit' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true,
                'null'              => true,
            ],
            'name' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
                'null'              => false,
            ],
            'code' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
                'null'              => false,
            ],
            'active' => [
                'type'              => 'INT',
                'default'           => 1
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp', 
            'deleted_at' => [
                'type'              => 'datetime',
                'null'              => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_warehouse', 'warehouses', 'id', 'cascade');
        $this->forge->addForeignKey('id_unit', 'units', 'id', 'cascade');
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('items');
    }

    public function down()
    {
        $this->forge->dropForeignKey('warehouses', 'items_id_warehouse_foreign');
        $this->forge->dropForeignKey('units', 'items_id_unit_foreign');
        $this->forge->dropTable('items');
    }
}
