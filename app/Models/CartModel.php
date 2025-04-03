<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'cart_id';
    protected $allowedFields = ['user_id', 'inventory_id', 'status', 'quantity', 'created_at', 'updated_at'];
    protected $useTimestamps = true; // Automatically handle created_at and updated_at
    protected $useSoftDeletes = false;

    // Relationships
    public function getUser($user_id)
    {
        return $this->db->table('users')->where('id', $user_id)->get()->getRow();
    }

    public function getInventory($inventory_id)
    {
        return $this->db->table('inventory')->where('id', $inventory_id)->get()->getRow();
    }
}
