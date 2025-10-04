<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDestinationSchedulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'destination_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'day' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'open' => [
                'type'       => 'TIME',
                'null'       => true,
            ],
            'close' => [
                'type'       => 'TIME',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('destination_id', 'destinations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('destination_schedules');
    }

    public function down()
    {
        $this->forge->dropTable('destination_schedules');
    }
}
