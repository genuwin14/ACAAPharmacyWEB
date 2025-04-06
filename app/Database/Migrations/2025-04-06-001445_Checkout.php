<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Checkout extends Migration
{
    public function up()
    {
        // Add fields to the checkout table
        $this->forge->addField([
            'checkout_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'cart_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status'          => ['type' => 'ENUM', 'constraint' => ['Pending', 'Received'], 'default' => 'Pending'],
            'pickup_date'     => ['type' => 'DATETIME', 'null' => true],
            'datetime_received' => ['type' => 'DATETIME', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'total_amount'    => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00], // Added total_amount field
        ]);

        // Add primary key
        $this->forge->addKey('checkout_id', true);

        // Add foreign key constraints
        $this->forge->addForeignKey('cart_id', 'cart', 'cart_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Create the checkout table
        $this->forge->createTable('checkout');
    }

    public function down()
    {
        $this->forge->dropTable('checkout');
    }
}
