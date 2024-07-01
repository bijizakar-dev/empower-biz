<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupplierContactsTable extends Migration
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
            'id_supplier' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'name' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
            ],
            'email' => [
                'type'              => 'TEXT',
                'null'              => true
            ],
            'phone' => [
                'type'              => 'varchar',
                'constraint'        => '255',
                'null'              => true
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
        $this->forge->addForeignKey('id_supplier', 'suppliers', 'id', 'cascade');
        $this->forge->createTable('supplier_contacts');
    }

    public function down()
    {
        $this->forge->dropForeignKey('suppliers', 'supplier_contacts_id_supplier_foreign');
        $this->forge->dropTable('supplier_contacts');
    }
}
