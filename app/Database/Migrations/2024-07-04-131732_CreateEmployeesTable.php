<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeesTable extends Migration
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
            'nip' => [
                'type'              => 'varchar',
                'constraint'        => '255',
                'null'              => true
            ],
            'name' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
            ],
            'gender' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
                'null'              => true
            ],
            'birth_date' => [
                'type'              => 'DATE',
                'null'              => true
            ],
            'phone_number' => [
                'type'              => 'VARCHAR',
                'constraint'        => '100',
                'null'              => true
            ],
            'address' => [
                'type'              => 'TEXT',
                'null'              => true
            ],
            'education' => [
                'type'              => 'varchar',
                'constraint'        => '225',
                'null'              => true
            ],
            'id_department' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'photo' => [
                'type'              => 'VARCHAR',
                'constraint'        => '225',
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
        $this->forge->addForeignKey('id_department', 'departments', 'id', 'cascade');
        $this->forge->createTable('employees');
    }

    public function down()
    {
        $this->forge->dropForeignKey('departments', 'employees_id_department_foreign');
        $this->forge->dropTable('employees');
    }
}
