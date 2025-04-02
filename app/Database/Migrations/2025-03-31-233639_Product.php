<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Product extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'image'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // Add image field
            'name'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'details'    => ['type' => 'TEXT', 'null' => true],
            'category'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'stock'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'price'      => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('inventory');
    }

    public function down()
    {
        $this->forge->dropTable('inventory');
    }
}