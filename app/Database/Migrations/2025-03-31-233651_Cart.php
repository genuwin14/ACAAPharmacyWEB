<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Cart extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cart_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'inventory_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status'     => ['type' => 'ENUM', 'constraint' => ['Cart', 'To ship', 'To receive', 'Completed'], 'default' => 'Cart'],
            'quantity'   => ['type' => 'INT', 'constraint' => 100, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('cart_id', true);

        // Foreign Key Constraints
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inventory_id', 'inventory', 'id', 'CASCADE', 'CASCADE');

        // Create the 'cart' table
        $this->forge->createTable('cart');
    }

    public function down()
    {
        $this->forge->dropTable('cart');
    }
}
